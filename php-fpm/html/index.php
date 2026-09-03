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
        // Default page
        $page = isset($_GET['page']) ? $_GET['page'] : 'main';

        // Ensure the page exists and is safe to include
        $allowed_pages = ['main', 'subnet-find', 'date-calculator', 'unixtime-calc', 'hash-calculator', 'lunar-iCal-generator'];
        if (in_array($page, $allowed_pages)) {
            $head_file = __DIR__ . "/" . $page . "-head.php";
            if (file_exists($head_file)) {
                include($head_file);
            }
        }
    ?>
    <!-- JSON-LD를 통한 구조화된 데이터 (검색엔진이 페이지 컨텐츠를 이해하는 데 도움) -->
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
    <!-- Google tag (gtag.js) -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 5px;
            text-align: center;
        }
        nav {
            margin-top: 5px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 5px;
            padding: 5px;
            display: inline-block;
        }
        nav a.active {
            background-color: #555;
            border-radius: 5px;
        }
        main {
            padding: 5px;
        }
  	table {
    	   border: 1px solid #555555;
    	   border-collapse: collapse; /* 선택 사항: 테이블의 셀 간격을 제거합니다 */
           padding: 5px; /* padding 속성은 테이블 전체에는 적용되지 않습니다. 셀에 적용하려면 별도로 설정해야 합니다 */
  	}
  	table td, th {
    	   border: 1px solid #555555; /* 셀의 보더 색깔 설정 */
           padding: 5px; /* 셀의 패딩 */
  	}
	div {
    	  background-color: #dddddd;
    	  color: #000000;
    	  padding: 10px;
  	}
    /* main을 위한 css */
    .container {
        max-width: 1000px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .update-date {
        color: #5D4037;
        font-size: 14px;
        text-align: left;
        font-weight: bold;
    }
    ul {
        line-height: 1.8;
        color: #333;
    }
    li {
       margin-bottom: 3px;
    }
    .highlight {
        color: #d9534f;
        font-weight: bold;
    }
    .section-title {
        color: #0275d8;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <header>
        <h1>taeho's internet tools</h1>
        <nav>
            <a href="?main" class="<?= isset($_GET['page']) && $_GET['page'] === 'main' ? 'active' : '' ?>">&nbsp;&nbsp;홈으로&nbsp;&nbsp;</a>
            <a href="?page=subnet-find" class="<?= isset($_GET['page']) && $_GET['page'] === 'subnet-find' ? 'active' : '' ?>">IP 계산기</a>
            <a href="?page=date-calculator" class="<?= isset($_GET['page']) && $_GET['page'] === 'date-calculator' ? 'active' : '' ?>">날짜 계산기</a>
            <a href="?page=unixtime-calc" class="<?= isset($_GET['page']) && $_GET['page'] === 'unixtime-calc' ? 'active' : '' ?>">유닉스시간 변환기</a>
            <a href="?page=hash-calculator" class="<?= isset($_GET['page']) && $_GET['page'] === 'hash-calculator' ? 'active' : '' ?>">Hash 계산기</a>
            <a href="?page=lunar-iCal-generator" class="<?= isset($_GET['page']) && $_GET['page'] === 'lunar-iCal-generator' ? 'active' : '' ?>">음력 iCal 생성기</a>
        </nav>
    </header>
    <main>
    <?php
        if (in_array($page, $allowed_pages)) {
            include("$page.php");
        } else {
            echo "<h1>페이지를 찾을 수 없습니다.</h1>";
            echo "<p>죄송합니다, 요청하신 페이지가 존재하지 않습니다.</p>";
        }
    ?>
    </main>
</body>
</html>
