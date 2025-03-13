<?php
  $cn = new mysqli("localhost","root","","php21_news");
  $sql = "SELECT * FROM tbl_menu";
  $res = $cn->query($sql);
  if($res->num_rows>0){
    while($row = $res->fetch_array()){
      ?>
<tr>
  <td><?php echo $row[0]; ?></td>
  <td><?php echo $row[1]; ?></td>
  <td>
    <img src="img/product/<?php echo $row[2]; ?>" alt="">
  </td>
  <td align="center"><?php echo $row[3]; ?></td>
</tr>
<?php
    }
  }
?>