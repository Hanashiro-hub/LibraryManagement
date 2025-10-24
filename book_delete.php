<?php
include_once("./database/connect.php");
include_once("./lib/common_function.php");

date_default_timezone_set('Asia/Tokyo');
session_start();
isLogin();

if (!isset($_SESSION["csrf_token"])){
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

$sql = "SELECT title, author FROM books WHERE id = :id AND title = :title AND author = :author;";
$statement = $pdo->prepare($sql);

$statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
$statement->bindParam(":title", $_GET["title"], PDO::PARAM_STR);
$statement->bindParam(":author", $_GET["author"], PDO::PARAM_STR);

try{
    $statement->execute();
    $stmt = $statement->fetch(PDO::FETCH_ASSOC);
}catch(PDOException){
    echo "DB読み込み失敗";
}

//削除ボタン押下時の処理
if (isset($_POST["deletebutton"])){
    //csrfトークン判定
    CheckCsrfToken();

    $sql = "DELETE FROM `books` WHERE `books`.`id` = :id AND `books`.`title` = :title AND `books`.`author` = :author;";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
    $statement->bindParam(":title", $_GET["title"], PDO::PARAM_STR);
    $statement->bindParam(":author", $_GET["author"], PDO::PARAM_STR);

    try{
        $statement->execute();
        header("Location:/LibraryManagement/book_page.php");
    }catch(PDOException){
        echo "削除失敗";
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
    <title>書籍削除</title>
</head>
<body>
    <h1>書籍削除ページ</h1>
    <a>
        <?php
        //エスケープ処理
        if ($stmt !== false){
            //エスケープ処理
            $escaped["title"] = htmlspecialchars($stmt["title"],ENT_QUOTES,"UTF-8");
            $escaped["author"] = htmlspecialchars($stmt["author"],ENT_QUOTES,"UTF-8");
        }else{
            echo "書籍の取得に失敗しました。<br>";
            echo "<a href='book_page.php'>書籍ページへ戻る</a>";
            exit;
        }  

        echo "書籍名：".$escaped["title"];
        echo "<br>";
        echo "作者名：".$escaped["author"];
        ?>
    </a>
    <br>
    <form method="POST">
        <input type="submit" value="削除する" name="deletebutton">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
    </form>
    <a href="book_page.php">書籍ページへ戻る</a>
</body>
</html>