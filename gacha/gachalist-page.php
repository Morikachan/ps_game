<?php
    session_start();
    empty($_SESSION['user']) ? header("Location: ./index.php") : ;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <script src="../core/bgmPlay.js" defer></script>
    <script src="../core/pageBack.js" defer></script>
    <script src="https://kit.fontawesome.com/f8fcf0ba93.js" crossorigin="anonymous"></script>
    <title>ホームページ</title>
</head>
<body>
    <div class="game-container">
        <header>
            <div>
                <p><?php echo $_SESSION['user']['username'] ?></p>
                <button type="button" id="changeUsername">
                    <!-- icon -->
                </button>
            </div>
            <div>
                <div>
                    <!-- lvl bar -->
                     <p>
                        <br>
                     </p>
                     <p></p>
                </div>
                <div>
                    <!-- coins -->
                    <img src="" alt="">
                    <p></p>
                </div>
                <div>
                    <!-- gems -->
                    <img src="" alt="">
                    <p></p>
                </div>
                <div>
                    <!-- settings -->
                </div>
            </div>
        </header>
        <main>
            <div class="main-container">
                <div class="top-buttons">
                    <div id="homeChar-change">
                        <!-- change home char -->
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <div id="sound">
                        <!-- stop music -->
                        <i class="fa-solid fa-volume-high"></i>
                        <!-- <i class="fa-solid fa-volume-xmark"></i> on click -->
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div>
                <button type="button" id="pageBackButton" class="gray-button left">
                    <i class="fa-solid fa-left-long"></i>
                </button>
            </div>
            <div>
                <button type="button" id="soundButton" class="gray-button right">
                    <i class="fa-solid fa-volume-high"></i>
                </button>
            </div>
        </footer>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="bgm/tokyo-glow-285247.mp3" type="audio/mpeg">
    </audio>
</body>
</html>