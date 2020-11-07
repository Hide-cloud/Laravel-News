<?php
 $title="";
 $article="";
 $postFile='keiziban.text';
 $postArray=[];
 $arrayData=[];
 $errorMesseage="";


 
 //エスケープ処理
 function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
  }

  //テキストファイルに書き込まれたデータ($arrayData)を読み込む
  $arrayData= json_decode(file_get_contents($postFile));
  
  

 if($_SERVER['REQUEST_METHOD']==='POST'){
     if(!empty($_POST['title']&& !empty($_POST['article']))){
        
      //タイトルと記事のデータを取得
        $title=$_POST['title'];
        $article=$_POST['article'];
      //取得したデータを$postArray配列に代入
        $postArray=[$title,$article];
      //postArray配列をさらに$arrayData配列に代入
        $arrayData[]=$postArray;
      //入力されたデータ($arrayData)をテキストファイルに書き込む
        file_put_contents($postFile,json_encode($arrayData));
     }else{
       //タイトルか記事が入力されていない場合はエラーメッセージ(タイトルと記事への入力は必須です)を作る
        $errorMesseage="※タイトルと記事への入力は必須です";
     }
  }
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="keiziban.css">
    <title>Laravel News</title>
  </head>
  <body>
      <p class="topTitle">Laravel News</p>
      <p class="sentence">さあ、最新のニュースをシェアしましょう</p>
     <!--エラーメッセージを表示する-->
      <p　style="color:red"><?php echo h($errorMesseage) ?></p>
    <form name="task_form" method="POST">
      <div class="titleInput">
        <label for="titleName">タイトル：</label>
        <input type="text" name="title" value="" size="30">
      </div>
      <div class="articleInput">
        <label for="articleName">記事：</label>
        <textarea name="article" cols="60" rows="10" value=""></textarea>
      </div>
      <div class="postButton">
        <input type="submit" name="send" value="投稿" id="submitButton" onclick="pushButton()">
    </form> 

    <?php 
    //配列arrayDataの中にデータが入っていると
        if(!empty($arrayData)){  ?>
       <?php 
       //最新の更新を上から順に表示するために配列の順序を後ろから並び替える
       $arrayData=array_reverse($arrayData); ?>
          <?php 
          //二次元配列arrayDataから配列Data取り出しそれぞれのタイトル、記事を表示する
          foreach ( $arrayData as $data ) {  ?>
            <div class="postArea">
              <p style="border-top:solid 1px black"><p>
              <p　style="font-weight:bolder"><?php echo h($data[0]); ?></p>
              <p><?php echo h($data[1]); ?></p>
              <a href="">記事全文・コメントを見る</a>
            </div>
          <?php } ?>
          
          <?php } ?>
          

    <script type="text/javascript" src="keiziban.js"></script>
  </body>
</html>