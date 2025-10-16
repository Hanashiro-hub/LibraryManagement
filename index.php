<?php
//ログインフォームと会員登録ページへのリンク
//ログイン後マイページへ遷移
session_start();
include_once("./database/connect.php");

if (!isset($_SESSION["csrf_token"])){
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

if (isset($_POST["submitbutton"])){

    //csrfトークン判定
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

    $sql = "SELECT id, password FROM users WHERE `email` = :email";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
    $statement->execute();
    $stmt = $statement->fetch(PDO::FETCH_ASSOC);

    //ログイン処理
    if ($stmt !== false){
        if(password_verify($_POST["pass"], $stmt["password"])){
            $_SESSION["id"] = $stmt["id"];
            session_regenerate_id(true);
            header("Location:/LibraryManagement/mypage.php");
            exit;
        }else{
            echo "メールアドレス又はパスワードが間違っています。";
        }
    }else{
        echo "メールアドレス又はパスワードが間違っています。";
    }
    
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOP</title>
</head>
<body>
    <h1>TOPページ</h1>

    <form method="POST">
        <div class="login">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" required>
            <br>
            <label for="pass">パスワード</label>
            <input type="password" name="pass" required>
            <br>
            <input type="submit" value="ログイン" name="submitbutton">
            <br>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
        </div>
    </form>
    
    <a href="sign_up.php">会員登録</a>
    
</body>
</html>