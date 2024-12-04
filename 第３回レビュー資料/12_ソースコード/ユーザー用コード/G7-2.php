<?php
    session_start();
    require_once 'db.php';
    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)){
        header('Location: G7.php');
        exit;
    };


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $postal = $_POST['postal'] ?? '';
        $address = $_POST['address'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($user_id) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE user
                    SET 
                        name = :name,
                        email = :email,
                        phonenumber = :phone,
                        postal_code = :postal,
                        address = :addres,
                        password = :password
                    WHERE user_id = :user_id
                ");
        
                // パラメータをバインドして実行
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':postal' => $postal,
                    ':addres' => $address,
                    ':password' => $password,
                    ':user_id' => $user_id
                ]);

                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;
        
                header('Location: G7-1.php'); // リダイレクト先のページを指定
                exit;
            } catch (PDOException $e) {
                echo "更新中にエラーが発生しました: " . $e->getMessage();
            }
        } else {
            echo "ユーザーIDが指定されていません。";
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>会員登録</span>
            </div>
        </div>
    </header>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント情報変更</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7-1-2.css">
</head>
<body>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const passwordField = form.querySelector('input[name="password"]');
        const confirmPasswordField = form.querySelector('input[name="confirm_password"]');
        const submitButton = form.querySelector('button[type="submit"]');
        const errorMessage = document.getElementById('error-message');

        // 初期状態でボタンを無効化
        submitButton.disabled = true;

        // パスワードチェック関数
        function checkPasswords() {
            if (passwordField.value && passwordField.value === confirmPasswordField.value) {
                submitButton.disabled = false; // 一致したらボタンを有効化
                errorMessage.style.display = 'none'; // エラーメッセージ非表示
            } else {
                submitButton.disabled = true; // 一致しない場合は無効化
                errorMessage.style.display = 'block'; // エラーメッセージ表示
            }
        }

        // イベントリスナーを設定
        passwordField.addEventListener('input', checkPasswords);
        confirmPasswordField.addEventListener('input', checkPasswords);
    });
    </script>


    <?php 
    $user_id = $_SESSION['user_id'];
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);

    $user = $sql->fetch();

    $pdo = null;
    ?>
    <main class="form-container">
        <div class="back-link">
            <a href="G7-1.php">＜マイページに戻る</a>
        </div>
        
        <section class="form-section">
            <h2>アカウント情報変更</h2>

            <form method="POST" action="">

                <div class="form-group">
                    <h3>氏名(カナ)</h3>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                </div>

                <div class="form-group">
                    <h3>メールアドレス</h3>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <h3>電話番号</h3>
                    <input type="tel" name="phone" value="<?php echo $user['phonenumber']; ?>" required>
                </div>

                <div class="form-group">
                    <h3>郵便番号</h3>
                    <input type="text" name="postal" value="<?php echo $user['postal_code']; ?>" required>
                </div>

                <div class="form-group">
                    <h3>住所(都道府県/市町村/番地・号)</h3>
                    <input type="text" name="address" value="<?php echo $user['address']; ?>"  required>
                </div>

                <section class="password-change">
                    <h2>パスワード変更</h2>

                    <div class="form-group">
                        <h3>新しいパスワード</h3>
                        <input type="password" name="password" value="<?php echo $user['password']; ?>" required>
                    </div>

                    <div class="form-group">
                        <h3>新しいパスワード(確認)</h3>
                        <input type="password" name="confirm_password" required>
                        <p id="error-message" style="color: red; display: none;">パスワードが一致していません。</p>
                    </div>
                </section>

                <div class="form-submit">
                    <button type="submit">完了</button>
                </div>
            </form>
        </section>
    </main>
</body>
<footer>
        <div class="footer-container">
            <a href="#"><div class="footer-logo">
                <span>chocolate</span>
                <span>GARDEN</span>
             </div></a>
             <div class="footer-text">
                <span>生チョコレート専門店オンラインショップ</span>
            </div>
        </div>
     </footer>    
    
</html>
