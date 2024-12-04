<?php
session_start();
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$admin_id = $_SESSION['admin_id'];
$password = $_SESSION['password'];
if (!isset($admin_id)) {
    header('Location: KG1.php');
    exit;
};
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/KG2-3-3-3.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>削除完了</title>
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>管理者サイト</span>
        </div>
    </header>
    <main>
        <?php
            $delete_admin_id = $_POST['delete_admin_id'];

            require 'db.php';
            try {
                $stmt = $pdo->prepare('DELETE FROM administrators WHERE admin_id = ?');
                $stmt->execute([$delete_admin_id]);
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
            }
        ?>

        <h2>削除完了</h2>

        <div class="area">
            <form action="KG2-3" method="post">
                <button class="modoru" name="">管理者TOP</button>
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