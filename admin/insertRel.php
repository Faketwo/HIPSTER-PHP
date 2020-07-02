<style>
body {
  background: #EEE;
  font-family: helvetica, arial, sans-serif;
  margin: 0;
  padding: 0;
  margin-top: 20px;
}

#scissor {
	width: 300px;
	height: 46px;
	margin: 0 auto;
}

#cut-out {
  width: 300px;  
  height: 200px;
  margin: 0px auto 20px auto;
  border: 10px dashed black;
  font-size: 25px;
  font-weight: bold;
  text-align: center;
  padding-top:30px;
}

#cut-out p {
	margin-top: 136px;
}
</style>
<?php
header("Content-Type: text/html; chartset=utf-8");

require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//SQL 敘述
$sql = "INSERT INTO `rel_member_coupon` 
        (`couponId`, `memberId`, `memberCouponNum`) 
        VALUES (?, ?, ?)";


//繫結用陣列
$arr = [
    $_POST['couponId'],
    $_POST['memberId'],
    $_POST['memberCouponNum']
];

$pdo_stmt = $pdo->prepare($sql);
$pdo_stmt->execute($arr);
if($pdo_stmt->rowCount() === 1) {
    header("Refresh: 2; url=./couponRel.php");
    // echo "新增成功";
    echo '<div id="scissor"><img src="https://i.imgur.com/PRgxt.png"></div>';
    echo '<div id="cut-out">新增成功<br><br>New Coupon Assigned!</新增成功></div>';
} else {
    header("Refresh: 2; url=./couponRel.php");
    echo '<div id="scissor"><img src="https://i.imgur.com/PRgxt.png"></div>';
    echo '<div id="cut-out">新增失敗<br><br>New Coupon Assigned!</新增失敗></div>';
}