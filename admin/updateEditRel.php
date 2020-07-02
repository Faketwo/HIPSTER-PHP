<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 

/**
 * 注意：
 * 
 * 因為要判斷更新時檔案有無上傳，
 * 所以要先對前面/其它的欄位先進行 SQL 語法字串連接，
 * 再針對圖片上傳的情況，給予對應的 SQL 字串和資料繫結設定。
 * 
 */

// echo "<pre>";
// print_r($_FILES);
// echo "</pre>";
// exit();

//先對其它欄位，進行 SQL 語法字串連接
$sql = "UPDATE `rel_member_coupon` 
        SET
        `couponId` = ?,
        `memberId` = ?,  
        `memberCouponNum` = ? ";

//先對其它欄位進行資料繫結設定
$arrParam = [
    $_POST['couponId'],
    $_POST['memberId'],
    $_POST['memberCouponNum']
];



//SQL 結尾
$sql.= "WHERE `id` = ? ";
$arrParam[] = (int)$_POST['editId'];

$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

if( $stmt->rowCount() > 0 ){
    header("Refresh: 2; url=./couponRel.php");
    $answer = "更新成功";
} else {
    header("Refresh: 2; url=./couponRel.php");
    $answer = "更新失敗";
}

?>
<div style="width:800px;margin:auto;margin-top:110px;text-align:center;position:relative;">
<h2 style="position:absolute;font-size: 40px;color: #759fb4;left: 50%;transform: translateX(-50%);"><?= $answer  ?></h2>
<img style="width:100%" src="../images/image.gif" alt="">
</div>