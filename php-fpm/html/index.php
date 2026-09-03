<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="taeho"/>
    <meta name="by" content="taeho"/>
    <meta property="og:type" content="article"/>
    <meta property="og.article.author" content="taeho"/>
    <meta property="og:site_name" content="Internet Engineering Tools"/>
    <?php
        // Default page determination
        $page = 'main';
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = $_GET['page'];
        } elseif (isset($_GET['main'])) {
            $page = 'main';
        }

        // Ensure the page exists and is safe to include
        $allowed_pages = ['main', 'subnet-find', 'date-calculator', 'unixtime-calc', 'hash-calculator', 'lunar-iCal-generator'];
        if (in_array($page, $allowed_pages)) {
            $head_file = __DIR__ . "/" . $page . "-head.php";
            if (file_exists($head_file)) {
                include($head_file);
            }
        }
    ?>
    <!-- JSON-LD를 통한 구조화된 데이터 -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Website",
      "name": "IT 엔지니어링 도구 모음",
      "url": "https://tools.blogger.pe.kr",
      "description": "IP주소 계산 및 변환기, 유닉스타임과 날짜 계산기, 세계시간 계산기, 음력 iCal 생성기를 제공하는 웹사이트",
      "author": {
        "@type": "Person",
        "name": "taeho"
      }
    }
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LTJ1SM2FD0"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-LTJ1SM2FD0');
    </script>

    <!-- Modern Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=2.0">
</head>
<body>
    <header class="app-header">
        <div class="header-inner">
            <a href="?page=main" class="brand">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <span class="brand-title">taeho's tools</span>
            </a>

            <nav>
                <ul class="nav-menu">
                    <li>
                        <a href="?page=main" class="nav-link <?= $page === 'main' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            홈
                        </a>
                    </li>
                    <li>
                        <a href="?page=subnet-find" class="nav-link <?= $page === 'subnet-find' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                            IP 계산기
                        </a>
                    </li>
                    <li>
                        <a href="?page=date-calculator" class="nav-link <?= $page === 'date-calculator' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            날짜 계산기
                        </a>
                    </li>
                    <li>
                        <a href="?page=unixtime-calc" class="nav-link <?= $page === 'unixtime-calc' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            유닉스시간
                        </a>
                    </li>
                    <li>
                        <a href="?page=hash-calculator" class="nav-link <?= $page === 'hash-calculator' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Hash 계산기
                        </a>
                    </li>
                    <li>
                        <a href="?page=lunar-iCal-generator" class="nav-link <?= $page === 'lunar-iCal-generator' ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                            음력 iCal
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="header-actions">
                <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" aria-label="테마 전환" title="라이트/다크 테마 전환">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="main-wrapper">
    <?php
        if (in_array($page, $allowed_pages)) {
            include("$page.php");
        } else {
            echo "<div class='card' style='text-align: center; padding: 3rem;'>";
            echo "<h2 style='color: var(--danger); margin-bottom: 0.5rem;'>페이지를 찾을 수 없습니다.</h2>";
            echo "<p style='color: var(--text-muted);'>요청하신 페이지가 존재하지 않거나 주소가 잘못되었습니다.</p>";
            echo "<a href='?page=main' class='btn btn-primary' style='margin-top: 1.5rem;'>홈으로 돌아가기</a>";
            echo "</div>";
        }
    ?>
    </main>

    <footer class="app-footer">
        <div class="footer-inner">
            <div>
                <strong>taeho's internet tools</strong> &copy; <?= date('Y') ?> &middot; All rights reserved.
            </div>
            <div class="footer-links">
                <a href="https://tools.blogger.pe.kr" target="_blank" rel="noopener">tools.blogger.pe.kr</a>
                <span>&middot;</span>
                <a href="https://github.com/taeho9/internet_tools" target="_blank" rel="noopener">GitHub</a>
            </div>
        </div>
    </footer>

    <!-- Client Scripts -->
    <script src="assets/js/app.js?v=2.0"></script>
</body>
</html>
