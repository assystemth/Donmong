<?php
class P_sp_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('space_model');
    }

    public function add_type()
    {

        $data = array(
            'p_sp_type_name' => $this->input->post('p_sp_type_name'),
            'p_sp_type_date' => $this->input->post('p_sp_type_date'),
            'p_sp_type_by' => $this->session->userdata('m_fname'),
        );

        $query = $this->db->insert('tbl_p_sp_type', $data);

        $this->space_model->update_server_current();

        if ($query) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('Error !');";
            echo "</script>";
        }
    }

    public function edit_type($p_sp_type_id)
    {

        $data = array(
            'p_sp_type_name' => $this->input->post('p_sp_type_name'),
            'p_sp_type_date' => $this->input->post('p_sp_type_date'),
            'p_sp_type_by' => $this->session->userdata('m_fname'),
        );

        $this->db->where('p_sp_type_id', $p_sp_type_id);
        $this->db->update('tbl_p_sp_type', $data);

        $this->space_model->update_server_current();

        if ($data) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            echo "<script>";
            echo "alert('Error !');";
            echo "</script>";
        }
    }

    public function list_type()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_sp_type');
        $this->db->group_by('tbl_p_sp_type.p_sp_type_id');
        $this->db->order_by('tbl_p_sp_type.p_sp_type_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function list_p_sp_type()
    {
        $this->db->order_by('p_sp_type_id', 'DESC');
        $query = $this->db->get('tbl_p_sp_type');
        return $query->result();
    }


    public function add()
    {
        // Configure PDF upload
        $pdf_config['upload_path'] = './docs/file';
        $pdf_config['allowed_types'] = 'pdf';
        $this->load->library('upload', $pdf_config, 'pdf_upload');

        // // Configure image upload
        // $img_config['upload_path'] = './docs/img';
        // $img_config['allowed_types'] = 'gif|jpg|png|jpeg';
        // $this->load->library('upload', $img_config, 'img_upload');


        // กำหนดค่าใน $p_sp_data
        $p_sp_data = array(
            'p_sp_ref_id' => $this->input->post('p_sp_ref_id'),
            'p_sp_name' => $this->input->post('p_sp_name'),
            'p_sp_detail' => $this->input->post('p_sp_detail'),
            'p_sp_date' => $this->input->post('p_sp_date'),
            'p_sp_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่แก้ไขข้อมูล
        );

        // // ทำการอัปโหลดรูปภาพ
        // $img_main = $this->img_upload->do_upload('p_sp_img');
        // // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
        // if (!empty($img_main)) {
        //     // ถ้ามีการอัปโหลดรูปภาพ
        //     $p_sp_data['p_sp_img'] = $this->img_upload->data('file_name');
        // } // else {
        // //     // ถ้าไม่มีการอัปโหลดรูปภาพ ให้ใช้รูปภาพ default
        // //     $p_sp_data['p_sp_img'] = 'coverphoto.png';
        // // }
        // // เพิ่มข้อมูลลงในฐานข้อมูล
        $this->db->insert('tbl_p_sp', $p_sp_data);
        $p_sp_id = $this->db->insert_id();

        // หาพื้นที่ว่าง และอัพโหลดlimit จากฐานข้อมูล
        $used_space = $this->space_model->get_used_space();
        $upload_limit = $this->space_model->get_limit_storage();

        $total_space_required = 0;
        // // ตรวจสอบว่ามีข้อมูลรูปภาพเพิ่มเติมหรือไม่
        // if (isset($_FILES['p_sp_img_img'])) {
        //     foreach ($_FILES['p_sp_img_img']['name'] as $index => $name) {
        //         if (isset($_FILES['p_sp_img_img']['size'][$index])) {
        //             $total_space_required += $_FILES['p_sp_img_img']['size'][$index];
        //         }
        //     }
        // }

        // ตรวจสอบว่ามีข้อมูลไฟล์ PDF หรือไม่
        if (isset($_FILES['p_sp_file_pdf'])) {
            foreach ($_FILES['p_sp_file_pdf']['name'] as $index => $name) {
                if (isset($_FILES['p_sp_file_pdf']['size'][$index])) {
                    $total_space_required += $_FILES['p_sp_file_pdf']['size'][$index];
                }
            }
        }

        // เช็คค่าว่าง
        if ($used_space + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('p_sp_backend/adding');
            return;
        }

        // $imgs_data = array();

        // // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        // if (!empty($_FILES['p_sp_img_img']['name'][0])) {
        //     foreach ($_FILES['p_sp_img_img']['name'] as $index => $name) {
        //         $_FILES['p_sp_img_img_multiple']['name'] = $name;
        //         $_FILES['p_sp_img_img_multiple']['type'] = $_FILES['p_sp_img_img']['type'][$index];
        //         $_FILES['p_sp_img_img_multiple']['tmp_name'] = $_FILES['p_sp_img_img']['tmp_name'][$index];
        //         $_FILES['p_sp_img_img_multiple']['error'] = $_FILES['p_sp_img_img']['error'][$index];
        //         $_FILES['p_sp_img_img_multiple']['size'] = $_FILES['p_sp_img_img']['size'][$index];

        //         if ($this->img_upload->do_upload('p_sp_img_img_multiple')) {
        //             $upload_data = $this->img_upload->data();
        //             $imgs_data[] = array(
        //                 'p_sp_img_ref_id' => $p_sp_id,
        //                 'p_sp_img_img' => $upload_data['file_name']
        //             );
        //         }
        //     }
        //     $this->db->insert_batch('tbl_p_sp_img', $imgs_data);
        // }

        $pdf_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['p_sp_file_pdf']['name'][0])) {
            foreach ($_FILES['p_sp_file_pdf']['name'] as $index => $name) {
                $_FILES['p_sp_file_pdf_multiple']['name'] = $name;
                $_FILES['p_sp_file_pdf_multiple']['type'] = $_FILES['p_sp_file_pdf']['type'][$index];
                $_FILES['p_sp_file_pdf_multiple']['tmp_name'] = $_FILES['p_sp_file_pdf']['tmp_name'][$index];
                $_FILES['p_sp_file_pdf_multiple']['error'] = $_FILES['p_sp_file_pdf']['error'][$index];
                $_FILES['p_sp_file_pdf_multiple']['size'] = $_FILES['p_sp_file_pdf']['size'][$index];

                if ($this->pdf_upload->do_upload('p_sp_file_pdf_multiple')) {
                    $upload_data = $this->pdf_upload->data();
                    $pdf_data[] = array(
                        'p_sp_file_ref_id' => $p_sp_id,
                        'p_sp_file_pdf' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_p_sp_file', $pdf_data);
        }
        $this->space_model->update_server_current();
        $this->session->set_flashdata('save_success', TRUE);
    }

    public function list_all($p_sp_type_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_p_sp');
        $this->db->join('tbl_p_sp_type', 'tbl_p_sp.p_sp_ref_id = tbl_p_sp_type.p_sp_type_id', 'inner'); // เปลี่ยนเป็น INNER JOIN
        $this->db->where('tbl_p_sp_type.p_sp_type_id', $p_sp_type_id);
        $query = $this->db->get();
        return $query->result();
    }


    public function list_all_pdf($p_sp_id)
    {
        $this->db->select('p_sp_file_pdf');
        $this->db->from('tbl_p_sp_file');
        $this->db->where('p_sp_file_ref_id', $p_sp_id);
        return $this->db->get()->result();
    }

    //show form edit
    public function read_type($p_sp_id)
    {
        $this->db->where('p_sp_type_id', $p_sp_id);
        $query = $this->db->get('tbl_p_sp_type');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function read($p_sp_id)
    {
        $this->db->select('m.*,t.p_sp_type_name');
        $this->db->from('tbl_p_sp as m');
        $this->db->join('tbl_p_sp_type as t', 'm.p_sp_ref_id=t.p_sp_type_id');
        $this->db->where('m.p_sp_id', $p_sp_id);
        $query = $this->db->get('tbl_p_sp');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function read_file($p_sp_id)
    {
        $this->db->where('p_sp_file_ref_id', $p_sp_id);
        $this->db->order_by('p_sp_file_id', 'DESC');
        $query = $this->db->get('tbl_p_sp_file');
        return $query->result();
    }

    public function read_img($p_sp_id)
    {
        $this->db->where('p_sp_img_ref_id', $p_sp_id);
        $this->db->order_by('p_sp_img_id', 'DESC');
        $query = $this->db->get('tbl_p_sp_img');
        return $query->result();
    }

    public function del_pdf($file_id)
    {
        // ดึงชื่อไฟล์ PDF จากฐานข้อมูลโดยใช้ $file_id
        $this->db->select('p_sp_file_pdf');
        $this->db->where('p_sp_file_id', $file_id);
        $query = $this->db->get('tbl_p_sp_file');
        $file_data = $query->row();

        // ลบไฟล์จากแหล่งที่เก็บไฟล์ (อาจต้องใช้ unlink หรือวิธีอื่น)
        $file_path = './docs/file/' . $file_data->p_sp_file_pdf;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // ลบข้อมูลของไฟล์จากฐานข้อมูล
        $this->db->where('p_sp_file_id', $file_id);
        $this->db->delete('tbl_p_sp_file');
    }

    public function del_img($file_id)
    {
        // ดึงชื่อไฟล์ PDF จากฐานข้อมูลโดยใช้ $file_id
        $this->db->select('p_sp_img_img');
        $this->db->where('p_sp_img_id', $file_id);
        $query = $this->db->get('tbl_p_sp_img');
        $file_data = $query->row();

        // ลบไฟล์จากแหล่งที่เก็บไฟล์ (อาจต้องใช้ unlink หรือวิธีอื่น)
        $file_path = './docs/img/' . $file_data->p_sp_img_img;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // ลบข้อมูลของไฟล์จากฐานข้อมูล
        $this->db->where('p_sp_img_id', $file_id);
        $this->db->delete('tbl_p_sp_img');
        $this->space_model->update_server_current();
        $this->session->set_flashdata('del_success', TRUE);
    }


    public function edit($p_sp_id)
    {
        // Update p_sp information
        $data = array(
            'p_sp_ref_id' => $this->input->post('p_sp_ref_id'),
            'p_sp_name' => $this->input->post('p_sp_name'),
            'p_sp_detail' => $this->input->post('p_sp_detail'),
            'p_sp_date' => $this->input->post('p_sp_date'),
            'p_sp_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่แก้ไขข้อมูล
        );

        $this->db->where('p_sp_id', $p_sp_id);
        $this->db->update('tbl_p_sp', $data);

        // หาพื้นที่ว่าง และอัพโหลดlimit จากฐานข้อมูล
        $used_space = $this->space_model->get_used_space();
        $upload_limit = $this->space_model->get_limit_storage();

        $total_space_required = 0;
        // ตรวจสอบว่ามีข้อมูลรูปภาพเพิ่มเติมหรือไม่
        if (isset($_FILES['p_sp_img_img'])) {
            foreach ($_FILES['p_sp_img_img']['name'] as $index => $name) {
                if (isset($_FILES['p_sp_img_img']['size'][$index])) {
                    $total_space_required += $_FILES['p_sp_img_img']['size'][$index];
                }
            }
        }

        // ตรวจสอบว่ามีข้อมูลไฟล์ PDF หรือไม่
        if (isset($_FILES['p_sp_file_pdf'])) {
            foreach ($_FILES['p_sp_file_pdf']['name'] as $index => $name) {
                if (isset($_FILES['p_sp_file_pdf']['size'][$index])) {
                    $total_space_required += $_FILES['p_sp_file_pdf']['size'][$index];
                }
            }
        }

        // เช็คค่าว่าง
        if ($used_space + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('p_sp_backend/adding');
            return;
        }

        $pdf_config['upload_path'] = './docs/file';
        $pdf_config['allowed_types'] = 'pdf';
        $this->load->library('upload', $pdf_config, 'pdf_upload');

        $img_config['upload_path'] = './docs/img';
        $img_config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $img_config, 'img_upload');

        // ทำการอัปโหลดรูปภาพ
        $img_main = $this->img_upload->do_upload('p_sp_img');

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
        if (!empty($img_main)) {
            $this->db->trans_start(); // เริ่ม Transaction

            // ดึงข้อมูลรูปเก่า
            $old_document = $this->db->get_where('tbl_p_sp', array('p_sp_id' => $p_sp_id))->row();

            // ตรวจสอบว่ามีไฟล์เก่าหรือไม่
            if ($old_document && $old_document->p_sp_img) {
                $old_file_path = './docs/img/' . $old_document->p_sp_img;

                if (file_exists($old_file_path)) {
                    unlink($old_file_path); // ลบไฟล์เก่า
                }
            }

            // ถ้ามีการอัปโหลดรูปภาพใหม่
            $img_data['p_sp_img'] = $this->img_upload->data('file_name');
            $this->db->where('p_sp_id', $p_sp_id);
            $this->db->update('tbl_p_sp', $img_data);

            $this->db->trans_complete(); // สิ้นสุด Transaction
        }

        $imgs_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['p_sp_img_img']['name'][0])) {

            foreach ($_FILES['p_sp_img_img']['name'] as $index => $name) {
                $_FILES['p_sp_img_img_multiple']['name'] = $name;
                $_FILES['p_sp_img_img_multiple']['type'] = $_FILES['p_sp_img_img']['type'][$index];
                $_FILES['p_sp_img_img_multiple']['tmp_name'] = $_FILES['p_sp_img_img']['tmp_name'][$index];
                $_FILES['p_sp_img_img_multiple']['error'] = $_FILES['p_sp_img_img']['error'][$index];
                $_FILES['p_sp_img_img_multiple']['size'] = $_FILES['p_sp_img_img']['size'][$index];

                if ($this->img_upload->do_upload('p_sp_img_img_multiple')) {
                    $upload_data = $this->img_upload->data();
                    $imgs_data[] = array(
                        'p_sp_img_ref_id' => $p_sp_id,
                        'p_sp_img_img' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_p_sp_img', $imgs_data);
        }

        $pdf_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['p_sp_file_pdf']['name'][0])) {
            foreach ($_FILES['p_sp_file_pdf']['name'] as $index => $name) {
                $_FILES['p_sp_file_pdf_multiple']['name'] = $name;
                $_FILES['p_sp_file_pdf_multiple']['type'] = $_FILES['p_sp_file_pdf']['type'][$index];
                $_FILES['p_sp_file_pdf_multiple']['tmp_name'] = $_FILES['p_sp_file_pdf']['tmp_name'][$index];
                $_FILES['p_sp_file_pdf_multiple']['error'] = $_FILES['p_sp_file_pdf']['error'][$index];
                $_FILES['p_sp_file_pdf_multiple']['size'] = $_FILES['p_sp_file_pdf']['size'][$index];

                if ($this->pdf_upload->do_upload('p_sp_file_pdf_multiple')) {
                    $upload_data = $this->pdf_upload->data();
                    $pdf_data[] = array(
                        'p_sp_file_ref_id' => $p_sp_id,
                        'p_sp_file_pdf' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_p_sp_file', $pdf_data);
        }
        $this->space_model->update_server_current();
        $this->session->set_flashdata('save_success', TRUE);
    }

    public function del_p_sp_type($p_sp_type_id)
    {
        $this->db->delete('tbl_p_sp_type', array('p_sp_type_id' => $p_sp_type_id));
        $this->space_model->update_server_current();
    }


    public function del_p_sp($p_sp_type_id)
    {
        $this->db->delete('tbl_p_sp', array('p_sp_ref_id' => $p_sp_type_id));
        $this->space_model->update_server_current();
    }

    public function del_p_sp_pdf($p_sp_type_id)
    {
        $p_sp_ids = $this->get_p_sp_id($p_sp_type_id);

        if ($p_sp_ids) {
            // แปลง array เป็น string โดยคั่นด้วย ','
            $p_sp_ids_string = implode(',', $p_sp_ids);

            // สร้าง SQL query โดยใส่ ' รอบแต่ละค่า
            $sql = "SELECT * FROM `tbl_p_sp_file` WHERE p_sp_file_ref_id IN ($p_sp_ids_string)";

            // ดึงข้อมูลรายการรูปภาพที่ต้องการลบ
            $files = $this->db->query($sql)->result();

            // ลบรูปภาพจากตาราง tbl_p_sp_file
            $this->db->where_in('p_sp_file_ref_id', $p_sp_ids);
            $this->db->delete('tbl_p_sp_file');

            // ลบไฟล์รูปภาพที่เกี่ยวข้อง
            foreach ($files as $file) {
                $file_path = './docs/file/' . $file->p_sp_file_pdf;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
    }

    public function get_p_sp_id($p_sp_type_id)
    {
        $query = $this->db->select('p_sp_id')
            ->from('tbl_p_sp')
            ->where('p_sp_ref_id', $p_sp_type_id)
            ->get();

        if ($query->num_rows() > 0) {
            $result = array();
            foreach ($query->result() as $row) {
                $result[] = $row->p_sp_id;
            }
            return $result; // คืน Array ของ p_sp_id ทั้งหมด
        } else {
            return null; // หรือค่าที่เหมาะสมเมื่อไม่พบข้อมูล
        }
    }

    public function del_p_sp_img($p_sp_id)
    {
        // ดึงข้อมูลรายการรูปภาพก่อน
        $files = $this->db->get_where('tbl_p_sp_img', array('p_sp_img_ref_id' => $p_sp_id))->result();

        // ลบรูปภาพจากตาราง tbl_p_sp_file
        $this->db->where('p_sp_img_ref_id', $p_sp_id);
        $this->db->delete('tbl_p_sp_img');

        // ลบไฟล์รูปภาพที่เกี่ยวข้อง
        foreach ($files as $files) {
            $file_path = './docs/img/' . $files->p_sp_img_img;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }


    public function p_sp_topic_frontend()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_sp_type');
        $this->db->limit(8);
        $this->db->order_by('tbl_p_sp_type.p_sp_type_date', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_sp_frontend_list_topic()
    {
        $this->db->select('*');
        $this->db->from('tbl_p_sp_type');
        $this->db->order_by('tbl_p_sp_type.p_sp_type_date', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function p_sp_frontend_list($p_sp_type_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_p_sp');
        $this->db->join('tbl_p_sp_type', 'tbl_p_sp.p_sp_ref_id = tbl_p_sp_type.p_sp_type_id', 'inner'); // เปลี่ยนเป็น INNER JOIN
        $this->db->where('tbl_p_sp_type.p_sp_type_id', $p_sp_type_id);
        $this->db->where('tbl_p_sp.p_sp_status', 'show');
        $query = $this->db->get();
        return $query->result();
    }
    public function increment_view($p_sp_id)
    {
        $this->db->where('p_sp_id', $p_sp_id);
        $this->db->set('p_sp_view', 'p_sp_view + 1', false); // บวกค่า p_sp_view ทีละ 1
        $this->db->update('tbl_p_sp');
    }
    // ใน p_sp_model
    public function increment_download_p_sp($p_sp_file_id)
    {
        $this->db->where('p_sp_file_id', $p_sp_file_id);
        $this->db->set('p_sp_file_download', 'p_sp_file_download + 1', false); // บวกค่า p_sp_download ทีละ 1
        $this->db->update('tbl_p_sp_file');
    }
}
