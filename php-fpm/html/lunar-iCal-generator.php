<?php
require_once '/usr/share/pear/KASI_Lunar.php';
require_once '/usr/share/pear/Lunar.php';

$lunar = new oops\Lunar;

function lunarYearHasLeapMonth($lunar, $year)
{
    for ($month = 1; $month <= 12; $month++) {
        $date = sprintf("%04d-%02d-%02d", $year, $month, 1);
        $normal = $lunar->tosolar($date);
        $leap = $lunar->tosolar($date, true);
        if ($normal->fmt !== $leap->fmt) {
            return true;
        }
    }
    return false;
}

function lunarMonthMayBeLeap($lunar, $lunarDate)
{
    $normal = $lunar->tosolar($lunarDate);
    $leap = $lunar->tosolar($lunarDate, true);
    return $normal->fmt !== $leap->fmt;
}

// 사용자가 입력한 값 (기본값 설정)
$lunarDate = isset($_POST['lunarDate']) ? $_POST['lunarDate'] : '1970-01-01';
$isLeapMonth = isset($_POST['isLeapMonth']) ? $_POST['isLeapMonth'] : 'no';
$title = isset($_POST['title']) ? mb_substr($_POST['title'], 0, 32) : '기념일(음력)의 제목을 입력하세요.';
$desc = isset($_POST['desc']) ? mb_substr($_POST['desc'], 0, 64) : '기념일(음력)의 설명을 입력하세요.';
$startYear = isset($_POST['startYear']) ? $_POST['startYear'] : 2025;
$endYear = isset($_POST['endYear']) ? $_POST['endYear'] : 2040;
$yearHasLeapMonth = false;
$inputMonthMayBeLeap = false;
$leapNotice = '';

// 입력된 날짜 분리
list($inputYear, $lunarMonth, $lunarDay) = explode('-', $lunarDate);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yearHasLeapMonth = lunarYearHasLeapMonth($lunar, (int)$inputYear);
    $inputMonthMayBeLeap = lunarMonthMayBeLeap($lunar, $lunarDate);

    if ($yearHasLeapMonth) {
        if ($inputMonthMayBeLeap) {
            $leapNotice = "입력한 연도 {$inputYear}에는 윤달이 있습니다. 입력하신 음력 월 {$lunarMonth}월은 윤달월일 수 있으니 윤달 여부를 확인하세요.";
        } else {
            $leapNotice = "입력한 연도 {$inputYear}에는 윤달이 있습니다. 입력하신 음력 월 {$lunarMonth}월은 윤달월이 아닙니다.";
        }
    } else {
        $leapNotice = "입력한 연도 {$inputYear}에는 윤달이 없습니다.";
    }

    // iCal 초기 설정
    $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nCALSCALE:GREGORIAN\r\n";
    
    for ($year = $startYear; $year <= $endYear; $year++) {
        // 각 연도에 맞게 음력 연도 업데이트
        $lunarDateFormatted = sprintf("%04d-%02d-%02d", $year, $lunarMonth, $lunarDay);

        // 몇 번째 기념일 인지 계산
        $formattedYear = (int)substr($lunarDateFormatted, 0, 4);
        $yearDifference = $formattedYear - (int)$inputYear;

        // 음력을 양력으로 변환 (윤달 여부에 따라 변환 옵션 설정)
        if ($isLeapMonth == 'yes')
            $result = $lunar->tosolar($lunarDateFormatted, true);
        else 
            $result = $lunar->tosolar($lunarDateFormatted);

        $dateSolar = $result->fmt;

        // iCal 형식으로 변환된 양력 날짜 추가
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "DTSTART:" . str_replace('-', '', $dateSolar) . "\r\n"; // 시간 부분을 제외한 날짜만 입력
        $ical .= "DTEND:" . str_replace('-', '', $dateSolar) . "\r\n";   // 시간 부분을 제외한 날짜만 입력
        if ($isLeapMonth === 'yes') {
            // 윤달일 경우
            $ical .= "SUMMARY:" . htmlspecialchars($title) . " (음력 " . $lunarDate . " / 윤달)\r\n";
            $ical .= "DESCRIPTION:" . htmlspecialchars($desc) . " (음력 " . $lunarDate . " / 윤달) " . $yearDifference . " 번째 기념일 입니다.\r\n";
        } else {
            // 평달일 경우
            $ical .= "SUMMARY:" . htmlspecialchars($title) . " (음력 " . $lunarDate . ")\r\n";
            $ical .= "DESCRIPTION:" .htmlspecialchars($desc) . " (음력 " . $lunarDate . ") " . $yearDifference . " 번째 기념일 입니다.\r\n";
        }
        $ical .= "BEGIN:VALARM\r\n";
        $ical .= "TRIGGER:-T15H\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder: Event tomorrow at 10 AM\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
    }
    $ical .= "END:VCALENDAR";
}

?>

    <h2 align="center">■ 음력 iCal 생성기</h2>
    <div class="container">
        <h3>■ 음력 날짜를 기준으로 시작 연도부터 마지막 연도까지 반복되는 iCal 일정을 생성합니다.</h3>
        <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;양력과 음력날짜의 상호 변환은 "날짜 계산기" 메뉴를 이용해주세요.</b><br>
        <table>
            <form method="post">
                <tr>
                    <td><label>음력 날짜 (YYYY-MM-DD): </label></td>
                    <td><input type="date" name="lunarDate" value="<?= htmlspecialchars($lunarDate) ?>"></td>
                </tr>
                <tr>
                    <td><label>윤달 여부: </label></td>
                    <td>
                        <label><input type="radio" name="isLeapMonth" value="no" <?= $isLeapMonth === 'no' ? 'checked' : '' ?>> 평달</label>
                        <label><input type="radio" name="isLeapMonth" value="yes" <?= $isLeapMonth === 'yes' ? 'checked' : '' ?>> 윤달</label>
                    </td>
                </tr>
                <tr>
                    <td><label>일정 제목(최대 32자): </label></td>
                    <td><input type="text" style="width:300px;" name="title" maxlength="32" value="<?= htmlspecialchars($title) ?>"></td>
                </tr>
                <tr>
                    <td><label>일정 내용(최대 64자): </label></td>
                    <td><input type="text" style="width:300px;" name="desc" maxlength="64" value="<?= htmlspecialchars($desc) ?>"></td>
                </tr>
                <tr>
                    <td><label>시작 연도: </label></td>
                    <td><input type="number" name="startYear" value="<?= htmlspecialchars($startYear) ?>"></td>
                </tr>
                <tr>
                    <td><label>마지막 연도: </label></td>
                    <td><input type="number" name="endYear" value="<?= htmlspecialchars($endYear) ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="iCal 생성"></td>
                </tr>
            </form>
        </table>

        <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
            <h3 style="color:red;">【iCal】</h3>
            <p style="color: blue; font-weight: bold;"><?php echo htmlspecialchars($leapNotice); ?></p>
            <textarea rows="20" cols="70"><?php echo htmlspecialchars($ical); ?></textarea>
        <?php 
        } ?>
    </div>
</body>
</html>
