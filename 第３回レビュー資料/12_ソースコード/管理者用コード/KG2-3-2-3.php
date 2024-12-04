<?php
session_start();
$_SESSION['email'] = $_POST['changeemail'];
$_SESSION['name'] = $_POST['changename'];
$admin_id = $_SESSION['admin_id'];
$_SESSION['password'] = $_POST['changepass'];
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
    <link rel="stylesheet" href="./css/KG2-3-2-3.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>修正完了</title>
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
            $changename = $_POST['changename'];
            $changeemail = $_POST['changeemail'];
            $changepass = $_POST['changepass'];

            require 'db.php';
            try {
                $stmt = $pdo->prepare('UPDATE administrators SET name = ?, email = ?, password = ? WHERE admin_id = ?');
                $stmt->execute([$changename, $changeemail, $changepass, $admin_id]);
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
            }
        ?>

        <h2>修正完了</h2>

        <div class="area">
            <form action="KG2-3.php" method="post">
                <button class="back" name="">管理者TOP</button>
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