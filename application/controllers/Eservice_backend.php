<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eservice_backend extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (
                        $this->session->userdata('m_level') != 1 &&
            $this->session->userdata('m_level') != 2 &&
            $this->session->userdata('m_level') != 3 &&
            $this->session->userdata('m_level') != 4 &&
            $this->session->userdata('m_level') != 2 &&
            $this->session->userdata('m_level') != 3 &&
            $this->session->userdata('m_level') != 4
        ) {
            redirect('user', 'refresh');
        }
        $this->load->model('member_model');
        $this->load->model('space_model');
        $this->load->model('eservice_model');
    }

    public function index()
    {
        $eservices = $this->eservice_model->get_eservices();

        foreach ($eservices as $eservice) {
            $eservice->images = $this->eservice_model->get_images_for_eservice($eservice->eservice_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/eservice', ['eservices' => $eservices]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    // public function updateeserviceStatus()
    // {
    //     // ตรวจสอบว่ามีการส่งข้อมูล POST มาหรือไม่
    //     if ($this->input->post()) {
    //         $eserviceId = $this->input->post('eservice_id'); // รับค่า eservice_id
    //         $newStatus = $this->input->post('new_status'); // รับค่าใหม่จาก dropdown

    //         // ทำการอัพเดตค่าในตาราง tbl_eservice ในฐานข้อมูลของคุณ
    //         $data = array(
    //             'eservice_status' => $newStatus
    //         );
    //         $this->db->where('eservice_id', $eserviceId); // ระบุ eservice_id ของแถวที่ต้องการอัพเดต
    //         $this->db->update('tbl_eservice', $data);

    //         // ดึงข้อมูลของ eservice_id จากฐานข้อมูล
    //         $eserviceData = $this->db->get_where('tbl_eservice', array('eservice_id' => $eserviceId))->row();
    //         if ($eserviceData) {
    //             $message = "เรื่องร้องเรียน !" . "\n";
    //             $message .= "case: " . $eserviceData->eservice_id . "\n";
    //             $message .= "สถานะ: " . $newStatus . "\n";
    //             $message .= "เรื่อง: " . $eserviceData->eservice_head . "\n";
    //             $message .= "ประเภท: " . $eserviceData->eservice_type . "\n";
    //             $message .= "รายละเอียด: " . $eserviceData->eservice_detail . "\n";
    //             $message .= "พิกัด: " . $eserviceData->eservice_map . "\n";
    //             $message .= "เบอร์โทรศัพท์ผู้แจ้ง: " . $eserviceData->eservice_phone . "\n";
    //             $message .= "ชื่อผู้แจ้ง: " . $eserviceData->eservice_by . "\n";
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

    // public function detail($eservice_id)
    // {
    //     $data['query'] = $this->eservice_model->read_detail($eservice_id);
    //     $data['qeservice'] = $this->eservice_model->read($eservice_id);
    //     $data['latest_query'] = $this->eservice_model->getLatestDetail($eservice_id);

    //     $this->load->view('templat/header');
    //     $this->load->view('asset/css');
    //     $this->load->view('templat/navbar_system_admin');
    //     $this->load->view('system_admin/eservice_detail', $data);
    //     $this->load->view('asset/js');
    //     $this->load->view('templat/footer');
    // }


    // public function updateStatus($eservice_detail_case_id)
    // {
    //     // รับข้อมูลจากฟอร์ม
    //     $eservice_detail_case_id = $this->input->post('eservice_detail_case_id');
    //     $eservice_detail_status = $this->input->post('eservice_detail_status');
    //     $eservice_detail_com = $this->input->post('eservice_detail_com');

    //     // เรียกใช้ฟังก์ชัน updateeservice
    //     $this->eservice_model->updateeservice($eservice_detail_case_id, $eservice_detail_status, $eservice_detail_com);

    //     // รีเดิร็คหน้าหลังจากทำการบันทึก
    //     redirect('eservice_backend/detail/' . $eservice_detail_case_id);
    // }

    // public function statusCancel($eservice_detail_case_id)
    // {
    //     $eservice_detail_case_id = $this->input->post('eservice_detail_case_id');
    //     $eservice_detail_status = 'ยกเลิก';
    //     $eservice_detail_com = $this->input->post('eservice_detail_com'); // รับข้อมูลจาก Swal

    //     // เรียกใช้ Model เพื่ออัปเดตข้อมูล
    //     $this->eservice_model->statusCancel($eservice_detail_case_id, $eservice_detail_status, $eservice_detail_com);

    //     // รีเดิร็คหน้าหลังจากทำการบันทึก
    //     redirect('eservice_backend/detail/' . $eservice_detail_case_id);
    // }


}
