<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");

    $params['receipt_id'] = chkReqRpl("receipt_id", "", "50", "POST", "STR");

    if (chkBlank($params['receipt_id'])) fnMsgJson(501, "예약번호 값이 유효하지 않습니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");

	$cls_booking = new CLS_BOOKING;

    //결제 검증하기
    $payment = $cls_booking->payment_verify_proc($params['receipt_id']);
    if (!$payment) fnMsgJson(502, "결제 승인 처리중 오류가 발생되었습니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");

    //예약접수 정보 불러오기(예약번호 조회)
    $booking_view = $cls_booking->booking_view('', $payment['order_id'], '', '', '');
    if ($booking_view == false) fnMsgJson(503, "일치하는 예약정보가 없습니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");

    //결제 금액 확인
    if ($payment['price'] != $booking_view['booking_agency_fee']) fnMsgJson(504, "잘못된 결제금액을 요청하였습니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");

    //결제 상태 확인
    if (!chkBlank($booking_view['payment_status'])) fnMsgJson(505, "결제대기 예약건만 결제 가능합니다.\n이미 결제가 완료되었을경우 고객센터에 문의주세요.", "");
?>
{"result": 200, "message": "OK"}