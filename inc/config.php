<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    /*
	if (strpos(URL_HOST, 'www') === false) {
		if (URL_QUERYSTRING != '') {
			header('Location: '. SITE_URL . SITE_URL . URL_PATH ."?". URL_QUERYSTRING);
			exit;
		} else {
			header('Location: '. SITE_URL . URL_PATH);
			exit;
		}
    }
	*/

	//if (NOW_IP != '220.85.206.108') {
	//	header('Location: index.html');
	//	exit;
	//}

	//접속통계 DB입력
	require($_SERVER["DOCUMENT_ROOT"]."/module/statistics/statistics_sava_proc.php");

    $cls_pop = new CLS_SETTING_POPUP;
?>