<?php
class Sme_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('space_model');
    }

    public function add()
    {
        $sme_name = $this->input->post('sme_name');

        // ตรวจสอบว่ามีข้อมูลที่มีชื่อ sme_name นี้อยู่แล้วหรือไม่
        $existing_record = $this->db->get_where('tbl_sme', array('sme_name' => $sme_name))->row();

        if ($existing_record) {
            // ถ้ามีข้อมูลแล้วให้แสดงข้อความแจ้งเตือนหรือทำตามที่ต้องการ
            $this->session->set_flashdata('save_again', TRUE);
        } else {
            // ถ้าไม่มีข้อมูลในฐานข้อมูลให้ทำการเพิ่มข้อมูล
            $data = array(
                'sme_name' => $sme_name,
                'sme_link' => $this->input->post('sme_link'),
                'sme_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่เพิ่มข้อมูล
            );

            $query = $this->db->insert('tbl_sme', $data);

            $this->space_model->update_server_current();

            if ($query) {
                $this->session->set_flashdata('save_success', TRUE);
            } else {
                echo "<script>";
                echo "alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูลใหม่ !');";
                echo "</script>";
            }
        }
    }



    public function list_all()
    {
        $this->db->order_by('sme_id', 'DESC');
        $query = $this->db->get('tbl_sme');
        return $query->result();
    }

    //show form edit
    public function read($sme_id)
    {
        $this->db->where('sme_id', $sme_id);
        $query = $this->db->get('tbl_sme');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function edit($sme_id)
    {

        $data = array(
            'sme_name' => $this->input->post('sme_name'),
            'sme_link' => $this->input->post('sme_link'),
            'sme_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่เพิ่มข้อมูล
        );

        $this->db->where('sme_id', $sme_id);
        $query = $this->db->update('tbl_sme', $data);

        $this->space_model->update_server_current();


        if ($query) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล !');";
            echo "</script>";
        }
    }

    public function del_sme($sme_id)
    {
        $this->db->delete('tbl_sme', array('sme_id' => $sme_id));
    }

    public function sme_frontend()
    {
        $this->db->select('*');
        $this->db->from('tbl_sme');
        $query = $this->db->get();
        return $query->result();
    }
}
