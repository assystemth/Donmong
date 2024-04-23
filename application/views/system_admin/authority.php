   <!-- DataTales Example -->
   <div class="card shadow mb-4">
       <div class="card-header py-3">
           <h6 class="m-0 font-weight-bold text-black">จัดการข้อมูลอำนาจหน้าที่</h6>
       </div>
       <div class="card-body">
           <div class="table-responsive">

               <?php
                $Index = 1;
                ?>
               <!-- <table id="newdataTables" class="table">
                   <thead>
                       <tr>
                           <th style="width: 5%;">ลำดับ</th>
                           <th style="width: 13%;">รูปภาพ</th>
                           <th style="width: 55%;">รายละเอียด</th>
                           <th style="width: 13%;">อัพโหลด</th>
                           <th style="width: 7%;">วันที่</th>
                           <th style="width: 7%;">จัดการ</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php foreach ($query as $rs) { ?>
                           <tr role="row">
                               <td align="center"><?= $Index; ?></td>
                               <td>
                                   <?php if (!empty($rs->authority_img)) : ?>
                                       <img src="<?php echo base_url('docs/img/' . $rs->authority_img); ?>" width="180px" height="120px">
                                   <?php else : ?>
                                       <img src="<?php echo base_url('docs/k.logo.png'); ?>" width="180px" height="120px">
                                   <?php endif; ?>
                               </td>
                               <td class="limited-text"><?= $rs->authority_detail; ?></td>
                               <td><?= $rs->authority_by; ?></td>
                               <td><?= date('d/m/Y H:i', strtotime($rs->authority_datesave . '+543 years')) ?> น.</td>
                               <td><a href="<?= site_url('authority_backend/editing/' . $rs->authority_id); ?>"><i class="bi bi-pencil-square fa-lg "></i></a></td>
                           </tr>
                       <?php
                            $Index++;
                        } ?>
                   </tbody>
               </table> -->
               <table id="newdataTables" class="table">
                   <thead>
                       <tr>
                           <th style="width: 5%;">ลำดับ</th>
                           <th style="width: 13%;">ไฟล์ PDF</th>
                           <th style="width: 55%;">เรื่อง</th>
                           <th style="width: 13%;">อัพโหลด</th>
                           <th style="width: 7%;">วันที่</th>
                           <th style="width: 7%;">จัดการ</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php foreach ($query as $rs) { ?>
                           <tr role="row">
                               <td align="center"><?= $Index; ?></td>
                               <td><a class="btn btn-info btn-sm mb-2" href="<?= base_url('docs/file/' . $rs->authority_pdf); ?>" target="_blank">ดูไฟล์ <?= $rs->authority_pdf; ?></a></td>
                               <td><?= $rs->authority_name; ?></td>
                               <td><?= $rs->authority_by; ?></td>
                               <td><?= date('d/m/Y H:i', strtotime($rs->authority_datesave . '+543 years')) ?> น.</td>
                               <td><a href="<?= site_url('authority_backend/editing/' . $rs->authority_id); ?>"><i class="bi bi-pencil-square fa-lg "></i></a></td>
                           </tr>
                       <?php
                            $Index++;
                        } ?>
                   </tbody>
               </table>
           </div>
       </div>
   </div>