<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['idx']  = chkReqRpl("idx", null, "", "", "INT");
    $params['fnum'] = chkReqRpl("fnum", null, "", "", "INT");

    if (chkBlank($params['idx'])) fnMsgJson(502, "강의 고유번호 값이 유효하지 않습니다.", "");
	if (chkBlank($params['fnum'])) fnMsgJson(503, "파일번호 값이 유효하지 않습니다.", "");

	$cls_content = new CLS_CONTENT;

    //첨부파일 삭제
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	if (!$cls_content->lecture_file_delete($params)) fnMsgJson(504, "첨부파일 삭제 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
