<?php
//userテーブルに情報を登録
//登録後ログインページ(index.php)へリダイレクト
include_once("./database/connect.php");
include_once("./lib/common_function.php");

date_default_timezone_set('Asia/Tokyo');
session_start();
GenerateCsrfToken("sign_up");

//エラーメッセージ表示(CSRFで弾かれた場合)
err_message();

if (isset($_POST["submitbutton"])){
    //CSRFトークン照合とリセット
    TokenCkeckAndReset("sign_up", "sign_up.php");

    $created_date = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `users` (`user_name`, `password`, `email`, `created`) VALUES (:username, :pass, :email, :created);";
    $statement = $pdo->prepare($sql);

    $pass_hash = password_hash($_POST["pass"], PASSWORD_DEFAULT);

    $statement->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
    $statement->bindParam(":pass", $pass_hash, PDO::PARAM_STR);
    $statement->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
    $statement->bindParam(":created", $created_date, PDO::PARAM_STR);
    
    try{
        $statement->execute();
        header("Location:/LibraryManagement/index.php");
        exit;
    }
    catch(Exception){
        GenerateCsrfToken("sign_up");//トークン再発行
        echo "登録に失敗しました。";
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
</head>
<body>
    <h1>会員登録ページ</h1>
    
    <form method="POST">
        <div class="sign_up">
            <label for="username">ユーザー名</label>
            <input type="text" name="username" required>
            <br>

            <label for="email">メールアドレス</label>
            <input type="email" name="email" required>
            <br>

            <label for="pass">パスワード</label>
            <input type="password" name="pass" required>
            <br>

            <input type="submit" value="登録" name="submitbutton">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]["sign_up"]; ?>">
        </div>
    </form>

    <a href="index.php">TOP</a>

</body>
</html>