<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線


if(!isset($_GET['company_search'])){$_GET['company_search']="companyName";};
if(!isset($_GET['companyStatus'])){$_GET['companyStatus']="";};
if(!isset($_GET['search'])){$_GET['search']="";};

$s=$_GET['company_search'];


//SQL搜尋語法:取得 company 資料表總筆數
$sqlTotal = "SELECT count(1) 
FROM `company` 
WHERE `{$s}` LIKE '%{$_GET['search']}%' 
AND `companyStatus` LIKE '%{$_GET['companyStatus']}%' ";

//取得總筆數
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0];

//每頁幾筆
$numPerPage = 9;

//總頁數
$totalPages = ceil($total/$numPerPage);



$page = isset($_GET['page'])?(int)$_GET['page'] : 1;
//若 page 小於 1，則回傳 1
$page = $page < 1 ? 1 : $page;

?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>廠商資料列表</title>
    <style>
    .companyborder {
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
    tfoot{
        font-size: 20px;
        line-height: 40px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<?php require_once('./templates/sidebar.php'); ?>
<hr />
<h3>廠商管理頁面</h3>
<hr>

<form name="myForm2" method="GET"  action="company.php" >
    <table class="search">
        <tr>
            <td>
                <label for="companyStatus">狀態：</label>
                <select   select name="companyStatus" id="">
                    <option value="" selected>全部</option>
                    <option value="true" >啟用</option>
                    <option value="false">停用</option>
                </select> 
            </td>     
            <td>
                <label for="companyOther">其它：</label>
                <select name="company_search" id="">
                    <option value="companyName">廠商名稱</option>
                    <option value="companyIdentity">統一編號</option>
                    <option value="companyMail">信箱帳號</option>
                    <option value="companyPhone">連絡電話</option>
                    <option value="companyAddress">連絡地址</option>
                </select>
                <input class="input" type="text" name="search">
            </td>
            <td><input type="submit" value="搜尋"></td>
        </tr>
        <tr>
            
        </tr>   
    </table>


 

</form>

<form name="myForm" class="myForm animated fadeInUp" method="POST" action="./companys_delete.php " class="animated fadeInRight"  style="animation-duration:0.5s;">

    <table class="table table-striped border_article">
        <thead class="thead-mainColor">
            <tr>
                <th class="companyborder">ID</th>
                <th class="companyborder">狀態</th>
                <th class="companyborder">廠商logo</th>
                <th class="companyborder">廠商名稱</th>
                <th class="companyborder">統一編號</th>
                <th class="companyborder">聯絡電話</th>
                <th class="companyborder">聯絡地址</th>
                <th class="companyborder">信箱帳號</th>
                <th class="companyborder">功能</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql = "SELECT `companyId`,`companyName`,`companyPhone`,`companyIdentity`,`companyAddress`,`companyMail`,`companyLogo`,`companyStatus` 
                    FROM `company` 
                    WHERE `{$s}` LIKE '%{$_GET['search']}%' 
                    AND `companyStatus` LIKE '%{$_GET['companyStatus']}%'                   
                    ORDER BY `companyId` ASC LIMIT ? , ? ";
                    
            
            $arrParam = [($page - 1) * $numPerPage, $numPerPage];
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arrParam);

            if( $stmt->rowCount() > 0){
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for( $i = 0 ; $i < count($arr) ; $i++ ){
            ?>
                <tr>
                    <td class="companyborder">
                        <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['companyId']; ?>">
                        <?php echo $arr[$i]['companyId'] ?>
                    </td>

                    <td class="companyborder">
                    <?php
                        if($arr[$i]['companyStatus'] === "true"){ echo "啟用"; }else{echo "停用";}?>
                    </td>

                    <td class="companyborder">
                    <?php 
                    if($arr[$i]['companyLogo'] !== NULL){?>
                        <img class="wpx" src="../images/company/<?php echo $arr[$i]['companyLogo']  ?>" alt="">
                    <?php } ?>
                    </td>
                    <td class="companyborder"><?php echo $arr[$i]['companyName'] ?></td>
                    <td class="companyborder"><?php echo $arr[$i]['companyIdentity'] ?></td>
                    <td class="companyborder"><?php echo $arr[$i]['companyPhone'] ?></td>
                    <td class="companyborder"><?php echo $arr[$i]['companyAddress'] ?></td>
                    <td class="companyborder"><?php echo $arr[$i]['companyMail'] ?></td>
                    <td class="companyborder">
                        <input class="btn btn-mainColor btn-sm" type="button" value="詳細資料" onclick="location.href='./company_info.php?infoId=<?= $arr[$i]['companyId'] ?>'">
                        <input class="btn btn-mainColor btn-sm" type="button" value="刪除" onclick="if(confirm('是否確認刪除這筆資料？')) location.href='./company_delete.php?deleteId=<?= $arr[$i]['companyId'] ?>'">
                    </td>
                </tr>
            <?php
                }
            }else{
            ?>
                <tr>
                    <td class="companyborder" colspan="10">沒有資料</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
            <td  class="companyborder" colspan="10">
                <?php
                    if($page > 1){
                        echo '<a class="btn btn-mainColor btn-sm" href="company.php?page='.($page-1).'&companyStatus='.$_GET['companyStatus'].'&company_search='.$_GET['company_search'].'&search='.$_GET['search'].'">上一頁</a>　';
                    }
                    for( $i = 1 ; $i <= $totalPages ; $i++){?>

                        <a class="btn btn-mainColor btn-sm" href="?page=<?= $i ?>"> <?= $i ?> </a>

                <?php } 
                    if($page < $totalPages){
                        echo '<a class="btn btn-mainColor btn-sm" href="company.php?page='.($page+1).'&companyStatus='.$_GET['companyStatus'].'&company_search='.$_GET['company_search'].'&search='.$_GET['search'].'">下一頁</a>　';
                    }
                
                ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input type="submit" name="smb" value="多筆刪除" onclick="return confirm('是否確認刪除這些資料');">
</form>

<?php

?>

</body>
</html>