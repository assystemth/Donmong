<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_sp_backend extends CI_Controller
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
        $this->load->model('p_sp_model');
    }

    public function index()
    {
        $data['query'] = $this->p_sp_model->list_type();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sp_type', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add_type()
    {
        $this->p_sp_model->add_type();
        redirect('p_sp_backend');
    }

    public function index_detail($p_sp_type_id)
    {
        $query = $this->p_sp_model->list_all($p_sp_type_id);

        foreach ($query as $files) {
            $files->file = $this->p_sp_model->list_all_pdf($files->p_sp_id);
        }

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sp', ['query' => $query]);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding()
    {
        $data['rs_type'] = $this->p_sp_model->list_p_sp_type();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sp_form_add', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function add()
    {
        $p_sp_ref_id = $this->input->post('p_sp_ref_id');
        $this->p_sp_model->add();
        redirect('p_sp_backend/index_detail/' . $p_sp_ref_id);
    }


    public function editing($p_sp_id)
    {
        $data['rs_type'] = $this->p_sp_model->list_p_sp_type();

        $data['rsedit'] = $this->p_sp_model->read($p_sp_id);
        $data['rsFile'] = $this->p_sp_model->read_file($p_sp_id);
        // echo '<pre>';
        // print_r($data['rsedit']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sp_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function editing_type($p_sp_id)
    {
        $data['rs_type'] = $this->p_sp_model->read_type($p_sp_id);

        // echo '<pre>';
        // print_r($data['rsedit']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_sp_type_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit($p_sp_id)
    {
        $p_sp_ref_id = $this->input->post('p_sp_ref_id');
        $this->p_sp_model->edit($p_sp_id);
        redirect('p_sp_backend/index_detail/' . $p_sp_ref_id);
    }

    public function edit_type($p_sp_type_id)
    {
        $this->p_sp_model->edit_type($p_sp_type_id);
        redirect('p_sp_backend');
    }

    public function toggleUserOperationCdmStatus()
    {
        if ($this->input->post()) {
            $operationCdmId = $this->input->post('p_sp_id');
            $newStatus = $this->input->post('new_status');

            // ทำการอัพเดตค่าในตาราง tbl_p_sp ในฐานข้อมูลของคุณ
            $data = array(
                'p_sp_status' => $newStatus
            );
            $this->db->where('p_sp_id', $operationCdmId);
            $this->db->update('tbl_p_sp', $data);

            $response = array('status' => 'success', 'message' => 'อัพเดตสถานะเรียบร้อย');
            echo json_encode($response);
        } else {
            show_404();
        }
    }

    public function del_pdf($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->p_sp_model->del_pdf($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_img($file_id)
    {
        // เรียกใช้ฟังก์ชันใน Model เพื่อลบไฟล์ PDF ด้วย $file_id
        $this->p_sp_model->del_img($file_id);

        // ใส่สคริปต์ JavaScript เพื่อรีเฟรชหน้าเดิม
        echo '<script>window.history.back();</script>';
    }

    public function del_p_sp($p_sp_id)
    {
        $this->p_sp_model->del_p_sp_pdf($p_sp_id);
        $this->p_sp_model->del_p_sp($p_sp_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('p_sp_backend');
    }

    public function del_p_sp_type($p_sp_type_id)
    {
        $this->p_sp_model->del_p_sp_pdf($p_sp_type_id);
        $this->p_sp_model->del_p_sp($p_sp_type_id);
        $this->p_sp_model->del_p_sp_type($p_sp_type_id);
        $this->space_model->update_server_current();
        $this->session->set_flashdata('del_success', TRUE);
        redirect('p_sp_backend');
    }
}
