# LearnTrack(開発中)
## アプリケーション概要
- このアプリは、ユーザーがログインしてマイページでプロフィール写真をアップロード。
- 学習目標を作成・管理したりできるツールです。目標には名前、詳細、勉強時間、優先順位、達成期限を設定でき、進捗はグラフや統計で可視化されます。
- 集中タイマーやポモドーロタイマーで学習時間を記録し、SNS機能を通じて投稿や「いいね」で学習のモチベーションを高める学習アプリです。
## 使用技術スタック
- フレームワーク: Laravel
- データベース: Mysql
- フロントエンド関連: JQuery
- 開発: Docker, Git
## 創意工夫（開発中）
【現時点の工夫】
- このアプリでは、文字だけでなくアイコンも活用することで、直感的に機能を理解できるよう工夫しています。
- 全体の配色には緑などの落ち着いた色を採用し、ユーザーが学習に集中しやすいデザインを意識しています。
- 学習計画一覧ページでは付箋風のデザインを採用し、優先度に応じて色を変えることで、ひと目で優先度がわかるよう工夫しています。
- タイマー機能について、タイマー２種類あります。集中タイマー(カウントアップ)とポモドーロタイマー(カウントダウン)があります。
  【タイマーの共通機能】
  - ページ内で作成した学習計画を選択でき、タイマーを使って記録した学習時間がその計画に自動で保存されます。学習計画を選ばずにタイマーを使うことも可能です。
  - プログレスバーやトマト獲得機能も搭載されています。トマトは6分間の学習で1個獲得でき、質問機能で使用することができます。これにより、学習を続けるモチベーションにつなげることを目指しています。
  - また、ポモドーロタイマーの切り替えやトマト獲得時には効果音が再生され、学習体験にメリハリを持たせています。効果音のオン／オフは設定画面から切り替え可能です。
  【集中タイマーの機能】
  - 集中タイマーのプログレスバーは、90分で一周するように設計しています。これは「90分が大人でも集中できる限界時間」とされていることを踏まえ、90分を基準に設計しています。
  【ポモドーロタイマーの機能】
  - ポモドーロタイマーでは、設定ページから学習時間と休憩時間を自由に設定できるようにしています。それぞれの時間をもとにプログレスバーが動作し、学習と休憩の切り替えが一目で分かるよう、バーの色も分けています。自分のペースに合わせた学習ができるよう工夫しています。
- 学習データページでは、タイマーページで記録した学習時間や選択された学習計画をもとに、自動でグラフを生成するように設計しています。学習の成果を視覚的に確認できることで、継続への意欲につなげています。また、カレンダー機能も搭載しており、日付を選択することで、その日の学習状況をグラフで簡単に確認できるようにしています。
- 質問ひろばページ（開発中）では、学習中に生じた疑問や悩みを自由に投稿できるようにする予定です。また、質問に対する報酬として「トマト」を使える仕組みを取り入れ、回答者のモチベーションを高める工夫も検討しています。
【今後の予定】
- 質問に回答できる機能を追加する予定です。ユーザー同士で疑問を解決し合える場を作ります。
- 回答がつかなかった質問は、一定期間後に自動で再投稿されるようにする予定です。埋もれずに多くの人に見てもらえるようにするための工夫です。
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
        <img width="1440" alt="スクリーンショット 2024-12-25 20 23 09" src="https://github.com/user-attachments/assets/5e4f30ae-1b8b-4a4d-92af-f7a24dbc566b" />
    </td>
    <td>
        <img width="1440" alt="スクリーンショット 2024-12-25 20 23 55" src="https://github.com/user-attachments/assets/35576a8e-4cbb-41ad-ae34-87e52f42fdb6" />
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
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/e9c7f9cc-98c3-46d0-bcd1-2a1436d839fa" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/f4083c80-4b98-4a1f-b33e-dffe20a5e7e2" />
    </td>
  </tr>
  <tr>
    <td>
      学習データページ（グラフ）
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/b4e752fc-eb7d-4553-a01d-9f420156578b" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/61b118ec-7ee0-4331-8832-c75c1627c5f4" />
    </td>
  </tr>
  <tr>
    <td>
      学習データページ（カレンダー）
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/ad415268-4c05-4d04-a06e-0c0d5ddafa6b" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/f3dc78cb-af1c-4ab1-9250-895712f62631" />
    </td>
  </tr>
      <tr>
    <td>
      集中タイマーページ
    </td>
  </tr>
  <tr>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/a18efd51-2e90-45d6-a2af-b92408a91b4f" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/8018b07c-6cdc-4990-b54d-da3cb71757c7" />
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
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/69ee8dc3-680a-487a-82b3-d3273a9f6373" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/8281ffbc-4f85-4443-a481-e6d839be6d99" />
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
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/315afcc5-49bb-400c-abb1-9ac11a301169" />
    </td>
    <td>
      <img width="1440" alt="Image" src="https://github.com/user-attachments/assets/8dcfbec3-44fa-4e87-a869-daf8c14558f8" />
    </td>
  </tr>
</table>