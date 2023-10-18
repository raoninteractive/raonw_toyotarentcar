<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['booking_idx']           = chkReqRpl("booking_idx", null, "", "POST", "INT");
	$params['out_date']              = chkReqRpl("out_date", "", "10", "POST", "STR");
    $params['out_airline']           = chkReqRpl("out_airline", "", "50", "POST", "STR");
    $params['hotel']                 = chkReqRpl("hotel", "", "100", "POST", "STR");
    $params['rental_sdate']          = chkReqRpl("rental_sdate", "", "10", "POST", "STR");
    $params['rental_hour']           = chkReqRpl("rental_hour", "", "2", "POST", "STR");
    $params['rental_minute']         = chkReqRpl("rental_minute", "", "2", "POST", "STR");
    $params['rental_day']            = chkReqRpl("rental_day", null, "", "POST", "INT");
    $params['pickup_area']           = chkReqRpl("pickup_area", "", "100", "POST", "STR");
    $params['return_area']           = chkReqRpl("return_area", "", "100", "POST", "STR");
    $params['infant_seat_cnt']       = chkReqRpl("infant_seat_cnt", 0, "", "POST", "INT");
    $params['child_seat_cnt']        = chkReqRpl("child_seat_cnt", 0, "", "POST", "INT");
    $params['booster_seat_cnt']      = chkReqRpl("booster_seat_cnt", 0, "", "POST", "INT");
    $params['local_send_email_flag'] = chkReqRpl("local_send_email_flag", "N", "1", "POST", "STR");


    if (chkBlank($params['booking_idx'])) fnMsgJson(502, "예약 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['out_date']) || !isDate($params['out_date'])) fnMsgJson(503, "출국일 값이 유효하지 않습니다.", "");
    if (chkBlank($params['out_airline'])) fnMsgJson(504, "출국일 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['hotel'])) fnMsgJson(505, "투숙 호텔 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_sdate']) || !isDate($params['rental_sdate'])) fnMsgJson(506, "렌트날짜 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_hour'])) fnMsgJson(507, "렌트날짜(시간) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_minute'])) fnMsgJson(508, "렌트날짜(분) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_day'])) fnMsgJson(509, "렌트기간(반납일) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['pickup_area'])) fnMsgJson(510, "인수/픽업 위치 값이 유효하지 않습니다.", "");
    if (chkBlank($params['return_area'])) fnMsgJson(511, "차량반납 위치 값이 유효하지 않습니다.", "");

    $cls_booking = new CLS_BOOKING;
    $cls_goods = new CLS_GOODS;

    //예약정보 불러오기
    $booking_view = $cls_booking->booking_view($params['booking_idx']);
    if ($booking_view == false) fnMsgJson(512, "일치하는 예약정보가 없습니다.", "");

    //렌트기간 변경이 있을경우
    if ($booking_view['rental_sdate'] != $params['rental_sdate']) {
        //예약가능일 불러오기
        $stock_params['goods_idx'] = $booking_view['goods_idx'];
        $stock_params['sch_sdate'] = date('Y-m-d');
        $stock_params['sch_stock'] = 'Y';
        $stock_list = $cls_goods->stock_list($stock_params);
        if (count($stock_list) == 0) fnMsgJson(513, "예약 가능한 차량이 없습니다. 다른 차량은 이용해주세요.", "");

        //예약가능일 체크
        $check_cnt = 0;
        for ($i=0; $i<count($stock_list); $i++) {
            if ($params['rental_sdate'] == $stock_list[$i]['sdate'] && $stock_list[$i]['stock_cnt'] > 0) {
                $check_cnt++;
            }
        }
        if ($check_cnt == 0) fnMsgJson(514, "예약 가능한 차량이 없습니다. 다른 차량은 이용해주세요.", "");
    }


    //렌트시작일 체크
    if (dateDiff("d", $params['out_date'], $params['rental_sdate']) < 0) fnMsgJson(515, "렌트 시작 날짜는 출국일 보다 작을 수 없습니다.\n렌트 시작 날짜를 다시 확인해주세요.", "");

    //렌트종료일 체크
    $params['rental_edate'] = dateAdd("d", $params['rental_day'], $params['rental_sdate']);

    //렌트시작시간
    $params['rental_time'] = $params['rental_hour'] .":". $params['rental_minute'];

    //차량반납위치가 공항이랑 오피스 제외 하고 호텔을 선택 하는 경우 $10 자동으로 추가
    $params['return_area_amt'] = 0;
    if ($booking_view['goods_category']=='C001') {
        if (strpos($params['return_area'], "AIRPORT") === false && strpos($params['return_area'], "MAIN OFFICE") === false) {
            $params['return_area_amt'] = $CONST_RETURN_AREA_AMT;
        }
    }


    //보조시트 체크 및 가격
    $params['total_seat_amt']   = 0;
    $params['infant_seat_amt']  = 0;
    $params['child_seat_amt']   = 0;
    $params['booster_seat_amt'] = 0;
    $params['seat_free_cnt']    = iif($booking_view['goods_category']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2);
    $total_seat_cnt = $params['infant_seat_cnt'] + $params['child_seat_cnt'] + $params['booster_seat_cnt'];

    //보조시트 가격
    $params['infant_seat_amt']  = $params['infant_seat_cnt'] * $booking_view['goods_car_seat_amt'];
    $params['child_seat_amt']   = $params['child_seat_cnt'] * $booking_view['goods_car_seat_amt'];
    $params['booster_seat_amt'] = $params['booster_seat_cnt'] * $booking_view['goods_car_seat_amt'];

    //보조시트 합산 가격
    if ($total_seat_cnt > iif($booking_view['goods_category']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2)) {
        $params['total_seat_amt'] = ($params['infant_seat_amt']+$params['child_seat_amt']+$params['booster_seat_amt']) - ($booking_view['goods_car_seat_amt'] * iif($booking_view['goods_category']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2));
    }


    //렌트가격
    $params['rental_amt'] = 0;
    for ($i=1; $i<=30; $i++) {
        if ($booking_view['goods_category'] == 'C001') {
            //괌은 할인율 적용 (1일 요금기준 1~14일은 정상가, 15~30 5% 할인가)
            $params['rental_amt'] = $booking_view['goods_rent_day1_amt'] * $i;
            if ($i >= 15) {
                $params['rental_amt'] = round($params['rental_amt'] * 0.95);
            }
        } else {
            $params['rental_amt'] = $booking_view['goods_rent_day1_amt'] * $i;
        }

        if ($params['rental_day'] == $i) break;
    }


    //총 렌트 비용
    $params['total_rental_amt'] = ($params['rental_amt'] + $params['total_seat_amt'] + $booking_view['airport_meeting_amt'] + $booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt'] + $params['return_area_amt']) + ($booking_view['total_add_amt']);


	$params['upt_ip']         = $NOW_IP;
    $params['upt_id']         = $MEM_ADM['usr_id'];
    if (!$cls_booking->booking_modify_proc($params)) fnMsgJson(516, "수정 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK"}
