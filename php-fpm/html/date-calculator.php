<?php
$koreanDows = [
    'Sunday' => '일요일',
    'Monday' => '월요일',
    'Tuesday' => '화요일',
    'Wednesday' => '수요일',
    'Thursday' => '목요일',
    'Friday' => '금요일',
    'Saturday' => '토요일'
];
?>

<div class="page-hero">
    <h1 class="page-title">
        <span class="brand-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </span>
        날짜 & 음력 계산기
    </h1>
    <p class="page-subtitle">
        두 날짜 사이의 총 일수 차이 계산, 특정 날짜 기준 D-Day/가감 연산, 그리고 한국천문연구원 기준 양력과 음력(윤달 여부 포함) 상호 변환을 지원합니다.
    </p>
</div>

<!-- Section 1: Diff between two dates -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-primary">기능 1</span>
                두 날짜 간의 일수 차이 계산 (D-Day)
            </h2>
            <p class="card-desc">시작 날짜부터 종료 날짜까지 총 며칠이 지났는지 또는 며칠이 남았는지 정확히 계산합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="date1">첫 번째 기준 날짜 (시작일)</label>
                <input type="date" class="form-input" id="date1" name="date1" value="<?= isset($_POST['date1']) ? htmlspecialchars($_POST['date1']) : '1970-01-01' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="date2">두 번째 기준 날짜 (종료일)</label>
                <input type="date" class="form-input" id="date2" name="date2" value="<?= isset($_POST['date2']) ? htmlspecialchars($_POST['date2']) : date('Y-m-d') ?>" required>
            </div>
        </div>
        <button type="submit" name="submit1" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            날짜 차이 계산하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit1'])) {
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];

        try {
            $d1 = new DateTime($date1);
            $d2 = new DateTime($date2);
            $diff = $d1->diff($d2);
            $formattedDays = number_format($diff->days);
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">계산 성공</span>
                        날짜 간격 산출 결과
                    </div>
                </div>
                <div class="result-body">
                    <div style="font-size: 1.15rem; color: var(--text-main);">
                        <strong><?= htmlspecialchars($date1) ?></strong> 부터 <strong><?= htmlspecialchars($date2) ?></strong> 까지의 차이는 
                        <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary);" id="res-diff-days"><?= $formattedDays ?></span> 일 입니다.
                        <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('res-diff-days', this)">복사</button>
                    </div>
                </div>
            </div>
            <?php
        } catch (Exception $e) {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 날짜 형식을 입력해 주세요.</div>';
        }
    }
    ?>
</div>

<!-- Section 2: Add or Subtract days -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-info">기능 2</span>
                날짜에 일수 더하기 / 빼기
            </h2>
            <p class="card-desc">특정 날짜로부터 N일 후 또는 N일 전의 날짜와 요일을 계산합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="add_date">기준 날짜</label>
                <input type="date" class="form-input" id="add_date" name="add_date" value="<?= isset($_POST['add_date']) ? htmlspecialchars($_POST['add_date']) : date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="days_to_add">가감할 일수 (일)</label>
                <input type="number" class="form-input" id="days_to_add" name="days_to_add" value="<?= isset($_POST['days_to_add']) ? (int)$_POST['days_to_add'] : 100 ?>" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">계산 방향</label>
                <div class="chip-group" style="margin-top: 0.25rem;">
                    <label class="chip-label">
                        <input type="radio" id="after" name="direction" value="after" <?= !isset($_POST['direction']) || $_POST['direction'] === 'after' ? 'checked' : '' ?>>
                        N일 후 (+)
                    </label>
                    <label class="chip-label">
                        <input type="radio" id="before" name="direction" value="before" <?= isset($_POST['direction']) && $_POST['direction'] === 'before' ? 'checked' : '' ?>>
                        N일 전 (-)
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" name="submit2" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 5v14M5 12h14"></path></svg>
            날짜 가감 계산하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
        $add_date = $_POST['add_date'];
        $days_to_add = (int)$_POST['days_to_add'];
        $direction = $_POST['direction'];

        try {
            $date_to_add = new DateTime($add_date);
            if ($direction === 'after') {
                $date_to_add->modify("+{$days_to_add} days");
            } else {
                $date_to_add->modify("-{$days_to_add} days");
            }

            $result_date = $date_to_add->format('Y-m-d');
            $day_of_week = $date_to_add->format('l');
            $day_of_week_ko = $koreanDows[$day_of_week] ?? $day_of_week;
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">계산 성공</span>
                        날짜 가감 계산 결과
                    </div>
                </div>
                <div class="result-body">
                    <div style="font-size: 1.15rem; color: var(--text-main);">
                        <strong><?= htmlspecialchars($add_date) ?></strong> 기준 <?= number_format($days_to_add) ?>일 <?= $direction === 'after' ? '후' : '전' ?> 날짜는
                        <strong style="font-size: 1.35rem; color: var(--primary);" id="res-calc-date"><?= $result_date ?> (<?= $day_of_week_ko ?>)</strong> 입니다.
                        <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('res-calc-date', this)">복사</button>
                    </div>
                </div>
            </div>
            <?php
        } catch (Exception $e) {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 날짜와 일수를 입력해 주세요.</div>';
        }
    }
    ?>
</div>

<!-- Section 3: Solar & Lunar Conversion -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-warning">기능 3</span>
                양력 ↔ 음력 날짜 상호 변환
            </h2>
            <p class="card-desc">한국천문연구원 데이터를 기반으로 양력을 음력으로, 또는 음력 날짜를 양력으로 상호 정밀 변환합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="date">변환할 기준 날짜</label>
                <input type="date" class="form-input" id="date" name="date" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">달력 유형</label>
                <div class="chip-group" style="margin-top: 0.25rem;">
                    <label class="chip-label">
                        <input type="radio" id="solar" name="calendar_type" value="solar" <?= !isset($_POST['calendar_type']) || $_POST['calendar_type'] === 'solar' ? 'checked' : '' ?> onclick="toggleLunarOptions()">
                        양력 &rarr; 음력
                    </label>
                    <label class="chip-label">
                        <input type="radio" id="lunar" name="calendar_type" value="lunar" <?= isset($_POST['calendar_type']) && $_POST['calendar_type'] === 'lunar' ? 'checked' : '' ?> onclick="toggleLunarOptions()">
                        음력 &rarr; 양력
                    </label>
                </div>
            </div>
            <div class="form-group" id="leap_month_wrapper" style="display: <?= isset($_POST['calendar_type']) && $_POST['calendar_type'] === 'lunar' ? 'flex' : 'none' ?>;">
                <label class="form-label" for="leap_month">음력 윤달 여부</label>
                <select class="form-select" id="leap_month" name="leap_month">
                    <option value="false" <?= !isset($_POST['leap_month']) || $_POST['leap_month'] === 'false' ? 'selected' : '' ?>>평달 (일반 음력월)</option>
                    <option value="true" <?= isset($_POST['leap_month']) && $_POST['leap_month'] === 'true' ? 'selected' : '' ?>>윤달 (윤달 음력월)</option>
                </select>
            </div>
        </div>

        <button type="submit" name="submit3" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M7 16V4M7 4L3 8M7 4L11 8M17 8V20M17 20L21 16M17 20L13 16"></path></svg>
            날짜 상호 변환하기
        </button>
    </form>

    <script>
        function toggleLunarOptions() {
            const leapMonthWrapper = document.getElementById('leap_month_wrapper');
            const lunarRadio = document.getElementById('lunar');
            if (lunarRadio && lunarRadio.checked) {
                leapMonthWrapper.style.display = 'flex';
            } else if (leapMonthWrapper) {
                leapMonthWrapper.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleLunarOptions);
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit3'])) {
        require_once '/usr/share/pear/KASI_Lunar.php';
        require_once '/usr/share/pear/Lunar.php';

        $lunar = new oops\Lunar;
        $input_date = $_POST['date'];
        $calendar_type = $_POST['calendar_type'] ?? 'solar';
        $leap_month = $_POST['leap_month'] ?? 'false';

        $timestamp = strtotime($input_date);
        $dow = date('l', $timestamp);
        $dow_ko = $koreanDows[$dow] ?? $dow;
        ?>
        <div class="result-card">
            <div class="result-header">
                <div class="result-title">
                    <span class="badge badge-success">변환 완료</span>
                    양력 / 음력 상호 변환 결과
                </div>
            </div>
            <div class="result-body" style="padding: 0;">
                <table class="modern-table">
                    <tbody>
                    <?php
                    if ($calendar_type === 'solar') {
                        $result = $lunar->tolunar($input_date);
                        $dateLunar = $result->fmt;
                        $resultCheck = $lunar->tosolar($dateLunar);
                        $isLeap = ($input_date !== $resultCheck->fmt);
                        ?>
                        <tr>
                            <th style="width: 220px;">입력 날짜 (양력)</th>
                            <td><?= htmlspecialchars($input_date) ?> (<?= $dow_ko ?>)</td>
                        </tr>
                        <tr>
                            <th>변환된 음력 날짜</th>
                            <td>
                                <strong style="font-size: 1.15rem; color: var(--primary);" id="res-lunar-date"><?= $dateLunar ?><?= $isLeap ? ' (윤달)' : ' (평달)' ?></strong>
                                <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('res-lunar-date', this)">복사</button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        if ($leap_month === 'true') {
                            $result = $lunar->tosolar($input_date, true);
                        } else {
                            $result = $lunar->tosolar($input_date);
                        }
                        $solarTimestamp = strtotime($result->fmt);
                        $solarDow = date('l', $solarTimestamp);
                        $solarDow_ko = $koreanDows[$solarDow] ?? $solarDow;
                        ?>
                        <tr>
                            <th style="width: 220px;">입력 날짜 (음력)</th>
                            <td><?= htmlspecialchars($input_date) ?> <?= $leap_month === 'true' ? '(윤달)' : '(평달)' ?></td>
                        </tr>
                        <tr>
                            <th>변환된 양력 날짜</th>
                            <td>
                                <strong style="font-size: 1.15rem; color: var(--primary);" id="res-solar-date"><?= $result->fmt ?> (<?= $solarDow_ko ?>)</strong>
                                <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('res-solar-date', this)">복사</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>