<?php
  $cn = new mysqli("localhost","root","","php21_news");
  $tbl = array(
    "tbl_menu",
    "tbl_news",
    "tbl_ads"
  );
  $tblOpt = $_POST['tbl'];
  $sql = "SELECT id FROM ".$tbl[$tblOpt]." ORDER BY id DESC";
  $res = $cn->query($sql);
  $msg['id'] = 1;
  if($res->num_rows > 0){
    $row = $res->fetch_array();
    $msg['id']=$row[0]+1;
  }
  echo json_encode($msg);
  ?>