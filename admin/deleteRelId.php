<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php');

//SQL 語法
$sql = "DELETE FROM `rel_member_coupon` WHERE `id` = ? ";

$count = 0;

for($i = 0; $i < count($_POST['chk']); $i++){
    $arrParam = [
        $_POST['chk'][$i]
    ];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($arrParam);
    $count += $stmt->rowCount();
}

if($stmt->rowCount() > 0) {
    header("Refresh: 3; url=./couponRel.php");
    $answer = "刪除成功";
} else {
    header("Refresh: 3; url=./couponRel.php");
    $answer = "刪除失敗";
}

?>
<div style="width:800px;margin:auto;margin-top:110px;text-align:center;position:relative;">
<h2 style="position:absolute;font-size: 40px;color: #759fb4;left: 50%;transform: translateX(-50%);"><?= $answer  ?></h2>
<img style="width:100%" src="../images/image.gif" alt="">
</div>