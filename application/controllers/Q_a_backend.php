<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Q_a_backend extends CI_Controller
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
        $this->load->model('q_a_model');
    }

    public function index()
    {
        // อ่านข้อมูลความคิดเห็นทั้งหมดจากตาราง tbl_q_a
        $data['rsCom'] = $this->q_a_model->list_all();

        // อ่านข้อมูลความคิดเห็นตอบกลับทั้งหมดจากตาราง tbl_q_a_reply
        foreach ($data['rsCom'] as $index => $com) {
            $q_a_id = $com->q_a_id;
            $q_a_reply_data = $this->q_a_model->read_all_q_a_reply($q_a_id);

            // เก็บข้อมูลความคิดเห็นตอบกลับลงในอาร์เรย์ของความคิดเห็น
            $data['rsCom'][$index]->com_reply_data = $q_a_reply_data;
        }
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/q_a', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function del_com($q_a_id)
    {
        $this->q_a_model->del_reply($q_a_id);
        $this->q_a_model->del_com($q_a_id);

        // ส่งคำตอบในรูปแบบ JSON เพื่อระบุว่าการลบสำเร็จ
        $response = array('success' => true);
        header('Content-Type: application/json');
        echo json_encode($response);
        $this->session->set_flashdata('del_success', TRUE);
    }

    public function del_com_reply($q_a_reply_id)
    {
        $this->q_a_model->del_com_reply($q_a_reply_id);

        // ส่งคำตอบในรูปแบบ JSON เพื่อระบุว่าการลบสำเร็จ
        $response = array('success' => true);
        header('Content-Type: application/json');
        echo json_encode($response);
        $this->session->set_flashdata('del_success', TRUE);
    }

    public function adding_reply_q_a($q_a_id)
    {
        $data['rsedit'] = $this->q_a_model->read($q_a_id);

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/reply_q_a', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add_reply_q_a()
    {
        $this->q_a_model->add_reply_q_a();
        redirect('Q_a_backend', 'refresh');
    }
}
