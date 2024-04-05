<?php
class P_learder_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('space_model');
    }

    public function add_p_learder()
    {

        // Check used space
        $used_space_mb = $this->space_model->get_used_space();
        $upload_limit_mb = $this->space_model->get_limit_storage();

        // Calculate the total space required for all files
        $total_space_required = 0;
        if (!empty($_FILES['p_learder_img']['name'])) {
            $total_space_required += $_FILES['p_learder_img']['size'];
        }

        // Check if there's enough space
        if ($used_space_mb + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit_mb) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('p_learder/adding_p_learder');
            return;
        }

        $config['upload_path'] = './docs/img';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $config);

        // Upload main file
        if (!$this->upload->do_upload('p_learder_img')) {
            $this->session->set_flashdata('save_maxsize', TRUE);
            redirect('p_learder/adding_p_learder'); // กลับไปหน้าเดิม
            return;
        }
        $data = $this->upload->data();
        $filename =  $data['file_name'];


        $data = array(
            'p_learder_name' => $this->input->post('p_learder_name'),
            'p_learder_rank' => $this->input->post('p_learder_rank'),
            'p_learder_phone' => $this->input->post('p_learder_phone'),
            'p_learder_row' => $this->input->post('p_learder_row'),
            'p_learder_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่เพิ่มข้อมูล
            'p_learder_img' => $filename
        );

        $query = $this->db->insert('tbl_p_learder', $data);

        $this->space_model->update_server_current();


        if ($query) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('Error !');";
            echo "</script>";
        }
    }

    public function get_group()
    {
        $this->db->distinct();
        $this->db->select('pgroup_gname');
        $query = $this->db->get('tbl_p_learder_group');
        return $query->result();
    }

    public function get_department_by_group($group_name)
    {
        $this->db->distinct();
        $this->db->select('pgroup_dname');
        $this->db->where('pgroup_gname', $group_name);
        $query = $this->db->get('tbl_p_learder_group');
        return $query->result();
    }


    public function list_all()
    {
        $this->db->order_by('p_learder_row', 'asc');
        $this->db->order_by('p_learder_column', 'asc');
        $query = $this->db->get('tbl_p_learder');
        return $query->result();
    }

    //show form edit
    public function read($p_learder_id)
    {
        $this->db->where('p_learder_id', $p_learder_id);
        $query = $this->db->get('tbl_p_learder');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    // public function getAllColumn($p_learder_id)
    // {
    //     // ใช้ค่า p_learder_row ในการดึงข้อมูลทั้งหมดที่ต้องการ
    //     $old_row = $this->db->get_where('tbl_p_learder', array('p_learder_id' => $p_learder_id))->row();

    //     // ใช้ค่า p_learder_row ในการดึงข้อมูลทั้งหมดที่ต้องการ
    //     $this->db->where('p_learder_row', $old_row->p_learder_row);
    //     $this->db->order_by('p_learder_column', 'asc');
    //     $this->db->select('p_learder_column');
    //     $query = $this->db->get('tbl_p_learder');
    //     return $query->result_array();
    // }

    public function edit_p_learder($p_learder_id)
    {
        $old_document = $this->db->get_where('tbl_p_learder', array('p_learder_id' => $p_learder_id))->row();

        $update_doc_file = !empty($_FILES['p_learder_img']['name']) && $old_document->p_learder_img != $_FILES['p_learder_img']['name'];

        // ตรวจสอบว่ามีการอัพโหลดรูปภาพใหม่หรือไม่
        if ($update_doc_file) {
            $old_file_path = './docs/img/' . $old_document->p_learder_img;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }

            // Check used space
            $used_space_mb = $this->space_model->get_used_space();
            $upload_limit_mb = $this->space_model->get_limit_storage();

            $total_space_required = 0;
            if (!empty($_FILES['p_learder_img']['name'])) {
                $total_space_required += $_FILES['p_learder_img']['size'];
            }

            if ($used_space_mb + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit_mb) {
                $this->session->set_flashdata('save_error', TRUE);
                redirect('p_learder/editing_p_learder');
                return;
            }

            $config['upload_path'] = './docs/img';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('p_learder_img')) {
                echo $this->upload->display_errors();
                return;
            }

            $data = $this->upload->data();
            $filename = $data['file_name'];
        } else {
            // ใช้รูปภาพเดิม
            $filename = $old_document->p_learder_img;
        }

        $data = array(
            'p_learder_name' => $this->input->post('p_learder_name'),
            'p_learder_rank' => $this->input->post('p_learder_rank'),
            'p_learder_phone' => $this->input->post('p_learder_phone'),
            'p_learder_column' => $this->input->post('p_learder_column'),
            'p_learder_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่เพิ่มข้อมูล
            'p_learder_img' => $filename
        );

        $this->db->where('p_learder_id', $p_learder_id);
        $query = $this->db->update('tbl_p_learder', $data);

        // เปลี่ยนตำแหน่ง และ + 1 ค่าที่ *******
        $p_learder_row = $old_document->p_learder_row;

        $old_column = $old_document->p_learder_column;
        $new_column = $this->input->post('p_learder_column');

        // ดึงข้อมูลทั้งหมดจากฐานข้อมูล
        $all_column = $this->db->get('tbl_p_learder')->result_array();

        // ตรวจสอบตำแหน่งที่มีค่ามากกว่าหรือเท่ากับตำแหน่งที่เปลี่ยนมา
        foreach ($all_column as $column) {
            if ($column['p_learder_column'] >= $new_column && $column['p_learder_column'] <= $old_column) {
                // ตำแหน่งใหม่ที่จะตั้ง
                $new_column_value = $column['p_learder_column'] + 1;

                // อัปเดตตำแหน่งในฐานข้อมูล
                $this->db->where('p_learder_id', $column['p_learder_id']);
                $this->db->where('p_learder_row', $p_learder_row);
                $this->db->update('tbl_p_learder', ['p_learder_column' => $new_column_value]);
            }
        }

        // อัปเดตตำแหน่งของรายการที่กำลังแก้ไข
        $this->db->where('p_learder_id', $p_learder_id);
        $this->db->where('p_learder_row', $p_learder_row);
        $this->db->update('tbl_p_learder', ['p_learder_column' => $new_column]);

        $this->space_model->update_server_current();


        if ($query) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล !');";
            echo "</script>";
        }
    }

    public function del_p_learder($p_learder_id)
    {
        $old_document = $this->db->get_where('tbl_p_learder', array('p_learder_id' => $p_learder_id))->row();

        $old_file_path = './docs/img/' . $old_document->p_learder_img;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }

        // อัพเดทข้อมูลในฐานข้อมูลให้ค่าว่างหรือ null
        $data = array(
            'p_learder_name' => null,
            'p_learder_rank' => null,
            'p_learder_phone' => null,
            'p_learder_img' => null,
            'p_learder_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่เพิ่มข้อมูล
            // เพิ่มคอลัมน์อื่นๆ ที่ต้องการลบข้อมูล ให้ใส่ค่า null ด้วย
        );
        $this->db->where('p_learder_id', $p_learder_id);
        $this->db->update('tbl_p_learder', $data);
    }

    public function p_learder_one()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_id', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function p_learder_under_one()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $query = $this->db->get();
        return $query->result();
    }


    public function p_learder_frontend_one()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_id', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function p_learder_row_1()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_row', 1);
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $this->db->order_by('tbl_p_learder.p_learder_column', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_learder_row_2()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_row', 2);
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $this->db->order_by('tbl_p_learder.p_learder_column', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_learder_row_3()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_row', 3);
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $this->db->order_by('tbl_p_learder.p_learder_column', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_learder_row_4()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_row', 4);
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $this->db->order_by('tbl_p_learder.p_learder_column', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_learder_row_5()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_learder');
        $this->db->where('tbl_p_learder.p_learder_row', 5);
        $this->db->where('tbl_p_learder.p_learder_id !=', 1);
        $this->db->order_by('tbl_p_learder.p_learder_column', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

        // public function p_learder_frontend_list()
    // {
    //     $this->db->select('*');
    //     $this->db->from('tbl_p_learder');
    //     $this->db->where_in('tbl_p_learder.p_learder_id', array(2, 3, 4, 6));
    //     $this->db->order_by('tbl_p_learder.p_learder_id', 'asc');
    //     $query = $this->db->get();
    //     return $query->result();
    // }
}
