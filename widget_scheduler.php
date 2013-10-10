<?php

//
// スケジュールデータのフォーマット
//
// 月,日,時,分,場所
//
// で上から順に入力する。
// 月日時分については2桁（0→00、1→01）で入力する。
//
// 例）
// 1月2日3時45分からホールの場合
//
// 01,02,03,45,ホール
//

  date_default_timezone_set("Asia/Tokyo");
  $filepath = "schedule.txt";
  $schedule = file($filepath);
  $now = date("n,j,G,i");

  $schedule_cal = explode ( "," , $schedule[0] );
  $now_cal = explode ( "," , $now);

  $schedule_size = sizeof($schedule);
  $schedule_cal = str_replace(array("\r\n","\r","\n"), '', $schedule_cal);

  // 1月の読み替え
  if((int)$now_cal[0] == "12" and $schedule_cal[0] == "1" ){
      $schedule_cal[0] = "13";
  }

  // 次の活動日が活動日当日の開始時分のとき
  if((int)$now_cal[0] == $schedule_cal[0] ){
      if((int)$now_cal[1] == $schedule_cal[1]){
          if((int)$now_cal[2] == $schedule_cal[2]){
              if((int)$now_cal[3] > $schedule_cal[3]){
                  $fp = fopen($filepath, "w");
                  for($i=1 ; $i<=$schedule_size ; $i++){
                      fwrite($fp, $schedule[$i]);
                  }
                  fclose($fp);
              }
          }
          // 次の活動日が活動日当"時"より後のとき
          if((int)$now_cal[2] > $schedule_cal[2]){
              $fp = fopen($filepath, "w");
              for($i=1 ; $i<=$schedule_size ; $i++){
                  fwrite($fp, $schedule[$i]);
              }
              fclose($fp);
          }
      }
      // 次の活動日が活動日当日より後のとき
      if((int)$now_cal[1] > $schedule_cal[1]){
          $fp = fopen($filepath, "w");
          for($i=1 ; $i<=$schedule_size ; $i++){
              fwrite($fp, $schedule[$i]);
          }
          fclose($fp);
      }
  }

  // 次の活動日が活動日当月より後のとき
  if((int)$now_cal[0] > $schedule_cal[0]){
      $fp = fopen($filepath, "w");
      for($i=1 ; $i<=$schedule_size ; $i++){
          fwrite($fp, $schedule[$i]);
      }
      fclose($fp);
  }

  // 区切り
  $filepath2 = "schedule.txt";
  $schedule2 = file($filepath2);
  $schedule_cal3 = explode ( "," , $schedule2[0] );

  // 月が空白のとき
  if($schedule2[0] == ""){
    $schedule2[0] = "未定";
  } else {
    $schedule2[0] = $schedule2[0];
  }

  // 日が空白のとき
  if($schedule2[1] == ""){
    $schedule2[1] = "未定 ";
  } else {
    // 月日があるとき
    $schedule2[1] = $schedule2[1];
  }

  // 「その次」から場所の消去
  $schedule2[1] = mb_substr($schedule2[1],0,11);

  // 月、日、時の 01-09の 0 を消去
  for($i = 0; $i <= 2; $i++){
    $array_schedule2 = explode(",",$schedule2[$i]);
      for($j = 0; $j <= 2; $j++){
        for($k = 1; $k <= 9; $k++){
          $array_schedule2[$j] = preg_replace("/0".$k."/", $k, $array_schedule2[$j]);
        }
      }
    $schedule2[$i] = implode(",",$array_schedule2);
  }

  // 区切りを付ける
  $schedule_cal3 = explode ( "," , $schedule2[0] );

  // 区切りの置き換え
  $schedule2 = preg_replace("/,/", "月", $schedule2,1);
  $schedule2 = preg_replace("/,/", "日 ", $schedule2,1);
  $schedule2 = preg_replace("/,/", ":", $schedule2,1);
  $schedule2 = preg_replace("/,/", "　", $schedule2,1);

  // 改行コードの消去
  $schedule2 = str_replace(array("\r\n","\r","\n"), '', $schedule2);

  echo "<div style='font-size:18px;margin:15px 0px;'>".$schedule2[0]."</div>";
  echo "<div style='color:gray;'>その次は ".$schedule2[1]." からです。</div>";
?>