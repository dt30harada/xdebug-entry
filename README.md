# Xdebug入門

2024年12月28日(土) 勉強会資料

## 目的

今年は自社サービス開発や受託案件でLaravelに触れる機会が増えた方も多いはず。  
そこで、Laravel (PHP) のデバッグツールであるXdebugの使い方を知ってもらい、  
よりバックエンドアプリケーションの開発/保守の効率を上げてもらうことを目的とする。

## 本日の流れ

1. サンプルアプリケーションの概要
1. Xdebugの概要
1. ハンズオン

## 0. サンプルアプリケーションの概要

- Laravelで実装されたバックエンドAPI
- 機能はユーザーの登録と取得のみ
- いくつかバグがあるため、Xdebugのステップ実行を使用してデバッグしたい

## 1. Xdebugの概要

### Xdebugとは

### ステップ実行の操作方法

- ブレークポイントを設定する
  - 行を指定する
  - 条件を設定する
  - 特定のイベントを指定する
- ステップイン: メソッド(関数)の中に入る
- ステップオーバー: メソッド(関数)の中に入らずに次の行に進む
- ステップアウト: メソッド(関数)から抜ける
- コンテキストの確認: 変数の中身を確認する

## 2. ハンズオン

### 環境構築

- Docker Desktopのインストール
- VSCodeのインストール
- VSCodeの拡張機能「PHP Debug」のインストール
- ソースコードの取得とDockerコンテナの起動

```bash
# ソースコードを取得して
git clone git@github.com:dt30harada/xdebug-entry.git
cd xdebug-entry
# Dockerコンテナを起動する
make init
```

### タスクリスト

#### [1] バリデーションの内部処理を追ってみる

```bash
make test-user-store-ok
```

#### [2] デバッグしてみる その1

```bash
make test-user-me
```

正常系ではなく404エラーレスポンスが返ってくる原因を調査する

#### [3] デバッグしてみる その2

```bash
make test-user-store-ng
```

バリデーションエラーの内容が期待したものと異なる原因を調査する

- バリデーションルールの仕様
  - `role` : `1 (staff)` or `2 (admin)` のみ許可
  - `nickname` : `role` が `1` の場合のみ必須
  - `tel` : `role` が `2` の場合のみ必須

```php
// 期待するエラー内容
[
    "role" => [
        "The role field is invalid."
    ],
]

// 実際のエラー内容
[
    'nickname' => [
        'The nickname field is required when role is true.'
    ],
    'tel' => [
        'The tel field is required when role is true.'
    ],
]
```
