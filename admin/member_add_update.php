<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線



//判斷是否有未填項
if($_POST["memberName"] == "" || $_POST["memberGender"] == "" || $_POST["memberBirth"] == "" || 
    $_POST["memberPhone"] == "" || $_POST["memberIdentity"] == "" || $_POST["memberAddress"] == "" || 
    $_POST["memberMail"] == "" || $_POST["memberPwd"] == ""){
    header("Refresh: 0; url=./register.php?err=1");   
    exit();
}



//搜尋語法
$sql = "INSERT INTO `member` (`memberName`,`memberGender`,`memberBirth`,`memberPhone`,`memberIdentity`,`memberAddress`,`memberMail`,`memberPwd`,`memberImg`,`memberStatus`) 
VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

$sittime = date("YmdHis");


if($_FILES['memberImg']["error"] === 4){
    // echo "none";
    $tmpImg = "memberImg_".$sittime.".jpg";
    copy("../images/member/tmp/tmp.jpg","../images/member/".$tmpImg);
}elseif($_FILES["memberImg"]["error"] === 0){
    $extension = pathinfo($_FILES["memberImg"]["name"], PATHINFO_EXTENSION);
    $tmpImg = "memberImg_".$sittime.".".$extension;
    if(!move_uploaded_file($_FILES["memberImg"]["tmp_name"], "./images/member/".$tmpImg)){
        header("Refresh:3; url=./register.php");
        echo "圖片上傳失敗";
        exit();
    }
}



$arrParam = [
    $_POST["memberName"],
    $_POST["memberGender"],
    $_POST["memberBirth"],
    $_POST["memberPhone"],
    $_POST["memberIdentity"],
    $_POST["memberAddress"],
    $_POST["memberMail"],
    sha1($_POST["memberPwd"]),
    $tmpImg,
    "true"
];



//查詢
$stmt = $pdo->prepare($sql);


// var_dump($stmt);
// echo "<pre>";
// print_r($stmt);

// echo "</pre>";
// exit();


$stmt->execute($arrParam);


if($stmt->rowCount() === 1) {
    header("Refresh: 2; url=./member_add.php?complete=1");
    $answer = "註冊成功";
    require_once('../loading.php');
} else {
    header("Refresh: 2; url=./member_add.php?err=2");
    $answer = "註冊失敗";
    require_once('../loading.php');
}

?>






















?>

