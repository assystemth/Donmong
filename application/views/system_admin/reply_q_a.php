<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <h4>ตอบกลับความคิดเห็น : <?= $rsedit->q_a_msg; ?></h4>
            <form action=" <?php echo site_url('q_a_backend/add_reply_q_a/' . $rsedit->q_a_id); ?> " method="post" class="form-horizontal">
                <br>
                <input type="hidden" name="q_a_reply_ref_id" class="form-control font-label-e-service-complain" required value="<?= $rsedit->q_a_id; ?>">
                <div class="form-group row">
                    <div class="col-sm-2 control-label">รายละเอียด</div>
                    <div class="col-sm-6">
                        <input type="text" name="q_a_reply_detail" required class="form-control">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-2 control-label"></div>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                        <a class="btn btn-danger" href="<?php echo site_url('q_a_backend'); ?>">ยกเลิก</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>