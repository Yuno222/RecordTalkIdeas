# RecordTalkIdeas

### 〇概要
RecordTalkIdeasはこちらから閲覧できます。  
https://recordtalkideas.herokuapp.com  

※テストアカウント  
メールアドレス：aaa@gmail.com  
パスワード：aaaaa

使った技術などの詳細はこちらにも書いています。  
https://qiita.com/yuno222/private/a1d785f1fbe1bac7b014


### 〇環境
OS : mac  
エディタ : Visual Studio Code  
ローカル開発環境 : MUMPで構築  
データベース : ClearDB  
デプロイ : Heroku  


### 〇実装した機能
・ユーザ登録機能（名前、メールアドレス、年齢）    
・ログイン機能（自動ログイン選択可）  
・ページング機能(スマホ、PC)  
・いいね機能  
・レスポンシブデザイン  


### 〇使用した技術
・PHP  
・Ajax  
・javaScript（jQuery）  
・HTML、CSS  
・MySQL  


### 〇サイト構成
ログイン画面　  
→ ジャンル選択画面　  
→ 会話のタイトル一覧画面　  
→ 会話の内容作成、編集、消去画面  


## ユーザ登録画面  

氏名、メールアドレス、パスワード、性別、年齢を入力してユーザ登録を行います。  
<img width="1440" alt="スクリーンショット 2021-09-29 20 47 18" src="https://user-images.githubusercontent.com/91469826/135262973-87266408-66ae-4e00-a57a-bf4201683c17.png">

記入漏れがあると、エラーを返します。
<img width="1440" alt="スクリーンショット 2021-09-29 20 47 52" src="https://user-images.githubusercontent.com/91469826/135263907-5a789aa2-90d6-4f23-9778-a8b62d70c02d.png">


## ログイン画面

※自動ログインにチェックを入れると、
ブラウザを閉じた際にセッションが切れてもログイン状態が保持される。
<img width="1440" alt="スクリーンショット 2021-09-29 20 46 57" src="https://user-images.githubusercontent.com/91469826/135263130-b50d5967-3535-4308-9803-d4ea04145ef8.png">


## ジャンル選択画面
ピンクのボタンを押すタイトル一覧に進みます。  
ジャンル３とジャンル４は予備です。いつか追加するかも.....  
<img width="1440" alt="スクリーンショット 2021-09-29 20 49 15" src="https://user-images.githubusercontent.com/91469826/135263180-44ab0c02-de68-4580-afc4-4b89a8032369.png">

右上のボタンを押すとログアウトメッセージが出てきます。  
OKを押すと、ログアウトが実行されログイン画面に戻ります。
<img width="501" alt="スクリーンショット 2021-09-29 20 49 40" src="https://user-images.githubusercontent.com/91469826/135264090-b91a433f-c068-4535-8a0a-af5958fbf071.png">


## タイトル一覧画面
<img width="1435" alt="スクリーンショット 2021-09-29 20 55 20" src="https://user-images.githubusercontent.com/91469826/135263596-efd652d6-b29f-4021-9fd2-ee3a05438ed3.png">


作成したタイトルの一覧を確認できます。
作成順、評価順、更新順で並び替えることができます。
右のいいねボタンをクリックするといいねできます。
5件を超えるタイトルを作成するとページングできるようになります。
<img width="1440" alt="スクリーンショット 2021-09-29 20 43 56" src="https://user-images.githubusercontent.com/91469826/135263749-975dc8fb-9715-423d-b39d-61c7c06d8f01.png">

（こんな感じ）


作成順、評価順、更新順を選択し、並び替えボタンを押すことでソートできます。
<img width="156" alt="スクリーンショット 2021-09-29 20 50 34" src="https://user-images.githubusercontent.com/91469826/135264387-ca78bf70-b3d2-4b8a-9f2e-e48339ef06a4.png">



## タイトル作成画面
<img width="1437" alt="スクリーンショット 2021-09-29 20 40 54" src="https://user-images.githubusercontent.com/91469826/135263830-e7df3b32-3f02-4402-a7c0-4a7af6334cd7.png">




## 会話内容作成画面
<img width="1440" alt="スクリーンショット 2021-09-29 20 45 23" src="https://user-images.githubusercontent.com/91469826/135263859-f303d1d7-9d76-4349-9a7a-565685385e25.png">



下の入力欄に残したいテキストを入力します。
自分を選択すると、右から青い吹き出しで、
相手を選択すると、左からピンクの吹き出しで記録できます。
