<?php
//booksテーブルに書籍情報を登録
//登録後書籍ページ(book_page.php)へリダイレクト
include_once("./database/connect.php");
include_once("./lib/common_function.php");
date_default_timezone_set('Asia/Tokyo');
session_start();
isLogin();

GenerateCsrfToken("book_registration");

//エラーメッセージ表示(CSRFで弾かれた場合)
err_message();

//備忘:以下if内は登録ボタンが押下された時の処理
if (isset($_POST["submitbutton"])){
    //csrfトークン照合とリセット
    TokenCkeckAndReset("book_registration", "book_registration.php");

    $created_date = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `books` (`id`, `title`, `author`, `star`, `created`, `updated`) VALUES (:id, :title, :author, :star, :created, :updated);";
    $statement = $pdo->prepare($sql);

    $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
    $statement->bindParam(":title", $_POST["title"], PDO::PARAM_STR);
    $statement->bindParam(":author", $_POST["author"], PDO::PARAM_STR);
    $statement->bindParam(":star", $_POST["star"], PDO::PARAM_INT);
    $statement->bindParam(":created", $created_date, PDO::PARAM_STR);
    $statement->bindParam(":updated", $created_date, PDO::PARAM_STR);

    try
    {
        $statement->execute();
        header("Location:/LibraryManagement/book_page.php");
        exit;
    }
    catch(Exception)
    {
        echo "登録に失敗しました。";
        GenerateCsrfToken("book_registration");//トークン再発行
    }

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
    <title>新規書籍登録</title>
</head>
<body>
    <h1>新規書籍登録</h1>
    <form method="POST">
        <label for="title">書籍タイトル</label>
        <input type="text" name="title" required>
        <br>

        <label for="author">著者</label>
        <input type="text" name="author" required>
        <br>

        <label for="star">評価</label>
        <input type="number" name="star" min=1 max=5 required>
        <br>

        <input type="submit" value="登録" style="margin: 3px" name="submitbutton">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]["book_registration"]; ?>">
    </form>  
    <a href="book_page.php">書籍ページへ戻る</a>
</body>
</html>