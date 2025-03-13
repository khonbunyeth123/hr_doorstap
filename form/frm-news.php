<?php
 $cn = new mysqli("localhost","root","","php21_news");
 $cn->set_charset("utf8");
?>
<div class="frm" style="width:900px;">
  <div class="frm-title">
    <div class="title">
      News
    </div>
    <div class="btn-close">
      <i class="fa fa-times"></i>
    </div>
  </div>
  <form class="upl">
    <div class="frm-body">
      <input type="text" name="txt-edit-id" id="txt-edit-id" value="0">
      <div style="width: 30%; float:left;">
        <label for="">ID</label>
        <input type="text" name="txt-id" id="txt-id" class="frm-control">
        <label for="">Menu</label>
        <select name="txt-menu" id="txt-menu" class="frm-control">
          <?php
          $sql = "SELECT * FROM tbl_menu WHERE status =1";
          $res = $cn->query($sql);
          // echo "<pre>";
          // print_r($res);
          // echo "</pre>";
        
          if($res->num_rows>0){
            while($row=$res->fetch_array()){
              ?>
          <option value="<?php echo $row[0] ;?>">
            <?php echo $row[1] ;?>
          </option>
          <?php
            }
          }
        ?>
        </select>
        <label for="">OD</label>
        <input type="text" name="txt-od" id="txt-od" class="frm-control">
        <label for="">Location</label>
        <select name="txt-location" id="txt-location" class="frm-control">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
        <label for="">Staus (1=Active ,2=Delete)</label>
        <select name="txt-status" id="txt-status" class="frm-control">
          <option value="1">1</option>
          <option value="2">2</option>
        </select>
        <label for="">Photo</label>
        <div class="img-box">
          <input type="file" name="txt-file" id="txt-file" class="txt-file">
        </div>
        <input type="text" name="txt-photo" id="txt-photo">
      </div>
      <div style="width: 68%; float: left; margin-left:2%;">
        <label for="">Title</label>
        <input type="text" name="txt-name" id="txt-name" class="frm-control">
        <label for="">Detail</label>
        <textarea name="txt-detail" id="txt-detail" rows="22" class="frm-control"></textarea>
      </div>
    </div>
    <div class="frm-footer">
      <a class="btn btn-save">
        save change
      </a>
    </div>
  </form>
</div>