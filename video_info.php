<?php
require('video_output.php');
// 1:before, 2:after, 3:base
/*
    詳細情報を格納　1:before, 2:after, 3:base
    title:動画のタイトル, ID:動画ID, view:再生数, comment:コメント,
    mylist:マイリスト, adv:宣伝ポイント, total:総合ポイント, revise:補正値
*/
  $VALUE = 1000000;
  $title[3][$VALUE];
  $ID[3][$VALUE];
  $view[3][$VALUE];
  $comment[3][$VALUE];
  $mylist[3][$VALUE];
  $adv[3][$VALUE];
  $total[3][$VALUE];
  $revise[3][$VALUE];
/*
    出力した動画の中で総合ポイントが10位までの動画の詳細情報を格納
    $rank[10][1] : タイトル
    $rank[10][2] : 動画ID
    $rank[10][3] : 再生数
    $rank[10][4] : コメント
    $rank[10][5] : マイリスト
    $rank[10][6] : 宣伝ポイント
    $rank[10][7] : 総合ポイント
    $rank[10][8] : 補正値
*/
  $rank[10][8];
/*
    過去と未来の動画IDを取得
*/
function video_data($xml_videoID, $sort_value_after, $sort_value_before){
  //echo $sort_kind."<br>".$sort_ID;
  //$sort_ID_before = $sort_ID - $sort_value_before;　　　　　　//取得する最下位のIDを指定
  //$sort_ID_after = $sort_ID - $sort_value_after;　　　　　　  //取得する最下位のIDを指定

  $sort_kind = video_IDsearch($xml_videoID);
    if($sort_kind == 1){
        die();
    }

/*
    過去の動画を取得
*/
//  $sort_ID_before : 検索した動画IDの過去の動画ID，$sort_kind_before : 過去の動画の種類
  $sort_ID_before[0] = $xml_videoID - 1;
//  $sort_value_beforeで指定した数だけ存在する動画の種類とIDを取得
  $i = 0;
    while($i <= $sort_value_before - 1){
        $sort_kind_before[$i] = video_IDsearch($sort_ID_before[$i]);
            if($sort_kind_before[$i] == 1){
                $sort_ID_before[$i] = $sort_ID_before[$i] - 1;
                continue;
            }
        //echo $sort_kind_before[$i].$sort_ID_before[$i]."<br>";
        $i_store = $i;
        $i++;
        $sort_ID_before[$i] = $sort_ID_before[$i_store] - 1;
    }
//echo "<br>".$sort_kind.$xml_videoID."<br><br>";
/*
    未来の動画を取得
*/
//  $sort_ID_after : 検索した動画IDの未来の動画ID，$sort_kind_after : 未来の動画の種類
  //$sort_ID_after[$sort_value_after];$sort_kind_after[$sort_value_after];
  $sort_ID_after[0] = $xml_videoID + 1;
//  $sort_value_afterで指定した数だけ存在する動画の種類とIDを取得
  $i = 0;
    while($i <= $sort_value_after - 1){
        $sort_kind_after[$i] = video_IDsearch($sort_ID_after[$i]);
            if($sort_kind_after[$i] == 1){
                $sort_ID_after[$i] = $sort_ID_after[$i] + 1;
                continue;
            }
        //echo $sort_kind_after[$i].$sort_ID_after[$i]."<br>";
        $i_store = $i;
        $i++;
        $sort_ID_after[$i] = $sort_ID_after[$i_store] + 1;
    }
    video_detailinfo($sort_ID_before, $sort_kind_before, $sort_ID_after, $sort_kind_after, $xml_videoID, $sort_kind, $sort_value_after, $sort_value_before);
}

/*
    取得した動画の詳細情報をbefore, after, baseに格納
*/
function video_detailinfo($sort_ID_before, $sort_kind_before, $sort_ID_after, $sort_kind_after, $xml_videoID, $sort_kind, $sort_value_after, $sort_value_before){

    $xml_base = 'http://ext.nicovideo.jp/api/getthumbinfo/' . $sort_kind . $xml_videoID;//ニコニコAPI, ベース(動画情報取得)
    $xml_base = simplexml_load_file($xml_base); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)
    //echo $xml_base->thumb->view_counter;

    //  ニコニコAPI(動画の宣伝情報:json形式)
    //  base
    $xml_base_adv = 'http://uad-api.nicovideo.jp/sub/1.0/UadVideoService/findAdsInfoJsonp?idvideos=' . $sort_kind . $xml_videoID;
    $json_adv = file_get_contents($xml_base_adv); //APIの情報を読み取る
    $json_adv = preg_replace('/.+?({.+}).+/','$1',$json_adv); //$json_advのJSON形式から余分なものを抜き取る --> {} の形になり、変数を読み込めるようになる
    $base_adv = json_decode( $json_adv ) ; //JSON用の形式をPHP用に読み込む

    //総合ポイント計算するための補正値
    $revise[2][0] = ($xml_base->thumb->view_counter + $xml_base->thumb->mylist_counter ) / ( $xml_base->thumb->view_counter + $xml_base->thumb->comment_num + $xml_base->thumb->mylist_counter);

        $title[2][0] = $xml_base->thumb->title;
        $ID[2][0] = $xml_base->thumb->video_id;
        $view[2][0] = $xml_base->thumb->view_counter;
        $comment[2][0] = $xml_base->thumb->comment_num;
        $mylist[2][0] = $xml_base->thumb->mylist_counter;
        $adv[2][0] = $base_adv->total;
        $total[2][0] = str_replace(',','',number_format($view[2][0] + ($comment[2][0] * $revise[2][0]) + $mylist[2][0] * 15 + $adv[2][0]  * 0.3, 0));

    $i = 0;
    while($i < $sort_value_before){
      $xml_before = 'http://ext.nicovideo.jp/api/getthumbinfo/' . $sort_kind_before[$i] . $sort_ID_before[$i]; //ニコニコAPI, 過去(動画情報取得)
      $xml_before = simplexml_load_file($xml_before); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)

      //  ニコニコAPI(動画の宣伝情報:json形式)
      //  before
      $xml_before_adv = 'http://uad-api.nicovideo.jp/sub/1.0/UadVideoService/findAdsInfoJsonp?idvideos=' . $sort_kind_before[$i] . $sort_ID_before[$i];
      $json_adv = file_get_contents($xml_before_adv); //APIの情報を読み取る
      $json_adv = preg_replace('/.+?({.+}).+/','$1',$json_adv); //$json_advのJSON形式から余分なものを抜き取る --> {} の形になり、変数を読み込めるようになる
      $before_adv = json_decode( $json_adv ) ; //JSON用の形式をPHP用に読み込む

        $title[0][$i] = $xml_before->thumb->title;
        $ID[0][$i] = $xml_before->thumb->video_id;
        $view[0][$i] = $xml_before->thumb->view_counter;
        $comment[0][$i] = $xml_before->thumb->comment_num;
        $mylist[0][$i] = $xml_before->thumb->mylist_counter;
        $adv[0][$i] = $before_adv->total;
        //総合ポイント計算するための補正値
        $revise[0][$i] = ($xml_before->thumb->view_counter + $xml_before->thumb->mylist_counter ) / ( $xml_before->thumb->view_counter + $xml_before->thumb->comment_num + $xml_before->thumb->mylist_counter);
        //総合ポイント＝再生数＋（コメント数×補正値）＋マイリスト数×15＋ニコニ広告宣伝ポイント×0.3 ※補正値＝（再生数＋マイリスト数）÷（再生数＋コメント数＋マイリスト数）
        $total[0][$i] = str_replace(',','',number_format($view[0][$i] + ($comment[0][$i] * $revise[0][$i]) + $mylist[0][$i] * 15 + $adv[0][$i]  * 0.3, 0));
        //echo $title[0][$i] .' '. $ID[0][$i] .' '. $view[0][$i] .' '. $comment[0][$i] .' '. $mylist[0][$i] .' '. $adv[0][$i] .' '. $total[0][$i].'<br>';
        $i++;
    }
    $i = 0; //echo "<br>";
    while($i < $sort_value_after){
      $xml_after = 'http://ext.nicovideo.jp/api/getthumbinfo/' . $sort_kind_after[$i] . $sort_ID_after[$i];    //ニコニコAPI, 未来(動画情報取得)
      $xml_after = simplexml_load_file($xml_after); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)

      //  ニコニコAPI(動画の宣伝情報:json形式)
      //  after
      $xml_after_adv = 'http://uad-api.nicovideo.jp/sub/1.0/UadVideoService/findAdsInfoJsonp?idvideos=' . $sort_kind_after[$i] . $sort_ID_after[$i];
      $json_adv = file_get_contents($xml_after_adv); //APIの情報を読み取る
      $json_adv = preg_replace('/.+?({.+}).+/','$1',$json_adv); //$json_advのJSON形式から余分なものを抜き取る --> {} の形になり、変数を読み込めるようになる
      $after_adv = json_decode( $json_adv ) ; //JSON用の形式をPHP用に読み込む

      $title[1][$i] = $xml_after->thumb->title;
      $ID[1][$i] = $xml_after->thumb->video_id;
      $view[1][$i] = $xml_after->thumb->view_counter;
      $comment[1][$i] = $xml_after->thumb->comment_num;
      $mylist[1][$i] = $xml_after->thumb->mylist_counter;
      $adv[1][$i] = $after_adv->total;
      //総合ポイント計算するための補正値
      $revise[1][$i] = ($xml_after->thumb->view_counter + $xml_after->thumb->mylist_counter ) / ( $xml_after->thumb->view_counter + $xml_after->thumb->comment_num + $xml_after->thumb->mylist_counter);
      //総合ポイント＝再生数＋（コメント数×補正値）＋マイリスト数×15＋ニコニ広告宣伝ポイント×0.3 ※補正値＝（再生数＋マイリスト数）÷（再生数＋コメント数＋マイリスト数）
      $total[1][$i] = str_replace(',','',number_format($view[1][$i] + ($comment[1][$i] * $revise[1][$i]) + $mylist[1][$i] * 15 + $adv[1][$i]  * 0.3, 0));
      //echo $title[1][$i] .' '. $ID[1][$i] .' '. $view[1][$i] .' '. $comment[1][$i] .' '. $mylist[1][$i] .' '. $adv[1][$i] .' '. $total[1][$i].'<br>';
      $i++;
    }

    video_output($title, $ID, $view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after);
    video_rank($title, $ID, $view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after);
    video_total($view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after);

}
/*
function bubble_sort($array)
{
    // 要素数回繰り返し
    for($i = 0; $i < count($array); $i++)
    {
        // 要素数-1回繰り返し
        for($n = 1; $n < count($array); $n++)
        {
            // 隣接要素を比較し大小が逆なら入替える
            if($array[$n-1] > $array[$n])
            {
                $temp = $array[$n];
                $array[$n] = $array[$n-1];
                $array[$n-1] = $temp;
            }
        }
    }
    return $array;
}
*/
/*
     中央値を求める関数
*/
/*
function median($view_sort, $sort_value_before, $sort_value_after){
  echo $sort_value_before;
  // 要素数回繰り返し
  for($i = 0; $i < $sort_value_before + $sort_value_after; $i++)
  {
      // 要素数-1回繰り返し
      for($n = 1; $n < $sort_value_before + $sort_value_after; $n++)
      {
          // 隣接要素を比較し大小が逆なら入替える
          if($view_sort[$n-1] > $view_sort[$n])
          {
              $temp = $view_sort[$n];
              $view_sort[$n] = $view_sort[$n-1];
              $view_sort[$n-1] = $temp;
          }
      }
  }
  for($i = 0; $i < $sort_value_before + $sort_value_after; $i++){
    echo $i." ".$view_sort[$i]."<br>";
  }
}
*/
/*
function median(array $values){
     = sort($values);
  //  echo $values."<br>";
        if (count($values) % 2 == 0){
          return (($values[(count($values)/2)-1]+$values[((count($values)/2))])/2);
        }
        else {
          return ($values[floor(count($values)/2)]);
        }
}
*/
/*
function median($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}
*/

function video_total($view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after){

  $view_total = 0;
  $comment_total = 0;
  $mylist_total = 0;
  $adv_total = 0;
  $total_total = 0;

  $i = 0;
      //再生数の合計求める
      for($j = 0; $j < $sort_value_before; $j++){
        $view_total += $view[$i][$j];//　　    過去の動画の再生数
      }
      for($j = 0; $j < $sort_value_after; $j++){
        $view_total += $view[$i + 1][$j];//　　未来の動画の再生数
      }
        $view_total += $view[$i + 2][0];//　　検索した動画IDの再生数
      //コメントの合計求める
      for($j = 0; $j < $sort_value_before; $j++){
        $comment_total += $comment[$i][$j];
      }
      for($j = 0; $j < $sort_value_after; $j++){
        $comment_total += $comment[$i + 1][$j];
      }
        $comment_total += $comment[$i + 2][0];
      //マイリストの合計求める
      for($j = 0; $j < $sort_value_before; $j++){
        $mylist_total += $mylist[$i][$j];
      }
      for($j = 0; $j < $sort_value_after; $j++){
        $mylist_total += $mylist[$i + 1][$j];
      }
        $mylist_total += $mylist[$i + 2][0];
      //宣伝ポイントの合計求める
      for($j = 0; $j < $sort_value_before; $j++){
        $adv_total += $adv[$i][$j];
      }
      for($j = 0; $j < $sort_value_after; $j++){
        $adv_total += $adv[$i + 1][$j];
      }
        $adv_total += $adv[$i + 2][0];
      //総合ポイントの合計求める
      for($j = 0; $j < $sort_value_before; $j++){
        $total_total += $total[$i][$j];
      }
      for($j = 0; $j < $sort_value_after; $j++){
        $total_total += $total[$i + 1][$j];
      }
        $total_total += $total[$i + 2][0];

  //echo "<br>".$view_total." ".$comment_total." ".$mylist_total." ".$adv_total." ".$total_total;

    //再生数の平均値と中央値を求める関数に引数として渡すため一次配列にする
    for($i = 0; $i < $sort_value_before; $i++){
      $view_sort[$i] = $view[0][$i];
    }
    for($i = 0; $i < $sort_value_after; $i++){
      $view_sort[$sort_value_before + $i] = $view[1][$i];
    }
    $view_sort[$sort_value_before + $sort_value_after] = $view[2][0];
/*
  $view_median = median($view_sort, $sort_value_before, $sort_value_after);
  echo "<br>".$view_median."<br>";
*/

/*
  for($i = 0; $i <= 100; $i++){
    echo $i." ".$view_sort[$i]."<br>";
  }
*/
}


/*
    総合ポイントのランキング10位までを取得
*/
function video_rank($title, $ID, $view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after){
//   念のため配列の値を0で初期化
  for($i = 0; $i < 10; $i++){
    for($j = 0; $j < 8; $j++){
      $rank[$i][$j] = 0;
    }
  }
/*
  echo $total[1][2]." ".$total[1][8];
if($total[1][8] > $total[1][2]){
  echo $total[1][2]."<br>";
}
*/
//  過去、未来、検索したIDの動画情報のランキング付けを行う
  for($i = 0; $i <= 2; $i++){
    //過去
    if($i == 0){
      for($j = 0; $j < $sort_value_before; $j++){
        for($k = 0; $k < 10; $k++){
          if($rank[$k][6] < $total[$i][$j]){
              for($l = 9; $l >= $k; $l--){
                $rank[$l + 1][0] = $rank[$l][0];
                $rank[$l + 1][1] = $rank[$l][1];
                $rank[$l + 1][2] = $rank[$l][2];
                $rank[$l + 1][3] = $rank[$l][3];
                $rank[$l + 1][4] = $rank[$l][4];
                $rank[$l + 1][5] = $rank[$l][5];
                $rank[$l + 1][6] = $rank[$l][6];
              }
            $rank[$k][0] = $title[$i][$j];
            $rank[$k][1] = $ID[$i][$j];
            $rank[$k][2] = $view[$i][$j];
            $rank[$k][3] = $comment[$i][$j];
            $rank[$k][4] = $mylist[$i][$j];
            $rank[$k][5] = $adv[$i][$j];
            $rank[$k][6] = $total[$i][$j];
            //echo $k." : ".$rank[$k][0]."<br>";
            continue 2;
          }
        }
      }
    }
    //未来
    else if($i == 1){
      for($j = 0; $j < $sort_value_after; $j++){
        for($k = 0; $k < 10; $k++){
          if($rank[$k][6] < $total[$i][$j]){
              for($l = 9; $l >= $k; $l--){
                $rank[$l + 1][0] = $rank[$l][0];
                $rank[$l + 1][1] = $rank[$l][1];
                $rank[$l + 1][2] = $rank[$l][2];
                $rank[$l + 1][3] = $rank[$l][3];
                $rank[$l + 1][4] = $rank[$l][4];
                $rank[$l + 1][5] = $rank[$l][5];
                $rank[$l + 1][6] = $rank[$l][6];
              }
            $rank[$k][0] = $title[$i][$j];
            $rank[$k][1] = $ID[$i][$j];
            $rank[$k][2] = $view[$i][$j];
            $rank[$k][3] = $comment[$i][$j];
            $rank[$k][4] = $mylist[$i][$j];
            $rank[$k][5] = $adv[$i][$j];
            $rank[$k][6] = $total[$i][$j];
            //echo $k." : ".$rank[$k][0]."<br>";
            continue 2;
          }
        }
      }
    }
    //現在
    else {
      $j = 0;
        for($k = 0; $k < 10; $k++){
          if($rank[$k][6] < $total[$i][$j]){
              for($l = 9; $l >= $k; $l--){
                $rank[$l + 1][0] = $rank[$l][0];
                $rank[$l + 1][1] = $rank[$l][1];
                $rank[$l + 1][2] = $rank[$l][2];
                $rank[$l + 1][3] = $rank[$l][3];
                $rank[$l + 1][4] = $rank[$l][4];
                $rank[$l + 1][5] = $rank[$l][5];
                $rank[$l + 1][6] = $rank[$l][6];
              }
            $rank[$k][0] = $title[$i][$j];
            $rank[$k][1] = $ID[$i][$j];
            $rank[$k][2] = $view[$i][$j];
            $rank[$k][3] = $comment[$i][$j];
            $rank[$k][4] = $mylist[$i][$j];
            $rank[$k][5] = $adv[$i][$j];
            $rank[$k][6] = $total[$i][$j];
            //echo $k." : ".$rank[$k][0]."<br>";
            break;
          }
        }
    }
  }
  video_rank_output($rank);
/*
  echo "<br>".$rank[0][6]."<br>".$rank[1][6]."<br>".$rank[2][6]."<br>".$rank[3][6]."<br>".$rank[4][6]."<br>".
       $rank[5][6]."<br>".$rank[6][6]."<br>".$rank[7][6]."<br>".$rank[8][6]."<br>".$rank[9][6]."<br>";

  echo "<br>".$rank[0][0]."<br>".$rank[1][0]."<br>".$rank[2][0]."<br>".$rank[3][0]."<br>".$rank[4][0]."<br>".
       $rank[5][0]."<br>".$rank[6][0]."<br>".$rank[7][0]."<br>".$rank[8][0]."<br>".$rank[9][0]."<br>";
*/
}

/*
    動画の種類の判断(sm,nm,so)
    sm:SMILE VIDEOから投稿
    nm:ニコニコムービーメーカーで制作された動画
    so:チャンネル・公式動画
*/
function video_IDsearch($id){

  $base_id = 'http://ext.nicovideo.jp/api/getthumbinfo/sm' . $id; //ニコニコAPI(動画情報取得)
  $xml = simplexml_load_file($base_id); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)
  $id_sort = substr($xml->thumb->video_id, 0, 2);

    if($id_sort != "sm"){
      $base_id = 'http://ext.nicovideo.jp/api/getthumbinfo/so' . $id; //ニコニコAPI(動画情報取得)
      $xml = simplexml_load_file($base_id); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)
      $id_sort = substr($xml->thumb->video_id, 0, 2);
                  //echo "<p>".$id_sort."</p>";
        if($id_sort != "so"){
          $base_id = 'http://ext.nicovideo.jp/api/getthumbinfo/nm' . $id; //ニコニコAPI(動画情報取得)
          $xml = simplexml_load_file($base_id); //ニコニコAPIのxml形式のデータを読み込む(動画情報取得)
          $id_sort = substr($xml->thumb->video_id, 0, 2);

          if($id_sort != "nm"){
            $novideo = 1;
            return $novideo; //  動画の種類がないと1を返す(動画が消去または非公開と判断)
          }
          return $id_sort; //  動画の種類:nm
        }
      return $id_sort; //  動画の種類:so
    }
  return $id_sort; //  動画の種類:sm
}
?>
