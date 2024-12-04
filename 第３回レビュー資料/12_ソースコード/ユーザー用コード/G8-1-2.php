<?php
session_start();
$previousUrl = $_SERVER['HTTP_REFERER'] ?? null;

if (isset($_COOKIE['session_token'])) {
    $session_token = $_COOKIE['session_token'];
}


if (isset($_SESSION['user_id']) || isset($session_token)) {
    

    $user_id = $_SESSION['user_id'];
    require_once 'db.php';
    if ($user_id) {
        // ログインユーザーのカートアイテムを取得
        $stmt = $pdo->prepare("
            SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
            FROM cartsitem ci
            JOIN products p ON ci.product_id = p.product_id
            WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
        ");
        $stmt->execute(['user_id' => $user_id]);
    } else {
        // ゲストユーザーのセッショントークンを基にカートアイテムを取得
        $stmt = $pdo->prepare("
            SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
            FROM cartsitem ci
            JOIN products p ON ci.product_id = p.product_id
            WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE session_token = :session_token)
        ");
        $stmt->execute(['session_token' => $session_token]);
    }

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!($cartItems)){
        header("Location: G8-1.php");
        exit;  
    }

    $name = $_SESSION['form_data']['name'];
    $email = $_SESSION['form_data']['email'];
    $phone = $_SESSION['form_data']['phone'];
    $postalCode = $_SESSION['form_data']['postalCode'];
    $address = $_SESSION['form_data']['address'];

    // データベース接続



    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $user = $sql->fetch();
}else{
    header("Location: G8-1.php");
    exit;  
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お届け先住所入力</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G8-1-2.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <main class="main-container">
        <a href="./G8-1.php" class="back-to-shop">＜カートへ戻る</a><br>
        <form action="G8-1-3.php" method="POST" id="addressForm">
            <div class="form-section">
                <div class="form-group">
                    <h1>お届け先住所入力</h1>
                    <label for="email" class="form-label required">メールアドレス</label>
                    <input type="email" id="email" name="email" class="form-input" value="<?php echo isset($email) ?  htmlspecialchars($email) : htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">お届け先情報</h2>
                
                <div class="form-group">
                    <label for="lastName" class="form-label">お名前</label>
                    <input type="text" id="lastName" name="name" class="form-input" value="<?php echo isset($name) ?  htmlspecialchars($name) : htmlspecialchars($user['name']); ?>" required>
                    <div class="form-note">例）山田 太郎</div>
                </div>

                <div class="form-group">
                    <label for="postalCode" class="form-label">郵便番号</label>
                    <input type="text" id="postalCode" name="postalCode" class="form-input" value="<?php echo isset($postalCode) ? htmlspecialchars($postalCode) : htmlspecialchars($user['postal_code']); ?>"
                           pattern="\d{3}-?\d{4}" required>
                    <div class="form-note">例）123-4567</div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">住所(都道府県/市町村/番地・号)</label>
                    <input type="text" id="address" name="address" class="form-input" value="<?php echo isset($address) ? htmlspecialchars($address) : htmlspecialchars($user['address']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">電話番号</label>
                    <input type="tel" id="phone" name="phone" class="form-input" 
                           pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?php echo isset($phone) ? htmlspecialchars($phone) : htmlspecialchars($user['phonenumber']); ?>" required>
                    <div class="form-note">例）090-1234-5678</div>
                </div>
            </div>

            <button type="submit" class="submit-button">レジに進む</button>
        </form>
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
        document.getElementById('addressForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // メールアドレスのバリデーション
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                document.getElementById('emailError').style.display = 'block';
                email.classList.add('error');
                isValid = false;
            } else {
                document.getElementById('emailError').style.display = 'none';
                email.classList.remove('error');
            }

            // 郵便番号のフォーマット
            const postalCode = document.getElementById('postalCode');
            postalCode.value = postalCode.value.replace(/^(\d{3})(\d{4})$/, '$1-$2');

            // 電話番号のフォーマット
            const phone = document.getElementById('phone');
            phone.value = phone.value.replace(/^(\d{3})(\d{4})(\d{4})$/, '$1-$2-$3');

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
