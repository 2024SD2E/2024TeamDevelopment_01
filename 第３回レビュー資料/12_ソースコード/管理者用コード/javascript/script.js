// JavaScript用ファイル
new Vue({
    el: '#app',
    data: {
      name: '',            // ユーザーが入力した名前
      fortune: '',         // 運勢の結果
      errorMessage: '',    // エラーメッセージ
      isValidName: false   // 名前のバリデーションフラグ
    },
    methods: {
      validateName() {
        //名前は5文字以上で入力5文字以下ならエラーメッセージ表示
        if(this.name.length >= 5){
          this.isValidName = true;
          this.errorMessage = '';
        }else{
          this.isValidName = false;
          this.errorMessage = '名前は5文字以上で入力してください。';
        }
      },
      drawFortune() {
        // バリデーションが成功した場合のみランダムな運勢を生成
        if () {
          //0から100までのランダムな数値を取得する
          const randomNumber = Math.round(Math.random() * 100);
          //数値が80以上の場合（大吉）
  
          //数値が60以上の場合（吉）
  
          //数値が40以上の場合（中吉）
  
          //数値が20以上の場合（小吉）
  
          //それ以外の場合（凶）
  
        }
        }
      }
    }
  );