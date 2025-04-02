<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./registration-style.css">
    <script src="../core/bgmPlay.js" defer></script>
    <script src="./validation.js" defer></script>
    <script src="./modalWindowShow.js" defer></script>
    <title>新規</title>
</head>

<body>
    <div class="start-container">
        <main>
            <div class="form-container">
                <div class="game-logo">
                    <img src="../src/logo.png" alt="ロゴ">
                </div>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <p>
                            <?php echo $_SESSION['error'] ?>
                        </p>
                    </div>
                    <?php session_destroy(); ?>
                <?php endif; ?>
                <form>
                    <label for="username">
                        <h3>ユーザーネーム</h3>
                    </label>
                    <input type="text" id="username" name="username" require />
                    <span class="inputErrorMess" id="usernameError"></span>
                    <label for="email">
                        <h3>メールアドレス</h3>
                    </label>
                    <input type="email" id="email" name="email" require />
                    <span class="inputErrorMess" id="emailError"></span>
                    <label for="password">
                        <h3>パスワード</h3>
                    </label>
                    <input type="password" id="password" name="password" require />
                    <span class="inputErrorMess" id="passwordError"></span>
                    <label for="passwordCheck">
                        <h3>パスワード確認</h3>
                    </label>
                    <input type="password" id="passwordCheck" name="passwordCheck" require />
                    <span class="inputErrorMess" id="passwordCheckError"></span>
                    <button type="button" method="post" class="regist-button" id="createBtn" disabled>アカウント作成</button>
                </form>
                <div id="modalRegistration" class="modal" style="display: none">
                    <div class="modal-content">
                        <h4>登録完了</h4>
                        <p>登録できました</p>
                        <p>ログインしてください</p>
                        <a class="modalBtn" href="../index.php">ログインへ</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="../bgm/pixel-dreams-259187.mp3" type="audio/mpeg">
    </audio>
</body>

</html>