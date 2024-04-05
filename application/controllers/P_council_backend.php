<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_council_backend extends CI_Controller
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
        $this->load->model('p_council_model');
    }

    public function index()
    {

        $data['query_one'] = $this->p_council_model->p_council_one();
        $data['query_under_one'] = $this->p_council_model->p_council_under_one();
        // $data['query'] = $this->p_council_model->list_all();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_council', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding_p_council()
    {

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_council_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    // public function get_departments()
    // {
    //     $group_name = $this->input->post('group_name');
    //     $p_councilDepartments = $this->p_council_model->get_department_by_group($group_name);
    //     echo json_encode($p_councilDepartments);
    // }


    public function add_p_council()
    {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // exit;
        $this->p_council_model->add_p_council();
        redirect('p_council_backend', 'refresh');
    }

    public function editing_p_council($p_council_id)
    {
        $data['rsedit'] = $this->p_council_model->read($p_council_id);
        // $data['allcolumn'] = $this->p_council_model->getAllColumn($p_council_id);

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_council_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit_p_council($p_council_id)
    {
        $this->p_council_model->edit_p_council($p_council_id);
        redirect('p_council_backend', 'refresh');
    }

    public function del_p_council($p_council_id)
    {
        $this->p_council_model->del_p_council($p_council_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('p_council_backend', 'refresh');
    }
}
