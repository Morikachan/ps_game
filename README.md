# ps_game
ガチャシステムのブラウザゲームです。<br>
🖼 画像はAI無料ソースを使用しています。<br>
🎶 ページに流れる音楽のソースは[こちら](https://pixabay.com/music/search/genre/video%20games/)です。

## インストール
まずはリポジトリから始めます。<br>
プロジェクトを[Zipファイル](https://github.com/Morikachan/ps_game/archive/refs/heads/main.zip)でダウンロードします。

または、こちらのリポジトリをローカル システムにクローンします：

```
$ git clone https://github.com/Morikachan/ps_game.git
```
### データベース設定
このプロジェクトはXAMPPのPHP開発環境を使用していますので、先に[XAMPP](https://www.apachefriends.org/jp/download.html)をダウンロードします。
ダウンロードができてから、「Apache」と「MySQL」を立ち上げて、MySQLのAdminで「ps_database」というデータベースを作成し、リポジトリからの[データベースファイル](https://github.com/Morikachan/ps_game/tree/main/database)をインポートしてから、データベースの設定が終了になります。

## 使い方
ダウンロードと設定が出来ましたら、登録ページにユーザーを登録し、ログインしてから、作成が完成したら、ホームページに行きます。ここからゲームシステムが始まっています。

## システム紹介
ここからはシステム画像を紹介します。

### ホームページ
![screenshot](screenshot/homepage.png)

#### ホームページのカード選択
![screenshot](screenshot/homepage-homecard.png)

### ショップ
#### 有償ジェムの購入
![screenshot](screenshot/shop-paid-gems.png)

#### パックの購入
![screenshot](screenshot/shop-packs.png)

### ガチャ
#### ガチャ選択
![screenshot](screenshot/event_gacha.png)

#### 有償ジェムのガチャ
![screenshot](screenshot/paid-gacha.png)

#### ガチャを回した結果（10回分）
![screenshot](screenshot/gacha-result.png)

#### ガチャに合わせてカードリスト
![screenshot](screenshot/gacha-cardlist.png)

#### ユーザーが回したガチャ履歴
![screenshot](screenshot/gacha-history.png)

### カード一覧
![screenshot](screenshot/cardlist.png)

#### カード一覧のカード情報
![screenshot](screenshot/cardlist-details.png)

### ランキング
![screenshot](screenshot/ranking.png)
