# 環境構築手順

本リポジトリで構築できる環境は以下の構成です。

|種類|名前|
|:--:|:--:|
|OS|linux|
|Webサーバー|Nginx|
|DBサーバー|MySQL|
|アプリケーション|PHP|

詳細はこちらの記事でまとめておりますので、下記手順を実施する前に必ず読んでください。<br>
- [絶対に失敗しないDockerでLaravel+Vueの実行環境（LEMP環境）を構築する方法〜前編〜](https://qiita.com/shimotaroo/items/29f7878b01ee4b99b951)
- [絶対に失敗しないDockerでLaravel6.8+Vueの実行環境（LEMP環境）を構築する方法〜後編〜](https://qiita.com/shimotaroo/items/679104b7e00dd9f89907)

# 1.DockerとDocker Composeを使えるようにする

こちらの記事からDocker fo Macをインストールしてください。<br>
[DockerをMacにインストールする（更新: 2019/7/13）](https://qiita.com/kurkuru/items/127fa99ef5b2f0288b81)

Dockerについてはこちらの記事を一度読んでおくと良いです↓<br>
[【図解】Dockerの全体像を理解する -前編-](https://qiita.com/etaroid/items/b1024c7d200a75b992fc)


## 2.プロジェクトのディレクトリを作成

```
$ cd
$ mkdir yanbaru-qiita
$ cd yanbaru-qiita
```

```
$pwd
```

を実行して`/Users/{ご自身のユーザー名}/yanbaru-qiita`になればOKです。

## 3.このリポジトリのソースコードをクローン

```
$ git clone https://github.com/shimotaroo/Yanbaru-Docker.git .
```

以下のコマンドを実行してこのリポジトリのディレクトリ・ファイルがクローンされていればOKです。

```
$ ls
README.md		docker			docker-compose.yml
```

## 4.docker-composeのDBコンテナに環境変数を設定する

yanbaru-qiita/docker-compose.ymlの

```yml
environment:
      MYSQL_DATABASE: {DBの名前が入ります}
      MYSQL_USER: {DBのユーザー名が入ります}
      MYSQL_PASSWORD: {DBのパスワードが入ります}
      MYSQL_ROOT_PASSWORD: root
      TZ: 'Asia/Tokyo'
```

の`{DBの名前が入ります}`、`{DBのユーザー名が入ります}`、`{DBのパスワードが入ります}`に任意のDB名、ユーザー名、パスワードにそれぞれ修正してください。

## 5.Dockerイメージのビルド、コンテナの起動

`yanbaru-qiita`ディレクトリで

- Dockerイメージのbuikd

```
$ docker-compose build
```

を実行し、以下の出力になればOKです。

```
（略）
Successfully built a6948df85a74
Successfully tagged yanbaru-qiita_app:latest
```

- Dockerコンテナを起動

```
$ docker-compose up -d
```

を実行し、以下の出力になればDockerでのLEMP環境の構築は完了です。

```
（略）
Creating yanbaru-qiita_db_1  ... done
Creating yanbaru-qiita_app_1 ... done
Creating yanbaru-qiita_web_1 ... done
```

## 6.DBの接続を確認

MySQlのクライアントツールである`Sequel Pro`をインストールします。<br>

参考：[Mac MySQL Sequel Proの導入方法](https://qiita.com/miriwo/items/f24e6906105386ddfa83)

Sequel Proを起動します。<br>
左下の「+」ボタンを押して以下の通り入力欄を埋めます。

|入力欄|埋める文字|
|:--:|:--:|
|名前|yanbaru-qiita|
|ホスト|127.0.0.1|
|ユーザー名|docker-compose.ymlのMYSQL_USER|
|パスワード|docker-compose.ymlのMYSQL_PASSWORD|
|データベース|空欄でOK|
|ポート|空欄でOK|

接続の前に「お気に入りに追加」を押しておくと次回からすぐに接続できます。<br>
お気に入り登録した後、「接続」ボタンで接続。<br>
左上の「データベースを選択...」で`docker-compose.yml`の`MYSQL_DATABASE`に指定したデータベースを選択すれば完了です。

ここまででMySQlに接続できない場合は各自調べてみてエラー解決に挑戦してみましょう。
