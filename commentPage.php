<?php
//コメントを追加
$comment="";
$commentId="";
$commentData=[];
$commentArray=[];
$commentErrorMesseage="";
$postComment='comment.txt';
 $postFile='keiziban.txt';
 $postArray=[];
 $arrayData=[];
 $uniqueId=uniqid();
 //$commentId=$_GET[$id];
 $postID=$_GET[$id];
 //ページのURLを取得(id=~~~~~の部分)
 $url= htmlspecialchars($_SERVER['QUERY_STRING']);

 
 //エスケープ処理
 function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
  }

  //テキストファイルに書き込まれたデータ($arrayData)を読み込む
  $arrayData= json_decode(file_get_contents($postFile));
  //テキストファイルに書き込まれたデータ($commentArray)を読み込む
  $commentArray= json_decode(file_get_contents($postComment));

  //コメントが送信されたら
  if($_SERVER['REQUEST_METHOD']==='POST'){
    
    //取り出したURL(id=~~~~)の３文字目から１３文字取り出したものを$commentIdとする
    $commentId=substr($url,3,13);
    //$commentId=$postID;

    //コメントが入力されていたら
      if(!empty($_POST['comment'])){

       //コメントのデータを取得
         $comment=$_POST['comment'];

       //コメントIDとコメントのデータを$commentData配列に代入 
         $commentData=[$uniqueId,$commentId,$comment];
 
       //コメントのデータを$commentArray配列に代入
         $commentArray[]=$commentData;

       //入力されたデータ($commentArray)をテキストファイル($postComment)に書き込む
         file_put_contents($postComment,json_encode($commentArray));
      
       //header()で指定したページにリダイレクト
        //今回は今と同じ場所にリダイレクト（つまりWebページを更新）
          header('Location: ' . $_SERVER['REQUEST_URI']);
        //プログラム終了
          exit;

      //コメントを消すボタンが押された時
      }else if(isset($_POST['del'])){
        //変数定義
        $postDeleteComment=$_POST['del'];
        //テキストファイルを上書きするために新しい配列を作成
        $New_Comment_Array=[];
        //元の配列$commentArrayを新しい配列$New_Comment_Arrayに入れ直す
        foreach($commentArray as $value){
          //コメント消すボタンを押して送られてきた値$postDeleteCommentが配列内の$postIDと同じじゃないものを新しい配列$New_Comment_Arrayに代入する
            if($value[0] !== $postDeleteComment){
             $New_Comment_Array[]=$value;
            }
        }

        //入力されたデータ(新しい配列$New_Comment_Array)をテキストファイル($postComment)に書き込み直す
        file_put_contents($postComment,json_encode($New_Comment_Array));
        
        //header()で指定したページにリダイレクト
        //今回は今と同じ場所にリダイレクト（つまりWebページを更新）
           header('Location: ' . $_SERVER['REQUEST_URI']);
        //プログラム終了
           exit;

      }else if(empty($_POST['comment'])){
         $commentErrorMesseage="・コメントは必須です。";
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
  　 <p class="commentPageTopTitle">Laravel News</p>
     <?php 
      //配列arrayDataの中にデータが入っていると
        if(!empty($arrayData)){  

          //二次元配列arrayDataから配列Data取り出しそれぞれのタイトル、記事を表示する
          foreach ( $arrayData as $data ) {  
          //strpos関数を使いそのページのURL($url)の中に$data[0](id)という文字列が含まれていたら
           if(strpos($url,$data[0])){ ?>
           <div class="articleArea">
              <div class="title">
                <p><?php echo h($data[1]); ?></p>
              </div>
              <div class="srticle">
                <p><?php echo h($data[2]); ?></p>
              </div>
           </div>
          <?php }
          } 
        } 
        ?>
       <div class="intermediate"></div>

      <p　class="commentErrorMesseage"><?php echo h($commentErrorMesseage) ?></p>
    
    <section class="commentArea">
     <div class="form-comment">
      
        <form method="POST" >
         <div class="pushCommentArea">
           <textarea class="commentInput" name="comment" cols="26" rows="9" value=""></textarea>
           <input class="commentPushBotton" type="submit" value="コメントを書く"> 
         </div>
        </form>
      

      
       <?php 
       //$commentArray配列からデータ(コメントIDとコメント)を取り出す
          foreach($commentArray as $array){ 

            ////$array[1](記事)と$deleteCommentが同じ場合に配列$arrayから$deleteCommentを削除する
            //if(strpos($array[1],$deleteComment)){
            //  array_splice($array,$deleteComment);
            // }

            //そのページでのコメントのみを表示するためにそのページのURL($url)の中に$array[0](URLから取り出した13文字)という文字列が含まれていた場合にコメント欄を繰り返し処理で作っていく
            if(strpos($url,$array[1])){ ?>
           
         
             <form method="POST">
               <div class="pushedCommentArea">
                 <textarea class="pushedCommentInput" name="" cols="26" rows="9" readonly><?php echo $array[2]; ?></textarea>
                 <input type="hidden" name='del' value="<?php echo h($array[0]); ?>">
                 <input class="commentDeleteBotton" type="submit" value="コメントを消す">
               </div>
             </form>
         
           <?php 
            }
          }
        ?>
      
     </div>
    </section>　

     <div class="backHome">
       <a href="keiziban.php">ホームへ戻る</a>
     </div>


    <script type="text/javascript" src="keiziban.js"></script>
  </body>
</html>