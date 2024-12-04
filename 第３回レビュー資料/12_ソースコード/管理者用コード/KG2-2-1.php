<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-1.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>管理者サイト</span>
            </div>
        </div>
    </header>

    <!-- ここから -->
    <a href="#" class="">＜ 戻る</a>
    <h1>商品更新</h1>
    <hr><br>
    <div class="kg2-2-1">
        <h3>商品ID</h3>
    </div>
    <div class="kg2-2-1-2">
    <div class="kg2-2-1-3">
        <input type="text" name="">
        <button>検索</button>
    </div>
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>商品名</h3>
    </div>
    <div class="kg2-2-1-1">
        <input type="text" name="">
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>商品詳細</h3>
    </div>
    <div class="kg2-2-1-1">
        <textarea id="" name="" rows="7" cols="35"></textarea>
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>種別</h3>
    </div>
    <div class="kg2-2-1-1">
        <select id="category" name="">
            <option value="">選択してください</option>
            <!-- オプションを追加 -->
        </select>
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>価格</h3>
    </div>
    <div class="kg2-2-1-1">
        <input type="number" name="">
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>カカオ含有量</h3>
    </div>
    <div class="kg2-2-1-1">
        <input type="text" name="">
    </div>
    <br>

    <div class="kg2-2-1">
        <h3>商品画像</h3>
    </div>
    <div class="kg2-2-1-1">
        <input type="file" name=""><br><br>
    </div>
    <br>

    <div class="button-container">
        <div class="kg2-2-1-1">
            <button>確認画面へ</button>
        </div>
    </div>
    <br>
    </form>
    <!-- ここまで -->
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