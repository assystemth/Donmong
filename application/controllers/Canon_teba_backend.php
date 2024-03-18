<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Canon_teba_backend extends CI_Controller
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
        $this->load->model('canon_teba_model');
    }

    public function index()
    {
        $canon_teba = $this->canon_teba_model->list_all();

        foreach ($canon_teba as $files) {
            $files->file = $this->canon_teba_model->list_all_pdf($files->canon_teba_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_teba', ['canon_teba' => $canon_teba]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_teba_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->canon_teba_model->add();
        redirect('canon_teba_backend');
    }


    public function editing($canon_teba_id)
    {
        $data['rsedit'] = $this->canon_teba_model->read($canon_teba_id);
        $data['rsFile'] = $this->canon_teba_model->read_file($canon_teba_id);
        $data['rsImg'] = $this->canon_teba_model->read_img($canon_teba_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_teba_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($canon_teba_id)
    {
        $this->canon_teba_model->edit($canon_teba_id);
        redirect('canon_teba_backend');
    }

    public function update_canon_teba_status()
    {
        $this->canon_teba_model->update_canon_teba_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->canon_teba_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->canon_teba_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_canon_teba($canon_teba_id)
    {
        $this->canon_teba_model->del_canon_teba_img($canon_teba_id);
        $this->canon_teba_model->del_canon_teba_pdf($canon_teba_id);
        $this->canon_teba_model->del_canon_teba($canon_teba_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('canon_teba_backend');
    }
}
