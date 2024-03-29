<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 
$count = 0;
//判斷此筆訂單是否存在
$sqlisset = "SELECT * FROM `orderlist` WHERE `orderId` = {$_POST['orderId']}";
$stmtisset = $pdo->prepare($sqlisset);
$stmtisset->execute();
if(!$stmtisset->rowCount()>0){
    header("Refresh: 2; ./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "此筆訂單不存在!!!!";
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
//先取得商品的價格
$sqlprice = "SELECT `product`.`productId`,`product`.`productPrice`
            FROM `product`
            WHERE `product`.`productId` = ?";
$stmtprice= $pdo->prepare($sqlprice);
$arrParamprice = [
    $_POST["productId"]
];
$stmtprice->execute($arrParamprice);
$arrprice = $stmtprice->fetchAll(PDO::FETCH_ASSOC);
$price = $arrprice[0]['productPrice'];
$total = (int)$price * (int)$_POST['amountId'];

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

if($count > 0) {
    header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "新增成功";
    require_once('../loading.php');
    // $objResponse['info'] = "訂單新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 2; url=./orderdetail.php?orderId={$_POST['orderId']}");
    $answer = "新增失敗";
    require_once('../loading.php');
    // $objResponse['info'] = "訂單新增失敗";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
