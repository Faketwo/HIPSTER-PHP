<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$count = 0;

for($i = 0; $i < count($_POST['chk']); $i++){
    //加入繫結陣列
    $arrParam = [
        $_POST['chk'][$i]
    ];

    //找出特定 productId 的資料
    $sqlImg = "SELECT `productImg` FROM `product` WHERE `productId` = ? ";
    $stmt_img = $pdo->prepare($sqlImg);
    $stmt_img->execute($arrParam);

    //有資料，則進行檔案刪除
    if($stmt_img->rowCount() > 0) {
        //取得檔案資料 (單筆)
        $arr = $stmt_img->fetchAll();
        
        //刪除檔案
        @$bool = unlink("../images/products/".$arr[0]['productImg']);

        //若檔案刪除成功，則刪除資料
        if($bool === true){
            //SQL 語法
            $sql = "DELETE FROM `product` WHERE `productId` = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arrParam);
            echo "圖片已刪除";

            //累計每次刪除的次數
            $count += $stmt->rowCount();
        }else {
            $sql = "DELETE FROM `product` WHERE `productId` = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arrParam);
            $count += $stmt->rowCount();
            echo "未找到圖片，但已刪除資料";
        };
    }
}

if($count > 0) {
    header("Refresh: 2; url=./admin2.php");
    $objResponse['success'] = true;
    $objResponse['code'] = 200;
    $objResponse['info'] = "刪除成功";
    $answer = "刪除成功";
    require_once('../loading.php');
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 2; url=./admin2.php");
    $objResponse['success'] = false;
    $objResponse['code'] = 500;
    $objResponse['info'] = "刪除失敗";
    $answer = "刪除失敗";
    require_once('../loading.php');
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}