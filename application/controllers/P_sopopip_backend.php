<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_sopopip_backend extends CI_Controller
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
        $this->load->model('p_sopopip_model');
    }

    public function index()
    {
        $p_sopopip = $this->p_sopopip_model->list_all();

        foreach ($p_sopopip as $pdf) {
            $pdf->pdf = $this->p_sopopip_model->list_all_pdf($pdf->p_sopopip_id);
        }
        foreach ($p_sopopip as $doc) {
            $doc->doc = $this->p_sopopip_model->list_all_doc($doc->p_sopopip_id);
        }


        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sopopip', ['p_sopopip' => $p_sopopip]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sopopip_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $this->p_sopopip_model->add();
        redirect('p_sopopip_backend');
    }


    public function editing($p_sopopip_id)
    {
        $data['rsedit'] = $this->p_sopopip_model->read($p_sopopip_id);
        $data['rsPdf'] = $this->p_sopopip_model->read_pdf($p_sopopip_id);
        $data['rsDoc'] = $this->p_sopopip_model->read_doc($p_sopopip_id);
        $data['rsImg'] = $this->p_sopopip_model->read_img($p_sopopip_id);
        // echo '<pre>';
        // print_r($data['rsfile']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sopopip_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($p_sopopip_id)
    {
        $this->p_sopopip_model->edit($p_sopopip_id);
        redirect('p_sopopip_backend');
    }

    public function update_p_sopopip_status()
    {
        $this->p_sopopip_model->update_p_sopopip_status();
    }

    public function del_pdf($pdf_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $pdf_id
        $this->p_sopopip_model->del_pdf($pdf_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_doc($doc_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $doc_id
        $this->p_sopopip_model->del_doc($doc_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->p_sopopip_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_p_sopopip($p_sopopip_id)
    {
        $this->p_sopopip_model->del_p_sopopip_img($p_sopopip_id);
        $this->p_sopopip_model->del_p_sopopip_pdf($p_sopopip_id);
        $this->p_sopopip_model->del_p_sopopip_doc($p_sopopip_id);
        $this->p_sopopip_model->del_p_sopopip($p_sopopip_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('p_sopopip_backend');
    }
}
