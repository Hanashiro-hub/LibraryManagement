<?php
//booksテーブルからtitle, author, star, created, updatedを取得
//検索結果は縦に一覧表示
//検索はタイトルのみ対応
include_once("./database/connect.php");
session_start();

//idが空なら未ログイン状態のためindexへリダイレクト
if (empty($_SESSION["id"])){
    header("Location:/LibraryManagement/index.php");
    exit;
}

$_SESSION["search"] = "";

$sql = "SELECT id, title, author, star, created, updated FROM books WHERE id = :id;";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id",$_SESSION["id"], PDO::PARAM_INT);
$statement->execute();


//検索ボタン押下時の処理
if (isset($_GET["submitbutton"])){
    $q = $_GET["search"] ?? '';
    $_SESSION["search"] = htmlspecialchars($_GET["search"], ENT_QUOTES, "UTF-8");
    
    $sql = "SELECT id, title, author, star, created, updated FROM books WHERE title LIKE :search_word AND id = :id;";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(":search_word", '%'.$_GET["search"].'%', PDO::PARAM_STR);
    $statement->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
    $statement->execute();
}

//リセットボタン押下時の処理
if (isset($_GET["resetbutton"])){
    if (isset($_SESSION["search"])){
        $_SESSION["search"]="";
        header("Location: " . $_SERVER['PHP_SELF'], true, 303);
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
    <title>書籍ページTOP</title>
</head>
<body>
    <h1>書籍ページ</h1>
    <form method="GET">
        <input type="text" name="search" placeholder="書籍タイトルを入力" value="<?php echo $_SESSION['search'] ?>">
        <input type="submit" value="検索" name="submitbutton"><!-- タイトル限定 -->
        <input type="submit" value="検索条件をリセット" name="resetbutton">
    </form>
    <br>
    <a href="book_registration.php">書籍新規登録</a>
    <br>
    <a href="mypage.php">マイページへ戻る</a>
    <h2>書籍一覧</h2>
    <a>書籍名 / 作者名 / 評価 / 登録日 / 更新日</a>
    <br>
    <a>
        <?php
        //書籍ごとにタイトル・作者名・評価・登録日・更新日を表示
        //title, author, star, created, updated
    
        //1行ずつ取得し表示
        while($stmt = $statement->fetch()){
            //エスケープ処理
            $escaped["title"] = htmlspecialchars($stmt["title"],ENT_QUOTES,"UTF-8");
            $escaped["author"] = htmlspecialchars($stmt["author"],ENT_QUOTES,"UTF-8");
            $escaped["star"] = htmlspecialchars($stmt["star"],ENT_QUOTES,"UTF-8");
            $escaped["created"] = htmlspecialchars($stmt["created"],ENT_QUOTES,"UTF-8");
            $escaped["updated"] = htmlspecialchars($stmt["updated"],ENT_QUOTES,"UTF-8");

            //評価表示
            switch($escaped["star"]){
                case 1:
                    $escaped["star"] = "★☆☆☆☆";
                    break;
                case 2:
                    $escaped["star"] = "★★☆☆☆";
                    break;
                case 3:
                    $escaped["star"] = "★★★☆☆";
                    break;
                case 4:
                    $escaped["star"] = "★★★★☆";
                    break;
                case 5:
                    $escaped["star"] = "★★★★★";
                    break;
            }

            //クエリをエンコード
            $encode_title = urlencode($stmt["title"]);
            $encode_author = urlencode($stmt["author"]);

            echo $escaped["title"]." / ".$escaped["author"]." / ".$escaped["star"]." / ".$escaped["created"]." / ".$escaped["updated"];
            echo "<a href='book_edit.php?title=$encode_title&author=$encode_author'>[更新]</a>";
            echo "<a href='book_delete.php?title=$encode_title&author=$encode_author'>[削除]</a>";
            echo "<br>";
        }        
        ?>
    </a>
</body>
</html>