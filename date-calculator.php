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