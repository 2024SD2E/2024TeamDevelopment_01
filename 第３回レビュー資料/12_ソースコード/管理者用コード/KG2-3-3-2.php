<?php
session_start();
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$admin_id = $_SESSION['admin_id'];
$password = $_SESSION['password'];
if (!isset($admin_id)) {
    header('Location: KG1.php');
    exit();
};

$delete_admin_id = $_SESSION['delete_admin_id'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/KG2-3-3-2.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>削除確認</title>

    <style>
        .text-label {
            display: flex;
            flex-direction: column;
            /* 縦に並べる */
            align-items: center;
            /* 中央揃え */
        }
    </style>

</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>管理者サイト</span>
        </div>
    </header>

    <main>
        <div class="header-inner">
            <a href="KG2-3-3-1.php" class="header-back">≺戻る</a>
            <h2 class="header-title">削除アカウント確認</h2>
        </div>

        <?php 
            require 'db.php';
            try {
                $stmt = $pdo->prepare('SELECT * FROM administrators WHERE admin_id = ?');
                $stmt->execute([$delete_admin_id]);
                $delete_admin = $stmt->fetch();
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
            }            
        
            echo '<h2>'.$delete_admin['name'].'さんのアカウント</h2>';
        ?>
    
        <label>
            <div class="text-lable">
                <span class="textbox-label">名前</span>
                <input type="text" class="textbox" name="" value="<?php echo $delete_admin['name']; ?>" readonly><br>
                <span class="textbox-label">メールアドレス</span>
                <input type="text" class="textbox" name="" value="<?php echo $delete_admin['email']; ?>" readonly><br>
            </div>
        </label>

        <hr>
        <h2>パスワード</h2>

        <label>
            <div class="text-lable">
                <span class="textbox-label">パスワード</span>
                <input type="password" class="textbox" name="" value="<?php echo $_SESSION['deletepass']; ?>" readonly><br>
                <span class="textbox-label">パスワード(確認)</span>
                <input type="password" class="textbox" name="" value="<?php echo $_SESSION['deletepass']; ?>" readonly><br>
            </div>
        </label>

        <div class="area">
            <form action="KG2-3-3-3.php" method="post">
            <input type="hidden" name="delete_admin_id" value="<?php echo $_SESSION['delete_admin_id']; ?>">
                <button class="check" name="">削除</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <a href="#">
                <div class="footer-logo">
                    <span>chocolate</span>
                    <span>GARDEN</span>
                </div>
            </a>
            <div class="footer-text">
                <span>生チョコレート専門店オンラインショップ</span>
            </div>
        </div>
    </footer>
</body>

</html>