<!DOCTYPE html>
<html lang="ko">
<head>
<title><?=SITE_NAME?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi, shrink-to-fit=no">
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Cache-Control" content="no-cache,no-store" />
<meta name="naver-site-verification" content="aedabfc3413b0cea468f17ee93bda79b920828c4" />
<meta name="writer" content="<?=SITE_NAME?>">
<meta name="title" content="<?=SITE_NAME?>" />
<meta name="description" content="괌사이판 도요타렌트카. 괌 및 사이판 도요타렌트카 총판 사무실." />
<meta name="keywords" content="<?=SITE_NAME?>" />
<meta name="author" content="<?=SITE_NAME?>" />
<meta name="format-detection" content="telephone=no" />

<meta property="og:title" content=<?=SITE_NAME?>""/>
<meta property="og:type" content="<?=SITE_NAME?>"/>
<meta property="og:url" content="<?=SITE_URL?>"/>
<meta property="og:description" content="<?=SITE_NAME?>"/>
<meta property="og:image" content="<?=SITE_URL?>/images/common/index.jpg"/>


<!-- link -->
<link rel="canonical" href=""/>
<link rel="shortcut icon" href="<?=SITE_URL?>/images/favicon.ico">

<link href="/css/common.css" rel="stylesheet">
<link href="/css/mobile.css" rel="stylesheet">
<link href="/css/popup.css" rel="stylesheet">

<!-- script -->
<!--[if lt IE 9]>
	<script type="text/javascript" src="/js/html5.js"></script>
	<script type="text/javascript" src="/js/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/common.js"></script>

<script type="text/javascript" src="/module/js/class.helper.js"></script>
<script type="text/javascript" src="/module/js/fn.user.define.js"></script>

<script type="text/javascript">
    var ajaxStatus = false;
</script>

</head>
<body>
<?if ($pageNum != '0003') { //확정서메인이 아닐경우?>
	<div id="skipNaviWrap">
		<p class="hidden">바로가기 메뉴</p>
		<a href="#container">컨텐츠바로가기</a>
		<a href="#flink">하단메뉴바로가기</a>
	</div>

	<div id="wrap">
<?} else { //확정서메인일 경우?>
	<div id="wrap-window">
<?}?>