<?php
// IP 서브넷 계산 헬퍼 함수들 (중복 선언 방지)
if (!function_exists('subnet_ipToBinary')) {
    function subnet_ipToBinary($ip) {
        return sprintf("%032b", ip2long($ip));
    }
}

if (!function_exists('subnet_findCommonPrefix')) {
    function subnet_findCommonPrefix($bin1, $bin2) {
        $prefix = '';
        for ($i = 0; $i < 32; $i++) {
            if ($bin1[$i] == $bin2[$i]) {
                $prefix .= $bin1[$i];
            } else {
                break;
            }
        }
        return $prefix;
    }
}

if (!function_exists('subnet_prefixToSubnetMask')) {
    function subnet_prefixToSubnetMask($prefixLength) {
        return long2ip(-1 << (32 - $prefixLength));
    }
}

if (!function_exists('subnet_calculateBroadcastAddress')) {
    function subnet_calculateBroadcastAddress($networkAddress, $prefixLength) {
        $hostBits = 32 - $prefixLength;
        return long2ip(ip2long($networkAddress) | ((1 << $hostBits) - 1));
    }
}

if (!function_exists('subnet_calculateStartIP')) {
    function subnet_calculateStartIP($networkAddress) {
        return long2ip(ip2long($networkAddress) + 1);
    }
}

if (!function_exists('subnet_calculateEndIP')) {
    function subnet_calculateEndIP($broadcastAddress) {
        return long2ip(ip2long($broadcastAddress) - 1);
    }
}

if (!function_exists('subnet_findSmallestSubnet')) {
    function subnet_findSmallestSubnet($ip1, $ip2) {
        $bin1 = subnet_ipToBinary($ip1);
        $bin2 = subnet_ipToBinary($ip2);

        $commonPrefix = subnet_findCommonPrefix($bin1, $bin2);
        $prefixLength = strlen($commonPrefix);

        $networkAddress = substr($commonPrefix, 0, $prefixLength) . str_repeat('0', 32 - $prefixLength);
        $networkAddress = long2ip(bindec($networkAddress));

        $subnetMask = subnet_prefixToSubnetMask($prefixLength);
        $broadcastAddress = subnet_calculateBroadcastAddress($networkAddress, $prefixLength);

        $startIP = subnet_calculateStartIP($networkAddress);
        $endIP = subnet_calculateEndIP($broadcastAddress);

        return [$networkAddress, $subnetMask, $prefixLength, $startIP, $endIP, $broadcastAddress];
    }
}
?>

<div class="page-hero">
    <h1 class="page-title">
        <span class="brand-icon" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                <line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
        </span>
        IP & 서브넷 계산기
    </h1>
    <p class="page-subtitle">
        두 IP를 포함하는 가장 작은 서브넷 산출, IP가 속하는 전체 서브넷 목록 분석, CIDR 표기법의 네트워크 및 브로드캐스트 주소 범위를 정밀 계산합니다.
    </p>
</div>

<!-- Section 1: Smallest Subnet containing two IPs -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-primary">기능 1</span>
                두 IP를 포함하는 최소 서브넷 계산
            </h2>
            <p class="card-desc">시작 IP와 마지막 IP 주소를 입력하면 두 주소를 모두 수용하는 최소 크기의 서브넷(CIDR)을 도출합니다.</p>
        </div>
    </div>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="ip1">시작 IP 주소</label>
                <input type="text" class="form-input" id="ip1" name="ip1" required placeholder="예: 192.168.1.10" value="<?= isset($_POST['ip1']) ? htmlspecialchars($_POST['ip1']) : '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="ip2">마지막 IP 주소</label>
                <input type="text" class="form-input" id="ip2" name="ip2" required placeholder="예: 192.168.1.150" value="<?= isset($_POST['ip2']) ? htmlspecialchars($_POST['ip2']) : '' ?>">
            </div>
        </div>
        <button type="submit" name="submit1" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            최소 서브넷 계산하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit1'])) {
        $ip1 = trim($_POST['ip1']);
        $ip2 = trim($_POST['ip2']);

        if (filter_var($ip1, FILTER_VALIDATE_IP) && filter_var($ip2, FILTER_VALIDATE_IP)) {
            list($networkAddress, $subnetMask, $prefixLength, $startIP, $endIP, $broadcastAddress) = subnet_findSmallestSubnet($ip1, $ip2);
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">계산 성공</span>
                        최소 서브넷 산출 결과
                    </div>
                </div>
                <div class="result-body" style="padding: 0;">
                    <table class="modern-table">
                        <tbody>
                            <tr>
                                <th style="width: 220px;">CIDR 네트워크 표기</th>
                                <td>
                                    <strong style="color: var(--primary); font-size: 1.05rem;" id="res-cidr"><?= $networkAddress ?>/<?= $prefixLength ?></strong>
                                    <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('res-cidr', this)">복사</button>
                                </td>
                            </tr>
                            <tr>
                                <th>Network Address</th>
                                <td><code id="res-net"><?= $networkAddress ?></code></td>
                            </tr>
                            <tr>
                                <th>Subnet Mask</th>
                                <td><code id="res-mask"><?= $subnetMask ?></code></td>
                            </tr>
                            <tr>
                                <th>First Usable Host IP</th>
                                <td><code id="res-start"><?= $startIP ?></code></td>
                            </tr>
                            <tr>
                                <th>Last Usable Host IP</th>
                                <td><code id="res-end"><?= $endIP ?></code></td>
                            </tr>
                            <tr>
                                <th>Broadcast Address</th>
                                <td><code id="res-bcast"><?= $broadcastAddress ?></code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 IPv4 주소 형식을 입력해 주세요. (예: 192.168.0.1)</div>';
        }
    }
    ?>
</div>

<!-- Section 2: All subnets containing IP -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-info">기능 2</span>
                IP가 포함된 모든 서브넷 탐색 (/32 ~ /16)
            </h2>
            <p class="card-desc">입력한 IP 주소가 포함될 수 있는 모든 서브넷 범위(최대 /16 네트워크까지)를 한눈에 대조합니다.</p>
        </div>
    </div>

    <form method="POST" action="">
        <div class="form-group" style="max-width: 420px;">
            <label class="form-label" for="ip">기준 IP 주소</label>
            <input type="text" class="form-input" id="ip" name="ip" required placeholder="예: 10.10.25.100" value="<?= isset($_POST['ip']) ? htmlspecialchars($_POST['ip']) : '' ?>">
        </div>
        <button type="submit" name="submit2" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            모든 서브넷 탐색하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
        $ip = trim($_POST['ip']);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip_long = ip2long($ip);
            ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <span class="badge badge-success">탐색 완료</span>
                        <?= htmlspecialchars($ip) ?> 이 포함된 서브넷 목록
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>서브넷 (CIDR)</th>
                                <th>네트워크 시작 IP</th>
                                <th>네트워크 종료 IP (브로드캐스트)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($prefix = 32; $prefix >= 16; $prefix--) {
                                $mask = -1 << (32 - $prefix);
                                $network = $ip_long & $mask;
                                $start_ip = long2ip($network);
                                $end_ip = long2ip($network | ~$mask);
                                echo '<tr>';
                                echo '<td><strong style="color: var(--primary);">' . $start_ip . '/' . $prefix . '</strong></td>';
                                echo '<td><code>' . $start_ip . '</code></td>';
                                echo '<td><code>' . $end_ip . '</code></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 IPv4 주소 형식을 입력해 주세요.</div>';
        }
    }
    ?>
</div>

<!-- Section 3: CIDR Range Calculator -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-warning">기능 3</span>
                CIDR 네트워크 범위 계산
            </h2>
            <p class="card-desc">CIDR 표기법(IP/Prefix)을 입력하면 네트워크 주소와 브로드캐스트 주소를 계산합니다.</p>
        </div>
    </div>

    <form method="POST" action="">
        <div class="form-group" style="max-width: 420px;">
            <label class="form-label" for="cidrip">CIDR 표기 IP</label>
            <input type="text" class="form-input" id="cidrip" name="cidrip" required placeholder="예: 172.16.0.0/20" value="<?= isset($_POST['cidrip']) ? htmlspecialchars($_POST['cidrip']) : '' ?>">
            <span class="form-helper">형식: IP주소/프리픽스길이 (예: 10.0.0.0/24)</span>
        </div>
        <button type="submit" name="submit3" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            범위 계산하기
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit3'])) {
        $cidr = trim($_POST['cidrip']);
        $cidrParts = explode('/', $cidr);

        if (count($cidrParts) === 2) {
            $ip3 = $cidrParts[0];
            $prefix = (int)$cidrParts[1];

            if (filter_var($ip3, FILTER_VALIDATE_IP) && $prefix >= 0 && $prefix <= 32) {
                $ip_long = ip2long($ip3);
                $mask = -1 << (32 - $prefix);
                $network_address = long2ip($ip_long & $mask);
                $broadcast_address = long2ip($ip_long | ~$mask);
                ?>
                <div class="result-card">
                    <div class="result-header">
                        <div class="result-title">
                            <span class="badge badge-success">계산 성공</span>
                            <?= htmlspecialchars($cidr) ?> 범위 산출 결과
                        </div>
                    </div>
                    <div class="result-body" style="padding: 0;">
                        <table class="modern-table">
                            <tbody>
                                <tr>
                                    <th style="width: 220px;">시작(네트워크) 주소</th>
                                    <td>
                                        <strong style="color: var(--primary);" id="cidr-res-net"><?= $network_address ?></strong>
                                        <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('cidr-res-net', this)">복사</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>마지막(브로드캐스트) 주소</th>
                                    <td>
                                        <strong style="color: var(--primary);" id="cidr-res-bcast"><?= $broadcast_address ?></strong>
                                        <button type="button" class="btn-copy" style="margin-left: 0.5rem;" onclick="copyToClipboard('cidr-res-bcast', this)">복사</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">유효하지 않은 CIDR 형식입니다. 예: 192.168.1.0/24 (Prefix: 0~32)</div>';
            }
        } else {
            echo '<div class="alert-box alert-warning" style="margin-top: 1.25rem;">올바른 CIDR 형식(IP/Prefix)을 입력해 주세요. (예: 10.0.0.0/16)</div>';
        }
    }
    ?>
</div>
