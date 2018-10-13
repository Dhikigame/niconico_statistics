<?php
require('video_info.php');
$xml_videoID = isset($_GET['id']) ? $_GET['id'] : null; //GETでIDを受け取る
$sort_value_after = isset($_GET['num']) ? $_GET['num'] : null; //GETでnumを受け取る
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
      <title>ニコニコ動画検索info　統計データ - <?php echo video_IDsearch($xml_videoID) . $xml_videoID; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <style type="text/css">
            p{
              font-size: 20px;
            }
            img {
              zoom: 150%;
            }
            button {
              -webkit-tap-highlight-color:rgba(0,0,0,0);
            }
            </style>
  </head>
    <body>
      <center>
  <a name="up"></a>
<?php

  if($xml_videoID < 1000){
    echo "動画IDは1000以上でお願いします";
    die();
  }
  if($sort_value_after <= 4){
    echo "検索総数は5以上2000以下でお願いします";
    die();
  }
  if($sort_value_after > 2000){
    echo "検索総数は5以上2000以下でお願いします";
    die();
  }
$sort_value_before = $sort_value_after;
/*
$xml_videoID = "29888485"; //検索するニコニコ動画の動画IDを指定(例:sm29888485の29888485)
$sort_value_after = "10";   //取得する最下位の範囲指定
$sort_value_before = "10";  //取得する最上位の範囲指定
*/
//echo $xml_videoID;

video_data($xml_videoID, $sort_value_after ,$sort_value_before);



?>
      </center>
  <a name="down"></a>
    </body>
</html>
