<?php

require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>會員優惠券查詢結果</title>
    <style>
    .couponborder {
        border: 1px solid #aaa;
    }
    .wpx {
        width: 60px;
    }
    .tb{
        width: 100%;
    }
    .myForm td , .myForm th{
        padding:3px 10px;
        text-align:center;
    }

    .search td{
        /* border:1px solid black; */
        padding:5px 10px;
    }

    .input{
        width:300px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<?php require_once('./templates/sidebar.php'); ?>
<hr />
<h3>優惠券搜尋結果</h3>
<hr>
<div>
    <a class="btn btn-secondary btn-sm" role="button" href="./coupon.php">優惠券列表</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./newCoupon.php">新增優惠券</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./couponRel.php">會員優惠券列表</a>
    <a class="btn btn-secondary btn-sm" role="button" href="./newRel.php">新增會員優惠券</a> 
</div>
<br />

<form name="myForm2" method="POST"  action="couponRel_search.php">
    <table class="search">
        <input type="text" name="search" placeholder="行銷名稱/折扣代碼/會員代碼"/ size="30">
        <input type="submit" name="submit-search" value="搜尋" />  
    </table>
</form>

<form name="myForm" class="myForm" method="POST" action="./deleteRelId.php" >

    <table class="tb  table table-striped border_article">
        <thead class="thead-mainColor">
            <tr>
                <th class="couponborder">選擇</th>
                <th class="couponborder">折扣碼ID</th>
                <th class="couponborder">會員代碼</th>
                <th class="couponborder">數量</th>
                <th class="couponborder">行銷名稱</th>
                <th class="couponborder">折扣代碼</th>
                <th class="couponborder">折扣數</th>
                <th class="couponborder">折扣開始時間</th>
                <th class="couponborder">折扣結束時間</th>
                <th class="couponborder">建立時間</th>
                <th class="couponborder">更新時間</th>
                <th class="couponborder">功能</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * 
                    FROM `coupon`
                    INNER JOIN `rel_member_coupon`
                    ON `coupon`.`couponId` = `rel_member_coupon`.`couponId` 
                    WHERE `coupon`.`discountName` LIKE '%{$_POST['search']}%'
                    OR `coupon`.`discountCode` LIKE '%{$_POST['search']}%'
                    OR `rel_member_coupon`.`couponId` LIKE '%{$_POST['search']}%'
                    OR `rel_member_coupon`.`memberId` LIKE '%{$_POST['search']}%'
                    ORDER BY `memberId` ASC";
                   

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            if( $stmt->rowCount() > 0){
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for( $i = 0 ; $i < count($arr) ; $i++ ){
            ?>
                <tr>
                    <td class="couponborder">
                        <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" />
                    </td>
                    <td class="couponborder"><?php echo $arr[$i]['couponId']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['memberId']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['memberCouponNum']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['discountName']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['discountCode']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['discountPercent']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['startTime']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['endTime']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['created_at']; ?></td>
                    <td class="couponborder"><?php echo $arr[$i]['updated_at']; ?></td>
                    </td>
                    <td class="couponborder">
                        <a class="btn btn-mainColor btn-sm"  href="./editRel.php?editId=<?php echo $arr[$i]['id']; ?>">編輯</a>
                        <a class="btn btn-mainColor btn-sm"  href="./deleteRel.php?deleteRelId=<?php echo $arr[$i]['id']; ?>">刪除</a>
                    </td>
                </tr>
            <?php
                }
            }else{
            ?>
                <tr>
                    <td class="companyborder" colspan="12">沒有資料</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <input class="btn btn-mainColor btn-sm" type="submit" name="smb" value="多筆刪除">
</form>



</body>
</html>