<h2 align="center">■ 유닉스시간(unixtime) 계산기</h2>
    <div class="container"`>
    <h3>■ 유닉스시간(Unixtime)을 날짜(연월일 시분초)로 변환</h3>

    <!-- Unixtime 입력 폼 -->
    <form method="post" action="">
    <table>
            <tr>
                <td><label for="unixtime">■ 유닉스시간<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Unixtime) : </label></td>
                <td><input type="unixtime1" id="unixtime1" name="unixtime1" value="<?php echo isset($_POST['unixtime1']) ? $_POST['unixtime1'] : '';?>" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit1" value="날짜로 변환"></td>
            </tr>
    </table>
    </form>

    <?php
    // 공통 변수 - 타임존들.
    $timezones = [
        "Asia/Seoul" => "서울 (KST)",
        "Asia/Tokyo" => "도쿄 (JST)",
        "Australia/Sydney" => "시드니 (AEST)",
        "Asia/Shanghai" => "상하이 (CST)",
        "Asia/Singapore" => "싱가포르 (SGT)",
        "Asia/Ho_Chi_Minh" => "호치민 (ICT)",
        "Asia/Bangkok" => "방콕 (ICT)",
        "Asia/Dubai" => "두바이 (GST)",
        "Asia/Hong_Kong" => "홍콩 (HKT)",
        "Asia/Kolkata" => "콜카타 (IST)",        
        "Europe/Berlin" => "베를린 (CEST)",
        "Europe/London" => "런던 (GMT)",
        "Europe/Paris" => "파리 (CEST)",
        "Europe/Moscow" => "모스크바 (MSK)",
        "Europe/Madrid" => "마드리드 (CEST)",
        "Europe/Rome" => "로마 (CEST)",
        "Europe/Amsterdam" => "암스테르담 (CEST)",
        "Europe/Zurich" => "취리히 (CEST)",    
        "America/New_York" => "뉴욕 (EST)",
        "America/Los_Angeles" => "LA (PDT)",
        "America/Chicago" => "시카고 (CDT)",
        "America/Toronto" => "토론토 (EDT)",
        "America/Vancouver" => "밴쿠버 (PDT)",
        "America/Mexico_City" => "멕시코시티 (CST)",
        "America/Sao_Paulo" => "상파울루 (BRT)",
        "America/Buenos_Aires" => "부에노스아이레스 (ART)",    
        "Africa/Cairo" => "카이로 (EET)",
        "Africa/Johannesburg" => "요하네스버그 (SAST)",
        "Africa/Lagos" => "라고스 (WAT)",    
        "Pacific/Auckland" => "오클랜드 (NZST)",
        "Pacific/Honolulu" => "호놀룰루 (HST)"
    ];

    // 첫 번째 폼이 제출되었는지 확인
    if (isset($_POST['submit1'])) {
        // 사용자로부터 입력받은 두 날짜
        $unixtime1 = $_POST['unixtime1'];

        // 유닉스 시간을 UTC 표준시 년월일시분초로 변환
        $utcDate = gmdate("Y-m-d H:i:s", $unixtime);

        // 결과 출력
        echo "<h3>■ Unixtime($unixtime1)을 날짜로 변환 결과 </h3>";
        echo "<table>";
        echo "<tr><td> <strong>타임존</strong></td><td> <strong>날짜 / 시간</strong></td></tr>";

        foreach ($timezones as $timezone => $label) {
            $dateTime = new DateTime("@$unixtime1");
            $dateTime->setTimezone(new DateTimeZone($timezone));
            echo "<tr><td>$label : </td><td>" . $dateTime->format("Y-m-d H:i:s") . "</td></tr>";
        }
        echo "</table>";
    }
    ?>
    </div>
    <br>
    <div class="container"`>
    <h3>■ 날짜와 시간을 유닉스타임으로 변환</h3>
    <!-- 날짜와 시간 입력 폼 -->
     <?php
        $stz = isset($_POST['timezone']) ? $_POST['timezone'] : '';
    ?>
    <form method="post" action="">
        <table>
            <tr>
                <td colspan="2"><label for="timezone">■ 타임존 : 
                <select id="timezone" name="timezone" required>
                    <option value="Asia/Seoul" <?php if ($stz == "Asia/Seoul") echo "selected"; ?>>서울 (KST)</option>
                    <option value="Asia/Tokyo" <?php if ($stz == "Asia/Tokyo") echo "selected"; ?>>도쿄 (JST)</option>
                    <option value="Australia/Sydney" <?php if ($stz == "Australia/Sydney") echo "selected"; ?>>시드니 (AEST)</option>
                    <option value="Asia/Shanghai" <?php if ($stz == "Asia/Shanghai") echo "selected"; ?>>상하이 (CST)</option>
                    <option value="Asia/Singapore" <?php if ($stz == "Asia/Singapore") echo "selected"; ?>>싱가포르 (SGT)</option>
                    <option value="Asia/Ho_Chi_Minh" <?php if ($stz == "Asia/Ho_Chi_Minh") echo "selected"; ?>>호치민 (ICT)</option>
                    <option value="Asia/Bangkok" <?php if ($stz == "Asia/Bangkok") echo "selected"; ?>>방콕 (ICT)</option>
                    <option value="Asia/Dubai" <?php if ($stz == "Asia/Dubai") echo "selected"; ?>>두바이 (GST)</option>
                    <option value="Asia/Hong_Kong" <?php if ($stz == "Asia/Hong_Kong") echo "selected"; ?>>홍콩 (HKT)</option>
                    <option value="Asia/Kolkata" <?php if ($stz == "Asia/Kolkata") echo "selected"; ?>>콜카타 (IST)</option>
                    <option value="Europe/Berlin" <?php if ($stz == "Europe/Berlin") echo "selected"; ?>>베를린 (CEST)</option>
                    <option value="Europe/London" <?php if ($stz == "Europe/London") echo "selected"; ?>>런던 (GMT)</option>
                    <option value="Europe/Paris" <?php if ($stz == "Europe/Paris") echo "selected"; ?>>파리 (CEST)</option>
                    <option value="Europe/Moscow" <?php if ($stz == "Europe/Moscow") echo "selected"; ?>>모스크바 (MSK)</option>
                    <option value="Europe/Madrid" <?php if ($stz == "Europe/Madrid") echo "selected"; ?>>마드리드 (CEST)</option>
                    <option value="Europe/Rome" <?php if ($stz == "Europe/Rome") echo "selected"; ?>>로마 (CEST)</option>
                    <option value="Europe/Amsterdam" <?php if ($stz == "Europe/Amsterdam") echo "selected"; ?>>암스테르담 (CEST)</option>
                    <option value="Europe/Zurich" <?php if ($stz == "Europe/Zurich") echo "selected"; ?>>취리히 (CEST)</option>
                    <option value="America/New_York" <?php if ($stz == "America/New_York") echo "selected"; ?>>뉴욕 (EST)</option>
                    <option value="America/Los_Angeles" <?php if ($stz == "America/Los_Angeles") echo "selected"; ?>>LA (PDT)</option>
                    <option value="America/Chicago" <?php if ($stz == "America/Chicago") echo "selected"; ?>>시카고 (CDT)</option>
                    <option value="America/Toronto" <?php if ($stz == "America/Toronto") echo "selected"; ?>>토론토 (EDT)</option>
                    <option value="America/Vancouver" <?php if ($stz == "America/Vancouver") echo "selected"; ?>>밴쿠버 (PDT)</option>
                    <option value="America/Mexico_City" <?php if ($stz == "America/Mexico_City") echo "selected"; ?>>멕시코시티 (CST)</option>
                    <option value="America/Sao_Paulo" <?php if ($stz == "America/Sao_Paulo") echo "selected"; ?>>상파울루 (BRT)</option>
                    <option value="America/Buenos_Aires" <?php if ($stz == "America/Buenos_Aires") echo "selected"; ?>>부에노스아이레스 (ART)</option>
                    <option value="Africa/Cairo" <?php if ($stz == "Africa/Cairo") echo "selected"; ?>>카이로 (EET)</option>
                    <option value="Africa/Johannesburg" <?php if ($stz == "Africa/Johannesburg") echo "selected"; ?>>요하네스버그 (SAST)</option>
                    <option value="Africa/Lagos" <?php if ($stz == "Africa/Lagos") echo "selected"; ?>>라고스 (WAT)</option>
                    <option value="Pacific/Auckland" <?php if ($stz == "Pacific/Auckland") echo "selected"; ?>>오클랜드 (NZST)</option>
                    <option value="Pacific/Honolulu" <?php if ($stz == "Pacific/Honolulu") echo "selected"; ?>>호놀룰루 (HST)</option>
                </select>
                </td>
            </tr>
            <tr>
                <td><label for="date">■ 날짜/시간 : </label>&nbsp;<input type="date" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');?>" required></td>
                <td><label for="time">■ 시간 (시:분:초) : </label>&nbsp;<input type="text" id="time" name="time" placeholder="HH:MM:SS" value="<?php echo isset($_POST['time']) ? $_POST['time'] : date("H:i:s");?>" required></td>
                <script>
                    const timeInput = document.getElementById('time');
                        timeInput.addEventListener('input', function (e) {
                            let value = e.target.value.replace(/[^0-9]/g, '');  // 숫자 이외의 문자 제거
                            if (value.length >= 3 && value.length <= 4) {
                                value = value.slice(0, 2) + ':' + value.slice(2);
                            } else if (value.length >= 5 && value.length <= 6) {
                                value = value.slice(0, 2) + ':' + value.slice(2, 4) + ':' + value.slice(4);
                            }
                            e.target.value = value;
                        });
                    </script>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit2" value="유닉스타임으로 변환"></td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST['submit2'])) {
        $timezone = $_POST['timezone'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $dateTimeStr = $date . ' ' . $time;

        // DateTime 객체 생성 및 타임존 설정
        $dateTime = new DateTime($dateTimeStr, new DateTimeZone($timezone));
        $unixtime2 = $dateTime->getTimestamp();

        // 결과 출력
        echo "<h3>■ " . $timezones[$timezone] . "의 $dateTimeStr 를 유닉스타임으로 변환한 결과 </h3>";
        echo "<p> ■ 유닉스타임 : $unixtime2</p>";
    }
    ?> 
    </div>
    <br>
    <div class="container"`>
    <h3>■ 세계 시간 보기</h3>
    <!-- 날짜와 시간 입력 폼 -->
    <?php
        $stz2 = isset($_POST['timezone2']) ? $_POST['timezone2'] : '';
    ?>
    <form method="post" action="">
        <table>
            <tr>
                <td colspan="2"><label for="timezone2">■ 타임존 : </label>
                <select id="timezone2" name="timezone2" required>
                    <option value="Asia/Seoul" <?php if ($stz2 == "Asia/Seoul") echo "selected"; ?>>서울 (KST)</option>
                    <option value="Asia/Tokyo" <?php if ($stz2 == "Asia/Tokyo") echo "selected"; ?>>도쿄 (JST)</option>
                    <option value="Australia/Sydney" <?php if ($stz2 == "Australia/Sydney") echo "selected"; ?>>시드니 (AEST)</option>
                    <option value="Asia/Shanghai" <?php if ($stz2 == "Asia/Shanghai") echo "selected"; ?>>상하이 (CST)</option>
                    <option value="Asia/Singapore" <?php if ($stz2 == "Asia/Singapore") echo "selected"; ?>>싱가포르 (SGT)</option>
                    <option value="Asia/Ho_Chi_Minh" <?php if ($stz2 == "Asia/Ho_Chi_Minh") echo "selected"; ?>>호치민 (ICT)</option>
                    <option value="Asia/Bangkok" <?php if ($stz2 == "Asia/Bangkok") echo "selected"; ?>>방콕 (ICT)</option>
                    <option value="Asia/Dubai" <?php if ($stz2 == "Asia/Dubai") echo "selected"; ?>>두바이 (GST)</option>
                    <option value="Asia/Hong_Kong" <?php if ($stz2 == "Asia/Hong_Kong") echo "selected"; ?>>홍콩 (HKT)</option>
                    <option value="Asia/Kolkata" <?php if ($stz2 == "Asia/Kolkata") echo "selected"; ?>>콜카타 (IST)</option>
                    <option value="Europe/Berlin" <?php if ($stz2 == "Europe/Berlin") echo "selected"; ?>>베를린 (CEST)</option>
                    <option value="Europe/London" <?php if ($stz2 == "Europe/London") echo "selected"; ?>>런던 (GMT)</option>
                    <option value="Europe/Paris" <?php if ($stz2 == "Europe/Paris") echo "selected"; ?>>파리 (CEST)</option>
                    <option value="Europe/Moscow" <?php if ($stz2 == "Europe/Moscow") echo "selected"; ?>>모스크바 (MSK)</option>
                    <option value="Europe/Madrid" <?php if ($stz2 == "Europe/Madrid") echo "selected"; ?>>마드리드 (CEST)</option>
                    <option value="Europe/Rome" <?php if ($stz2 == "Europe/Rome") echo "selected"; ?>>로마 (CEST)</option>
                    <option value="Europe/Amsterdam" <?php if ($stz2 == "Europe/Amsterdam") echo "selected"; ?>>암스테르담 (CEST)</option>
                    <option value="Europe/Zurich" <?php if ($stz2 == "Europe/Zurich") echo "selected"; ?>>취리히 (CEST)</option>
                    <option value="America/New_York" <?php if ($stz2 == "America/New_York") echo "selected"; ?>>뉴욕 (EST)</option>
                    <option value="America/Los_Angeles" <?php if ($stz2 == "America/Los_Angeles") echo "selected"; ?>>LA (PDT)</option>
                    <option value="America/Chicago" <?php if ($stz2 == "America/Chicago") echo "selected"; ?>>시카고 (CDT)</option>
                    <option value="America/Toronto" <?php if ($stz2 == "America/Toronto") echo "selected"; ?>>토론토 (EDT)</option>
                    <option value="America/Vancouver" <?php if ($stz2 == "America/Vancouver") echo "selected"; ?>>밴쿠버 (PDT)</option>
                    <option value="America/Mexico_City" <?php if ($stz2 == "America/Mexico_City") echo "selected"; ?>>멕시코시티 (CST)</option>
                    <option value="America/Sao_Paulo" <?php if ($stz2 == "America/Sao_Paulo") echo "selected"; ?>>상파울루 (BRT)</option>
                    <option value="America/Buenos_Aires" <?php if ($stz2 == "America/Buenos_Aires") echo "selected"; ?>>부에노스아이레스 (ART)</option>
                    <option value="Africa/Cairo" <?php if ($stz2 == "Africa/Cairo") echo "selected"; ?>>카이로 (EET)</option>
                    <option value="Africa/Johannesburg" <?php if ($stz2 == "Africa/Johannesburg") echo "selected"; ?>>요하네스버그 (SAST)</option>
                    <option value="Africa/Lagos" <?php if ($stz2 == "Africa/Lagos") echo "selected"; ?>>라고스 (WAT)</option>
                    <option value="Pacific/Auckland" <?php if ($stz2 == "Pacific/Auckland") echo "selected"; ?>>오클랜드 (NZST)</option>
                    <option value="Pacific/Honolulu" <?php if ($stz2 == "Pacific/Honolulu") echo "selected"; ?>>호놀룰루 (HST)</option>
                </select>
                </td>
            </tr>
            <tr>
                <td><label for="date2">■ 날짜/시간 : </label>&nbsp;<input type="date" id="date2" name="date2" value="<?php echo isset($_POST['date2']) ? $_POST['date2'] : date('Y-m-d');?>" required></td>
                <td><label for="time2">■ 시간 (시:분:초) : </label>&nbsp;<input type="text" id="time2" name="time2" placeholder="HH:MM:SS" value="<?php echo isset($_POST['time2']) ? $_POST['time2'] : date("H:i:s");?>" required></td>
                <script>
                    const timeInput2 = document.getElementById('time2');
                        timeInput2.addEventListener('input', function (e) {
                            let value = e.target.value.replace(/[^0-9]/g, '');  // 숫자 이외의 문자 제거
                            if (value.length >= 3 && value.length <= 4) {
                                value = value.slice(0, 2) + ':' + value.slice(2);
                            } else if (value.length >= 5 && value.length <= 6) {
                                value = value.slice(0, 2) + ':' + value.slice(2, 4) + ':' + value.slice(4);
                            }
                            e.target.value = value;
                        });
                    </script>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit3" value="세계 시간 보기"></td>
            </tr>
        </table>
    </form>
    <br>
    <?php
    if (isset($_POST['submit3'])) {
        $timezone2 = $_POST['timezone2'];
        $date2 = $_POST['date2'];
        $time2 = $_POST['time2'];
        
        // 입력된 날짜와 시간으로 DateTime 객체 생성
        $inputDateTime = new DateTime("$date2 $time2", new DateTimeZone($timezone2));

        // 세계 주요 도시의 타임존 목록
        $timezones = [
            "Asia/Seoul" => "서울 (KST)",
            "Asia/Tokyo" => "도쿄 (JST)",
            "Australia/Sydney" => "시드니 (AEST)",
            "Asia/Shanghai" => "상하이 (CST)",
            "Asia/Singapore" => "싱가포르 (SGT)",
            "Asia/Ho_Chi_Minh" => "호치민 (ICT)",
            "Asia/Bangkok" => "방콕 (ICT)",
            "Asia/Dubai" => "두바이 (GST)",
            "Asia/Hong_Kong" => "홍콩 (HKT)",
            "Asia/Kolkata" => "콜카타 (IST)",        
            "Europe/Berlin" => "베를린 (CEST)",
            "Europe/London" => "런던 (GMT)",
            "Europe/Paris" => "파리 (CEST)",
            "Europe/Moscow" => "모스크바 (MSK)",
            "Europe/Madrid" => "마드리드 (CEST)",
            "Europe/Rome" => "로마 (CEST)",
            "Europe/Amsterdam" => "암스테르담 (CEST)",
            "Europe/Zurich" => "취리히 (CEST)",    
            "America/New_York" => "뉴욕 (EST)",
            "America/Los_Angeles" => "LA (PDT)",
            "America/Chicago" => "시카고 (CDT)",
            "America/Toronto" => "토론토 (EDT)",
            "America/Vancouver" => "밴쿠버 (PDT)",
            "America/Mexico_City" => "멕시코시티 (CST)",
            "America/Sao_Paulo" => "상파울루 (BRT)",
            "America/Buenos_Aires" => "부에노스아이레스 (ART)",    
            "Africa/Cairo" => "카이로 (EET)",
            "Africa/Johannesburg" => "요하네스버그 (SAST)",
            "Africa/Lagos" => "라고스 (WAT)",    
            "Pacific/Auckland" => "오클랜드 (NZST)",
            "Pacific/Honolulu" => "호놀룰루 (HST)"
        ];

        echo "<h3>■ $timezone2 시간 기준 세계 시간</h3>";
        echo "<table border='1'>";
        echo "<tr><th>도시</th><th>현재 시각</th></tr>";

        // 입력된 타임존 기준으로 다른 타임존의 시간을 변환 및 출력
        foreach ($timezones as $tz => $city) {
            $cityTime = clone $inputDateTime;
            $cityTime->setTimezone(new DateTimeZone($tz));
            echo "<tr><td>$city</td><td>" . $cityTime->format('Y-m-d H:i:s') . "</td></tr>";
        }

        echo "</table>";
    }
    ?>
</div>
