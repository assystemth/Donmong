<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laws_ec_backend extends CI_Controller
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
        $this->load->model('laws_ec_model');
    }

    public function index()
    {

        $data['query'] = $this->laws_ec_model->list_all();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/laws_ec', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/laws_ec_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->laws_ec_model->add();
        redirect('laws_ec_backend', 'refresh');
    }

    public function editing($laws_ec_id)
    {
        $data['rsedit'] = $this->laws_ec_model->read($laws_ec_id);

        // echo '<pre>';
        // print_r($data['rsedit']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/laws_ec_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($laws_ec_id)
    {
        $this->laws_ec_model->edit($laws_ec_id);
        redirect('laws_ec_backend', 'refresh');
    }

    public function del_laws_ec($laws_ec_id)
    {
        $this->laws_ec_model->del_laws_ec($laws_ec_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('laws_ec_backend', 'refresh');
    }

    public function updatelaws_ecStatus()
    {
        $this->laws_ec_model->updatelaws_ecStatus();
    }
}
