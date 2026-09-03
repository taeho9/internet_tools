<?php
require_once("cisco7.php");
$stz = isset($_POST['algorithm']) ? $_POST['algorithm'] : 'sha256';
?>

<div class="page-hero">
    <h1 class="page-title">
        <span class="brand-icon" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </span>
        Hash(MD) 계산기 & 크랙
    </h1>
    <p class="page-subtitle">
        평문 텍스트로부터 MD5, SHA 시리즈, BCrypt, SCrypt 등 다양한 해시 함수를 통해 메시지 다이제스트(MD)를 생성하고, 네트워크 장비의 Cisco Type 7 비밀번호를 복호화(크랙)합니다.
    </p>
</div>

<!-- Section 1: Generate Hash -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-primary">기능 1</span>
                문자열 해시값(Message Digest) 생성
            </h2>
            <p class="card-desc">원하는 암호화 해시 알고리즘과 평문 문자열을 입력하여 다이제스트를 생성합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="passwd1">평문 문자열 (Plaintext)</label>
                <input type="text" class="form-input" id="passwd1" name="passwd1" value="<?= isset($_POST['passwd1']) ? htmlspecialchars($_POST['passwd1']) : '' ?>" placeholder="해시를 생성할 텍스트 입력" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="algorithm">해시 알고리즘 선택</label>
                <select class="form-select" id="algorithm" name="algorithm" onchange="toggleSaltInput()">
                    <option value="sha256" <?= $stz === "sha256" ? "selected" : "" ?>>SHA-256 (권장 표준)</option>
                    <option value="sha512" <?= $stz === "sha512" ? "selected" : "" ?>>SHA-512</option>
                    <option value="sha1" <?= $stz === "sha1" ? "selected" : "" ?>>SHA-1 (SHA-128)</option>
                    <option value="md5" <?= $stz === "md5" ? "selected" : "" ?>>MD5</option>
                    <option value="bcrypt" <?= $stz === "bcrypt" ? "selected" : "" ?>>BCrypt (단방향 비밀번호 저장용)</option>
                    <option value="scrypt" <?= $stz === "scrypt" ? "selected" : "" ?>>SCrypt (메모리 집약형 암호화)</option>
                    <option value="unix" <?= $stz === "unix" ? "selected" : "" ?>>Unix Default (crypt)</option>
                    <option value="cisco7" <?= $stz === "cisco7" ? "selected" : "" ?>>Cisco Type 7</option>
                </select>
            </div>
        </div>

        <!-- Salt Input Field -->
        <div class="form-group" id="saltRow" style="display: <?= isset($_POST['algorithm']) && in_array($_POST['algorithm'], ['md5', 'sha256', 'sha512']) ? 'flex' : 'none' ?>;">
            <label class="form-label" for="salt">Salt 값 입력 (선택 사항)</label>
            <input type="text" class="form-input" id="salt" name="salt" placeholder="예: random_salt_123" value="<?= isset($_POST['salt']) ? htmlspecialchars($_POST['salt']) : '' ?>">
            <span class="form-helper">
                * Salt를 입력할 경우 Linux shadow 방식의 해시값($1$, $5$, $6$)을 생성합니다.<br>
                * 비워두실 경우 일반 표준 Hex 다이제스트를 생성합니다.
            </span>
        </div>

        <button type="submit" name="submit1" class="btn btn-primary" style="margin-top: 0.5rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            해시값(MD) 생성하기
        </button>
    </form>

    <script>
        function toggleSaltInput() {
            const algorithm = document.getElementById("algorithm").value;
            const saltRow = document.getElementById("saltRow");
            if (algorithm === "md5" || algorithm === "sha256" || algorithm === "sha512") {
                saltRow.style.display = "flex";
            } else {
                saltRow.style.display = "none";
            }
        }
    </script>

    <?php  
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit1'])) {
        $password = $_POST['passwd1'];
        $algorithm = $_POST['algorithm'];
        $salt = isset($_POST['salt']) ? trim($_POST['salt']) : '';
        $hashed_password = '';

        switch ($algorithm) {
            case 'cisco7':
                $cisco = new Cisco7();
                $hashed_password = $cisco->encrypt($password);
                break;
            case 'unix':
                $hashed_password = crypt($password, "salt");
                break;
            case 'md5':
                if (!empty($salt)) {
                    $hashed_password = crypt($password, '$1$' . $salt . '$');
                } else {
                    $hashed_password = md5($password);
                }
                break;
            case 'sha1':
                $hashed_password = sha1($password);
                break;
            case 'sha256':
                if (!empty($salt)) {
                    $hashed_password = crypt($password, '$5$' . $salt . '$');
                } else {
                    $hashed_password = hash('sha256', $password);
                }
                break;
            case 'sha512':
                if (!empty($salt)) {
                    $hashed_password = crypt($password, '$6$' . $salt . '$');
                } else {
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
        ?>
        <div class="result-card">
            <div class="result-header">
                <div class="result-title">
                    <span class="badge badge-success"><?= strtoupper(htmlspecialchars($algorithm)) ?></span>
                    생성된 해시 결과
                </div>
                <button type="button" class="btn-copy" onclick="copyToClipboard('hash-output', this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    복사하기
                </button>
            </div>
            <div class="result-body">
                <div class="code-display" id="hash-output"><?= htmlspecialchars($hashed_password) ?></div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Section 2: Cisco Type 7 Crack -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span class="badge badge-warning">기능 2</span>
                Cisco Password Type 7 복호화 (크랙)
            </h2>
            <p class="card-desc">Cisco 라우터 및 스위치 설정 파일(running-config)에 저장된 Type 7 암호문을 평문으로 복호화합니다.</p>
        </div>
    </div>

    <form method="post" action="">
        <div class="form-group" style="max-width: 500px;">
            <label class="form-label" for="passwd2">Cisco Type 7 암호 문자열</label>
            <input type="text" class="form-input" id="passwd2" name="passwd2" value="<?= isset($_POST['passwd2']) ? htmlspecialchars($_POST['passwd2']) : '' ?>" placeholder="예: 0822455D0A16" required>
        </div>
        <button type="submit" name="submit2" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 2l-2 2m-6 6l-3-3m0 0l-3 3m3-3v12"></path></svg>
            암호 복호화(크랙)하기
        </button>
    </form>

    <?php  
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
        $password = trim($_POST['passwd2']);
        $cisco = new Cisco7();
        $pwd_text = $cisco->decrypt($password);
        ?>
        <div class="result-card">
            <div class="result-header">
                <div class="result-title">
                    <span class="badge badge-success">복호화 완료</span>
                    Cisco Type 7 원본 평문
                </div>
                <button type="button" class="btn-copy" onclick="copyToClipboard('cisco-plain-output', this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    복사하기
                </button>
            </div>
            <div class="result-body">
                <div style="font-size: 1.15rem; color: var(--text-main);">
                    암호문 <code><?= htmlspecialchars($password) ?></code> 의 원본 비밀번호:
                    <strong style="font-size: 1.35rem; color: var(--primary); margin-left: 0.5rem;" id="cisco-plain-output"><?= htmlspecialchars($pwd_text) ?></strong>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>