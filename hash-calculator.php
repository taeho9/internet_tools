<h2 align="center">■ Hash 계산기</h2>
    <div class="container"`>
    <h3>■ 문자열을 입력받아 다양한 Hash 함수를 통해 MD(메시지 다이제스트)를 출력합니다.</h3>

    <form method="post" action="">
    <table>
    <!-- 비밀번호 입력 폼 -->
    <?php
        require_once("cisco7.php");
        $stz = isset($_POST['algorithm']) ? $_POST['algorithm'] : '';
    ?>
            <tr>
                <td width="150px"><label for="passwd">■ 평문 문자열 입력 : </label></td>
                <td><input type="text" id="passwd1" name="passwd1" value="<?php echo isset($_POST['passwd1']) ? $_POST['passwd1'] : '';?>" required></td>
            </tr>
            <tr>
                <td><label for="algorithm">■ 알고리즘 선택 </label></td>
                <td><select id="algorithm" name="algorithm" onchange="toggleSaltInput()">
                    <option value="cisco7" <?php if ($stz == "cisco7") echo "selected"; ?>>Cisco Type 7</option>
                    <option value="unix" <?php if ($stz == "unix") echo "selected"; ?>>Unix Default (crypt)</option>
                    <option value="md5" <?php if ($stz == "md5") echo "selected"; ?>>MD5</option>
                    <option value="sha1" <?php if ($stz == "sha1") echo "selected"; ?>>SHA1(SHA-128)</option>
                    <option value="sha256" <?php if ($stz == "sha256") echo "selected"; ?>>SHA-256</option>
                    <option value="sha512" <?php if ($stz == "sha512") echo "selected"; ?>>SHA-512</option>
                    <option value="scrypt" <?php if ($stz == "scrypt") echo "selected"; ?>>SCrypt</option>
                    <option value="bcrypt" <?php if ($stz == "bcrypt") echo "selected"; ?>>BCrypt</option>
                </select>
                </td>
            </tr>
            <!-- Salt 입력 필드 (기본은 숨김) -->
            <tr id="saltRow" style="display: <?php echo isset($_POST['algorithm']) && in_array($_POST['algorithm'], ['md5', 'sha256', 'sha512']) ? 'table-row;' : 'none;'; ?>">
                <td><label for="salt">■ Salt 값 입력 : </label></td>
                <td><input type="text" id="salt" name="salt" value="<?php echo isset($_POST['salt']) ? $_POST['salt'] : ''; ?>"><br>※ salt를 입력할 경우 Linux 비밀번호 방식의 HASH값을 생성합니다.(단, Linux의 배포본 및 round횟수 salt 값 사용 방식의 차이로 인해 실제 비밀번호 Hash와는 다릅니다.)<br>※ 입력하지 않으면 단순 HASH 값을 생성합니다.</td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit1" value="MD 생성"></td>
            </tr>
    </table>
</form>

<script>
// md5, sha256, sha512인 경우에만 salt입력창을 보여줌
function toggleSaltInput() {
    var algorithm = document.getElementById("algorithm").value;
    var saltRow = document.getElementById("saltRow");

    // MD5, SHA-256, SHA-512 선택 시만 Salt 입력을 활성화
    if (algorithm === "md5" || algorithm === "sha256" || algorithm === "sha512") {
        saltRow.style.display = "table-row";  // 보이도록 설정
    } else {
        saltRow.style.display = "none";  // 숨김
    }
}
</script>

<?php  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit1'])) {
        $password = $_POST['passwd1'];
        $algorithm = $_POST['algorithm'];
        $salt = isset($_POST['salt']) ? $_POST['salt'] : '';  // Salt 값 가져오기
        $hashed_password = '';

        // 선택한 알고리즘에 따라 해시 또는 암호화 처리
        switch ($algorithm) {
            case 'cisco7':
                $cisco = new Cisco7();
                $hashed_password = $cisco->encrypt($password);
                break;
            case 'unix':
                $hashed_password = crypt($password, "salt");  // 기본 Unix 암호화 (crypt 함수)
                break;
            case 'md5':
                if (!empty($salt)) {
                    // Salt 값이 있을 때 Linux 스타일의 MD5 해시 생성
                    $hashed_password = crypt($password, '$1$' . $salt . '$');
                } else {
                    // Salt 값이 없을 때 기존 방식 사용
                    $hashed_password = md5($password);
                }
                break;
            case 'sha1':
                $hashed_password = sha1($password);
                break;
            case 'sha256':
                if (!empty($salt)) {
                    // Salt 값이 있을 때 Linux 스타일의 SHA-256 해시 생성
                    $hashed_password = crypt($password, '$5$' . $salt . '$');
                } else {
                    // Salt 값이 없을 때 기존 방식 사용
                    $hashed_password = hash('sha256', $password);
                }
                break;
            case 'sha512':
                if (!empty($salt)) {
                    // Salt 값이 있을 때 Linux 스타일의 SHA-512 해시 생성
                    $hashed_password = crypt($password, '$6$' . $salt . '$');
                } else {
                    // Salt 값이 없을 때 기존 방식 사용
                    $hashed_password = hash('sha512', $password);
                }
                break;
            case 'scrypt':
                if (function_exists('sodium_crypto_pwhash_str')) {
                    $hashed_password = sodium_crypto_pwhash_str($password, SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);
                } else {
                    $hashed_password = "SCrypt not supported in this PHP version";
                }
                break;
            case 'bcrypt':
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                break;
            default:
                $hashed_password = "알 수 없는 알고리즘";
        }

        // 결과 출력
        echo "<h3>■ 선택한 알고리즘: $algorithm</h3>";
        echo "<p><strong>해시 값:</strong> $hashed_password</p>";
    }
}
?>
</div>
<br>
<div class="container"`>
    <h3>■ Cisco Type 7으로 암호화된 패스워드를 복호화합니다.</h3>

    <form method="post" action="">
    <table>
    <!-- 비밀번호 입력 폼 -->
        <tr>
            <td><label for="passwd2">■ Cisco Type 7 암호문자열 : </label></td>
            <td><input type="text" id="passwd2" name="passwd2" value="<?php echo isset($_POST['passwd2']) ? $_POST['passwd2'] : '';?>" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="submit2" value="복호화하기"></td>
        </tr>
    </table>
    </form>

<?php  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit2'])) {
        $password = $_POST['passwd2'];
        $cisco = new Cisco7();
        $pwd_text = $cisco->decrypt($password);
        // 결과 출력
        echo "<h3>■ Cisco Type 7로 암호화된 문자열 $password의 복호화 문자열</h3>";
        echo "<p><strong>암호 문자열의 원본 문자열은 </strong> " . $pwd_text ." 입니다.</p>";
    }
}
?>

</div>