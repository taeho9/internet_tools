<?php
// 세계 주요 도시 타임존 목록 공통 정의
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
    "Europe/Berlin" => "베를린 (CEST/CET)",
    "Europe/London" => "런던 (GMT/BST)",
    "Europe/Paris" => "파리 (CEST/CET)",
    "Europe/Moscow" => "모스크바 (MSK)",
    "Europe/Madrid" => "마드리드 (CEST/CET)",
    "Europe/Rome" => "로마 (CEST/CET)",
    "Europe/Amsterdam" => "암스테르담 (CEST/CET)",
    "Europe/Zurich" => "취리히 (CEST/CET)",    
    "America/New_York" => "뉴욕 (EST/EDT)",
    "America/Los_Angeles" => "LA (PST/PDT)",
    "America/Chicago" => "시카고 (CST/CDT)",
    "America/Toronto" => "토론토 (EST/EDT)",
    "America/Vancouver" => "밴쿠버 (PST/PDT)",
    "America/Mexico_City" => "멕시코시티 (CST)",
    "America/Sao_Paulo" => "상파울루 (BRT)",
    "America/Buenos_Aires" => "부에노스아이레스 (ART)",    
    "Africa/Cairo" => "카이로 (EET)",
    "Africa/Johannesburg" => "요하네스버그 (SAST)",
    "Africa/Lagos" => "라고스 (WAT)",    
    "Pacific/Auckland" => "오클랜드 (NZST/NZDT)",
    "Pacific/Honolulu" => "호놀룰루 (HST)"
];
?>

<div class="page-hero">
    <h1 class="page-title">
        <span class="brand-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </span>
        유닉스시간 & 세계시간 변환기
    </h1>
    <p class="page-subtitle">
        유닉스 타임스탬프(Unixtime)를 표준 날짜 및 각국 타임존 시간으로 변환하고, 특정 일시를 유닉스타임으로 상호 변환하며, 전 세계 주요 30개 도시의 현지 시간을 일괄 계산합니다.
    </p>
</div>

<!-- Section 1: Unixtime to Date & World Times -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-primary">기능 1</span>
                유닉스시간(Unixtime) &rarr; 날짜/시간 변환
            </h2>
            <p class="card-desc">10자리 또는 초 단위 유닉스 타임스탬프를 표준 일시 및 주요 도시 시간으로 변환합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-group" style="max-width: 460px;">
            <label class="form-label" for="unixtime1">유닉스 타임스탬프 (Unixtime)</label>
            <div style="display: flex; gap: 0.5rem;">
                <input type="number" class="form-input" id="unixtime1" name="unixtime1" value="<?= isset($_POST['unixtime1']) ? htmlspecialchars($_POST['unixtime1']) : time() ?>" required>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('unixtime1').value = Math.floor(Date.now() / 1000);">현재 시각</button>
            </div>
            <span class="form-helper">현재 실시간 타임스탬프: <code><?= time() ?></code></span>
        </div>
        <button type="submit" name="submit1" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            날짜/시간으로 변환하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit1'])) {
        $unixtime1 = (int)$_POST['unixtime1'];
        $utcDate = gmdate("Y-m-d H:i:s", $unixtime1);
        ?>
        <div class="result-card">
            <div class="result-header">
                <div class="result-title">
                    <span class="badge badge-success">변환 성공</span>
                    Unixtime (<?= $unixtime1 ?>) 변환 결과
                </div>
                <div>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">UTC 기준: <code><?= $utcDate ?> UTC</code></span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">도시 및 타임존</th>
                            <th>변환된 일시 (YYYY-MM-DD HH:MM:SS)</th>
                            <th style="width: 90px; text-align: right;">복사</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $idx = 0;
                        foreach ($timezones as $timezone => $label) {
                            $idx++;
                            $dateTime = new DateTime("@$unixtime1");
                            $dateTime->setTimezone(new DateTimeZone($timezone));
                            $formatted = $dateTime->format("Y-m-d H:i:s");
                            $isKST = ($timezone === "Asia/Seoul");
                            ?>
                            <tr style="<?= $isKST ? 'background-color: var(--primary-light); font-weight: 600;' : '' ?>">
                                <td>
                                    <?= htmlspecialchars($label) ?>
                                    <?php if ($isKST): ?>
                                        <span class="badge badge-primary" style="margin-left: 0.35rem;">기준</span>
                                    <?php endif; ?>
                                </td>
                                <td><code id="tz-res-<?= $idx ?>" style="font-size: 0.92rem;"><?= $formatted ?></code></td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('tz-res-<?= $idx ?>', this)">복사</button>
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

<!-- Section 2: Date & Time to Unixtime -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-info">기능 2</span>
                날짜와 시간 &rarr; 유닉스타임 변환
            </h2>
            <p class="card-desc">지정한 타임존의 날짜와 시간을 입력하면 해당하는 10자리 유닉스 타임스탬프로 환산합니다.</p>
        </div>
    </div>

    <?php $stz = isset($_POST['timezone']) ? $_POST['timezone'] : 'Asia/Seoul'; ?>
    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="timezone">기준 타임존</label>
                <select class="form-select" id="timezone" name="timezone" required>
                    <?php foreach ($timezones as $tzKey => $tzName): ?>
                        <option value="<?= $tzKey ?>" <?= $stz === $tzKey ? 'selected' : '' ?>><?= htmlspecialchars($tzName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="date">날짜 (YYYY-MM-DD)</label>
                <input type="date" class="form-input" id="date" name="date" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="time">시간 (시:분:초)</label>
                <input type="text" class="form-input" id="time" name="time" placeholder="HH:MM:SS" value="<?= isset($_POST['time']) ? htmlspecialchars($_POST['time']) : date('H:i:s') ?>" required>
            </div>
        </div>

        <button type="submit" name="submit2" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
            유닉스타임으로 변환하기
        </button>
    </form>

    <script>
        const timeInput = document.getElementById('time');
        if (timeInput) {
            timeInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length >= 3 && value.length <= 4) {
                    value = value.slice(0, 2) + ':' + value.slice(2);
                } else if (value.length >= 5 && value.length <= 6) {
                    value = value.slice(0, 2) + ':' + value.slice(2, 4) + ':' + value.slice(4);
                }
                e.target.value = value;
            });
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
        $timezone = $_POST['timezone'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $dateTimeStr = $date . ' ' . $time;

        try {
            $dateTime = new DateTime($dateTimeStr, new DateTimeZone($timezone));
            $unixtime2 = $dateTime->getTimestamp();
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">변환 성공</span>
                        유닉스타임 산출 결과
                    </div>
                </div>
                <div class="result-body">
                    <div style="font-size: 1.05rem; color: var(--text-main); margin-bottom: 0.5rem;">
                        <strong><?= htmlspecialchars($timezones[$timezone] ?? $timezone) ?></strong> 기준 일시: <code><?= htmlspecialchars($dateTimeStr) ?></code>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 1.6rem; font-weight: 800; color: var(--primary);" id="res-unixtime-val"><?= $unixtime2 ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('res-unixtime-val', this)">복사</button>
                    </div>
                </div>
            </div>
            <?php
        } catch (Exception $e) {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 날짜와 시간(HH:MM:SS)을 입력해 주세요.</div>';
        }
    }
    ?>
</div>

<!-- Section 3: World Time View based on specified time -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-warning">기능 3</span>
                지정 일시 기준 전 세계 시간 비교
            </h2>
            <p class="card-desc">특정 도시의 날짜/시간을 기준으로 전 세계 30개 주요 도시의 동시각 현지 시간을 즉시 비교합니다.</p>
        </div>
    </div>

    <?php $stz2 = isset($_POST['timezone2']) ? $_POST['timezone2'] : 'Asia/Seoul'; ?>
    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="timezone2">기준 타임존</label>
                <select class="form-select" id="timezone2" name="timezone2" required>
                    <?php foreach ($timezones as $tzKey => $tzName): ?>
                        <option value="<?= $tzKey ?>" <?= $stz2 === $tzKey ? 'selected' : '' ?>><?= htmlspecialchars($tzName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="date2">기준 날짜</label>
                <input type="date" class="form-input" id="date2" name="date2" value="<?= isset($_POST['date2']) ? htmlspecialchars($_POST['date2']) : date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="time2">기준 시간 (시:분:초)</label>
                <input type="text" class="form-input" id="time2" name="time2" placeholder="HH:MM:SS" value="<?= isset($_POST['time2']) ? htmlspecialchars($_POST['time2']) : date('H:i:s') ?>" required>
            </div>
        </div>

        <button type="submit" name="submit3" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            세계 시간 비교하기
        </button>
    </form>

    <script>
        const timeInput2 = document.getElementById('time2');
        if (timeInput2) {
            timeInput2.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length >= 3 && value.length <= 4) {
                    value = value.slice(0, 2) + ':' + value.slice(2);
                } else if (value.length >= 5 && value.length <= 6) {
                    value = value.slice(0, 2) + ':' + value.slice(2, 4) + ':' + value.slice(4);
                }
                e.target.value = value;
            });
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit3'])) {
        $timezone2 = $_POST['timezone2'];
        $date2 = $_POST['date2'];
        $time2 = $_POST['time2'];

        try {
            $inputDateTime = new DateTime("$date2 $time2", new DateTimeZone($timezone2));
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">비교 완료</span>
                        <?= htmlspecialchars($timezones[$timezone2] ?? $timezone2) ?> (<?= htmlspecialchars("$date2 $time2") ?>) 기준 세계 시간
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">도시 및 타임존</th>
                                <th>현지 시각 (YYYY-MM-DD HH:MM:SS)</th>
                                <th style="width: 90px; text-align: right;">복사</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $wIdx = 0;
                            foreach ($timezones as $tz => $city) {
                                $wIdx++;
                                $cityTime = clone $inputDateTime;
                                $cityTime->setTimezone(new DateTimeZone($tz));
                                $isBase = ($tz === $timezone2);
                                ?>
                                <tr style="<?= $isBase ? 'background-color: var(--primary-light); font-weight: 600;' : '' ?>">
                                    <td>
                                        <?= htmlspecialchars($city) ?>
                                        <?php if ($isBase): ?>
                                            <span class="badge badge-primary" style="margin-left: 0.35rem;">기준도시</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><code id="w-res-<?= $wIdx ?>" style="font-size: 0.92rem;"><?= $cityTime->format('Y-m-d H:i:s') ?></code></td>
                                    <td style="text-align: right;">
                                        <button type="button" class="btn-copy" onclick="copyToClipboard('w-res-<?= $wIdx ?>', this)">복사</button>
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
        } catch (Exception $e) {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 날짜와 시간(HH:MM:SS)을 입력해 주세요.</div>';
        }
    }
    ?>
</div>
