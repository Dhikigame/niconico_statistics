<?php
function video_output($title, $ID, $view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after){
  echo "<a name='up'></a>";

  echo "<a href='#search_video'>検索した動画</a> ";
  echo "<a href='#before_video'>過去動画</a> ";
  echo "<a href='#after_video'>未来動画</a> ";
  echo "<a href='#rank_video'>総合ポイントランキング</a> ";
  echo "<a href='#statistics_video'>統計</a> ";
  echo "<a href='#view_video'>再生順ソート</a> ";
  echo "<a href='#comment_video'>コメント順ソート</a> ";
  echo "<a href='#mylist_video'>マイリスト順ソート</a> ";
  echo "<a href='#total_video'>総合ポイント順ソート</a> ";

  echo "<a name='search_video'></a>";
  echo "<table border=”1″>
          <caption>検索した動画<a href='#up'>上へ戻る</a></caption>
          <tr>
            <th>タイトル</th>
            <th>ID</th>
            <th>再生数</th>
            <th>コメント</th>
            <th>マイリスト</th>
            <th>宣伝ポイント</th>
            <th>総合ポイント</th>
          </tr>";
  echo   "<tr>
            <td><a href=http://www.nicovideo.jp/watch/".$ID[2][0]." target=_blank>".$title[2][0]."</a></td>
            <td><a href=https://www.google.co.jp/#q=".$ID[2][0]." target=_blank>".$ID[2][0]."</a></td>
            <td>".$view[2][0]."</td>
            <td>".$comment[2][0]."</td>
            <td>".$mylist[2][0]."</td>
            <td>".$adv[2][0]."</td>
            <td>".$total[2][0]."</td>
          </tr>";
  echo  "</table>";
  echo "<p></p>";
  echo "<a name='before_video'></a>";
  echo "<table border=”1″>
          <caption>検索した動画から".$sort_value_before."個前の動画<a href='#up'>上へ戻る</a></caption>
          <tr>
            <th>タイトル</th>
            <th>ID</th>
            <th>再生数</th>
            <th>コメント</th>
            <th>マイリスト</th>
            <th>宣伝ポイント</th>
            <th>総合ポイント</th>
          </tr>";
  $i = $sort_value_before - 1;
      while($i >= 0){
  echo   "<tr>
            <td><a href=http://www.nicovideo.jp/watch/".$ID[0][$i]." target=_blank>".$title[0][$i]."</a></td>
            <td><a href=https://www.google.co.jp/#q=".$ID[0][$i]." target=_blank>".$ID[0][$i]."</a></td>
            <td>".$view[0][$i]."</td>
            <td>".$comment[0][$i]."</td>
            <td>".$mylist[0][$i]."</td>
            <td>".$adv[0][$i]."</td>
            <td>".$total[0][$i]."</td>
          </tr>";
          $i--;
      }
  echo  "</table>";
  echo "<p></p>";
  echo "<a name='after_video'></a>";
  echo "<table border=”1″>
          <caption>検索した動画から".$sort_value_after."個後の動画<a href='#up'>上へ戻る</a></caption>
          <tr>
            <th>タイトル</th>
            <th>ID</th>
            <th>再生数</th>
            <th>コメント</th>
            <th>マイリスト</th>
            <th>宣伝ポイント</th>
            <th>総合ポイント</th>
          </tr>";
  $i = 0;
      while($i <= $sort_value_after - 1){
  echo   "<tr>
            <td><a href=http://www.nicovideo.jp/watch/".$ID[1][$i]." target=_blank>".$title[1][$i]."</a></td>
            <td><a href=https://www.google.co.jp/#q=".$ID[1][$i]." target=_blank>".$ID[1][$i]."</a></td>
            <td>".$view[1][$i]."</td>
            <td>".$comment[1][$i]."</td>
            <td>".$mylist[1][$i]."</td>
            <td>".$adv[1][$i]."</td>
            <td>".$total[1][$i]."</td>
          </tr>";
          $i++;
      }
  echo  "</table>";
}

function video_rank_output($rank){
  echo "<p></p>";
  echo "<a name='rank_video'></a>";
  echo "<table border=”1″>
          <caption>検索した動画の総合ポイントランキング<a href='#up'>上へ戻る</a></caption>
          <tr>
            <th>順位</th>
            <th>タイトル</th>
            <th>ID</th>
            <th>再生数</th>
            <th>コメント</th>
            <th>マイリスト</th>
            <th>宣伝ポイント</th>
            <th>総合ポイント</th>
          </tr>";
  for($i = 1; $i <= 10; $i++){
    echo  "<tr>
              <td>".$i."</td>
              <td><a href=http://www.nicovideo.jp/watch/".$rank[$i - 1][1]." target=_blank>".$rank[$i - 1][0]."</a></td>
              <td><a href=https://www.google.co.jp/#q=".$rank[$i - 1][1]." target=_blank>".$rank[$i - 1][1]."</a></td>
              <td>".$rank[$i - 1][2]."</td>
              <td>".$rank[$i - 1][3]."</td>
              <td>".$rank[$i - 1][4]."</td>
              <td>".$rank[$i - 1][5]."</td>
              <td>".$rank[$i - 1][6]."</td>
          </tr>";
  }
  echo  "</table>";
}

function video_grandtotal_output($view_total, $comment_total, $mylist_total, $adv_total, $total_total, $view_abg, $comment_abg, $mylist_abg, $adv_abg, $total_abg, $sort_value_before, $sort_value_after, $median){

  $video_total = $sort_value_before + $sort_value_after + 1;

  echo "<p></p>";
  echo "<a name='statistics_video'></a>";
  echo "<table border=”1″>
          <caption>全".$video_total."動画の総計<a href='#up'>上へ戻る</a></caption>
          <tr>
            <th></th>
            <th>再生数</th>
            <th>コメント</th>
            <th>マイリスト</th>
            <th>宣伝ポイント</th>
            <th>総合ポイント</th>
          </tr>";
    echo  "<tr>
          <td>中央値</td>
          <td>".$median[0]."</td>
          <td>".$median[1]."</td>
          <td>".$median[2]."</td>
          <td>".$median[3]."</td>
          <td>".$median[4]."</td>
        </tr>";
    echo  "<tr>
            <td>平均値</td>
            <td>".$view_abg."</td>
            <td>".$comment_abg."</td>
            <td>".$mylist_abg."</td>
            <td>".$adv_abg."</td>
            <td>".$total_abg."</td>
          </tr>";
    echo  "<tr>
              <td>合計値</td>
              <td>".$view_total."</td>
              <td>".$comment_total."</td>
              <td>".$mylist_total."</td>
              <td>".$adv_total."</td>
              <td>".$total_total."</td>
          </tr>";
  echo  "</table>";
}

?>
