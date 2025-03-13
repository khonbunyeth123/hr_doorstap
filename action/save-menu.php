<?php
  $cn = new mysqli("localhost","root","" ,"php21_news");
  $cn->set_charset("utf8");
  $id = $_POST['txt-id'];
  $name =trim( $_POST['txt-name']);
  $name = $cn->real_escape_string($name);
  $edit_id = $_POST['txt-edit-id'];
  $status = $_POST['txt-status'];
  $photo =$_POST['txt-photo'];
  $msg['edit']=false;

  //check duplicate name 
  $sql = "SELECT * FROM tbl_menu WHERE name='$name' && id != $id";
  $res = $cn-> query($sql);
  if($res ->num_rows>0){
    $msg['dpl']=true;
  }else{
    if($edit_id == 0){
      $sql = "INSERT INTO tbl_menu VALUES (null,'$name','$photo','$status')";
      $cn -> query($sql);
      $msg['id']= $cn->insert_id;
    }else{
      $sql = "UPDATE tbl_menu SET name='$name',img='$photo',status='$status'
              WHERE id = $edit_id";
      $cn->query($sql);
      $msg['edit']=true;
    }
  }
  echo json_encode($msg);
?>