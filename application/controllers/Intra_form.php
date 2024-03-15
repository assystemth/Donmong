<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Intra_form extends CI_Controller
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
        $this->load->model('Intra_form_model');
    }
    public function index()
    {
        $data['query'] = $this->Intra_form_model->list_all();

        $this->load->view('intranet_templat/header_form');
        $this->load->view('internet_asste/css');
        $this->load->view('intranet_templat/navbar');
        $this->load->view('intranet/form', $data);
        $this->load->view('internet_asste/js');
        $this->load->view('intranet_templat/footer');
    }

    public function add()
    {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // echo '<pre>';
        // print_r($_FILES);
        // echo '</pre>';
        // exit;
        $this->Intra_form_model->add();
        redirect('Intra_form', 'refresh');
    }

    public function del_intra_form($intra_form_id)
    {
        $this->Intra_form_model->del_intra_form($intra_form_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('Intra_form', 'refresh');
    }

    public function search()
    {
        $search_term = $this->input->post('search_term');

        // ถ้ามีคำค้นหา
        if (!empty($search_term)) {
            $data['query'] = $this->Intra_form_model->search($search_term);
        } else {
            // ถ้าไม่มีคำค้นหา ดึงข้อมูลทั้งหมด
            $data['query'] = $this->Intra_form_model->list_all();
        }

        $this->load->view('intranet_templat/header_form');
        $this->load->view('internet_asste/css');
        $this->load->view('intranet_templat/navbar');
        $this->load->view('intranet/form', $data);
        $this->load->view('internet_asste/js');
        $this->load->view('intranet_templat/footer');
    }
}
