<?php
//ログインチェック　セッションにidがなければ未ログインとみなしindexへリダイレクト
function isLogin(){
    if (empty($_SESSION["id"])){
        header("Location:/LibraryManagement/index.php");
        exit;
    }
}

//セッションがCSRFトークンを保持していなければ生成する
function GenerateCsrfToken(){
    if (!isset($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
    }
}

//csrfトークン判定
function CheckCsrfToken(){
    if(isset($_POST["csrf_token"])){
        if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])){
            //トークンが一致しない場合
            echo "不正なリクエスト";
            exit;
        }
    }else{
        //リクエストにトークが付与されていない場合
        echo "不正なリクエスト";
        exit;
    }
}