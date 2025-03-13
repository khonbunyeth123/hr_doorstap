<?php
  $cn = new mysqli("localhost","root","","php21_news");
  $cn->set_charset("utf8");
$id                         = $_POST['id'];
$sql                        = "SELECT * FROM tbl_news WHERE id = $id";
$res                        = $cn->query($sql);
$row                        = $res->fetch_array();
$msg['id']                  = $row[0];
$msg['mid']                 = $row[2];
$msg['title']               = $row[3];
$msg['des']                 = $row[4];
$msg['img']                 = $row[5];
$msg['od']                  = $row[6];
$msg['location']            = $row[7];
$msg['status']              = $row[10];
  echo json_encode($msg);
?>