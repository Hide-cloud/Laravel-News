<?php
//コメントを追加
$comment="";
//$deleteComment="";
$deleteComment=$_GET['pushedComment'];
$commentId="";
$commentData=[];
$commentArray=[];
$commentErrorMesseage="";
$postComment='comment.txt';
 $postFile='keiziban.txt';
 $postArray=[];
 $arrayData=[];
 $id=uniqid();
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

    //コメントが入力されていたら
      if(!empty($_POST['comment'])){
         
       //コメントのデータを取得
         $comment=$_POST['comment'];

       //コメントIDとコメントのデータを$commentData配列に代入 
         $commentData=[$commentId,$comment];
 
       //コメントのデータを$commentArray配列に代入
         $commentArray[]=$commentData;

       //入力されたデータ($commentArray)をテキストファイル($postComment)に書き込む
         file_put_contents($postComment,json_encode($commentArray));
      }

     if(empty($_POST['comment'])){
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
      
        <form method="POST" action="">
         <div class="pushCommentArea">
           <textarea class="commentInput" name="comment" cols="26" rows="9" value=""></textarea>
           <input class="commentPushBotton" type="submit" value="コメントを書く"> 
         </div>
        </form>
      

      
       <?php 
       //$commentArray配列からデータ(コメントIDとコメント)を取り出す
          foreach($commentArray as $array){ 

            //$array[1](記事)と$deleteCommentが同じ場合に配列$arrayから$deleteCommentを削除する
            if(strpos($array[1],$deleteComment)){
              array_splice($array,$deleteComment);
             }

            //そのページでのコメントのみを表示するためにそのページのURL($url)の中に$array[0](URLから取り出した13文字)という文字列が含まれていた場合にコメント欄を繰り返し処理で作っていく
           if(strpos($url,$array[0])){ ?>
         
             <form method="POST">
               <div class="pushedCommentArea">
                 <textarea class="pushedCommentInput" name="pushedComment" cols="26" rows="9" readonly><?php echo $array[1]; ?></textarea>
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