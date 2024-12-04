<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細検索</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G6.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
        function toggleForm(formId) {
            const forms = document.querySelectorAll('.search-form');
            forms.forEach(form => form.classList.add('hidden'));
            document.getElementById(formId).classList.remove('hidden');
        }
    </script>
</head>
<body>
    <?php require_once 'header.php'; ?>

    <div class="toptext">詳細検索</div>

    <div>
        <button type="button" class="search-toggle-btn" onclick="toggleForm('keyword-form')">キーワード検索</button>
        <button type="button" class="search-toggle-btn" onclick="toggleForm('category-form')">カテゴリ検索</button>
    </div>


    <!-- キーワード検索フォーム -->
    <form id="keyword-form" class="search-form" action="G6-1.php" method="post">
        <div class="G6text">
            <label for="keyword">キーワード：</label>
            <input type="text" id="keyword" name="keyword" class="category" placeholder="例：チョコレート" required>
        </div>
        <button type="submit" class="button">検索</button>
    </form>


    <!-- カテゴリ検索フォーム -->
    <form id="category-form" class="search-form hidden" action="G6-1.php" method="post">
        <div class="G6text">
            <label>カテゴリ：</label>
            <select class="category1" name="category">
                <option value="1">チョコレート</option>
                <option value="2">ケーキ</option>
                <option value="3">クッキー</option>
                <option value="4">その他</option>
                <option value="all">すべて</option>
            </select>
            <br>
            <label>並び順：</label>
            <select class="category2" name="sort_order">
                <option value="price">価格</option>
                <option value="cacao_content">カカオ含有量</option>
            </select>
        </div>
        <button type="submit" class="button">検索</button>
    </form>

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
