<style>
    div{
        width:800px;
        margin:auto;
        margin-top:110px;
        text-align:center;
        position:relative;
    }
    h2{
        position:absolute;
        font-size: 40px;
        color: #759fb4;
        left: 50%;
        transform: translateX(-50%);
        top:10%;
    }  
    img{
        width:100%;
    }
</style>
<div>
<img src="../images/image.gif" alt="">


</div>
<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 
//判斷商品數量
if((int)$_POST['amountId'] <= 0){
    header("Refresh: 2; url=./insetOrder.php");
    $anser="請確認商品數量";
    echo '<h2>'.$anser.'</h2>';
    // $objResponse['info'] = "請確認商品數量";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
//判斷會員是否存在
$sqlmember= "SELECT `memberId`
            FROM `member`
            WHERE `memberId` = {$_POST["memberId"]}";
$stmtmember= $pdo->prepare($sqlmember);
$stmtmember->execute();
if(!$stmtmember->rowCount()>0){
    header("Refresh: 2; url=./insetOrder.php");
    $anser="請確認會員ID";
    echo '<h2>'.$anser.'</h2>';
    // $objResponse['info'] = "請確認會員ID";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
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
        header("Refresh: 2; url=./insetOrder.php");
        $anser="商品數量不足，請確認";
        echo '<h2>'.$anser.'</h2>';
        // $objResponse['info'] = "商品數量不足，請確認";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit(); 
    }
    $price = $arrprice['productPrice'];
    $total = (int)$price * (int)$_POST['amountId'];
}else{
    header("Refresh: 2; url=./insetOrder.php");
    $anser="沒有此項商品";
    echo '<h2>'.$anser.'</h2>';
    // $objResponse['info'] = "沒有此項商品";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}
//先取得訂單編號
$sqlOrder = "INSERT INTO `orderlist` (`memberId`,`paymentTypeId`) VALUES (?,?)";
$stmtOrder = $pdo->prepare($sqlOrder);
$arrParamOrder = [
    $_POST["memberId"],
    $_POST["paymentTypeId"]
];
$stmtOrder->execute($arrParamOrder);
$orderId = $pdo->lastInsertId();

$count = 0;

//在新增到訂單明細
$sqlItemList = "INSERT INTO `item_lists` (`orderId`,`productId`,`checkPrice`,`checkQty`,`checkSubtotal`) VALUES (?,?,?,?,?)";
$stmtItemList = $pdo->prepare($sqlItemList);
$arrParamItemList = [
    $orderId,
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
    header("Refresh: 2; url=./orders.php");
    $anser="訂單新增成功";
    echo '<h2>'.$anser.'</h2>';
    // $objResponse['info'] = "訂單新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    // exit();
} else {
    header("Refresh: 2; url=./insetOrder.php");
    $anser="訂單新增失敗";
    echo '<h2>'.$anser.'</h2>';
    // $objResponse['info'] = "訂單新增失敗";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
}
?>
