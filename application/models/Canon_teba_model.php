<?php
class canon_teba_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('space_model');
    }

    public function add()
    {
        // Configure PDF upload
        $pdf_config['upload_path'] = './docs/file';
        $pdf_config['allowed_types'] = 'pdf';
        $this->load->library('upload', $pdf_config, 'pdf_upload');

        // Configure image upload
        $img_config['upload_path'] = './docs/img';
        $img_config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $img_config, 'img_upload');


        // กำหนดค่าใน $canon_teba_data
        $canon_teba_data = array(
            'canon_teba_name' => $this->input->post('canon_teba_name'),
            'canon_teba_detail' => $this->input->post('canon_teba_detail'),
            'canon_teba_date' => $this->input->post('canon_teba_date'),
            'canon_teba_link' => $this->input->post('canon_teba_link'),
            'canon_teba_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่แก้ไขข้อมูล
        );

        // ทำการอัปโหลดรูปภาพ
        $img_main = $this->img_upload->do_upload('canon_teba_img');
        // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
        if (!empty($img_main)) {
            // ถ้ามีการอัปโหลดรูปภาพ
            $canon_teba_data['canon_teba_img'] = $this->img_upload->data('file_name');
        }
        // เพิ่มข้อมูลลงในฐานข้อมูล
        $this->db->insert('tbl_canon_teba', $canon_teba_data);
        $canon_teba_id = $this->db->insert_id();

        // หาพื้นที่ว่าง และอัพโหลดlimit จากฐานข้อมูล
        $used_space = $this->space_model->get_used_space();
        $upload_limit = $this->space_model->get_limit_storage();

        $total_space_required = 0;
        // ตรวจสอบว่ามีข้อมูลรูปภาพเพิ่มเติมหรือไม่
        if (isset($_FILES['canon_teba_img_img'])) {
            foreach ($_FILES['canon_teba_img_img']['name'] as $index => $name) {
                if (isset($_FILES['canon_teba_img_img']['size'][$index])) {
                    $total_space_required += $_FILES['canon_teba_img_img']['size'][$index];
                }
            }
        }

        // ตรวจสอบว่ามีข้อมูลไฟล์ PDF หรือไม่
        if (isset($_FILES['canon_teba_file_pdf'])) {
            foreach ($_FILES['canon_teba_file_pdf']['name'] as $index => $name) {
                if (isset($_FILES['canon_teba_file_pdf']['size'][$index])) {
                    $total_space_required += $_FILES['canon_teba_file_pdf']['size'][$index];
                }
            }
        }

        // เช็คค่าว่าง
        if ($used_space + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('canon_teba_backend/adding');
            return;
        }

        $imgs_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['canon_teba_img_img']['name'][0])) {
            foreach ($_FILES['canon_teba_img_img']['name'] as $index => $name) {
                $_FILES['canon_teba_img_img_multiple']['name'] = $name;
                $_FILES['canon_teba_img_img_multiple']['type'] = $_FILES['canon_teba_img_img']['type'][$index];
                $_FILES['canon_teba_img_img_multiple']['tmp_name'] = $_FILES['canon_teba_img_img']['tmp_name'][$index];
                $_FILES['canon_teba_img_img_multiple']['error'] = $_FILES['canon_teba_img_img']['error'][$index];
                $_FILES['canon_teba_img_img_multiple']['size'] = $_FILES['canon_teba_img_img']['size'][$index];

                if ($this->img_upload->do_upload('canon_teba_img_img_multiple')) {
                    $upload_data = $this->img_upload->data();
                    $imgs_data[] = array(
                        'canon_teba_img_ref_id' => $canon_teba_id,
                        'canon_teba_img_img' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_canon_teba_img', $imgs_data);
        }

        $pdf_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['canon_teba_file_pdf']['name'][0])) {
            foreach ($_FILES['canon_teba_file_pdf']['name'] as $index => $name) {
                $_FILES['canon_teba_file_pdf_multiple']['name'] = $name;
                $_FILES['canon_teba_file_pdf_multiple']['type'] = $_FILES['canon_teba_file_pdf']['type'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['tmp_name'] = $_FILES['canon_teba_file_pdf']['tmp_name'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['error'] = $_FILES['canon_teba_file_pdf']['error'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['size'] = $_FILES['canon_teba_file_pdf']['size'][$index];

                if ($this->pdf_upload->do_upload('canon_teba_file_pdf_multiple')) {
                    $upload_data = $this->pdf_upload->data();
                    $pdf_data[] = array(
                        'canon_teba_file_ref_id' => $canon_teba_id,
                        'canon_teba_file_pdf' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_canon_teba_file', $pdf_data);
        }
        $this->space_model->update_server_current();
        $this->session->set_flashdata('save_success', TRUE);
    }

    public function list_all()
    {
        $this->db->select('*');
        $this->db->from('tbl_canon_teba');
        $this->db->group_by('tbl_canon_teba.canon_teba_id');
        $this->db->order_by('tbl_canon_teba.canon_teba_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function list_all_pdf($canon_teba_id)
    {
        $this->db->select('canon_teba_file_pdf');
        $this->db->from('tbl_canon_teba_file');
        $this->db->where('canon_teba_file_ref_id', $canon_teba_id);
        return $this->db->get()->result();
    }

    //show form edit
    public function read($canon_teba_id)
    {
        $this->db->where('canon_teba_id', $canon_teba_id);
        $query = $this->db->get('tbl_canon_teba');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function read_file($canon_teba_id)
    {
        $this->db->where('canon_teba_file_ref_id', $canon_teba_id);
        $this->db->order_by('canon_teba_file_id', 'DESC');
        $query = $this->db->get('tbl_canon_teba_file');
        return $query->result();
    }

    public function read_img($canon_teba_id)
    {
        $this->db->where('canon_teba_img_ref_id', $canon_teba_id);
        $this->db->order_by('canon_teba_img_id', 'DESC');
        $query = $this->db->get('tbl_canon_teba_img');
        return $query->result();
    }

    public function del_pdf($file_id)
    {
        // ดึงชื่อไฟล์ PDF จากฐานข้อมูลโดยใช้ $file_id
        $this->db->select('canon_teba_file_pdf');
        $this->db->where('canon_teba_file_id', $file_id);
        $query = $this->db->get('tbl_canon_teba_file');
        $file_data = $query->row();

        // ลบไฟล์จากแหล่งที่เก็บไฟล์ (อาจต้องใช้ unlink หรือวิธีอื่น)
        $file_path = './docs/file/' . $file_data->canon_teba_file_pdf;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // ลบข้อมูลของไฟล์จากฐานข้อมูล
        $this->db->where('canon_teba_file_id', $file_id);
        $this->db->delete('tbl_canon_teba_file');
    }

    public function del_img($file_id)
    {
        // ดึงชื่อไฟล์ PDF จากฐานข้อมูลโดยใช้ $file_id
        $this->db->select('canon_teba_img_img');
        $this->db->where('canon_teba_img_id', $file_id);
        $query = $this->db->get('tbl_canon_teba_img');
        $file_data = $query->row();

        // ลบไฟล์จากแหล่งที่เก็บไฟล์ (อาจต้องใช้ unlink หรือวิธีอื่น)
        $file_path = './docs/img/' . $file_data->canon_teba_img_img;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // ลบข้อมูลของไฟล์จากฐานข้อมูล
        $this->db->where('canon_teba_img_id', $file_id);
        $this->db->delete('tbl_canon_teba_img');
        $this->space_model->update_server_current();
        $this->session->set_flashdata('del_success', TRUE);
    }


    public function edit($canon_teba_id)
    {
        // Update canon_teba information
        $data = array(
            'canon_teba_name' => $this->input->post('canon_teba_name'),
            'canon_teba_detail' => $this->input->post('canon_teba_detail'),
            'canon_teba_date' => $this->input->post('canon_teba_date'),
            'canon_teba_link' => $this->input->post('canon_teba_link'),
            'canon_teba_by' => $this->session->userdata('m_fname'), // เพิ่มชื่อคนที่แก้ไขข้อมูล
        );

        $this->db->where('canon_teba_id', $canon_teba_id);
        $this->db->update('tbl_canon_teba', $data);

        // หาพื้นที่ว่าง และอัพโหลดlimit จากฐานข้อมูล
        $used_space = $this->space_model->get_used_space();
        $upload_limit = $this->space_model->get_limit_storage();

        $total_space_required = 0;
        // ตรวจสอบว่ามีข้อมูลรูปภาพเพิ่มเติมหรือไม่
        if (isset($_FILES['canon_teba_img_img'])) {
            foreach ($_FILES['canon_teba_img_img']['name'] as $index => $name) {
                if (isset($_FILES['canon_teba_img_img']['size'][$index])) {
                    $total_space_required += $_FILES['canon_teba_img_img']['size'][$index];
                }
            }
        }

        // ตรวจสอบว่ามีข้อมูลไฟล์ PDF หรือไม่
        if (isset($_FILES['canon_teba_file_pdf'])) {
            foreach ($_FILES['canon_teba_file_pdf']['name'] as $index => $name) {
                if (isset($_FILES['canon_teba_file_pdf']['size'][$index])) {
                    $total_space_required += $_FILES['canon_teba_file_pdf']['size'][$index];
                }
            }
        }

        // เช็คค่าว่าง
        if ($used_space + ($total_space_required / (1024 * 1024 * 1024)) >= $upload_limit) {
            $this->session->set_flashdata('save_error', TRUE);
            redirect('canon_teba_backend/adding');
            return;
        }

        $pdf_config['upload_path'] = './docs/file';
        $pdf_config['allowed_types'] = 'pdf';
        $this->load->library('upload', $pdf_config, 'pdf_upload');

        $img_config['upload_path'] = './docs/img';
        $img_config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $img_config, 'img_upload');

        // ทำการอัปโหลดรูปภาพ
        $img_main = $this->img_upload->do_upload('canon_teba_img');

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
        if (!empty($img_main)) {
            $this->db->trans_start(); // เริ่ม Transaction

            // ดึงข้อมูลรูปเก่า
            $old_document = $this->db->get_where('tbl_canon_teba', array('canon_teba_id' => $canon_teba_id))->row();

            // ตรวจสอบว่ามีไฟล์เก่าหรือไม่
            if ($old_document && $old_document->canon_teba_img) {
                $old_file_path = './docs/img/' . $old_document->canon_teba_img;

                if (file_exists($old_file_path)) {
                    unlink($old_file_path); // ลบไฟล์เก่า
                }
            }

            // ถ้ามีการอัปโหลดรูปภาพใหม่
            $img_data['canon_teba_img'] = $this->img_upload->data('file_name');
            $this->db->where('canon_teba_id', $canon_teba_id);
            $this->db->update('tbl_canon_teba', $img_data);

            $this->db->trans_complete(); // สิ้นสุด Transaction
        }

        $imgs_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['canon_teba_img_img']['name'][0])) {

            foreach ($_FILES['canon_teba_img_img']['name'] as $index => $name) {
                $_FILES['canon_teba_img_img_multiple']['name'] = $name;
                $_FILES['canon_teba_img_img_multiple']['type'] = $_FILES['canon_teba_img_img']['type'][$index];
                $_FILES['canon_teba_img_img_multiple']['tmp_name'] = $_FILES['canon_teba_img_img']['tmp_name'][$index];
                $_FILES['canon_teba_img_img_multiple']['error'] = $_FILES['canon_teba_img_img']['error'][$index];
                $_FILES['canon_teba_img_img_multiple']['size'] = $_FILES['canon_teba_img_img']['size'][$index];

                if ($this->img_upload->do_upload('canon_teba_img_img_multiple')) {
                    $upload_data = $this->img_upload->data();
                    $imgs_data[] = array(
                        'canon_teba_img_ref_id' => $canon_teba_id,
                        'canon_teba_img_img' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_canon_teba_img', $imgs_data);
        }

        $pdf_data = array();

        // ตรวจสอบว่ามีการอัปโหลดรูปภาพเพิ่มเติมหรือไม่
        if (!empty($_FILES['canon_teba_file_pdf']['name'][0])) {
            foreach ($_FILES['canon_teba_file_pdf']['name'] as $index => $name) {
                $_FILES['canon_teba_file_pdf_multiple']['name'] = $name;
                $_FILES['canon_teba_file_pdf_multiple']['type'] = $_FILES['canon_teba_file_pdf']['type'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['tmp_name'] = $_FILES['canon_teba_file_pdf']['tmp_name'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['error'] = $_FILES['canon_teba_file_pdf']['error'][$index];
                $_FILES['canon_teba_file_pdf_multiple']['size'] = $_FILES['canon_teba_file_pdf']['size'][$index];

                if ($this->pdf_upload->do_upload('canon_teba_file_pdf_multiple')) {
                    $upload_data = $this->pdf_upload->data();
                    $pdf_data[] = array(
                        'canon_teba_file_ref_id' => $canon_teba_id,
                        'canon_teba_file_pdf' => $upload_data['file_name']
                    );
                }
            }
            $this->db->insert_batch('tbl_canon_teba_file', $pdf_data);
        }
        $this->space_model->update_server_current();
        $this->session->set_flashdata('save_success', TRUE);
    }



    public function del_canon_teba($canon_teba_id)
    {
        $old_document = $this->db->get_where('tbl_canon_teba', array('canon_teba_id' => $canon_teba_id))->row();

        $old_file_path = './docs/img/' . $old_document->canon_teba_img;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }

        $this->db->delete('tbl_canon_teba', array('canon_teba_id' => $canon_teba_id));
        $this->space_model->update_server_current();
    }

    public function del_canon_teba_pdf($canon_teba_id)
    {
        // ดึงข้อมูลรายการรูปภาพก่อน
        $files = $this->db->get_where('tbl_canon_teba_file', array('canon_teba_file_ref_id' => $canon_teba_id))->result();

        // ลบรูปภาพจากตาราง tbl_canon_teba_file
        $this->db->where('canon_teba_file_ref_id', $canon_teba_id);
        $this->db->delete('tbl_canon_teba_file');

        // ลบไฟล์รูปภาพที่เกี่ยวข้อง
        foreach ($files as $files) {
            $file_path = './docs/file/' . $files->canon_teba_file_pdf;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    public function del_canon_teba_img($canon_teba_id)
    {
        // ดึงข้อมูลรายการรูปภาพก่อน
        $files = $this->db->get_where('tbl_canon_teba_img', array('canon_teba_img_ref_id' => $canon_teba_id))->result();

        // ลบรูปภาพจากตาราง tbl_canon_teba_file
        $this->db->where('canon_teba_img_ref_id', $canon_teba_id);
        $this->db->delete('tbl_canon_teba_img');

        // ลบไฟล์รูปภาพที่เกี่ยวข้อง
        foreach ($files as $files) {
            $file_path = './docs/img/' . $files->canon_teba_img_img;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    public function update_canon_teba_status()
    {
        // ตรวจสอบว่ามีการส่งข้อมูล POST มาหรือไม่
        if ($this->input->post()) {
            $canon_tebaId = $this->input->post('canon_teba_id'); // รับค่า canon_teba_id
            $newStatus = $this->input->post('new_status'); // รับค่าใหม่จาก switch checkbox

            // ทำการอัพเดตค่าในตาราง tbl_canon_teba ในฐานข้อมูลของคุณ
            $data = array(
                'canon_teba_status' => $newStatus
            );
            $this->db->where('canon_teba_id', $canon_tebaId); // ระบุ canon_teba_id ของแถวที่ต้องการอัพเดต
            $this->db->update('tbl_canon_teba', $data);

            // ส่งการตอบกลับ (response) กลับไปยังเว็บไซต์หรือแอพพลิเคชันของคุณ
            // โดยเช่นปกติคุณอาจส่ง JSON response กลับมาเพื่ออัพเดตหน้าเว็บ
            $response = array('status' => 'success', 'message' => 'อัพเดตสถานะเรียบร้อย');
            echo json_encode($response);
        } else {
            // ถ้าไม่มีข้อมูล POST ส่งมา ให้รีเดอร์เปรียบเสมอ
            show_404();
        }
    }

    public function canon_teba_frontend()
    {
        $this->db->select('*');
        $this->db->from('tbl_canon_teba');
        $this->db->where('tbl_canon_teba.canon_teba_status', 'show');
        $this->db->order_by('tbl_canon_teba.canon_teba_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function increment_view($canon_teba_id)
    {
        $this->db->where('canon_teba_id', $canon_teba_id);
        $this->db->set('canon_teba_view', 'canon_teba_view + 1', false); // บวกค่า canon_teba_view ทีละ 1
        $this->db->update('tbl_canon_teba');
    }
    // ใน canon_teba_model
    public function increment_download_canon_teba($canon_teba_file_id)
    {
        $this->db->where('canon_teba_file_id', $canon_teba_file_id);
        $this->db->set('canon_teba_file_download', 'canon_teba_file_download + 1', false); // บวกค่า canon_teba_download ทีละ 1
        $this->db->update('tbl_canon_teba_file');
    }
}
