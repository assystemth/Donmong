<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Canon_rmwp_backend extends CI_Controller
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
        $this->load->model('canon_rmwp_model');
    }

    public function index()
    {
        $canon_rmwp = $this->canon_rmwp_model->list_all();

        foreach ($canon_rmwp as $files) {
            $files->file = $this->canon_rmwp_model->list_all_pdf($files->canon_rmwp_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_rmwp', ['canon_rmwp' => $canon_rmwp]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_rmwp_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->canon_rmwp_model->add();
        redirect('canon_rmwp_backend');
    }


    public function editing($canon_rmwp_id)
    {
        $data['rsedit'] = $this->canon_rmwp_model->read($canon_rmwp_id);
        $data['rsFile'] = $this->canon_rmwp_model->read_file($canon_rmwp_id);
        $data['rsImg'] = $this->canon_rmwp_model->read_img($canon_rmwp_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/canon_rmwp_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($canon_rmwp_id)
    {
        $this->canon_rmwp_model->edit($canon_rmwp_id);
        redirect('canon_rmwp_backend');
    }

    public function update_canon_rmwp_status()
    {
        $this->canon_rmwp_model->update_canon_rmwp_status();
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->canon_rmwp_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->canon_rmwp_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_canon_rmwp($canon_rmwp_id)
    {
        $this->canon_rmwp_model->del_canon_rmwp_img($canon_rmwp_id);
        $this->canon_rmwp_model->del_canon_rmwp_pdf($canon_rmwp_id);
        $this->canon_rmwp_model->del_canon_rmwp($canon_rmwp_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('canon_rmwp_backend');
    }
}
