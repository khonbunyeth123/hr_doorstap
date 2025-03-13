<?php
  $cn = new mysqli("localhost","root","","php21_news");
  $cn->set_charset("utf8");
  //count data
  $s = $_POST['s'];
  $e = $_POST['e'];
  $searchOpt=$_POST['search_opt'];
  $searchVal = $_POST['searchVal'];
  $searchField = $_POST['searchField'];
  
  if($searchOpt==0){
    $sql_count = "SELECT COUNT(*) AS total FROM tbl_menu";
    $sql = "SELECT * FROM tbl_menu ORDER BY id DESC LIMIT $s,$e";
  }else{
    $sql_count = "SELECT COUNT(*) AS total FROM tbl_menu WHERE $searchField LIKE '$searchVal%'";
    $sql = "SELECT * FROM tbl_menu WHERE $searchField LIKE '$searchVal%' ORDER BY id DESC LIMIT $s,$e";
  }
  $res_count = $cn->query($sql_count);
  $total = $res_count->fetch_array();
  
  $res = $cn->query($sql);
  $data = array();
  if($res->num_rows>0){
    while($row = $res->fetch_array()){
      $data[] = array(
        "id"=>$row[0],
        "name"=>$row[1],
        "img" =>$row[2],
        "status"=>$row[3],
        "total" => $total[0]
      );
    }
  }
  echo json_encode($data);
?>