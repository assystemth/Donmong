    <!-- <h5 class="border border-#f5f5f5 p-2 mb-2 font-black" style="background-color: #f5f5f5;">จัดการข้อมูลเรื่องร้องเรียน</h5> -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">จัดการข้อมูล E-mail ถึงเทศบาล</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $Index = 1;
                ?>
                <table id="newdataTables" class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ลำดับ</th>
                            <th style="width: 20%;">ไฟล์</th>
                            <th style="width: 20%;">เรื่อง</th>
                            <th style="width: 35%;">รายละเอียด</th>
                            <th style="width: 10%;">ชื่อผู้ส่ง</th>
                            <th style="width: 10%;">เบอร์โทรศัพท์</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($query  as $rs) { ?>
                            <tr role="row">
                                <td align="center"><?= $Index; ?></td>
                                <td>
                                    <a href="<?php echo base_url('docs/file/' . $rs->emailto_file); ?>" target="_blank"><?= $rs->emailto_file; ?></a>
                                </td>
                                <td class="limited-text"><?= $rs->emailto_topic; ?></td>
                                <td class="limited-text"><?= $rs->emailto_detail; ?></td>
                                <td class="limited-text"><?= $rs->emailto_by; ?></td>
                                <td class="limited-text"><?= $rs->emailto_phone; ?></td>
                            </tr>
                        <?php
                            $Index++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- <h5 class="border border-#f5f5f5 p-2 mb-2 font-black" style="background-color: #f5f5f5;">จัดการข้อมูลเรื่องร้องเรียน</h5>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">จัดการข้อมูลเรื่องร้องเรียน</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $Index = 1;
                ?>
                <table id="newdataTables" class="table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">ลำดับ</th>
                            <th style="width: 10%;">รูปภาพ</th>
                            <th style="width: 10%;">ประเภท</th>
                            <th style="width: 15%;">หัวข้อร้องเรียน</th>
                            <th style="width: 10%;">รายละเอียด</th>
                            <th style="width: 10%;">พิกัด</th>
                            <th style="width: 10%;">ผู้แจ้ง</th>
                            <th style="width: 10%;">ติดต่อ</th>
                            <th style="width: 7%;">เวลา</th>
                            <th style="width: 15%;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emailtos  as $emailto) { ?>
                            <tr role="row">
                                <td align="center"><?= $Index; ?></td>
                                <td>
                                    <?php foreach ($emailto->images as $image) : ?>
                                        <img src="<?= base_url('docs/img/' . $image->emailto_img_img); ?>" alt="emailto Image" width="100">
                                    <?php endforeach; ?>
                                </td>
                                <td class="limited-text"><?= $emailto->emailto_type; ?></td>
                                <td class="limited-text"><?= $emailto->emailto_head; ?></td>
                                <td class="limited-text"><?= $emailto->emailto_detail; ?></td>
                                <td><?= $emailto->emailto_lat; ?>,<br><?= $emailto->emailto_long; ?></td>
                                <td class="limited-text"><?= $emailto->emailto_by; ?></td>
                                <td class="limited-text"><?= $emailto->emailto_phone; ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($emailto->emailto_datesave . '+543 years')) ?> น.</td>
                                <td>
                                    <select class="form-select emailto-status" name="emailto_status" data-emailto-id="<?= $emailto->emailto_id; ?>">
                                        <option value="<?= $emailto->emailto_status; ?>"><?= $emailto->emailto_status; ?></option>
                                        <option value="รับเรื่องแล้ว" style="color: black;">รับเรื่องแล้ว</option>
                                        <option value="กำลังดำเนินการ" style="color: black;">กำลังดำเนินการ</option>
                                        <option value="รอดำเนินการ" style="color: black;">รอดำเนินการ</option>
                                        <option value="แก้ไขเรียบร้อย" style="color: black;">แก้ไขเรียบร้อย</option>
                                        <option value="ยกเลิก" style="color: black;">ยกเลิก</option>
                                    </select>
                                </td>
                                <script>
                                    // รับค่า emailto_id และ new_status เมื่อมีการเลือกค่าใหม่
                                    const selectElement<?= $emailto->emailto_id; ?> = document.querySelector('.emailto-status[data-emailto-id="<?= $emailto->emailto_id; ?>"]');

                                    selectElement<?= $emailto->emailto_id; ?>.addEventListener('change', function() {
                                        const emailtoId = this.getAttribute('data-emailto-id');
                                        const newStatus = this.value;

                                        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ด้วย AJAX
                                        $.ajax({
                                            type: 'POST',
                                            url: 'emailto/updateemailtoStatus',
                                            data: {
                                                emailto_id: emailtoId,
                                                new_status: newStatus
                                            },
                                            success: function(response) {
                                                // รีเฟรชหน้าเมื่อมีการเปลี่ยนแปลง
                                                location.reload();
                                                console.log(response);
                                                // ทำอื่นๆตามต้องการ เช่น อัพเดตหน้าเว็บ
                                            },
                                            error: function(error) {
                                                console.error(error);
                                            }
                                        });
                                    });

                                    selectElement<?= $emailto->emailto_id; ?>.addEventListener('focus', function() {
                                        this.style.backgroundColor = 'white'; // เมื่อได้รับการโฟกัส (focus) ให้สีพื้นหลังเป็นสีขาว
                                    });

                                    selectElement<?= $emailto->emailto_id; ?>.addEventListener('blur', function() {
                                        const selectedValue = this.value;
                                        const statusColor = getStatusColor(selectedValue);
                                        this.style.color = statusColor;
                                        this.style.backgroundColor = getBackgroundColor(selectedValue); // เมื่อเลือกแล้วให้ใช้สีพื้นหลังตามสถานะที่เลือก
                                    });
                                    // ฟังก์ชันสำหรับกำหนดสีตามสถานะ
                                    function getStatusColor(status) {
                                        switch (status) {
                                            case 'รับเรื่องแล้ว':
                                                return '#4C97EE';
                                            case 'กำลังดำเนินการ':
                                                return '#3D5AF1';
                                            case 'รอดำเนินการ':
                                                return '#E05A33';
                                            case 'แก้ไขเรียบร้อย':
                                                return '#00B73E';
                                            case 'ยกเลิก':
                                                return '#FF0202';
                                            default:
                                                return '#FFC700';
                                        }
                                    }

                                    // ฟังก์ชันสำหรับกำหนดสีพื้นหลังของ <select> ตามสถานะที่เลือก
                                    function getBackgroundColor(status) {
                                        switch (status) {
                                            case 'รับเรื่องแล้ว':
                                                return '#D9EAFF';
                                            case 'กำลังดำเนินการ':
                                                return '#CFD7FE';
                                            case 'รอดำเนินการ':
                                                return '#FFECE7';
                                            case 'แก้ไขเรียบร้อย':
                                                return '#DBFFDD';
                                            case 'ยกเลิก':
                                                return '#FFE3E3';
                                            default:
                                                return '#FFFBDC'; // หากไม่ตรงกับสถานะที่กำหนดให้มีสีพื้นหลัง
                                        }
                                    }
                                    // กำหนดสีเริ่มต้นเมื่อหน้าเว็บโหลดเสร็จ
                                    const initialStatus<?= $emailto->emailto_id; ?> = selectElement<?= $emailto->emailto_id; ?>.value;
                                    const initialColor<?= $emailto->emailto_id; ?> = getStatusColor(initialStatus<?= $emailto->emailto_id; ?>);
                                    selectElement<?= $emailto->emailto_id; ?>.style.color = initialColor<?= $emailto->emailto_id; ?>;
                                    selectElement<?= $emailto->emailto_id; ?>.style.backgroundColor = getBackgroundColor(initialStatus<?= $emailto->emailto_id; ?>);
                                </script>
                            </tr>
                        <?php
                            $Index++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->