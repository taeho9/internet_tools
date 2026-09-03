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
$lunarDate = !empty($_POST['lunarDate']) ? $_POST['lunarDate'] : '1970-01-01';
$isLeapMonth = isset($_POST['isLeapMonth']) ? $_POST['isLeapMonth'] : 'no';
$title = isset($_POST['title']) ? mb_substr($_POST['title'], 0, 32) : '부모님 생신';
$desc = isset($_POST['desc']) ? mb_substr($_POST['desc'], 0, 64) : '매년 반복되는 음력 생신 일정';
$startYear = isset($_POST['startYear']) ? (int)$_POST['startYear'] : 2025;
$endYear = isset($_POST['endYear']) ? (int)$_POST['endYear'] : 2040;
$yearHasLeapMonth = false;
$inputMonthMayBeLeap = false;
$leapNotice = '';
$ical = '';

// 입력된 날짜 분리
$dateParts = explode('-', $lunarDate);
$inputYear = $dateParts[0] ?? '1970';
$lunarMonth = $dateParts[1] ?? '01';
$lunarDay = $dateParts[2] ?? '01';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yearHasLeapMonth = lunarYearHasLeapMonth($lunar, (int)$inputYear);
    $inputMonthMayBeLeap = lunarMonthMayBeLeap($lunar, $lunarDate);

    if ($yearHasLeapMonth) {
        if ($inputMonthMayBeLeap) {
            $leapNotice = "입력한 연도 {$inputYear}년에는 윤달이 있습니다. 입력하신 음력 월({$lunarMonth}월)은 윤달월일 수 있으니 윤달 여부를 확인하세요.";
        } else {
            $leapNotice = "입력한 연도 {$inputYear}년에는 윤달이 있습니다. 입력하신 음력 월({$lunarMonth}월)은 평달(일반달)입니다.";
        }
    } else {
        $leapNotice = "입력한 연도 {$inputYear}년에는 윤달이 없습니다.";
    }

    // iCal 초기 설정
    $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//taeho internet tools//Lunar iCal Generator//KO\r\nCALSCALE:GREGORIAN\r\n";
    
    for ($year = $startYear; $year <= $endYear; $year++) {
        // 각 연도에 맞게 음력 연도 업데이트
        $lunarDateFormatted = sprintf("%04d-%02d-%02d", $year, $lunarMonth, $lunarDay);

        // 몇 번째 기념일 인지 계산
        $formattedYear = (int)substr($lunarDateFormatted, 0, 4);
        $yearDifference = $formattedYear - (int)$inputYear;

        // 음력을 양력으로 변환
        if ($isLeapMonth == 'yes') {
            $result = $lunar->tosolar($lunarDateFormatted, true);
        } else {
            $result = $lunar->tosolar($lunarDateFormatted);
        }

        $dateSolar = $result->fmt;
        $dateSolarClean = str_replace('-', '', $dateSolar);

        // iCal 형식으로 변환된 양력 날짜 추가
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "DTSTART;VALUE=DATE:" . $dateSolarClean . "\r\n";
        $ical .= "DTEND;VALUE=DATE:" . $dateSolarClean . "\r\n";
        if ($isLeapMonth === 'yes') {
            $ical .= "SUMMARY:" . htmlspecialchars($title) . " (음력 " . $lunarDate . " / 윤달)\r\n";
            $ical .= "DESCRIPTION:" . htmlspecialchars($desc) . " (음력 " . $lunarDate . " / 윤달) " . $yearDifference . "번째 기념일\r\n";
        } else {
            $ical .= "SUMMARY:" . htmlspecialchars($title) . " (음력 " . $lunarDate . ")\r\n";
            $ical .= "DESCRIPTION:" . htmlspecialchars($desc) . " (음력 " . $lunarDate . ") " . $yearDifference . "번째 기념일\r\n";
        }
        $ical .= "BEGIN:VALARM\r\n";
        $ical .= "TRIGGER:-P1D\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder: " . htmlspecialchars($title) . "\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
    }
    $ical .= "END:VCALENDAR";
}
?>

<div class="page-hero">
    <h1 class="page-title">
        <span class="brand-icon" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
        음력 iCal 생성기
    </h1>
    <p class="page-subtitle">
        음력 기념일(생신, 제사 등)을 기준으로 시작 연도부터 종료 연도까지 매년 해당하는 양력 날짜를 자동 계산하여 Google/Apple/Outlook 캘린더 등록용 iCal(.ics) 일정을 생성합니다.
    </p>
</div>

<div class="alert-box alert-info">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="16" x2="12" y2="12"></line>
        <line x1="12" y1="8" x2="12.01" y2="8"></line>
    </svg>
    <div>
        단순한 특정 날짜의 양력/음력 1:1 변환이 필요하신 경우 상단 메뉴의 <strong><a href="?page=date-calculator" style="color: var(--primary); text-decoration: underline;">날짜 계산기</a></strong>를 이용해 주세요.
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            일정 정보 및 반복 기간 설정
        </h2>
    </div>

    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="lunarDate">음력 기준 날짜</label>
                <input type="date" class="form-input" id="lunarDate" name="lunarDate" value="<?= htmlspecialchars($lunarDate) ?>" required>
                <span class="form-helper">기념일의 기준이 되는 최초 음력 날짜를 선택하세요.</span>
            </div>

            <div class="form-group">
                <label class="form-label">윤달 여부</label>
                <div class="chip-group" style="margin-top: 0.25rem;">
                    <label class="chip-label">
                        <input type="radio" name="isLeapMonth" value="no" <?= $isLeapMonth === 'no' ? 'checked' : '' ?>> 평달
                    </label>
                    <label class="chip-label">
                        <input type="radio" name="isLeapMonth" value="yes" <?= $isLeapMonth === 'yes' ? 'checked' : '' ?>> 윤달
                    </label>
                </div>
                <span class="form-helper">입력한 음력 월이 윤달에 해당하는지 지정합니다.</span>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="title">일정 제목 (최대 32자)</label>
                <input type="text" class="form-input" id="title" name="title" maxlength="32" value="<?= htmlspecialchars($title) ?>" placeholder="예: 어머님 음력 생신" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="desc">일정 설명 / 메모 (최대 64자)</label>
                <input type="text" class="form-input" id="desc" name="desc" maxlength="64" value="<?= htmlspecialchars($desc) ?>" placeholder="예: 가족 모임 및 식사">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="startYear">생성 시작 연도</label>
                <input type="number" class="form-input" id="startYear" name="startYear" value="<?= htmlspecialchars($startYear) ?>" min="1970" max="2050" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="endYear">생성 종료 연도</label>
                <input type="number" class="form-input" id="endYear" name="endYear" value="<?= htmlspecialchars($endYear) ?>" min="1970" max="2050" required>
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                iCal 일정 생성하기
            </button>
        </div>
    </form>
</div>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($ical)): ?>
<div class="result-card">
    <div class="result-header">
        <div class="result-title">
            <span class="badge badge-success">생성 완료</span>
            iCal (.ics) 일정 코드
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn-copy" onclick="copyToClipboard('ical-content', this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                복사하기
            </button>
            <button type="button" class="btn btn-sm btn-primary" onclick="downloadIcs('ical-content', 'lunar-calendar.ics')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                .ics 파일 다운로드
            </button>
        </div>
    </div>

    <div class="result-body">
        <?php if (!empty($leapNotice)): ?>
        <div class="alert-box alert-warning" style="margin-bottom: 1rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            <div><?= htmlspecialchars($leapNotice) ?></div>
        </div>
        <?php endif; ?>

        <textarea id="ical-content" class="form-input" rows="12" readonly style="font-family: 'SFMono-Regular', Consolas, monospace; font-size: 0.85rem; line-height: 1.5; background: var(--bg-input);"><?= htmlspecialchars($ical) ?></textarea>
        
        <p style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.75rem;">
            * <strong>.ics 파일 다운로드</strong> 버튼을 눌러 다운로드한 파일을 Google 캘린더(설정 > 캘린더 가져오기)나 Apple 캘린더에 드래그하여 등록하시면 모든 연도의 음력 기념일이 자동으로 등록됩니다.
        </p>
    </div>
</div>
<?php endif; ?>
