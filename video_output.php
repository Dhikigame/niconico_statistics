<?php
function video_output($title, $ID, $view, $comment, $mylist, $adv, $total, $sort_value_before, $sort_value_after){
  echo "<table border=”1″>
          <caption>検索した動画</caption>
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
  echo "<table border=”1″>
          <caption>検索した動画から".$sort_value_before."個前の動画</caption>
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
  echo "<table border=”1″>
          <caption>検索した動画から".$sort_value_after."個後の動画</caption>
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
  echo "<table border=”1″>
          <caption>検索した動画の総合ポイントランキング</caption>
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

?>