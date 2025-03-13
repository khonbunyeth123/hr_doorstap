<?php
    $img = $_FILES['txt-file'];
    $path = $img['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $rand = str_pad(mt_rand(0, 999999),6,'0',STR_PAD_LEFT);
    $newName = time().'-'.$rand;
    $newFile = $newName. '.' .$ext;
    move_uploaded_file($img['tmp_name'], '../img/product/'.$newFile);
    $res['imgName']= $newFile;
    echo json_encode($res);
?>