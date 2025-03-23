<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./core/bgmPlay.js" defer></script>
    <title>ログイン</title>
</head>

<body>
    <div class="start-container">
        <main>
            <div class="form-container">
                <div class="game-logo">
                    <img src="./src/logo.png" alt="ロゴ">
                </div>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <p>
                            <?php echo $_SESSION['error'] ?>
                        </p>
                    </div>
                    <?php session_destroy(); ?>
                <?php endif; ?>
                <form action="./checkLogin.php" method="post">
                    <label for="email">
                        <h3>メールアドレス</h3>
                    </label>
                    <input type="email" id="email" name="email"><br>

                    <label for="passwd">
                        <h3>パスワード</h3>
                    </label>
                    <input type="password" id="password" name="password"><br>
                    <a href="registration/registration.php" class="underline">
                        <span>はじめての方</span><svg viewBox="0 0 13 20">
                            <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
                        </svg>
                    </a>
                    <button type="submit" href="#" class="link">
                        <span class="mask">
                            <div class="link-container">
                                <span class="link-title1 title">ゲームへ</span>
                                <span class="link-title2 title">ゲームへ</span>
                            </div>
                        </span>
                        <div class="link-icon">
                            <svg class="icon" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                <path d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529 6.236h-21.884v1h21.883z" />
                            </svg>
                            <svg class="icon" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                                <path d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529 6.236h-21.884v1h21.883z" />
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        </main>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="bgm/pixel-dreams-259187.mp3" type="audio/mpeg">
    </audio>
</body>

</html>