<h2>&nbsp;&nbsp;■ 서브넷 계산기</h2>
<div>
<h3>■ 두 IP를 포함하는 가장 작은 서브넷을 계산합니다.</h3>
<!-- 두 IP를 입력받기 위한 HTML 폼 -->
<table>
    <form method="POST" action="">
        <tr>
            <td><label for="ip1">● 시작 IP 주소 : </label></td>
            <td><input type="text" id="ip1" name="ip1" required value="<?php echo isset($_POST['ip1']) ? $_POST['ip1'] : '';?>"></td>
        </tr>
        <tr>
            <td><label for="ip2">● 마지막 IP 주소 : </label></td>
            <td><input type="text" id="ip2" name="ip2" required value="<?php echo isset($_POST['ip2']) ? $_POST['ip2'] : '';?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="submit1" value="계산하기">
            </td>
        </tr>
    </form>
</table>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit1'])) {
        // 첫 번째 폼이 제출된 경우
        $ip1 = $_POST['ip1'];
        $ip2 = $_POST['ip2'];

        // IP를 32비트 이진수로 변환하는 함수
        function ipToBinary($ip) {
            return sprintf("%032b", ip2long($ip));
        }

        // 공통 접두사를 찾는 함수
        function findCommonPrefix($bin1, $bin2) {
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

        // 접두사 길이에 따라 서브넷 마스크를 반환하는 함수
        function prefixToSubnetMask($prefixLength) {
            return long2ip(-1 << (32 - $prefixLength));
        }

        // 브로드캐스트 주소 계산 함수
        function calculateBroadcastAddress($networkAddress, $prefixLength) {
            $hostBits = 32 - $prefixLength;
            return long2ip(ip2long($networkAddress) | ((1 << $hostBits) - 1));
        }

        // 시작 IP 계산
        function calculateStartIP($networkAddress) {
            return long2ip(ip2long($networkAddress) + 1);  // 첫 번째 호스트 IP
        }

        // 끝 IP 계산
        function calculateEndIP($broadcastAddress) {
            return long2ip(ip2long($broadcastAddress) - 1);  // 마지막 호스트 IP
        }

        // 가장 작은 서브넷을 계산하는 함수
        function findSmallestSubnet($ip1, $ip2) {
            $bin1 = ipToBinary($ip1);
            $bin2 = ipToBinary($ip2);

            // 두 IP의 공통된 접두사 찾기
            $commonPrefix = findCommonPrefix($bin1, $bin2);
            $prefixLength = strlen($commonPrefix);

            // 공통 접두사를 기반으로 네트워크 주소 계산
            $networkAddress = substr($commonPrefix, 0, $prefixLength) . str_repeat('0', 32 - $prefixLength);
            $networkAddress = long2ip(bindec($networkAddress));

            // 서브넷 마스크와 브로드캐스트 주소 계산
            $subnetMask = prefixToSubnetMask($prefixLength);
            $broadcastAddress = calculateBroadcastAddress($networkAddress, $prefixLength);

            // 시작 IP와 끝 IP 계산
            $startIP = calculateStartIP($networkAddress);
            $endIP = calculateEndIP($broadcastAddress);

            return [$networkAddress, $subnetMask, $prefixLength, $startIP, $endIP, $broadcastAddress];
        }

        // 입력된 IP 주소로 서브넷 계산
        if (filter_var($ip1, FILTER_VALIDATE_IP) && filter_var($ip2, FILTER_VALIDATE_IP)) {
            list($networkAddress, $subnetMask, $prefixLength, $startIP, $endIP, $broadcastAddress) = findSmallestSubnet($ip1, $ip2);
            echo '<h3>【계산 결과】</h3>';
            echo '<table>';
            echo '<tr><td>● Network Address</td><td><strong>' . $networkAddress . '</strong></td></tr>';
            echo '<tr><td>● Subnet Mask</td><td><strong>' . $subnetMask . '</strong></td></tr>';
            echo '<tr><td>● CIDR IP</td><td><strong>' . $networkAddress . '/' . $prefixLength . '</strong></td></tr>';
            echo '<tr><td>● Start IP</td><td><strong>' . $startIP . '</strong></td></tr>';
            echo '<tr><td>● End IP</td><td><strong>' . $endIP . '</strong></td></tr>';
            echo '<tr><td>● Broadcast IP</td><td><strong>' . $broadcastAddress . '</strong></td></tr>';
            echo '</table>';
        } else {
            echo '<h3 style="color:red;">Invalid IP address format. Please enter valid IPs.</h3>';
        }
    }
}
?>
</div>
<br>
<div>
<h3>■ IP를 입력하면 IP가 포함되는 서브넷을 모두 출력합니다. (단, 최대 /16 네트워크까지)</h3>
<!-- IP를 입력받기 위한 HTML 폼 -->
<table>
    <form method="POST" action="">
        <tr>
            <td><label for="ip">● IP 주소 : </label></td>
            <td><input type="text" id="ip" name="ip" required value="<?php echo isset($_POST['ip']) ? $_POST['ip'] : '';?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="submit2" value="계산하기">
            </td>
        </tr>
    </form>
</table>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit2'])) {
        // 두 번째 폼이 제출된 경우
        $ip = $_POST['ip'];

        // IP를 32비트 이진수로 변환하는 함수
        function ipToBinary($ip) {
            return sprintf("%032b", ip2long($ip));
        }

        // IP가 포함된 서브넷을 출력하는 함수
        function findAllSubnets($ip) {
            $ip_long = ip2long($ip);

            echo '<h3>【서브넷 계산 결과】</h3>';
            echo '<table>';
            echo '<tr><td>● 서브넷</td><td><strong>시작 IP</strong></td><td><strong>종료 IP</strong></td></tr>';

            for ($prefix = 32; $prefix >= 16; $prefix--) {
                $mask = -1 << (32 - $prefix);
                $network = $ip_long & $mask;
                $start_ip = long2ip($network);
                $end_ip = long2ip($network | ~$mask);
                
                echo '<tr><td>' . $start_ip . '/' . $prefix . '</td><td><strong>' . $start_ip . '</strong></td><td><strong>' . $end_ip . '</strong></td></tr>';
            }

            echo '</table>';
        }

        // 입력된 IP로 서브넷 계산 후 출력
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            findAllSubnets($ip);
        } else {
            echo '<h3 style="color:red;">Invalid IP address format. Please enter a valid IP.</h3>';
        }
    }
}
?>
</div>
<br>
<div>
<h3>■ IP를 CIDR 형태로 입력하면 IP주소 범위(네트워크 시작주소와 끝 주소)를 계산합니다.</h3>
<!-- IP를 입력받기 위한 HTML 폼 -->
<table>
    <form method="POST" action="">
        <tr>
            <td><label for="cidrip">● IP 주소(CIDR) : </label></td>
            <td><input type="text" id="cidrip" name="cidrip" size=15 style="width: 150px;" required value="<?php echo isset($_POST['cidrip']) ? $_POST['cidrip'] : '';?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="submit3" value="계산하기">
            </td>
        </tr>
    </form>
</table>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit3'])) {
        $cidr = $_POST['cidrip'];

        // IP와 CIDR을 분리
        function parseCIDR($cidr) {
            list($ip3, $prefix) = explode('/', $cidr);
            return [$ip3, (int)$prefix];
        }

        // 네트워크 주소 계산
        function getNetworkAddress($ip3, $prefix) {
            $ip_long = ip2long($ip3);
            $mask = -1 << (32 - $prefix);
            return long2ip($ip_long & $mask);
        }

        // 브로드캐스트 주소 계산
        function getBroadcastAddress($ip3, $prefix) {
            $ip_long = ip2long($ip3);
            $mask = -1 << (32 - $prefix);
            return long2ip($ip_long | ~$mask);
        }

        // 입력된 IP와 CIDR에서 네트워크 범위 계산
        function calculateRange($cidr) {
            list($ip3, $prefix) = parseCIDR($cidr);

            if (filter_var($ip3, FILTER_VALIDATE_IP) && $prefix >= 0 && $prefix <= 32) {
                $network_address = getNetworkAddress($ip3, $prefix);
                $broadcast_address = getBroadcastAddress($ip3, $prefix);

                echo '<h3>【네트워크 범위 계산 결과】</h3>';
                echo '<table>';
                echo '<tr><td><strong>시작(네트워크)주소</strong></td><td><strong>마지막(브로드캐스트)주소</strong></td></tr>';
                echo '<tr><td>' . $network_address . '</td><td>' . $broadcast_address . '</td></tr>';
                echo '</table>';
            } else {
                echo '<h3 style="color:red;">잘못된 IP 주소 또는 CIDR 형식입니다. 올바른 값을 입력하세요.</h3>';
            }
        }

        // 입력된 CIDR로 범위 계산 후 출력
        calculateRange($cidr);
    }
}
?>
</div>
