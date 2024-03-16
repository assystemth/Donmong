<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbsv_ckl_backend extends CI_Controller
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
        $this->load->model('pbsv_ckl_model');
    }

    public function index()
    {
        $pbsv_ckl = $this->pbsv_ckl_model->list_all();

        foreach ($pbsv_ckl as $files) {
            $files->file = $this->pbsv_ckl_model->list_all_pdf($files->pbsv_ckl_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_ckl', ['pbsv_ckl' => $pbsv_ckl]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_ckl_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->pbsv_ckl_model->add();
        redirect('pbsv_ckl_backend');
    }


    public function editing($pbsv_ckl_id)
    {
        $data['rsedit'] = $this->pbsv_ckl_model->read($pbsv_ckl_id);
        $data['rsFile'] = $this->pbsv_ckl_model->read_file($pbsv_ckl_id);
        $data['rsImg'] = $this->pbsv_ckl_model->read_img($pbsv_ckl_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/pbsv_ckl_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($pbsv_ckl_id)
    {
        $this->pbsv_ckl_model->edit($pbsv_ckl_id);
        redirect('pbsv_ckl_backend');
    }

    public function update_pbsv_ckl_status()
    {
        $this->pbsv_ckl_model->update_pbsv_ckl_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_ckl_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->pbsv_ckl_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_pbsv_ckl($pbsv_ckl_id)
    {
        $this->pbsv_ckl_model->del_pbsv_ckl_img($pbsv_ckl_id);
        $this->pbsv_ckl_model->del_pbsv_ckl_pdf($pbsv_ckl_id);
        $this->pbsv_ckl_model->del_pbsv_ckl($pbsv_ckl_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('pbsv_ckl_backend');
    }
}
