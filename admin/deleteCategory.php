<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//刪除類別
if(isset($_GET['deleteCategoryId'])){
    $strCategoryIds = "";;
    $strCategoryIds.= $_GET['deleteCategoryId'];
    getRecursiveCategoryIds($pdo, $_GET['deleteCategoryId']);
    
    $sql = "DELETE FROM `category` WHERE `categoryId` in ( {$strCategoryIds} )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        header("Refresh: 2; url=./category.php");
        $objResponse['success'] = true;
        $objResponse['code'] = 200;
        $objResponse['info'] = "刪除成功";
        $answer = "刪除成功";
        require_once('../loading.php');
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    } else {
        header("Refresh: 2; url=./category.php");
        $objResponse['success'] = false;
        $objResponse['code'] = 400;
        $objResponse['info'] = "刪除失敗";
        $answer = "刪除失敗";
        require_once('../loading.php');
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
}

//搭配全域變數，遞迴取得上下階層的 id 字串集合
function getRecursiveCategoryIds($pdo, $categoryId){
    global $strCategoryIds;
    $sql = "SELECT `categoryId`
            FROM `category` 
            WHERE `categoryParentId` = ?";
    $stmt = $pdo->prepare($sql);
    $arrParam = [$categoryId];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
            $strCategoryIds.= ",".$arr[$i]['categoryId'];
            getRecursiveCategoryIds($pdo, $arr[$i]['categoryId']);
        }
    }
}