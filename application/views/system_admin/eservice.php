    <!-- <h5 class="border border-#f5f5f5 p-2 mb-2 font-black" style="background-color: #f5f5f5;">จัดการข้อมูลเรื่องร้องเรียน</h5> -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">จัดการข้อมูลบริการ E-service  </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $Index = 1;
                ?>
                <table id="newdataTables" class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">เคส</th>
                            <th style="width: 15%;">รูปภาพ</th>
                            <th style="width: 10%;">หมวดหมู่</th>
                            <th style="width: 20%;">หัวข้อร้องเรียน</th>
                            <th style="width: 30%;">รายละเอียด</th>
                            <th style="width: 10%;">ผู้แจ้ง</th>
                            <th style="width: 10%;">ติดต่อ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eservices  as $eservice) { ?>
                            <tr role="row">
                                <td><?= $eservice->eservice_id; ?></td>
                                <td>
                                    <?php foreach ($eservice->images as $image) : ?>
                                        <img src="<?= base_url('docs/img/' . $image->eservice_img_img); ?>" alt="eservice Image" width="100">
                                    <?php endforeach; ?>
                                </td>
                                <td class="limited-text"><?= $eservice->eservice_type; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_topic; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_detail; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_by; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_phone; ?></td>
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
                        <?php foreach ($eservices  as $eservice) { ?>
                            <tr role="row">
                                <td align="center"><?= $Index; ?></td>
                                <td>
                                    <?php foreach ($eservice->images as $image) : ?>
                                        <img src="<?= base_url('docs/img/' . $image->eservice_img_img); ?>" alt="eservice Image" width="100">
                                    <?php endforeach; ?>
                                </td>
                                <td class="limited-text"><?= $eservice->eservice_type; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_head; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_detail; ?></td>
                                <td><?= $eservice->eservice_lat; ?>,<br><?= $eservice->eservice_long; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_by; ?></td>
                                <td class="limited-text"><?= $eservice->eservice_phone; ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($eservice->eservice_datesave . '+543 years')) ?> น.</td>
                                <td>
                                    <select class="form-select eservice-status" name="eservice_status" data-eservice-id="<?= $eservice->eservice_id; ?>">
                                        <option value="<?= $eservice->eservice_status; ?>"><?= $eservice->eservice_status; ?></option>
                                        <option value="รับเรื่องแล้ว" style="color: black;">รับเรื่องแล้ว</option>
                                        <option value="กำลังดำเนินการ" style="color: black;">กำลังดำเนินการ</option>
                                        <option value="รอดำเนินการ" style="color: black;">รอดำเนินการ</option>
                                        <option value="แก้ไขเรียบร้อย" style="color: black;">แก้ไขเรียบร้อย</option>
                                        <option value="ยกเลิก" style="color: black;">ยกเลิก</option>
                                    </select>
                                </td>
                                <script>
                                    // รับค่า eservice_id และ new_status เมื่อมีการเลือกค่าใหม่
                                    const selectElement<?= $eservice->eservice_id; ?> = document.querySelector('.eservice-status[data-eservice-id="<?= $eservice->eservice_id; ?>"]');

                                    selectElement<?= $eservice->eservice_id; ?>.addEventListener('change', function() {
                                        const eserviceId = this.getAttribute('data-eservice-id');
                                        const newStatus = this.value;

                                        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ด้วย AJAX
                                        $.ajax({
                                            type: 'POST',
                                            url: 'eservice/updateeserviceStatus',
                                            data: {
                                                eservice_id: eserviceId,
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

                                    selectElement<?= $eservice->eservice_id; ?>.addEventListener('focus', function() {
                                        this.style.backgroundColor = 'white'; // เมื่อได้รับการโฟกัส (focus) ให้สีพื้นหลังเป็นสีขาว
                                    });

                                    selectElement<?= $eservice->eservice_id; ?>.addEventListener('blur', function() {
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
                                    const initialStatus<?= $eservice->eservice_id; ?> = selectElement<?= $eservice->eservice_id; ?>.value;
                                    const initialColor<?= $eservice->eservice_id; ?> = getStatusColor(initialStatus<?= $eservice->eservice_id; ?>);
                                    selectElement<?= $eservice->eservice_id; ?>.style.color = initialColor<?= $eservice->eservice_id; ?>;
                                    selectElement<?= $eservice->eservice_id; ?>.style.backgroundColor = getBackgroundColor(initialStatus<?= $eservice->eservice_id; ?>);
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