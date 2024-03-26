<?php
class Emailto_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_emailto()
    {
        // Check used space
        $used_space_mb = $this->space_model->get_used_space();
        $upload_limit_mb = $this->space_model->get_limit_storage();

        // Calculate the total space required for all files
        $total_space_required = 0;
        if (!empty($_FILES['emailto_file']['name'])) {
            $total_space_required += $_FILES['emailto_file']['size'];
        }

        // Check if there's enough space
        if ($used_space_mb + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit_mb) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('emailto/adding_emailto');
            return;
        }

        // Upload configuration
        $config['upload_path'] = './docs/file';
        $config['allowed_types'] = 'doc|docx|pdf|xls';
        $this->load->library('upload', $config);

        // Upload main file
        if (!$this->upload->do_upload('emailto_file')) {
            // If the file size exceeds the max_size, set flash data and redirect
            $this->session->set_flashdata('save_maxsize', TRUE);
            redirect('emailto/adding_emailto');
            return;
        }

        $data = $this->upload->data();
        $filename = $data['file_name'];

        $data = array(
            'emailto_topic' => $this->input->post('emailto_topic'),
            'emailto_detail' => $this->input->post('emailto_detail'),
            'emailto_by' => $this->input->post('emailto_by'),
            'emailto_phone' => $this->input->post('emailto_phone'),
            'emailto_email' => $this->input->post('emailto_email'),
            'emailto_file' => $filename
        );

        $query = $this->db->insert('tbl_emailto', $data);
        $emailto_id = $this->db->insert_id();
        // ดึงข้อมูลจาก tbl_emailto หลังจากอัปเดต
        $emailtoData = $this->db->get_where('tbl_emailto', array('emailto_id' => $emailto_id))->row();

        if ($emailtoData) {
            $message = "E-mail ถึงเทศบาล!" . "\n";
            $message .= "เรื่อง: " . $emailtoData->emailto_topic . "\n";
            $message .= "รายละเอียด: " . $emailtoData->emailto_detail . "\n";
            $message .= "ชื่อผู้: " . $emailtoData->emailto_by . "\n";
            $message .= "เบอร์โทรศัพท์ผู้แจ้ง: " . $emailtoData->emailto_phone . "\n";
            $message .= "อีเมล: " . $emailtoData->emailto_email . "\n";
            // เพิ่มข้อมูลอื่น ๆ ตามที่คุณต้องการ
        }

        $this->sendLineNotifyImg($message);

        $this->space_model->update_server_current();
        $this->session->set_flashdata('save_success', TRUE);

        if ($query) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('Error !');";
            echo "</script>";
        }
    }

    private function sendLineNotifyImg($message)
    {
        $headers = [
            'Authorization: Bearer ' . $this->lineNotifyAccessToken,
        ];

        $data = [
            'message' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->lineNotifyApiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response as needed
        echo "Line Notify API Response: $response";
    }
    private $lineNotifyApiUrl = 'https://notify-api.line.me/api/notify';
    private $lineNotifyAccessToken = 'Iff0yJEZxd1xtZQDhWGKHltb455decobtxXQlDjlWST'; // Replace with your Line Notify access token

    public function list_all()
    {
        $this->db->select('*');
        $this->db->from('tbl_emailto');
        $this->db->group_by('tbl_emailto.emailto_id');
        $this->db->order_by('tbl_emailto.emailto_datesave', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
}
