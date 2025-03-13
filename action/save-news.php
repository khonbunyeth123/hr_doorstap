<?php
  // date_default_timezone_set("Asia/Phnom_Penh");
  // $cn = new mysqli("localhost","root","" ,"php21_news");
  // $cn->set_charset("utf8");
  // $id = $_POST['txt-id'];
  // $menu= $_POST['txt-menu'];
  // $title =trim( $_POST['txt-name']);
  // $title = $cn->real_escape_string($title);
  // $od= $_POST['txt-od'];
  // $location=$_POST['txt-location'];
  // $status = $_POST['txt-status'];
  // $photo =$_POST['txt-photo'];
  // $edit_id = $_POST['txt-edit-id'];
  // $detail = $_POST['txt-detail'];
  // $date_post = date("Y/m/d H:i:s");
  // $uid=1;
  // $msg['edit']=false;
  // //check duplicate name 
  // $sql = "SELECT * FROM tbl_news WHERE title='$title' AND id != $id";
  // $res = $cn-> query($sql);
  // if($res ->num_rows>0){
  //   $msg['dpl']=true;
  // }else{
  //   if($edit_id == 0){
  //     $sql = "INSERT INTO tbl_news VALUES (
  //      null,'$date_post','$menu','$title','$detail',
  //      '$photo','$od','$location',0,'$uid','$status')";//true
  //     $cn -> query($sql);
  //     $msg['id']= $cn->insert_id;
  //   }else{
  //     $sql = "UPDATE tbl_news
  //     SET mid='$menu', title='$title',des='$detail',
  //     img='$photo',od='$od',location='$location',status='$status'
  //     WHERE id = $edit_id";
  //     $cn->query($sql);
  //     $msg['edit']=true;
  //   }   
  //   $msg['dpl']=false;
  // }
  // echo json_encode($msg);
?>


<?php
//new code

date_default_timezone_set("Asia/Phnom_Penh");
// Connect to MySQL
$cn = new mysqli("localhost", "root", "", "php21_news");
$cn->set_charset("utf8");
// Get Data from POST
$id         = $_POST['txt-id'] ?? 0 ;
$menu       = $_POST['txt-menu'] ?? '';
$title      = trim($_POST['txt-name'] ?? ''); // Changed from txt-name to txt-title
$title      = $cn->real_escape_string($title);
$od         = $_POST['txt-od'] ?? 0;
$location   = $_POST['txt-location'] ?? '';
$status     = $_POST['txt-status'] ?? 0;
$photo      = $_POST['txt-photo'] ?? '';
$edit_id    = $_POST['txt-edit-id'] ?? 0;
$detail     = $_POST['txt-detail'] ?? '';
$date_post  = date("Y/m/d H:i:s");
$uid        = 1;
// Response array
$msg        = ["edit" => false, "dpl" => false];
// Check for Duplicate Title
$sql        = "SELECT * FROM tbl_news WHERE title = ? AND id != ?";
$stmt       = $cn->prepare($sql);
$stmt->bind_param("si", $title, $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $msg["dpl"] = true;
} else {
    if ($edit_id == 0) {
        // Insert New Record
        $sql    = "INSERT INTO tbl_news (date_post, mid, title, des, img, od, location, view, uid, status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?)";
        $stmt   = $cn->prepare($sql);
        $stmt->bind_param("sssssisii", $date_post, $menu, $title, $detail, $photo, $od, $location, $uid, $status);

        if ($stmt->execute()) {
            $msg["id"] = $cn->insert_id;
        } else {
            $msg["error"] = "Insert failed: " . $stmt->error;
        }
    } else {
        // Update Existing Record
        $sql    = "UPDATE tbl_news 
                  SET mid = ?, title = ?, des = ?, img = ?, od = ?, location = ?, status = ? 
                  WHERE id = ?";
        $stmt   = $cn->prepare($sql);
        $stmt->bind_param("ssssisii", $menu, $title, $detail, $photo, $od, $location, $status, $edit_id);

        if ($stmt->execute()) {
            $msg["edit"] = true;
        } else {
            $msg["error"] = "Update failed: " . $stmt->error;
        }
    }
}
// Return JSON Response
echo json_encode($msg);
?>