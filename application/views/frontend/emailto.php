<div class="bg-pages ">
    <div class="row pad-path">
        <div class="path1-1 mt-3">
            <span class="font-path-1 underline"><a href="<?php echo site_url('Home'); ?>">หน้าแรก</a></span>
        </div>
        <div class="path2-1 mt-3">
            <span class="font-path-2 underline"><a href="#">E-Service</a></span>
        </div>
        <div class="col">
            <div class="d-flex justify-content-end">
                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-select custom-select" id="ChangPagesComplain">
                            <option value="" disabled selected>E-mail ถึงเทศบาล</option>
                            <option value="suggestions">รับฟังความคิดเห็น</option>
                            <option value="complain">ร้องเรียน/ร้องทุกข์</option>
                            <option value="follow-complain">ติดตามสถานะเรื่องร้องเรียน</option>
                            <option value="corruption">แจ้งเรื่องทุจริตหน่วยงานภาครัฐ</option>
                            <option value="esv_ods">ยื่นเอกสารออนไลน์</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-pages-news">
        <div class="page-center">
            <div class="head-pages-three">
                <span class="font-pages-head">E-mail ถึงเทศบาล</span>
            </div>
        </div>
        <div class="bg-pages-in-e-service ">
            <div class="pages-form-es-corruption underline">
                <form action=" <?php echo site_url('Pages/add_emailto'); ?> " method="post" class="form-horizontal" enctype="multipart/form-data">
                    <br>
                    <div class="form-group">
                        <div class="col-sm-6 control-label font-e-service-complain">เรื่อง <span class="red-font">*</span></div>
                        <div class="col-sm-12 mt-2">
                            <input type="text" name="emailto_topic" class="form-control font-label-e-service-complain" required placeholder="กรอกเรื่องร้องเรียน...">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <div class="col-sm-12 control-label  font-e-service-complain">ชื่อ-นามสกุล <span class="red-font">*</span></div>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" name="emailto_by" class="form-control font-label-e-service-complain" required placeholder="นางสาวน้ำใส ใจชื่นบาน">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <div class="col-sm-12 control-label  font-e-service-complain">เบอร์โทรศัพท์ <span class="red-font">*</span></div>
                                <div class="col-sm-12 mt-2">
                                    <input type="tel" name="emailto_phone" class="form-control font-label-e-service-complain" required placeholder="กรอกเบอร์โทรศัพท์" pattern="\d{10}" title="กรุณากรอกเบอร์มือถือเป็นตัวเลข 10 ตัว">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <div class="col-sm-12 control-label font-e-service-complain">อีเมล <span class="red-font">*</span></div>
                                <div class="col-sm-12 mt-2">
                                    <input type="email" name="emailto_email" class="form-control font-label-e-service-complain" required placeholder="example@youremail.com">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1" class="form-label font-e-service-complain">รายละเอียด <span class="red-font">*</span></label>
                        <div class="col-sm-12">
                            <textarea name="emailto_detail" class="form-control font-label-e-service-complain" id="exampleFormControlTextarea1" rows="12" placeholder="กรอกรายละเอียดเพิ่มเติม..."></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-7 control-label font-e-service-complain">แนบไฟล์<span class="red-font">*</span></div>
                        <div class="col-sm-12 mt-2">
                            <input type="file" name="emailto_file" class="form-control" accept=".pdf, .docx, .doc" required>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-6 font-e-service-danger">
                    <!-- <span><b>หมายเหตุ</b><br>
                        1.ผู้ยื่นคำขอดาวน์โหลดเอกสารเพื่อกรอกข้อมูล<br>
                        ในใบคำขอต่างๆและแนบเอกสารในช่องส่งฟอร์มเอกสาร<br>
                        2.เจ้าหน้าที่รับเรื่อง พิจารณาเอกสาร<br>
                        3.แจ้งผลการดำเนินงานทางเบอร์โทรหรืออีเมลที่ผู้ยื่นคำขอแจ้งใว้</span> -->
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div class="g-recaptcha" data-sitekey="6LcKoPcnAAAAAKGgUMRtkBs6chDKzC8XOoVnaZg_" data-callback="enableLoginButton"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-start">
                        <button type="submit" id="loginBtn" class="btn" disabled><img src="<?php echo base_url("docs/s.btn-add-q-a.png"); ?>"></button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    // เมื่อ reCAPTCHA ผ่านการตรวจสอบ
    function enableLoginButton() {
        document.getElementById("loginBtn").removeAttribute("disabled");
    }
</script>