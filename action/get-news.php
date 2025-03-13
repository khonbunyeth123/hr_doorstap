 <?php
  $cn = new mysqli("localhost","root","","php21_news");
  $cn->set_charset("utf8");
  //count data
  $s                = $_POST['s'];
  $e                = $_POST['e'];
  $searchOpt        =$_POST['search_opt'];
  $searchVal        = $_POST['searchVal'];
  $searchField      = $_POST['searchField'];
  
  if($searchOpt==0){
    $sql_count      = "SELECT COUNT(*) AS total FROM tbl_news";
    // $sql            = "SELECT * FROM tbl_news ORDER BY id DESC LIMIT $s,$e";
    $sql            = "SELECT tbl_news.id,tbl_menu.name,tbl_news.title,
                       tbl_news.img,tbl_news.status FROM tbl_news INNER JOIN tbl_menu 
                       ON tbl_menu.id = tbl_news.mid ORDER BY tbl_news.id DESC LIMIT $s,$e";
  }else{
    $sql_count      = "SELECT COUNT(*) AS total FROM tbl_news 
                       INNER JOIN tbl_menu ON tbl_news.mid = tbl_menu.id 
                       WHERE $searchField LIKE '$searchVal%'";
    //$sql            = "SELECT * FROM tbl_news WHERE $searchField LIKE '$searchVal%' ORDER BY id DESC LIMIT $s,$e";
    $sql            ="SELECT tbl_news.id,tbl_menu.name,tbl_news.title,
                       tbl_news.img,tbl_news.status FROM tbl_news INNER JOIN tbl_menu 
                       ON tbl_menu.id = tbl_news.mid  WHERE $searchField LIKE '$searchVal%' 
                       ORDER BY tbl_news.id DESC LIMIT $s,$e";
  }
  $res_count        = $cn->query($sql_count);
  $total            = $res_count->fetch_array();
  
  $res              = $cn->query($sql);
  $data             = array();
  if($res->num_rows>0){
    while($row      = $res->fetch_array()){
      $data[]       = array(
        "id"        =>$row[0],
        "menu"      =>$row[1],
        "title"     =>$row[2],
        "img"       =>$row[3],
        "status"    =>$row[4],
        "total"     =>$total[0]
      );
    }
  }
  echo json_encode($data);
?>