<?php
session_start(); 


if( !isset($_SESSION['username']) && !isset($_SESSION['identity']) ) {
    header("Refresh: 2; url=../login.php");
    $answer = "請確實登入…3秒後自動回登入頁";
    require_once('../loading.php');
    exit();
}

if($_SESSION['identity'] !== 'company' ){
    header("Refresh: 2; url=../login.php");
    $answer = "您無權使用該網頁…3秒後自動回登入頁";
    require_once('../loading.php');
    exit();
}

if($_SESSION['name'] !== 'admin'){
    header("Refresh: 2; url=../index.php");
    $answer = "您無權使用該網頁…3秒後自動回首頁";
    require_once('../loading.php');
    exit();
}