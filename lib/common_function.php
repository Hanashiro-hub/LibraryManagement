<?php
//ログインチェック　セッションにidがなければ未ログインとみなしindexへリダイレクト
function isLogin(){
    if (empty($_SESSION["id"])){
        header("Location:/LibraryManagement/index.php");
        exit;
    }
}

//CSRFトークンを生成
function GenerateCsrfToken($form){
    //トークンが無ければ生成
    if (!isset($_SESSION["csrf_token"][$form])){
        $_SESSION["csrf_token"][$form] = bin2hex(random_bytes(16));
    }
}

//csrfトークン判定
function CheckCsrfToken($form,$url){
    if (!isset($_POST["csrf_token"])){
        ResetCsrfToken($form);
        $_SESSION["err"] = "もう一度やり直してください。";
        header("Location:$url");
        exit;
    }

    if (!hash_equals($_SESSION["csrf_token"][$form], $_POST["csrf_token"])){
        ResetCsrfToken($form);
        $_SESSION["err"] = "もう一度やり直してください。";
        header("Location:$url",true,303);
        exit;
    }
}

//トークンリセット
function ResetCsrfToken($form){
    unset($_SESSION["csrf_token"][$form]);
}

//csrfトークン照合とリセット
function TokenCkeckAndReset($form,$url){
    CheckCsrfToken($form,$url);
    ResetCsrfToken($form);
}

function err_message(){
    if (isset($_SESSION["err"])){
        echo $_SESSION["err"];
        unset($_SESSION["err"]);
    }
}
