<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbsv_hkl_backend extends CI_Controller
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
        $this->load->model('pbsv_hkl_model');
    }

    public function index()
    {
        $pbsv_hkl = $this->pbsv_hkl_model->list_all();

        foreach ($pbsv_hkl as $files) {
            $files->file = $this->pbsv_hkl_model->list_all_pdf($files->pbsv_hkl_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_hkl', ['pbsv_hkl' => $pbsv_hkl]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_hkl_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->pbsv_hkl_model->add();
        redirect('pbsv_hkl_backend');
    }


    public function editing($pbsv_hkl_id)
    {
        $data['rsedit'] = $this->pbsv_hkl_model->read($pbsv_hkl_id);
        $data['rsFile'] = $this->pbsv_hkl_model->read_file($pbsv_hkl_id);
        $data['rsImg'] = $this->pbsv_hkl_model->read_img($pbsv_hkl_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_hkl_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($pbsv_hkl_id)
    {
        $this->pbsv_hkl_model->edit($pbsv_hkl_id);
        redirect('pbsv_hkl_backend');
    }

    public function update_pbsv_hkl_status()
    {
        $this->pbsv_hkl_model->update_pbsv_hkl_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_hkl_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_hkl_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_pbsv_hkl($pbsv_hkl_id)
    {
        $this->pbsv_hkl_model->del_pbsv_hkl_img($pbsv_hkl_id);
        $this->pbsv_hkl_model->del_pbsv_hkl_pdf($pbsv_hkl_id);
        $this->pbsv_hkl_model->del_pbsv_hkl($pbsv_hkl_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('pbsv_hkl_backend');
    }
}
