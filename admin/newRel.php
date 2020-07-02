<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新增會員優惠券</title>
    <style>
    .border {
        border: 1px solid;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<?php require_once('./templates/sidebar.php'); ?>
<hr />
<h3>新增會員優惠券</h3>
<hr />
<div>
    <a class="btn btn-secondary btn-sm" role="button" href="./coupon.php">優惠券列表</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./newCoupon.php">新增優惠券</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./couponRel.php">會員優惠券列表</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./newRel.php">新增會員優惠券</a> 
</div>
<br />
<form name="myForm" method="POST" action="./insertRel.php" enctype="multipart/form-data">
<table class="border">
    <thead>
        <tr>
            <th class="border">折扣碼ID</th>
            <th class="border">會員代碼</th>
            <th class="border">數量</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border">
                <input type="text" name="couponId" id="couponId" value="" maxlength="9" />
            </td>
            <td class="border">
                <input type="text" name="memberId" id="memberId" value="" maxlength="10" />
            </td>
            <td class="border">
                <input type="text" name="memberCouponNum" id="memberCouponNum" value="" maxlength="10" />
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="3"><input type="submit" name="smb" value="新增"></td>
        </tr>
    </tfoot>
</table>
</form>

</body>
</html>