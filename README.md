# LearnTrack
## 公開URL
- http://3.115.7.245:8001/register
## アプリケーション概要
- このアプリは、ユーザーがログインしてマイページでプロフィール写真をアップロード。
- 学習目標を作成・管理したりできるツールです。目標には名前、詳細、勉強時間、優先順位、達成期限を設定でき、進捗はグラフや統計で可視化されます。
- 集中タイマーやポモドーロタイマーで学習時間を記録し、SNS機能を通じて投稿や「いいね」で学習のモチベーションを高める学習アプリです。
## 使用技術スタック
- フレームワーク: Laravel
- データベース: Mysql
- フロントエンド関連: JQuery, JavaScript
- 開発: Docker, Git
- インフラ: AWS（EC2, S3）
## 創意工夫
- 全体の配色には緑などの落ち着いた色を採用し、ユーザーが学習に集中しやすいデザインを意識しています。
- 学習計画一覧ページでは付箋風のデザインを採用し、優先度に応じて色を変えることで、ひと目で優先度がわかるよう工夫しています。
- タイマー機能について、タイマー２種類あります。集中タイマー(カウントアップ)とポモドーロタイマー(カウントダウン)があります。
  ### 【タイマーの共通機能】
  - ページ内で作成した学習計画を選択でき、タイマーを使って記録した学習時間がその計画に自動で保存されます。学習計画を選ばずにタイマーを使うことも可能です。
  - プログレスバーやトマト獲得機能も搭載されています。トマトは6分間の学習で1個獲得でき、質問機能で使用することができます。これにより、学習を続けるモチベーションにつなげることを目指しています。
  - また、ポモドーロタイマーの切り替えやトマト獲得時には効果音が再生され、学習体験にメリハリを持たせています。効果音のオン／オフは設定画面から切り替え可能です。
  ### 【集中タイマーの機能】
  - 集中タイマーのプログレスバーは、90分で一周するように設計しています。これは「90分が大人でも集中できる限界時間」とされていることを踏まえ、90分を基準に設計しています。
  ### 【ポモドーロタイマーの機能】
  - ポモドーロタイマーでは、設定ページから学習時間と休憩時間を自由に設定できるようにしています。それぞれの時間をもとにプログレスバーが動作し、学習と休憩の切り替えが一目で分かるよう、バーの色も分けています。自分のペースに合わせた学習ができるよう工夫しています。
- 学習データページでは、タイマーページで記録した学習時間や選択された学習計画をもとに、自動でグラフを生成するように設計しています。学習の成果を視覚的に確認できることで、継続への意欲につなげています。また、カレンダー機能も搭載しており、日付を選択することで、その日の学習状況をグラフで簡単に確認できるようにしています。
- 質問ひろばページでは、質問の投稿や回答時に確認ページを表示する機能、および再投稿機能を追加しています。回答が1件もない場合は、投稿から7日が経過すると、再投稿を選択しない限り質問は自動的に削除されます。また、「トマト」を報酬として設定できる仕組みにより、より多くの回答を促す仕組みとなっており、ベストアンサーに選ばれた回答者にはトマト報酬が付与されます。
## 実際のアプリケーション画像
<table>
  <tr>
    <td>
      新規登録ページ
    </td>
    <td>
      ログインページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="新規登録" src="https://github.com/user-attachments/assets/78529ff5-63c7-4535-bc9f-80652dc29e32" />
    </td>
    <td>
      <img width="1440" alt="ログイン" src="https://github.com/user-attachments/assets/9034745a-a9c7-4693-844e-eb6f714325c7" />
    </td>
  </tr>
  <tr>
    <td>
      マイページ
    </td>
    <td>
      マイページ編集ページ
  </tr>
  <tr>
    <td>
      <img width="1440" alt="マイページ" src="https://github.com/user-attachments/assets/52c23a07-7270-423e-bda8-7b40a5370beb" />
    </td>
    <td>
      <img width="1440" alt="マイページ編集" src="https://github.com/user-attachments/assets/263c029b-2d5f-4872-99f4-bd642fd26b43" />
    </td>
  </tr>
  <tr>
    <td>
      学習計画一覧ページ
    </td>
    <td>
      学習計画作成ページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="学習計画一覧ページ" src="https://github.com/user-attachments/assets/efa82b07-2a0e-478f-8197-487447d59548" />
    </td>
    <td>
      <img width="1440" alt="学習計画作成ページ" src="https://github.com/user-attachments/assets/e281cb96-610d-4784-87a3-9e9e83c5b44a" />
    </td>
  </tr>
  <tr>
    <td>
      学習データページ（グラフ）
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="学習データページ（円グラフ）" src="https://github.com/user-attachments/assets/35502236-f567-4b21-bcb8-b6c5de9eea4e" />
    </td>
    <td>
      <img width="1440" alt="学習データページ（棒グラフ）" src="https://github.com/user-attachments/assets/2a704fbd-e4d9-4b4c-9e29-cb63b8c93f0e" />
    </td>
  </tr>
  <tr>
    <td>
      学習データページ（カレンダー）
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="学習データページ（カレンダー）" src="https://github.com/user-attachments/assets/09a38187-7851-45c4-97c2-8668dbd646ff" />
    </td>
    <td>
      <img width="1440" alt="学習データページ（カレンダー移動付き）" src="https://github.com/user-attachments/assets/e3a21b4a-1a20-4d22-847a-38bbd705c416" />
    </td>
  </tr>
      <tr>
    <td>
      集中タイマーページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="集中タイマーページ" src="https://github.com/user-attachments/assets/bf288f80-a59b-498e-98c5-d021b5b6cc27" />
    </td>
    <td>
      <img width="1440" alt="集中タイマーページ(開始中)" src="https://github.com/user-attachments/assets/27318bd3-0439-4d9e-8acf-220504116208" />
    </td>
  </tr>
  <tr>
    <td>
      ポモドーロタイマーページ
    </td>
    <td>
      ポモドーロタイマーページ（設定）
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="ポモドーロタイマーページ" src="https://github.com/user-attachments/assets/8294092b-c17b-4a99-970b-a026eab0b5b7" />
    </td>
    <td>
      <img width="1440" alt="ポモドーロタイマーページ（設定）" src="https://github.com/user-attachments/assets/ae27256a-29f5-471d-a265-bded206afad4" />
    </td>
  </tr>
    <tr>
    <td>
      質問一覧ページ
    </td>
    <td>
      質問詳細ページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="質問一覧ページ" src="https://github.com/user-attachments/assets/f92b25c3-8816-4ccd-9ebb-f424ae76eca0" />
    </td>
    <td>
      <img width="1440" alt="質問詳細ページ" src="https://github.com/user-attachments/assets/85e89526-c628-4b3c-9d6f-4771a0871a49" />
    </td>
  </tr>
  <tr>
    <td>
      質問投稿ページ
    </td>
    <td>
      質問確認ページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="質問投稿ページ" src="https://github.com/user-attachments/assets/68418205-4ba5-4c35-8e9e-f237d2469047" />
    </td>
    <td>
      <img width="1440" alt="質問確認ページ" src="https://github.com/user-attachments/assets/94b11b0e-f9df-48d1-9a45-dae0d0412f79" />
    </td>
  </tr>
    <tr>
    <td>
      カテゴリー選択ページ
    </td>
    <td>
      カテゴリー一覧ページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/a800fc93-702a-4e3e-889f-7f453ec05023" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/f20a6199-25b5-4438-8868-2fbdd7cf44bb" />
    </td>
  </tr>
</table>