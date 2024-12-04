<?php
require_once 'db.php';

$message = ""; // 送信メッセージを格納する変数

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $title = $_POST['title'];
    $messageContent = $_POST['message'];

    $sql = $pdo->prepare('INSERT INTO inquiries (email, subject, inquiry_content, name, phonenumber) VALUES (?, ?, ?, ?, ?)');
    $result = $sql->execute([$email, $title, $messageContent, $name, $phone]);

    if ($result) {
        $message = '※お問い合わせの送信が完了しました※';
    } else {
        $message = 'エラーが発生しました。もう一度お試しください。';
    }
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G4.css">
    <script>
        // 入力チェックのバリデーション関数
        function validateForm(event) {
            // 各フィールドの値を取得
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;
            const title = document.getElementById('title').value;
            const message = document.getElementById('message').value;

            // エラーメッセージ要素を取得
            const nameError = document.getElementById('name-error');
            const phoneError = document.getElementById('phone-error');
            const emailError = document.getElementById('email-error');
            const titleError = document.getElementById('title-error');
            const messageError = document.getElementById('message-error');

            // 各エラーメッセージをリセット
            nameError.textContent = "";
            phoneError.textContent = "";
            emailError.textContent = "";
            titleError.textContent = "";
            messageError.textContent = "";

            let isValid = true; // 入力が有効かどうかを判定

            // 名前の検証 (ひらがな、カタカナ、漢字、アルファベット、スペースを許可)
            if (!/^[ぁ-んァ-ヶ一-龠々a-zA-Z\s]+$/u.test(name)) {
                nameError.textContent = "お名前は無効な形式です。";
                isValid = false;
            }

            // 電話番号の検証 (10桁または11桁の数字)
            if (!/^\d{10,11}$/.test(phone)) {
                phoneError.textContent = "電話番号は10桁または11桁の数字で入力してください。";
                isValid = false;
            }

            // メールアドレスの検証
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.textContent = "メールアドレスの形式が無効です。";
                isValid = false;
            }

            // 件名の必須チェック
            if (title.trim() === '') {
                titleError.textContent = "件名は必須項目です。";
                isValid = false;
            }

            // お問い合わせ内容の必須チェック
            if (message.trim() === '') {
                messageError.textContent = "お問い合わせ内容は必須項目です。";
                isValid = false;
            }

            // 入力が無効な場合、送信を防止
            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
    <style>
        /* エラー表示のスタイル */
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php require_once 'header.php'; ?>

    <div class="toptext">
        CONTACT
    </div>
    <div style="color: red;"><?php echo $message; ?></div>
    <main>
        <section class="contact-form">
            <p><b>お問い合わせ内容をご入力ください</b></p><br>
            <form action="" method="POST" onsubmit="validateForm(event)">
                <label for="name">お名前（必須）</label><br>
                <input type="text" id="name" name="name" class="form-text" required>
                <div id="name-error" class="error-message"></div><br>

                <label for="phone">電話番号</label><br>
                <input type="tel" id="phone" name="phone" class="form-text" required>
                <div id="phone-error" class="error-message"></div><br>

                <label for="email">メールアドレス（必須）</label><br>
                <input type="email" id="email" name="email" class="form-text" required>
                <div id="email-error" class="error-message"></div><br>

                <label for="subject">件名（必須）</label><br>
                <input type="text" id="title" name="title" class="form-text" required>
                <div id="title-error" class="error-message"></div><br>

                <label for="message">お問い合わせ内容詳細</label><br>
                <textarea id="message" name="message" rows="10" class="form-text2" required></textarea>
                <div id="message-error" class="error-message"></div><br>

                <button type="submit" class="button">送信する</button>
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
</body>
</html>
