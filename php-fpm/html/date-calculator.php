<h2 align="center">■ 날짜 계산기</h2>
    <div class="container"`>
    <h3>■ 두 날짜 간의 차이 계산</h3>

    <!-- 날짜 입력 폼 -->
    <form method="post" action="">
    <table>
            <tr>
                <td><label for="date1">■ 첫 번째 날짜 (YYYY-MM-DD) 에서 : </label></td>
                <td><input type="date" id="date1" name="date1" value="<?php echo isset($_POST['date1']) ? $_POST['date1'] : '1970-01-01';?>" required></td>
            </tr>
            <tr>
                <td><label for="date2">■ 두 번째 날짜 (YYYY-MM-DD) 까지 : </label></td>
                <td><input type="date" id="date2" name="date2" value="<?php echo isset($_POST['date2']) ? $_POST['date2'] : date('Y-m-d');?>" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit1" value="날짜 차이 계산"></td>
            </tr>
    </table>
    </form>

    <?php
    // 첫 번째 폼이 제출되었는지 확인
    if (isset($_POST['submit1'])) {
        // 사용자로부터 입력받은 두 날짜
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];

        // DateTime 객체로 변환
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);

        // 날짜 차이 계산
        $diff = $d1->diff($d2);
        // 결과 출력
        echo "<h3>■ 날짜 차이 계산 결과</h3>";
        echo "<table>";
        echo "<tr><td>$date1 에서 $date2 까지는 " . number_format($diff->days) . " 일 입니다.</td></tr>";
        echo "</table>";
    }
    ?>
    </div>
    <br>
    <!-- 날짜에 날짜 수 더하기 기능 -->
    <div class="container"`>
    <h3>■ 날짜에 날짜 수 더하기/빼기</h3>

    <!-- 날짜, 날짜 수, 이후/이전 선택 입력 폼 -->
    <table>
        <form method="post" action="">
            <tr>
                <td><label for="add_date">■ 기준 날짜 (YYYY-MM-DD) : </label></td>
                <td><input type="date" id="add_date" name="add_date" value="<?php echo isset($_POST['add_date']) ? $_POST['add_date'] : '1970-01-01';?>" required></td>
            </tr>
            <tr>
                <td><label for="days_to_add">■ 날짜 수 (일) : </label></td>
                <td><input type="number" id="days_to_add" name="days_to_add" value="<?php echo isset($_POST['days_to_add']) ? $_POST['days_to_add'] : 0;?>" required>일</input> </td>
            </tr>
            <tr>
                <td>■ 계산 방향 :</td>
                <td>
                    <input type="radio" id="after" name="direction" value="after" checked>
                    <label for="after">후</label>
                    <input type="radio" id="before" name="direction" value="before">
                    <label for="before">전</label>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit2" value="날짜 계산"></td>
            </tr>
        </form>
    </table>
    <?php
    // 두 번째 폼이 제출되었는지 확인
    if (isset($_POST['submit2'])) {
        // 사용자로부터 입력받은 날짜, 날짜 수, 방향
        $add_date = $_POST['add_date'];
        $days_to_add = $_POST['days_to_add'];
        $direction = $_POST['direction'];

        // DateTime 객체로 변환
        $date_to_add = new DateTime($add_date);

        // 날짜 계산 (이후 또는 이전)
        if ($direction == 'after') {
            $date_to_add->modify("+$days_to_add days");
        } else {
            $date_to_add->modify("-$days_to_add days");
        }

        // 결과 출력 (날짜와 요일 포함)
        $result_date = $date_to_add->format('Y-m-d'); // 더한/뺀 날짜
        $day_of_week = $date_to_add->format('l'); // 요일 (영어)

        // 결과 출력
        echo "<h3>■ 날짜 계산 결과</h3>";
        echo "<table>";
        echo "<tr><td>$add_date 에 $days_to_add 일을 " . ($direction == 'after' ? '더한' : '뺀') . " 날짜는 $result_date ($day_of_week) 입니다.</td></tr>";
        echo "</table>";
    }
    ?>
    </div>
    <br>
    <div class="container">
    <!-- 제대로 동작하기 위해서는 Lunar Calendar 라이브러리 사용해야 함 -->
    <h3>■ 양력과 음력 날짜 변환 (양력 ↔ 음력)</h3>

    <!-- 날짜, 양력/음력 선택 입력 폼 -->
    <table>
        <form method="post" action="">
            <tr>
                <td><label for="date">■ 날짜 (YYYY-MM-DD) : </label></td>
                <td><input type="date" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); ?>" required></td>
            </tr>
            <tr>
                <td>■ 날짜 유형 :</td>
                <td>
                    <input type="radio" id="solar" name="calendar_type" value="solar" <?php if ($_POST['calendar_type'] == 'solar' || $_POST['calendar_type'] == '') echo 'checked'; ?> onclick="toggleLunarOptions()">
                    <label for="solar">양력</label>
                    <input type="radio" id="lunar" name="calendar_type" value="lunar" <?php if ($_POST['calendar_type'] == 'lunar') echo 'checked'; ?> onclick="toggleLunarOptions()">
                    <label for="lunar">음력</label>
                </td>
            </tr>
            <tr id="leap_month_row" style="display: none;">
            <td><label for="leap_month">■ 윤달 여부 :</label></td>
            <td>
                <select id="leap_month" name="leap_month">
                    <option value="false" <?php if ($_POST['leap_month'] == "false" || $_POST['leap_month'] == "") echo "selected"; ?>>평달</option>
                    <option value="true" <?php if ($_POST['leap_month'] == "true") echo "selected"; ?>>윤달</option>
                </select>
            </td>
        </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit3" value="날짜 변환"></td>
            </tr>
        </form>
    </table>
    <script>
        function toggleLunarOptions() {
            const leapMonthRow = document.getElementById('leap_month_row');
            const lunarRadio = document.getElementById('lunar');

            // 음력이 선택되었을 때만 윤달 선택박스를 보여준다
            if (lunarRadio.checked) {
                leapMonthRow.style.display = 'table-row';
            } else {
                leapMonthRow.style.display = 'none';
            }
        }

        // 페이지 로드 시 음력 선택 여부에 따라 윤달 선택박스를 초기화
        window.onload = toggleLunarOptions;
    </script>
    <?php
    // 폼이 제출되었는지 확인
    if (isset($_POST['submit3'])) {
        require_once '/usr/share/pear/KASI_Lunar.php';
        require_once '/usr/share/pear/Lunar.php';

        $lunar = new oops\Lunar;
        
        // 사용자로부터 입력받은 날짜와 달력 유형
        $input_date = $_POST['date'];
        $calendar_type = $_POST['calendar_type'];
        $leap_month = $_POST['leap_month'];

        $timestamp = strtotime($input_date);
        $dow = date('l', $timestamp);

        // 양력 ↔ 음력 변환 로직 
        //(https://www.pabburi.co.kr/content/php/%EC%96%91%EB%A0%A5-%EC%9D%8C%EB%A0%A5-%EC%9D%8C%EB%A0%A5-%EC%96%91%EB%A0%A5-%EB%B3%80%EA%B2%BD-pear/ 에서 소개하는 양/음력 변환 라이브러리 설치해야 함)
        if ($calendar_type == 'solar') {
            // 입력한 날짜가 양력일 때, 음력으로 변환
            $result   = $lunar->tolunar($input_date);  // 양력 -> 음력
            $dateLunar  = $result->fmt;
    
            $result   = $lunar->tosolar ($dateLunar);  // 음력 -> 양력
            $dateSolar  = $result->fmt;

            # 입력받은 양력 날짜와 음력으로 변환한 후 다시 양력으로 변환한 날짜가 다르면 윤달인것이다.
            $eqName   = ( $input_date == $dateSolar ) ? 'EQ':'NotEQ';
            if ( $eqName == 'NotEQ' ) {
                // 윤달이면 윤달임을 출력
                ?>
                    <h3>■ 날짜 변환 결과</h3>
                    <table>
                    <tr><td>입력한 날짜(양력) : </td><td><?php echo $input_date . " (" . $dow . ")"; ?></tr>
                    <tr><td>음력 날짜 : </td><td><?php echo $dateLunar; ?>&nbsp;(윤달)</td></tr>
                    </table>
                <?php
              } else {
                ?>
                    <h3>■ 날짜 변환 결과</h3>
                    <table>
                    <tr><td>입력한 날짜(양력) : </td><td><?php echo $input_date . " (" . $dow . ")"; ?></tr>
                    <tr><td>음력 날짜 : </td><td><?php echo $dateLunar; ?></td></tr>
                    </table>
                <?php
              }
        } else {
            // 입력한 날짜가 음력일 때, 양력으로 변환
            if ($leap_month == "true")
                $result   = $lunar->tosolar($input_date, true);
            else 
                $result   = $lunar->tosolar($input_date);
            ?>
                <h3>■ 날짜 변환 결과</h3>
                <table>
                <tr><td>입력한 날짜(음력) : </td><td><?php echo $input_date; if ($leap_month == "true") echo " (윤달)"; ?></tr>
                <?php
                    $timestamp = strtotime($result->fmt);
                    $dow = date('l', $timestamp);
                ?>
                <tr><td>양력 날짜 : </td><td><?php echo $result->fmt . " (" . $dow . ")"; ?></td></tr>
                </table>
            <?php

        }
    }
    ?>
</div>