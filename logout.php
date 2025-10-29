<?php
//ログアウト後トップへリダイレクト
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
include_once("./lib/common_function.php");
include_once("./database/connect.php");

isLogin();
GenerateCsrfToken("logout");

err_message();

if(isset($_POST["logout"])){
    //csrfトークン照合とリセット
    TokenCkeckAndReset("logout", "logout.php");
    //セッション破棄
    $_SESSION = [];
    session_destroy();
    header("Location:/LibraryManagement/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <script>
        /*ブラウザバックを検知しリロード*/
        window.addEventListener('pageshow', function(event) {
            if (event.persisted){
                window.location.reload();
            }
        });
    </script>

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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]["logout"]; ?>">
    </form>
</body>
</html>