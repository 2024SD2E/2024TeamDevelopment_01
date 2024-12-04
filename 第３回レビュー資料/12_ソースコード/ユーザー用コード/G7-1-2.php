<?php
session_start();
$previousUrl = $_SERVER['HTTP_REFERER'] ?? null;
// セッションに保存されたデータをフォームの初期値として利用
$name = $_SESSION['form_data']['name'] ?? '';
$email = $_SESSION['form_data']['email'] ?? '';
$phone = $_SESSION['form_data']['phone'] ?? '';
$postal = $_SESSION['form_data']['postal'] ?? '';
$address = $_SESSION['form_data']['address'] ?? '';
$password = $_SESSION['form_data']['password'] ?? '';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規登録</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7-1-2.css">
</head>
<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>会員登録</span>
            </div>
    </header>

    <main class="form-container">
        <div class="back-link">
            <a href="<?= htmlspecialchars($previousUrl); ?>">＜戻る</a>
        </div>

        <section class="form-section">
            <h2>アカウント新規登録</h2>
            <form method="POST" action="G7-1-3.php">
                <div class="form-group">
                    <h3>氏名(カナ)</h3>
                    <input type="text" name="name" placeholder="例：ヤマダ タロウ" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <h3>メールアドレス</h3>
                    <input type="email" name="email" placeholder="例：example@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <h3>電話番号</h3>
                    <input type="tel" name="phone" placeholder="例：090-1234-5678" value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>
                <div class="form-group">
                    <h3>郵便番号</h3>
                    <input type="text" name="postal" placeholder="例：123-4567" value="<?php echo htmlspecialchars($postal); ?>" required>
                </div>
                <div class="form-group">
                    <h3>住所(都道府県/市町村/番地・号)</h3>
                    <input type="text" name="address" placeholder="例：渋谷区神南1-1-1" value="<?php echo htmlspecialchars($address); ?>" required>
                </div>
                <div class="password-change">
                    <h2>パスワード</h2>
                    <div class="form-group">
                        <h3>新しいパスワード</h3>
                        <input type="password" name="password" placeholder="新しいパスワード" value="<?php echo htmlspecialchars($password); ?>" required>
                    </div>
                    <div class="form-group">
                        <h3>新しいパスワード(確認)</h3>
                        <input type="password" name="confirm_password" placeholder="新しいパスワード(確認)" required>
                        <p id="error-message" style="color: red; display: none;">パスワードが一致していません。</p>
                    </div>
                </div>
                <div class="form-submit">
                    <button type="submit">確認画面へ</button>
                </div>
            </form>
        </section>
    </main>

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
</body>
</html>
