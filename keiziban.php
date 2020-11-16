<?php
 $title="";
 $article="";
 $postFile='keiziban.txt';
 $postArray=[];
 $arrayData=[];
 $errorMesseage="";
 $id=uniqid();


 
 //エスケープ処理
 function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
  }

  //テキストファイルに書き込まれたデータ($arrayData)を読み込む
  $arrayData= json_decode(file_get_contents($postFile));
  
  

 if($_SERVER['REQUEST_METHOD']==='POST'){
   //タイトル、記事が入力されていたら
     if(!empty($_POST['title']&& !empty($_POST['article']))){
        
      //タイトルと記事のデータを取得
        $title=$_POST['title'];
        $article=$_POST['article'];

      //取得したタイトル、記事のデータ＋idを$postArray配列に代入
        $postArray=[$id,$title,$article];
      //postArray配列をさらに$arrayData配列に代入
        $arrayData[]=$postArray;
      //入力されたデータ($arrayData)をテキストファイルに書き込む
        file_put_contents($postFile,json_encode($arrayData));
      
        //タイトルと記事が入力されていない場合はエラーメッセージ(タイトルと記事への入力は必須です)を作る
      }else if(empty($_POST['title']&& empty($_POST['article']))){
        $errorMesseage="※タイトルと記事への入力は必須です";
      
      } 
       //タイトルが入力されていない場合のエラーメッセージ
      else if(empty($_POST['title'])){
        $errorMesseage="※タイトルの入力は必須です";
      
      }
      //記事が入力されていない場合のエラーメッセージ
      else if(empty($_POST['article'])){
        $errorMesseage="※記事の入力は必須です";
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
     <div class="wrapper">
       <p class="topTitle">Laravel News</p>
       <p class="sentence">さあ、最新のニュースをシェアしましょう</p>
      <!--エラーメッセージを表示する-->
       <p　class="errorMesseage"><?php echo h($errorMesseage) ?></p>
     <form class="topForm" name="task_form" method="POST" action=""  onsubmit="pushButton()"> 
       <div class="titleInput">
         <label for="titleName">タイトル：</label>
         <input type="text" class="textbox" name="title" value="" size="30">
       </div>
       <div class="articleInput">
         <label for="articleName">記事：</label>
         <textarea name="article" cols="65" rows="10" value=""></textarea>
       </div>
       <div class="postButton">
         <input type="submit" name="send" value="投稿" id="submitButton">
       </div>
     </form>
     </div>


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
              <div class="title">
                <p><?php echo h($data[1]); ?></p>
              </div>
              <div class="article">
                <p><?php echo h($data[2]); ?></p>
              </div>
              <div class="link">
              <!--リンク先のURLを繰り返し処理の中で変える方法を検討中-->
                <a href="commentPage.php?id=<?php echo $data[0]; ?>">記事全文・コメントを見る</a>
              </div>
            </div>
          <?php }
          }
          ?>
          

    <script type="text/javascript" src="keiziban.js"></script>
  </body>
</html>