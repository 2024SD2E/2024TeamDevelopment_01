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

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ラジオボタンの値を取得
        $selected_admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : null;
        $deletepass1 = $_POST['deletepass1'];
        $deletepass2 = $_POST['deletepass2'];
        $_SESSION['deletepass'] = $deletepass1;

        if($deletepass1 !== $deletepass2){
            $error_message = "パスワードが一致していません。";
        }elseif($deletepass1 !== $password){
            $error_message = "パスワードが間違っています。";
        }elseif(empty($selected_admin_id)){
            $error_message = "削除するアカウントを選択してください。";
        }else{
            $_SESSION['delete_admin_id'] = $_POST['admin_id'];
            // ログイン成功時にリダイレクト
            header("Location: KG2-3-3-2.php");
            exit(); // リダイレクト後にスクリプトを終了
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/KG2-3-3-1.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>管理者削除</title>
    <script>
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('click', function() {
                if (this.checked) {
                    this.checked = false; // 選択解除
                } else {
                    this.checked = true; // 再選択
                }
            });
        });
    </script>

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
            <a href="KG2-3.php" class="header-back">≺戻る</a>
            <h2 class="header-title">削除アカウント選択</h2>
        </div>

        <form action="" method="POST">
            <?php
            require 'db.php';

            try {
                $stmt = $pdo->prepare("SELECT * FROM administrators");
                $stmt->execute();
                $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

                echo '<div class="radio-container">';
                foreach ($result as $row) {
                    echo '<div class="radio-group">';
                    echo '<input type="radio" name="admin_id" value="' . $row['admin_id'] . '">';
                    echo $row['name'];
                    echo '</div>';
                }
                echo '</div>';
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
            }
            $pdo = null;
            ?>

            <hr>
            <h2>パスワード</h2>

            <label>
                <div class="text-lable">
                    <span class="textbox-label">パスワード</span>
                    <input type="password" class="textbox" name="deletepass1" required><br>
                    <span class="textbox-label">パスワード(確認)</span>
                    <input type="password" class="textbox" name="deletepass2" required><br>
                </div>
            </label>

            <!-- エラーメッセージを表示 -->
            <div class="passcheck">
                <?php if (!empty($error_message)) : ?>
                    <p class="error-message"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>

            <div class="area">
                <button type="submit" class="check" name="check">確認画面へ</button>
            </div>
        </form>
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
    </div>
</body>

</html>