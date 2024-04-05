<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_learder_backend extends CI_Controller
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
        $this->load->model('p_learder_model');
    }

    public function index()
    {

        $data['query_one'] = $this->p_learder_model->p_learder_one();
        $data['query_under_one'] = $this->p_learder_model->p_learder_under_one();
        // $data['query'] = $this->p_learder_model->list_all();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_learder', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function adding_p_learder()
    {

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_learder_form_add');
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    // public function get_departments()
    // {
    //     $group_name = $this->input->post('group_name');
    //     $p_learderDepartments = $this->p_learder_model->get_department_by_group($group_name);
    //     echo json_encode($p_learderDepartments);
    // }


    public function add_p_learder()
    {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // exit;
        $this->p_learder_model->add_p_learder();
        redirect('p_learder_backend', 'refresh');
    }

    public function editing_p_learder($p_learder_id)
    {
        $data['rsedit'] = $this->p_learder_model->read($p_learder_id);
        // $data['allcolumn'] = $this->p_learder_model->getAllColumn($p_learder_id);
        // $data['p_learderGroup'] = $this->p_learder_model->get_group();
        // echo '<pre>';
        // print_r($data['rsedit']);
        // echo '</pre>';
        // exit();

        $this->load->view('templat/header');
        $this->load->view('asset/css');
        $this->load->view('templat/navbar_system_admin');
        $this->load->view('system_admin/p_learder_form_edit', $data);
        $this->load->view('asset/js');
        $this->load->view('templat/footer');
    }

    public function edit_p_learder($p_learder_id)
    {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        // exit;
        $this->p_learder_model->edit_p_learder($p_learder_id);
        redirect('p_learder_backend', 'refresh');
    }

    public function del_p_learder($p_learder_id)
    {
        $this->p_learder_model->del_p_learder($p_learder_id);
        $this->session->set_flashdata('del_success', TRUE);
        redirect('p_learder_backend', 'refresh');
    }
}
