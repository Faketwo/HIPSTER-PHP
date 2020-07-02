<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 
$count = 0;
//判斷此筆訂單是否存在
$sqlisset = "SELECT * FROM `orderlist` WHERE `orderId` = {$_POST['orderId']}";
$stmtisset = $pdo->prepare($sqlisset);
$stmtisset->execute();
if(!$stmtisset->rowCount()>0){
    header("Refresh: 2; url=./orders.php");
    $answer = "此筆訂單不存在!!!";
    require_once('../loading.php');
    // $objResponse['info'] = "此筆訂單不存在!!!!";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
//判斷是否選擇商品，和商品數量
if($_POST["productId"] == 0 || $_POST["amountId"] <=0){
    header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "請確認商品是否正確";
    require_once('../loading.php');
    // $objResponse['info'] = "請確認商品是否正確";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
//確認商品是否重複{
$sqlisset = "SELECT *
            FROM `item_lists`
            WHERE `item_lists`.`orderId` = ?
            AND `item_lists`.`productId` = ? ";
$stmtisset= $pdo->prepare($sqlisset);
$arrParamisset = [
    $_POST['orderId'],
    $_POST["productId"]
];
$stmtisset->execute($arrParamisset);
if($stmtisset->rowCount()>0){
    header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
        // $objResponse['info'] = "已經有該商品";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        $answer = "已經有該商品";
        require_once('../loading.php');
        exit(); 
}

//先取得商品的價格數量 判斷商品是否存在
$sqlprice = "SELECT `product`.`productId`,`product`.`productPrice`,`product`.`productAmount`
            FROM `product`
            WHERE `product`.`productId` = ?";
$stmtprice= $pdo->prepare($sqlprice);
$arrParamprice = [
    $_POST["productId"]
];
$stmtprice->execute($arrParamprice);
if($stmtprice->rowCount()>0){
    $arrprice = $stmtprice->fetchAll(PDO::FETCH_ASSOC)[0];
    if((int)$_POST['amountId']>$arrprice['productAmount']){
        header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
        $answer = "商品數量不足，請確認";
        require_once('../loading.php');
        // $objResponse['info'] = "商品數量不足，請確認";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit(); 
    }
    $price = $arrprice['productPrice'];
    $total = (int)$price * (int)$_POST['amountId'];
}else{
    header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "沒有此商品";
    require_once('../loading.php');
    // $objResponse['info'] = "沒有此項商品";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}

//在新增到訂單明細
$sqlItemList = "INSERT INTO `item_lists` (`orderId`,`productId`,`checkPrice`,`checkQty`,`checkSubtotal`) VALUES (?,?,?,?,?)";
$stmtItemList = $pdo->prepare($sqlItemList);
$arrParamItemList = [
    $_POST['orderId'],
    $_POST["productId"],
    $price,
    $_POST["amountId"],
    $total
];
$stmtItemList->execute($arrParamItemList);
$count += $stmtItemList->rowCount();

//更新商品數量
$sqlamount = "UPDATE `product` SET productAmount = ? WHERE `productId` = ?";
$stmtamount = $pdo->prepare($sqlamount);
$arrParamamount = [
    $arrprice['productAmount'] - $_POST["amountId"],
    $_POST["productId"]
];
$stmtamount->execute($arrParamamount);

if($count > 0) {
    header("Refresh: 3; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "商品新增成功";
    require_once('../loading.php');
    // $objResponse['success'] = true;
    // $objResponse['code'] = 200;
    // $objResponse['info'] = "商品新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 50; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "商品新增失敗";
    require_once('../loading.php');
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "商品新增失敗";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
