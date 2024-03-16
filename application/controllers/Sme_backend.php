<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sme_backend extends CI_Controller
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
        $this->load->model('sme_model');
    }

    public function index()
    {
        $data['query'] = $this->sme_model->list_all();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/sme', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/sme_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }


    public function add()
    {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // exit;
        $this->sme_model->add();
        redirect('sme_backend', 'refresh');
    }

    public function editing($sme_id)
    {
        $data['rsedit'] = $this->sme_model->read($sme_id);
        // echo '<pre>';
        // print_r($data['rsedit']);
        // echo '</pre>';
        // exit();
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/sme_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($sme_id)
    {
        $this->sme_model->edit($sme_id);
        redirect('sme_backend', 'refresh');
    }

    public function del_sme($sme_id)
    {
        $this->sme_model->del_sme($sme_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('sme_backend', 'refresh');
    }
}
