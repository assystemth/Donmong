<?php
defined('BASEPATH') or exit('No direct script access allowed');

class operation_rprm_backend extends CI_Controller
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
        $this->load->model('operation_rprm_model');
    }

    public function index()
    {
        $operation_rprm = $this->operation_rprm_model->list_all();

        foreach ($operation_rprm as $files) {
            $files->file = $this->operation_rprm_model->list_all_pdf($files->operation_rprm_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/operation_rprm', ['operation_rprm' => $operation_rprm]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/operation_rprm_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->operation_rprm_model->add();
        redirect('operation_rprm_backend');
    }


    public function editing($operation_rprm_id)
    {
        $data['rsedit'] = $this->operation_rprm_model->read($operation_rprm_id);
        $data['rsFile'] = $this->operation_rprm_model->read_file($operation_rprm_id);
        $data['rsImg'] = $this->operation_rprm_model->read_img($operation_rprm_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/operation_rprm_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($operation_rprm_id)
    {
        $this->operation_rprm_model->edit($operation_rprm_id);
        redirect('operation_rprm_backend');
    }

    public function update_operation_rprm_status()
    {
        $this->operation_rprm_model->update_operation_rprm_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->operation_rprm_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->operation_rprm_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_operation_rprm($operation_rprm_id)
    {
        $this->operation_rprm_model->del_operation_rprm_img($operation_rprm_id);
        $this->operation_rprm_model->del_operation_rprm_pdf($operation_rprm_id);
        $this->operation_rprm_model->del_operation_rprm($operation_rprm_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('operation_rprm_backend');
    }
}
