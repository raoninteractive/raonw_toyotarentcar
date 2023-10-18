<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$gubun = chkReqRpl("gubun", "", "10", "", "STR");

	if (chkBlank($gubun) || strpos("C001, C002", $gubun) === false) fnMsgJson(501, "지역 정보 값이 유효하지 않습니다.", "");

	$db = new DB_HELPER;

	//괌 인기차종 불러오기
	$sql = "
			SELECT * FROM (
				SELECT
					idx, category, title, up_file_1, day1_amt, keyword, sort,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE category='$gubun' AND open_flag='Y' AND total_stock_cnt > 0 AND del_flag='N'
			) t
			ORDER BY sort DESC, popular_cnt DESC, idx DESC
		";
	$goods_list = $db->getQuery($sql);


	$list = array();
	for ($i=0; $i<count($goods_list); $i++) {
		if ($goods_list[$i]['rest_stock_cnt'] >= 1) {
			array_push($list, array(
				"goods_idx" => $goods_list[$i]['idx'],
				"title" => $goods_list[$i]['title']
			));
		}
	}

	$result = array();

	$result["result"] = 200;
	$result["message"] = "OK";
	$result["list"] = $list;

	echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>