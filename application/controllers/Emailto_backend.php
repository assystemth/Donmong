<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emailto_backend extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (
            $this->session->userdata('m_level') != 1 &&
            $this->session->userdata('m_level') != 2 &&
            $this->session->userdata('m_level') != 3 &&
            $this->session->userdata('m_level') != 4
        ) {
            redirect('user', 'refresh');
        }
        $this->load->model('member_model');
        $this->load->model('space_model');
        $this->load->model('emailto_model');
    }

    public function index()
    {
        $data['query'] = $this->emailto_model->list_all();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/emailto', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    // public function updateemailtoStatus()
    // {
    //     // ตรวจสอบว่ามีการส่งข้อมูล POST มาหรือไม่
    //     if ($this->input->post()) {
    //         $emailtoId = $this->input->post('emailto_id'); // รับค่า emailto_id
    //         $newStatus = $this->input->post('new_status'); // รับค่าใหม่จาก dropdown

    //         // ทำการอัพเดตค่าในตาราง tbl_emailto ในฐานข้อมูลของคุณ
    //         $data = array(
    //             'emailto_status' => $newStatus
    //         );
    //         $this->db->where('emailto_id', $emailtoId); // ระบุ emailto_id ของแถวที่ต้องการอัพเดต
    //         $this->db->update('tbl_emailto', $data);

    //         // ดึงข้อมูลของ emailto_id จากฐานข้อมูล
    //         $emailtoData = $this->db->get_where('tbl_emailto', array('emailto_id' => $emailtoId))->row();
    //         if ($emailtoData) {
    //             $message = "เรื่องร้องเรียน !" . "\n";
    //             $message .= "case: " . $emailtoData->emailto_id . "\n";
    //             $message .= "สถานะ: " . $newStatus . "\n";
    //             $message .= "เรื่อง: " . $emailtoData->emailto_head . "\n";
    //             $message .= "ประเภท: " . $emailtoData->emailto_type . "\n";
    //             $message .= "รายละเอียด: " . $emailtoData->emailto_detail . "\n";
    //             $message .= "พิกัด: " . $emailtoData->emailto_map . "\n";
    //             $message .= "เบอร์โทรศัพท์ผู้แจ้ง: " . $emailtoData->emailto_phone . "\n";
    //             $message .= "ชื่อผู้แจ้ง: " . $emailtoData->emailto_by . "\n";
    //         } else {
    //             $message = "สถานะใหม่: " . $newStatus;
    //         }


    //         // โค้ดสำหรับส่งข้อความ LINE Notify
    //         define('LINE_API', "https://notify-api.line.me/api/notify");
    //         $token = "ziHhjoKhdgWBAOSV8LiwhKm7LZxqfqP52esG3pYkNlK"; // ใส่ Token ที่คุณได้รับ

    //         $queryData = array('message' => $message);
    //         $queryData = http_build_query($queryData, '', '&');
    //         $headerOptions = array(
    //             'http' => array(
    //                 'method' => 'POST',
    //                 'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
    //                     "Authorization: Bearer " . $token . "\r\n" .
    //                     "Content-Length: " . strlen($queryData) . "\r\n",
    //                 'content' => $queryData
    //             ),
    //         );

    //         $context = stream_context_create($headerOptions);
    //         $result = file_get_contents(LINE_API, FALSE, $context);
    //         $res = json_decode($result);
    //     } else {
    //         // ถ้าไม่มีข้อมูล POST ส่งมา ให้รีเดอร์เปรียบเสมอ
    //         show_404();
    //     }
    // }

    // public function detail($emailto_id)
    // {
    //     $data['query'] = $this->emailto_model->read_detail($emailto_id);
    //     $data['qemailto'] = $this->emailto_model->read($emailto_id);
    //     $data['latest_query'] = $this->emailto_model->getLatestDetail($emailto_id);

    //     $this->load->view('templat/header');
    //     $this->load->view('asset/css');
    //     $this->load->view('templat/navbar_system_admin');
    //     $this->load->view('system_admin/emailto_detail', $data);
    //     $this->load->view('asset/js');
    //     $this->load->view('templat/footer');
    // }


    // public function updateStatus($emailto_detail_case_id)
    // {
    //     // รับข้อมูลจากฟอร์ม
    //     $emailto_detail_case_id = $this->input->post('emailto_detail_case_id');
    //     $emailto_detail_status = $this->input->post('emailto_detail_status');
    //     $emailto_detail_com = $this->input->post('emailto_detail_com');

    //     // เรียกใช้ฟังก์ชัน updateemailto
    //     $this->emailto_model->updateemailto($emailto_detail_case_id, $emailto_detail_status, $emailto_detail_com);

    //     // รีเดิร็คหน้าหลังจากทำการบันทึก
    //     redirect('emailto_backend/detail/' . $emailto_detail_case_id);
    // }

    // public function statusCancel($emailto_detail_case_id)
    // {
    //     $emailto_detail_case_id = $this->input->post('emailto_detail_case_id');
    //     $emailto_detail_status = 'ยกเลิก';
    //     $emailto_detail_com = $this->input->post('emailto_detail_com'); // รับข้อมูลจาก Swal

    //     // เรียกใช้ Model เพื่ออัปเดตข้อมูล
    //     $this->emailto_model->statusCancel($emailto_detail_case_id, $emailto_detail_status, $emailto_detail_com);

    //     // รีเดิร็คหน้าหลังจากทำการบันทึก
    //     redirect('emailto_backend/detail/' . $emailto_detail_case_id);
    // }


}
