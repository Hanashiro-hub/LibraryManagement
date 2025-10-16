<?php
//ログアウト後トップへリダイレクト
session_start();

//idが空なら未ログイン状態のためindexへリダイレクト
if (empty($_SESSION["id"])){
    header("Location:/LibraryManagement/index.php");
    exit;
}

if (!isset($_SESSION["csrf_token"])){
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

if(isset($_POST["logout"])){
    //csrfトークン判定
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])){
        //トークンが一致しない場合
        echo "不正なリクエスト";
        exit;
    }
    $_SESSION = [];
    session_destroy();
    header("Location:/LibraryManagement/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>
<body>
    <h1>ログアウトページ</h1>
    <a href="mypage.php">マイページ</a>
    <br>
    <form method="POST">
        <input type="submit" value="ログアウトする" name="logout">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
    </form>
</body>
</html>