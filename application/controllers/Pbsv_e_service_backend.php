<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbsv_e_service_backend extends CI_Controller
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
        $this->load->model('pbsv_e_service_model');
    }

    public function index()
    {
        $pbsv_e_service = $this->pbsv_e_service_model->list_all();

        foreach ($pbsv_e_service as $files) {
            $files->file = $this->pbsv_e_service_model->list_all_pdf($files->pbsv_e_service_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_e_service', ['pbsv_e_service' => $pbsv_e_service]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_e_service_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->pbsv_e_service_model->add();
        redirect('pbsv_e_service_backend');
    }


    public function editing($pbsv_e_service_id)
    {
        $data['rsedit'] = $this->pbsv_e_service_model->read($pbsv_e_service_id);
        $data['rsFile'] = $this->pbsv_e_service_model->read_file($pbsv_e_service_id);
        $data['rsImg'] = $this->pbsv_e_service_model->read_img($pbsv_e_service_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_e_service_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($pbsv_e_service_id)
    {
        $this->pbsv_e_service_model->edit($pbsv_e_service_id);
        redirect('pbsv_e_service_backend');
    }

    public function update_pbsv_e_service_status()
    {
        $this->pbsv_e_service_model->update_pbsv_e_service_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_e_service_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_e_service_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_pbsv_e_service($pbsv_e_service_id)
    {
        $this->pbsv_e_service_model->del_pbsv_e_service_img($pbsv_e_service_id);
        $this->pbsv_e_service_model->del_pbsv_e_service_pdf($pbsv_e_service_id);
        $this->pbsv_e_service_model->del_pbsv_e_service($pbsv_e_service_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('pbsv_e_service_backend');
    }
}
