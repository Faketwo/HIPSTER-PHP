<?php

require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//刪資料庫語法

$sql = "DELETE FROM `comments` WHERE `id` = ? ";

$count = 0;

$stmt = $pdo->prepare($sql);
$arrParam = [ $_GET['id'] ];
$stmt->execute($arrParam); 

if($stmt->rowCount() > 0) { 
    header("Refresh: 2; url=./editcomment.php");
    $answer= "刪除成功";
    require_once('../loading.php');
    // $anser = "刪除成功";
} else {
    header("Refresh: 2; url=./editcomment.php");
    $answer= "刪除失敗";
    require_once('../loading.php');
    // $anser = "刪除失敗";
}
 
?>

