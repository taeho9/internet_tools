<?php
  require_once '/usr/share/pear/KASI_Lunar.php';
  require_once '/usr/share/pear/Lunar.php';

  $lunar = new oops\Lunar;

  // 음력 날짜를 볼 연도 , 1971년에 윤달이 있음
  $year=1974;
  for( $month = 1; $month <= 12; $month++ ) {
    for ( $day = 1; $day <= 31; $day++ ) {

      $month2     = sprintf("%02d", $month);
      $day2       = sprintf("%02d", $day);
      $dateYmd    = "$year-$month2-$day2";

      if ( !checkdate($month2, $day2, $year) ) continue;

      // 양력을 음력으로
      $result   = $lunar->tolunar ($dateYmd);
      $dateLunar  = $result->fmt;
      //음력을 양력으로
      $result   = $lunar->tosolar ($dateLunar); 
      $dateSolar  = $result->fmt;

      $dateSolar2 = '';

      //양력을 음력으로 변환한 $dateLunar를 다시 양력으로 변환한 $dateSolar가 원래 양력인 $dateYmd와 다르면 윤달임
      $eqName   = ( $dateYmd == $dateSolar ) ? '평달':'윤달';
      if ( $eqName == '윤달' ) {
        //음력일자가 윤달이면 양력으로 변환할 때 tosolar 함수의 두번째 인자를 true로 넣어줘야 함
        $result   = $lunar->tosolar ($dateLunar, true);  // 음력 -> 양력
        $dateSolar2 = $result->fmt;
        echo $eqName . " : (양력)" . $dateYmd . " -> (음력)" . $dateLunar . " / (음력)" . $dateLunar . " -> (양력)" . $dateSolar . " 두 양력이 다르므로 윤달임<br>";
      } else
        echo $eqName . " : (양력)" . $dateYmd . " -> (음력)" . $dateLunar. " / (음력)" . $dateLunar . " -> (양력)" . $dateSolar . "<br>";
    }
  }
  // 음력을 양력으로 변환할때 윤달 여부를 입력받아 tosolar 함수의 두번째 인자를 true로 넣어줘야 하며
  // 양력을 음력으로 변환할 때는 음력으로 변환한 날짜를 다시 양력으로 변환하여 원래의 양력과 일치하는지 확인하여 불일치할 경우 변환된 음력날자가 윤달임을 표시해줘야 함

  ?>