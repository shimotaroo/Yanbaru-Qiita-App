# Docker環境構築手順
## 環境概要
本共同開発で構築する環境は以下の構成です。（LEMP環境と呼ばれます）

|種類|名前|
|:--:|:--:|
|OS|linux|
|Webサーバー|Nginx|
|DBサーバー|MySQL|
|アプリケーション|PHP|

詳細はこちらの記事でまとめておりますので、下記手順を実施する前に必ず読んでください。（Vue.jsの部分は不要です）<br>
- [絶対に失敗しないDockerでLaravel+Vueの実行環境（LEMP環境）を構築する方法〜前編〜](https://qiita.com/shimotaroo/items/29f7878b01ee4b99b951)
- [絶対に失敗しないDockerでLaravel6.8+Vueの実行環境（LEMP環境）を構築する方法〜後編〜](https://qiita.com/shimotaroo/items/679104b7e00dd9f89907)

## DockerとDocker Composeを使えるようにする

こちらの記事からDocker fo Macをインストールしてください。<br>
[DockerをMacにインストールする（更新: 2019/7/13）](https://qiita.com/kurkuru/items/127fa99ef5b2f0288b81)

Dockerについてはこちらの記事を必ず一度読んでおいてください。<br>
[【図解】Dockerの全体像を理解する -前編-](https://qiita.com/etaroid/items/b1024c7d200a75b992fc)

## プロジェクトのディレクトリを作成

```
$ cd
$pwd
/Users/{ご自身のユーザー名}
```
## リポジトリをクローン

以下コマンドでローカルにクローンします。
```
$ git clone https://github.com/shimotaroo/Yanbaru-Qiita-App.git
```

`Yanbaru-Qiita-App`ディレクトリが作成されるのでその中に移動して正常にクローンされているか確認します。


```
$ cd Yanbaru-Qiita-App
$ ls
README.md		development-document	docker			docker-compose.yml	src
```

## .envファイル作成

まず、`docker-compose.yml`をdbコンテナの部分を確認ください。

```yml
  db:
    image: mysql:5.7
    ports:
      - '6306:3306'
    environment:
      MYSQL_DATABASE: ${DATABASE_NAME}
      MYSQL_USER: ${USER_NAME}
      MYSQL_PASSWORD: ${PASSWORD}
      MYSQL_ROOT_PASSWORD: ${ROOT_PASSWORD}
      TZ: 'Asia/Tokyo'
    volumes:
      - docker-volume:/var/lib/mysql
```

`${DATABASE_NAME}`など（4つあります）は別ファイルに定義した環境変数を使用しています。<br>
`docker-compose.yml`はGit管理している(＝GitHubに上げている)のでDBのパスワードが誰にでも見れる状態であり、セキュリティ上よくないです。<br>
今回の共同開発で作成するアプリは実際にリリースすることはないですが、この「重要な情報をGitHubに上げてはならない」ということを覚えておいていただきたく、このような仕様にしています。<br>

ということで`.env`を作成します。<br>
`$ touch .env`でもいいですし、エディター上でファイルを作成してもらっても構いません。

```
DATABASE_NAME=任意
USER_NAME=任意
PASSWORD=任意
ROOT_PASSWORD=任意
```

それぞれの"任意"のところにご自身で値を設定してください。<br>
ここで設定した値が`docker-compose.yml`の`${****}`で読み込まれます。<br>

なお、`.gitigonre`ファイルで`.env`をGit管理下から外しています。<br>
（間違えて`.env`をGitHubにpushしないようにしてください）

## Dockerイメージのビルド & Dockerコンテナの起動

`Yanbaru-Qiita-App`ディレクトリで以下のコマンドを実行してDockerイメージをビルドします。

```
$ docker-compose build

（略）
Successfully built a6948df85a74
Successfully tagged yanbaru-qiita_app:latest
```

作成したDockerイメージを基にDockerコンテナを起動します。

```
$ docker-compose up -d

（略）
Creating yanbaru-qiita_db_1  ... done
Creating yanbaru-qiita_app_1 ... done
Creating yanbaru-qiita_web_1 ... done
```

これでDockerを使ったLEMP環境の構築は完了です。

#　DBの接続を確認

MySQlのクライアントツールである`Sequel Pro`をインストールします。<br>

参考：[Mac MySQL Sequel Proの導入方法](https://qiita.com/miriwo/items/f24e6906105386ddfa83)

Sequel Proを起動します。<br>
左下の「+」ボタンを押して以下の通り入力欄を埋めます。

|入力欄|埋める文字|
|:--:|:--:|
|名前|yanbaru-qiita|
|ホスト|127.0.0.1|
|ユーザー名|.envのUSER_NAME|
|パスワード|.envのPASSWORD|
|データベース|空欄でOK|
|ポート|3306|

接続の前に「お気に入りに追加」を押しておくと次回からすぐに接続できます。<br>
お気に入り登録した後、「接続」ボタンで接続。<br>
左上の「データベースを選択...」で`.env`の`DATABASE_NAME`に指定したデータベースを選択すれば完了です。

ここまででMySQlに接続できない場合は各自調べてみてエラー解決に挑戦してみましょう。

# Laravelアプリ環境構築手順
## はじめに
まずは以下の状態になっているか確認ください。

- Dockerコンテナが3つ（web、db、app）が起動している
- Sequel ProでMySQLに接続できている

Dockerコンテナの起動状態は以下コマンドから確認できます。

```
$ docker-compose ps
```

現在いるディレクトリが正しいか`ls`コマンドで実行してください。（以下の出力結果になれば問題なしです）
```
$ ls
README.md		development-document	docker			docker-compose.yml	src
```

## Laravelプロジェクト用の.envを作成

以下コマンドで`src`ディレクトリに移動します。<br>

※Linuxコマンドを実行するのでも、エディター上ディレクトリ上で移動するのでもどちらでも良いです。

```
$ cd src
```

既存の`.env.example`を複製して`.env`という名称に変更してください。（`.env.example`と`.env`が両方できる状態になります）

## .env編集

`.env`を以下の通り変更してください。

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:80

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=.env(Docker環境用)のDATABASE_NAME
DB_USERNAME=.env(Docker環境用)のUSER_NAME
DB_PASSWORD=.env(Docker環境用)のPASSWORD
```

一度、`Yanbaru-Qiita-App`ディレクトリに戻り、以下のコマンドを実行してappコンテナの中に入ります。

```
$ cd ..
$ docker-compose exe app bash
```

Composerで必要なパッケージをインストールします。<br>
（こんな感じの出力結果になればOKです）

```
$ composer install

（略）
Package manifest generated successfully.
61 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
```

以下のコマンドを実行

```
$ php artisan key:generate
```

`.env`の`APP_KEY`に乱数が入ります。

##　Laravelのウェルカムページの表示

`loaclhost:80`をブラウザに入力してLaravelのウェルカムページが表示されれば完了です！！<br>

これでDocker×Laravelの環境構築は完了です。

# 共同開発資料

`development-document`ディレクトリに以下のファイルがありますのでそちらを確認いただきチームメンバーと協力して進めてください。

- yanbaru-qiita.drawio：ER図
- ：画面定義書
- ：共同開発タスク手順書