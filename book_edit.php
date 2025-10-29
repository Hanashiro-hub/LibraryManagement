<?php
include_once("./database/connect.php");
include_once("./lib/common_function.php");
date_default_timezone_set('Asia/Tokyo');
session_start();
isLogin();

GenerateCsrfToken("book_edit");

$sql = "SELECT title, author, star FROM books WHERE id = :id AND title = :title AND author = :author;";
$statement = $pdo->prepare($sql);

//情報更新後にテキストボックスに反映させるための処理
if (isset($_GET["title"]) and isset($_GET["author"])){
    $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
    $statement->bindParam(":title", $_GET["title"], PDO::PARAM_STR);
    $statement->bindParam(":author", $_GET["author"], PDO::PARAM_STR);

    $statement->execute();
}else{
    $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
    $statement->bindParam(":title", $_SESSION["new_title"], PDO::PARAM_STR);
    $statement->bindParam(":author", $_SESSION["new_author"], PDO::PARAM_STR);

    $statement->execute();
}

$stmt = $statement->fetch(PDO::FETCH_ASSOC);

if ($stmt !== false){
    //エスケープ処理
    $escaped["title"] = htmlspecialchars($stmt["title"],ENT_QUOTES,"UTF-8");
    $escaped["author"] = htmlspecialchars($stmt["author"],ENT_QUOTES,"UTF-8");
    $escaped["star"] = htmlspecialchars($stmt["star"],ENT_QUOTES,"UTF-8");
}else{
    echo "書籍の取得に失敗しました。<br>";
    echo "<a href='book_page.php'>書籍ページへ戻る</a>";
    exit;
}

//更新ボタン押下時の処理
if (isset($_POST["submitbutton"])){
    //csrfトークン照合とリセット
    TokenCkeckAndReset("book_edit", "book_edit.php");

    $updated_date = date("Y-m-d H:i:s");

    $sql = "UPDATE `books` SET `title` = :title, `author` = :author, `star` = :star, `updated` = :updated WHERE `books`.`id` = :id AND `books`.`title` = :old_title AND `books`.`author` = :old_author;";
    $statement = $pdo->prepare($sql);

    $statement->bindParam(":title", $_POST["title"], PDO::PARAM_STR);
    $statement->bindParam(":author", $_POST["author"], PDO::PARAM_STR);
    $statement->bindParam(":old_title", $stmt["title"], PDO::PARAM_STR);
    $statement->bindParam(":old_author", $stmt["author"], PDO::PARAM_STR);
    $statement->bindParam(":star", $_POST["star"], PDO::PARAM_STR);
    $statement->bindParam(":updated", $updated_date, PDO::PARAM_STR);
    $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
    
    try{
        $statement->execute();
        header("Location:/LibraryManagement/book_page.php");
        exit;
    }catch(PDOException){
        echo "更新失敗";
        GenerateCsrfToken("book_edit");//トークン再発行
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
    <title>書籍情報変更</title>
</head>
<body>
    <h1>書籍情報変更ページ</h1>
    <a href="book_page.php">書籍ページ</a>

    <form method="POST">
        <label for="title">書籍名</label>
        <input type="text" name="title" value="<?php echo $escaped["title"]?>" required>
        <br>

        <label for="author">作者名</label>
        <input type="text" name="author" value="<?php echo $escaped["author"]?>" required>
        <br>

        <label for="star">評価</label>
        <input type="number" name="star" value="<?php echo $escaped["star"]?>" min=1 max=5 required>
        <br>

        <input type="submit" value="更新" name="submitbutton">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]["book_edit"]; ?>">
    </form>
</body>
</html>