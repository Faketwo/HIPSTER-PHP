<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$objResponse = [];

//若沒填寫文章類別時的行為
if( $_POST['categoryName'] == '' ){
    header("Refresh: 3; url=./articleEditCategory.php?editCategoryId={$_POST["editCategoryId"]}");
    $objResponse['success'] = false;
    $objResponse['code'] = 400;
    $objResponse['info'] = "請填寫文章類別";
    // echo "請填寫文章類別";
    // echo json_encode($objResponse['info'], JSON_UNESCAPED_UNICODE);
    $answer = "請填寫文章類別";
    require_once('../loading.php');
    exit();
}

$sql = "UPDATE `article_categories` SET `categoryName` = ? WHERE `categoryId` = ?";
$stmt = $pdo->prepare($sql);
$arrParam = [
    $_POST['categoryName'], 
    $_POST["editCategoryId"]
];
$stmt->execute($arrParam);
if($stmt->rowCount() > 0) {
    header("Refresh: 3; url=./articleCategory.php?editCategoryId={$_POST["editCategoryId"]}");
    $objResponse['success'] = true;
    $objResponse['code'] = 204;
    $objResponse['info'] = "修改成功";
    // echo "修改成功";
    // echo json_encode($objResponse['info'], JSON_UNESCAPED_UNICODE);
    $answer = "修改成功";
    require_once('../loading.php');
    exit();
} else {
    header("Refresh: 3; url=./articleCategory.php?editCategoryId={$_POST["editCategoryId"]}");
    $objResponse['success'] = false;
    $objResponse['code'] = 400;
    $objResponse['info'] = "沒有任何修改";
    // echo "沒有任何修改";
    // echo json_encode($objResponse['info'], JSON_UNESCAPED_UNICODE);
    $answer = "沒有任何修改";
    require_once('../loading.php');
    exit();
}