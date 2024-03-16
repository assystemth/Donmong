<div class="bg-pages">
    <div class="row pad-path">
        <div class="path1-1">
            <span class="font-path-1 underline"><a href="<?php echo site_url('Home'); ?>">หน้าแรก</a></span>
        </div>
        <div class="path2-1">
            <span class="font-path-2 underline"><a href="#">ข้อมูลทั่วไป</a></span>
        </div>
    </div>
    <div class="page-center">
        <div class="head-pages-three">
            <span class="font-pages-head">ระบบสารสนเทศสนับสนุนการบริหารจัดการ</span>
        </div>
    </div>
    <div class="bg-pages-in">
        <div class="scrollable-container-gi">
            <div class="pages-content break-word" style="padding-left: 120px;" >
                <!-- <span class="font-pages-content-head">ตารางแสดงจำนวนประชากรในเขตองค์การบริหารส่วนตำบลกาเกาะ</span><br> -->
                <?php foreach ($qSme as $rs) { ?>
                    <span class="dot-laws font-gi-content"><?= $rs->sme_name; ?> &nbsp; <a href="<?= $rs->sme_link; ?>"><?= $rs->sme_link; ?></a></span><br><br>
                <?php } ?>
            </div>
        </div>
    </div>
</div>