<?php
class Eservice_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_eservices()
    {
        $this->db->select('*');
        $this->db->from('tbl_eservice');
        return $this->db->get()->result();
    }
    public function get_images_for_eservice($eservice_id)
    {
        $this->db->select('eservice_img_img');
        $this->db->from('tbl_eservice_img');
        $this->db->where('eservice_img_ref_id', $eservice_id);
        return $this->db->get()->result();
    }

    //show form edit
    public function read($eservice_id)
    {
        $this->db->where('eservice_id', $eservice_id);
        $query = $this->db->get('tbl_eservice');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function read_detail($eservice_id)
    {
        $this->db->where('eservice_detail_case_id', $eservice_id);
        $this->db->order_by('eservice_detail_id', 'DESC');
        $query = $this->db->get('tbl_eservice_detail');
        return $query->result();
    }

    public function updateeserviceStatus($eserviceId, $eserviceStatus)
    {
        $data = array(
            'eservice_status' => $eserviceStatus
        );

        $this->db->where('eservice_id', $eserviceId);
        $result = $this->db->update('tbl_eservice', $data);

        return $result;
    }

    public function dashboard_eservice()
    {
        $this->db->select('*');
        $this->db->from('tbl_eservice as c');
        $this->db->limit(3);
        $this->db->order_by('c.eservice_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function updateeservice($eservice_detail_case_id, $eservice_detail_status, $eservice_detail_com)
    {
        // อัปเดต tbl_eservice
        $this->db->set('eservice_status', $eservice_detail_status);
        $this->db->where('eservice_id', $eservice_detail_case_id);
        $this->db->update('tbl_eservice');

        // ดึงข้อมูลจาก tbl_eservice หลังจากอัปเดต
        $eserviceData = $this->db->get_where('tbl_eservice', array('eservice_id' => $eservice_detail_case_id))->row();

        if ($eserviceData) {
            $message = "แจ้งเรื่องทุจริต !" . "\n";
            $message .= "case: " . $eserviceData->eservice_id . "\n";
            $message .= "สถานะ: " . $eserviceData->eservice_status . "\n";
            $message .= "เรื่อง: " . $eserviceData->eservice_head . "\n";
            $message .= "ประเภท: " . $eserviceData->eservice_type . "\n";
            $message .= "รายละเอียด: " . $eserviceData->eservice_detail . "\n";
            $message .= "พิกัด: " . $eserviceData->eservice_map . "\n";
            $message .= "ชื่อผู้อัพเดตข้อมูล: " . $eserviceData->eservice_by . "\n";
            $message .= "เบอร์โทรศัพท์ผู้แจ้ง: " . $eserviceData->eservice_phone . "\n";
            // เพิ่มข้อมูลอื่น ๆ ตามที่คุณต้องการ
        }

        // ส่งข้อมูลไปที่ LINE Notify
        $this->sendLineNotify($message);

        // เพิ่มข้อมูลใหม่ลงใน tbl_eservice_detail
        $data = array(
            'eservice_detail_case_id' => $eservice_detail_case_id,
            'eservice_detail_status' => $eservice_detail_status,
            'eservice_detail_by' => $this->session->userdata('m_fname'),
            'eservice_detail_com' => $eservice_detail_com
        );
        $this->db->insert('tbl_eservice_detail', $data);
    }

    private function sendLineNotify($message)
    {
        define('LINE_API', "https://notify-api.line.me/api/notify");
        $token = "k5KuFnUR64P2pI0usUJejwy1Ecn8XB73UVqFkUO7eeB"; // ใส่ Token ที่คุณได้รับ

        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData, '', '&');
        $headerOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Authorization: Bearer " . $token . "\r\n" .
                    "Content-Length: " . strlen($queryData) . "\r\n",
                'content' => $queryData
            ),
        );

        $context = stream_context_create($headerOptions);
        $result = file_get_contents(LINE_API, FALSE, $context);
        $res = json_decode($result);
    }

    public function statusCancel($eservice_detail_case_id, $eservice_detail_status, $eservice_detail_com)
    {
        // อัปเดต tbl_eservice
        $this->db->set('eservice_status', 'ยกเลิก');
        $this->db->where('eservice_id', $eservice_detail_case_id);
        $this->db->update('tbl_eservice');

        // ดึงข้อมูลจาก tbl_eservice หลังจากอัปเดต
        $eserviceData = $this->db->get_where('tbl_eservice', array('eservice_id' => $eservice_detail_case_id))->row();

        if ($eserviceData) {
            $message = "แจ้งเรื่องทุจริต !" . "\n";
            $message .= "case: " . $eserviceData->eservice_id . "\n";
            $message .= "สถานะ: " . $eserviceData->eservice_status . "\n";
            $message .= "เรื่อง: " . $eserviceData->eservice_head . "\n";
            $message .= "ประเภท: " . $eserviceData->eservice_type . "\n";
            $message .= "รายละเอียด: " . $eserviceData->eservice_detail . "\n";
            $message .= "พิกัด: " . $eserviceData->eservice_map . "\n";
            $message .= "ชื่อผู้อัพเดตข้อมูล: " . $eserviceData->eservice_by . "\n";
            $message .= "เบอร์โทรศัพท์ผู้แจ้ง: " . $eserviceData->eservice_phone . "\n";
            // เพิ่มข้อมูลอื่น ๆ ตามที่คุณต้องการ
        }

        // ส่งข้อมูลไปที่ LINE Notify
        $this->sendLineNotify($message);

        // เพิ่มข้อมูลใหม่ลงใน tbl_eservice_detail
        $data = array(
            'eservice_detail_case_id' => $eservice_detail_case_id,
            'eservice_detail_status' => 'ยกเลิก',
            'eservice_detail_com' => $eservice_detail_com, // เพิ่มฟิลด์นี้
            'eservice_detail_by' => $this->session->userdata('m_fname')
        );
        $this->db->insert('tbl_eservice_detail', $data);
    }


    public function getLatestDetail($eservice_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_eservice_detail');
        $this->db->where('eservice_detail_case_id', $eservice_id);
        $this->db->order_by('eservice_detail_id', 'DESC');
        $this->db->limit(1); // จำกัดให้เรียกข้อมูลอันล่าสุดเท่านั้น

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return null;
    }

    // เว็บ kakoh เริ่มจากตรงนี้ *********************************************************************
    public function add_eservice()
    {
        // Check used space
        $used_space_mb = $this->space_model->get_used_space();
        $upload_limit_mb = $this->space_model->get_limit_storage();

        // If images are uploaded, calculate the total space required
        $total_space_required = 0;
        if (isset($_FILES['eservice_imgs']) && !empty($_FILES['eservice_imgs']['name'][0])) {
            $eservice_imgs = $_FILES['eservice_imgs'];
            foreach ($eservice_imgs['size'] as $size) {
                $total_space_required += $size;
            }
        }

        // Check if there's enough space
        if ($used_space_mb + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit_mb) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('Pages/adding_eservice');
            return;
        }

        // Prepare eservice data
        $eservice_data = array(
            'eservice_type' => $this->input->post('eservice_type'),
            'eservice_topic' => $this->input->post('eservice_topic'),
            'eservice_detail' => $this->input->post('eservice_detail'),
            'eservice_by' => $this->input->post('eservice_by'),
            'eservice_phone' => $this->input->post('eservice_phone'),
            'eservice_email' => $this->input->post('eservice_email'),
            'eservice_address' => $this->input->post('eservice_address'),
        );

        // Insert eservice data into database
        $this->db->trans_start();
        $this->db->insert('tbl_eservice', $eservice_data);
        $eservice_id = $this->db->insert_id();

        // If images are uploaded, process and insert them into tbl_eservice_img
        if (isset($eservice_imgs)) {
            $image_data = array(); // Initialize the array
            $config['upload_path'] = './docs/img';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            foreach ($eservice_imgs['name'] as $index => $name) {
                $_FILES['eservice_img']['name'] = $name;
                $_FILES['eservice_img']['type'] = $eservice_imgs['type'][$index];
                $_FILES['eservice_img']['tmp_name'] = $eservice_imgs['tmp_name'][$index];
                $_FILES['eservice_img']['error'] = $eservice_imgs['error'][$index];
                $_FILES['eservice_img']['size'] = $eservice_imgs['size'][$index];

                if (!$this->upload->do_upload('eservice_img')) {
                    $this->session->set_flashdata('save_maxsize', TRUE);
                    redirect('Pages/adding_eservice');
                    return;
                }

                $upload_data = $this->upload->data();
                $image_data[] = array(
                    'eservice_img_ref_id' => $eservice_id,
                    'eservice_img_img' => $upload_data['file_name']
                );
            }

            // Insert image data into database
            $this->db->insert_batch('tbl_eservice_img', $image_data);
        }

        $this->db->trans_complete();

        // Update server space usage
        $this->space_model->update_server_current();

        // Set flash message for success
        $this->session->set_flashdata('save_success', TRUE);
    }
}
