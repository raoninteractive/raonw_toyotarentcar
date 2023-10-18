<?include("../inc/config.php")?>
<?
	$params['gubun']       = chkReqRpl("gubun", "C001", "10", "", "STR");
	$params['goods_idx']   = chkReqRpl("goods_idx", null, "", "", "INT");
	$params['rental_date'] = chkReqRpl("rental_date", "", "10", "", "STR");
	$params['rental_hour'] = chkReqRpl("rental_hour", "09", "2", "", "STR");
	$params['rental_day']  = chkReqRpl("rental_day", 1, "", "", "INT");

	if (chkBlank($params['goods_idx'])) fnMsgGo(501, "잘못된 요청 정보 입니다.", "BACK", "");

	$cls_goods = new CLS_GOODS;

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($params['goods_idx']);
	if ($goods_view == false) fnMsgGo(502, "일치하는 상품정보가 없습니다.", "BACK", "");

    //출발가능일
    $start_rent_date = dateAdd("day", 1, date('Y-m-d'));

	//예약가능일 불러오기
	$stock_params['goods_idx'] = $params['goods_idx'];
    $stock_params['sch_sdate'] = date('Y-m-d');
    $stock_params['sch_stock'] = 'Y';
	$stock_list = $cls_goods->stock_list($stock_params);
	if (count($stock_list) == 0) fnMsgGo(503, "예약이 불가능한 상품입니다.", "BACK", "");
	if (chkBlank($params['rental_date'])) {
		for ($i=0; $i<count($stock_list); $i++) {
			if ($start_rent_date <= $stock_list[$i]['sdate']) {
				$params['rental_date'] = $stock_list[$i]['sdate'];
				break;
			}
		}
	} else {
        if ($params['rental_date'] <= $start_rent_date) {
            $params['rental_date'] = $start_rent_date;
        }

		for ($i=0; $i<count($stock_list); $i++) {
			if ($params['rental_date'] < $stock_list[$i]['sdate']) {
				$params['rental_date'] = $stock_list[$i]['sdate'];
				break;
			}
		}
	}

	//예약설정 정보 불러오기
	$setview = getBookingSettingInfoView($params['gubun']);

	//차량 인수,픽업 장소 목록
	$pickup_area_list = $setview['pickup_area'];

	//차량 반납 장소 목록
	$return_area_list = $setview['return_area'];

	//출국 항공사
	$out_airline_list = $setview['out_airline'];

	//귀국 항공사
	$in_airline_list = $setview['in_airline'];

	//호텔
	$hotel_list = $setview['hotel'];

	if ($params['gubun'] == 'C001') {
		$pageNum = "0101";
		$pageName = "예약하기";

        //괌-예약하기 팝업 목록 불러오기
        $pop_list = $cls_pop->popup_list('4');
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0201";
		$pageName = "예약하기";

        //사이판-예약하기 팝업 목록 불러오기
        $pop_list = $cls_pop->popup_list('5');
	} else {
		fnMsgGo(500, "잘못된 요청 정보 입니다.", "BACK", "");
	}
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub reservation">
	<div class="inr-c">
		<section class="area_reser pr-pd1">
			<header class="hd_titbox">
				<h2 class="hd_tit1"><span class="h">예약하기</span></h2>
			</header>

			<form name="bookingFrm" id="bookingFrm" method="post">
			<input type="hidden" name="goods_idx" value="<?=$params['goods_idx']?>">
			<div class="hd_titbox ty2">
				<h3 class="hd_tit3">상품정보</h3>
			</div>
			<div class="tbl_basic ty2 pr-mb2 mtblty1">
				<table class="write">
					<colgroup>
						<col class="th1">
						<col class="th2">
						<col class="th1">
						<col class="th2">
					</colgroup>
					<tbody>
						<tr class="tdty1">
							<th>상품정보</th>
							<td><?=$goods_view['title']?></td>
							<th>옵션</th>
							<td>
								<?
									$option_txt = "";
									if ($goods_view['option_1'] == 'Y') {
										$option_txt .= "주유포함";
									}

									if ($goods_view['option_2'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "CDW포함";
									}

									if ($goods_view['option_7'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "ZDC포함";
									}

									if ($goods_view['option_8'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "PAI포함";
									}

                                    if ($goods_view['option_9'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "SCDW포함";
									}

									if ($option_txt == "") {
										echo "없음";
									} else {
										echo $option_txt;
									}
								?>
							</td>
						</tr>
						<tr class="tdty1">
							<th>출국일</th>
							<td colspan="3">
								<input type="text" name="out_date" id="out_date" value="<?=$params['rental_date']?>" class="inp_txt calender wid2" readonly>
								<select name="out_airline" id="out_airline" class="select1 wid22">
									<option value=""><?=iif($params['gubun']=='C001', "한국->괌", "한국->사이판")?> 출국 항공편명 선택 하기</option>
									<?for ($i=0; $i<count($out_airline_list); $i++) {?>
										<option value="<?=$out_airline_list[$i]?>"><?=$out_airline_list[$i]?></option>
									<?}?>
								</select>
                                <span class="wid22 c-color">※한국 출발 항공편명 선택 필수</span>
							</td>
							<!-- <th>귀국일</th>
							<td>
								<input type="text" name="in_date" id="in_date" value="<?=dateAdd("d", $params['rental_day'], $params['rental_date'])?>" class="inp_txt calender wid2" readonly>
								<select name="in_airline" id="in_airline" class="select1 wid2">
									<option value="">이용항공사 선택</option>
									<?for ($i=0; $i<count($in_airline_list); $i++) {?>
										<option value="<?=$in_airline_list[$i]?>"><?=$in_airline_list[$i]?></option>
									<?}?>
								</select>
							</td> -->
						</tr>
						<tr>
							<th>투숙 호텔</th>
							<td colspan="3">
								<select name="hotel" id="hotel" class="select1 wid1">
									<option value="">투숙 호텔 선택</option>
									<?for ($i=0; $i<count($hotel_list); $i++) {?>
										<option value="<?=$hotel_list[$i]?>"><?=$hotel_list[$i]?></option>
									<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th>렌트 날짜 및 시간</th>
							<td>
								<input type="text" name="rental_date" id="rental_date" value="<?=$params['rental_date']?>" class="inp_txt calender wid2" readonly>
								<select name="rental_hour" id="rental_hour" class="select1 wid3">
									<?for ($i=0; $i<=23; $i++) {?>
										<option value="<?=addZero($i)?>" <?=chkCompare($params['rental_hour'], addZero($i), 'selected')?>><?=addZero($i)?>시</option>
									<?}?>
								</select>
								<select name="rental_minute" id="rental_minute" class="select1 wid3">
									<?for ($i=0; $i<=59; $i+=10) {?>
										<option value="<?=addZero($i)?>"><?=addZero($i)?>분</option>
									<?}?>
								</select>
                                <span class="wid22 c-color">※클릭 불가 날짜는 차량 마감 입니다.</span>
							</td>
							<th>렌트기간</th>
							<td>
								<select name="rental_day" id="rental_day" class="select1 wid2">
									<?
										$rental_amt = 0;
                                        $rental_amt_val = 0;
										for ($i=1; $i<=30; $i++) {
                                            if ($params['gubun'] == 'C001') {
                                                //괌은 할인율 적용 (1일 요금기준 1~14일은 정상가, 15~30 5% 할인가)
                                                $rental_amt = $goods_view['day1_amt'] * $i;
                                                if ($i >= 15) {
                                                    $rental_amt = round($rental_amt * 0.95);
                                                }
                                            } else {
                                                //사이판은 1일요금 유지
                                                $rental_amt = $goods_view['day1_amt'] * $i;
                                            }

                                            if ($i > 1 || ($params['gubun'] == 'C001' && $goods_view['option_1'] == 'N' && $i==1) || ($params['gubun'] == 'C002' && $i >= 1)) {
									            ?><option value="<?=$i?>" data-rental-amt="<?=$rental_amt?>" <?=chkCompare($params['rental_day'], $i, 'selected')?>><?=$i?>일 ($<?=$rental_amt?>)</option><?
                                            }
										}
									?>
								</select>
                                <span class="t_info">
                                    (24시간 기준)
                                    <?if ($goods_view['option_1'] == 'Y') {?>
                                        <span class="c-color">※ 주유포함의 경우 최소 2일부터 예약가능합니다.</span>
                                    <?}?>
                                </span>
							</td>
						</tr>
						<tr>
							<th>인수/픽업 위치</th>
							<td>
								<select name="pickup_area" id="pickup_area" class="select1 wid1">
									<option value="">인수/픽업 위치 선택</option>
									<?for ($i=0; $i<count($pickup_area_list); $i++) {?>
										<option value="<?=$pickup_area_list[$i]?>"><?=$pickup_area_list[$i]?></option>
									<?}?>
								</select>
							</td>
							<th>차량반납 위치</th>
							<td>
								<select name="return_area" id="return_area" class="select1 wid1">
									<option value="">차량반납 위치 선택</option>
									<?for ($i=0; $i<count($return_area_list); $i++) {?>
										<option value="<?=$return_area_list[$i]?>"><?=$return_area_list[$i]?></option>
									<?}?>
								</select>

                                <?if ($params['gubun']=='C001') {?>
                                    <span class="t_info c-color ml0 ml5" style="font-size:0.8em;">
                                        ・ 공항 반납 (24시간가능)<br>
                                        ・ 사무실 반납 (AM 8:30 ~ PM 04:00가능) 그 외 시간은 불가능<br>
                                        ・ 호텔주차장 직접 반납 (24시간 가능) - $10 추가
                                    </span>
                                <?}?>
							</td>
						</tr>
						<tr>
							<th>예약자 이름</th>
							<td colspan="3">
								<div class="t_tx ty1">
									<p>
										<span>한글이름</span>
										<input type="text" name="name_kor" id="name_kor" class="inp_txt wid2" maxlength="10" placeholder="여권 이름">
									</p>
									<p>
										<span>영문이름</span>
										<input type="text" name="name_eng1" id="name_eng1" class="inp_txt wid3 mr5" maxlength="10" placeholder="성">
										<input type="text" name="name_eng2" id="name_eng2" class="inp_txt wid2" maxlength="30" placeholder="이름">
									</p>
								</div>
							</td>
						</tr>
						<tr>
							<th>예약자 휴대폰번호</th>
							<td>
								<input type="text" name="booking_phone" id="booking_phone" class="inp_txt wid2 onlyNum" placeholder="'-' 없이 숫자만 입력" maxlength="11">
								<p class="t_info">※ 로밍 예정 핸드폰 번호 입력 요망</p>
							</td>
							<th>예약자 이메일</th>
							<td>
								<input type="text" name="booking_email" id="booking_email" class="inp_txt wid1" placeholder="아이디@서비스도메인" maxlength="50">
							</td>
						</tr>
						<!-- <tr>
							<th>여행인원</th>
							<td colspan="3">
								<div class="t_tx ty1">
									<p><span>성인</span>
										<select name="adult_cnt" id="adult_cnt" class="select1 wid2">
											<option value="">선택</option>
											<?for($i=1; $i<=10; $i++) {?>
												<option value="<?=$i?>"><?=$i?>명</option>
											<?}?>
										</select>
									</p>
									<p><span>소아</span>
										<select name="child_cnt" id="child_cnt" class="select1 wid2">
											<option value="0">선택없음</option>
											<?for($i=1; $i<=10; $i++) {?>
												<option value="<?=$i?>"><?=$i?>명</option>
											<?}?>
										</select>
									</p>
									<p><span>유아</span>
										<select name="infant_cnt" id="infant_cnt" class="select1 wid2">
											<option value="0">선택없음</option>
											<?for($i=1; $i<=10; $i++) {?>
												<option value="<?=$i?>"><?=$i?>명</option>
											<?}?>
										</select>
									</p>
								</div>
							</td>
						</tr> -->
						<?if ($goods_view['option_6']=='Y') {?>
							<tr>
								<th></th>
								<td colspan="3">
									<div class="t_tx ty2">
										<p>
											<span>유아 보조시트(~12개월)</span>
											<select name="infant_seat_cnt" id="infant_seat_cnt" class="select1">
												<option value="" data-seat-amt="0">선택없음</option>
												<?for($i=1; $i<=3; $i++) {?>
													<option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
												<?}?>
											</select>
										</p>
										<p>
											<span>어린이 보조시트 (12~24개월)</span>
											<select name="child_seat_cnt" id="child_seat_cnt" class="select1">
												<option value="" data-seat-amt="0">선택없음</option>
												<?for($i=1; $i<=3; $i++) {?>
													<option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
												<?}?>
											</select>
										</p>
										<p>
											<span>부스터 시트 (24개월~)</span>
											<select name="booster_seat_cnt" id="booster_seat_cnt" class="select1">
												<option value="" data-seat-amt="0">선택없음</option>
												<?for($i=1; $i<=3; $i++) {?>
													<option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
												<?}?>
											</select>
										</p>
									</div>

									<span class="t_info c-color ml0 mt5" style="font-size:0.8em;">
										유아나 어린이 동반시에는 카시트, 부스터를 꼭 신청하셔야 합니다.(현지교통법)<br>
										아동보조시트는 <?=iif($params['gubun']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2)?>개 무료, 초과분 <?=iif($goods_view['option_6_amt']>0, '$'.$goods_view['option_6_amt'].'/24시간', '무료')?>
										(표시가격이 있어도 <?=iif($params['gubun']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2)?>대까지 무료지원 됩니다.)
									</span>
								</td>
							</tr>
						<?}?>

						<?if ($goods_view['option_3']=='Y' || $goods_view['option_5']=='Y') {?>
							<tr>
								<th>추가선택사항</th>
								<td colspan="3">
									<div class="t_tx ty1">
										<?if ($goods_view['option_3']=='Y') {?>
											<p>
												<label class="inp_checkbox">
													<input type="checkbox" name="add_option_1" id="add_option_1" value="Y" data-option-amt="<?=$goods_view['option_3_amt']?>">
													<span>아이스박스 (<?=iif($goods_view['option_3_amt']>0, '$'.$goods_view['option_3_amt'].'/24시간', '무료')?>)</span>
												</label>
											</p>
										<?}?>

										<?if ($goods_view['option_5']=='Y') {?>
											<label class="inp_checkbox">
												<input type="checkbox" name="add_option_2" id="add_option_2" value="Y" data-option-amt="<?=$goods_view['option_5_amt']?>">
												<span>네비게이션 (<?=iif($goods_view['option_5_amt']>0, '$'.$goods_view['option_5_amt'].'/24시간', '무료')?>)</span>
											</label>
										<?}?>
									</div>
								</td>
							</tr>
						<?}?>

						<?if ($goods_view['option_4']=='Y') {?>
							<tr>
								<th>공항픽업</th>
								<td colspan="3">
									<select name="airport_meeting" id="airport_meeting" class="select1 wid1">
										<option value="" data-meeting-amt="0">선택</option>
										<!-- <option value="N" data-meeting-amt="0">개별이동</option> -->
										<option value="Y" data-meeting-amt="<?=$goods_view['option_4_amt']?>">공항픽업 (<?=iif($goods_view['option_4_amt']>0, '$'.$goods_view['option_4_amt'], '무료')?>)</option>
									</select>

                                    <?if ($params['gubun'] == 'C001') {?>
                                        <span class="t_info c-color m20">
                                            ※ 인수 장소가 "Airport Office"인 경우만 공항픽업 선택은 <b>필수</b>이며, 차량 한대 기준 1회 공항세등으로 <b>$10 추가 비용이 발생</b>이 됩니다.
                                        </span>
                                    <?}?>
								</td>
							</tr>
						<?}?>
						<tr>
							<th>추가요청사항</th>
							<td colspan="3">
								<textarea name="booking_memo" id="booking_memo" class="textarea1" placeholder="500자 이내로 입력해주세요." maxlength="500"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="tabcont">
                <?if ($params['gubun']=='C001') {?>
                    <p class="word-pre"><strong>예약 전 필수 확인 사항</strong>
                        1. 공항 픽업 시 차종 및 차량 구분 없이 한대 기준 $10 /1회 추가 됩니다. (공항세등)
                        2. 호텔 픽업 가능 시간은 <span class="c-color">AM 8:30 ~ PM 04:00</span> 입니다.
                        3. 호텔 반납 시 반납은 메인오피스 반납만 가능 하며  반납 후 투숙 하시는 호텔로 직원이 모셔다 드립니다. (무료)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;메인오피스 반납 가능 시간 : <span class="c-color">AM 8:30 ~ PM 04:00</span>
                        4. 호텔 주차장에 직접 반납 시 24시간 가능 하며 차량 한대 기준 $10 추가 됩니다.
                        5. 입금 또는 카드 결제 된 예약 대행수수료는 차량 확정 후 취소 및 변경시 별도 환불 처리가 되지 않습니다.

                        <strong class="c-color">* 요청 하신 차종은 대표 차종이며 대표 차종 외 동급 차종으로 대여가 될 수 있습니다.</strong>
                    </p>
                <?} else {?>
                    <p class="word-pre"><strong>예약 전 필수 확인 사항</strong>
                        1. 공항 사무실 (AIRPORT OFFICE)  반납 시간은 <span class="c-color">AM 02:00 ~ PM 16:30</span> 입니다.
                        2. 메인 오피스 (MAIN OFFICE) 반납 가능 시간은 <span class="c-color">AM 08:00 ~ PM 16:30</span> 입니다.

                        <strong class="c-color">* 일요일 및 홀리데이인 경우 공항 사무실만 운영을 하므로 메인 오피스 반납은 불가능 하며 공항 사무실 반납은 가능 합니다.</strong>
                        <strong class="c-color">* 공항 또는 메인오피스 반납 해 주시면 호텔로 직원이 직접 모셔다 드립니다. (무료)</strong>
                    </p>
                <?}?>
			</div>

			<?for($i=1; $i<=2; $i++) {?>
				<!-- 추후 노출 | 20211020
                <div class="hd_titbox ty2">
					<h3 class="hd_tit3">운전자정보 <?=$i?> <?=iif($i==1,'(필수)','(선택)')?></h3>
				</div>
				<div class="tbl_basic ty2 pr-mb2 mtblty1">
					<table class="write">
						<colgroup>
							<col class="th1">
							<col class="th2">
							<col class="th1">
							<col class="th2">
						</colgroup>
						<tbody>
							<tr class="tdty1">
								<th>운전자 이름</th>
								<td colspan="3">
									<div class="t_tx ty1">
										<p>
											<span>한글이름</span>
											<input type="text" name="driver_name_kor<?=$i?>" id="driver_name_kor<?=$i?>" class="inp_txt wid2" maxlength="10" placeholder="여권 이름">
										</p>
										<p>
											<span>영문이름</span>
											<input type="text" name="driver_name_eng1<?=$i?>" id="driver_name_eng1<?=$i?>" class="inp_txt wid3 mr5" maxlength="10" placeholder="성">
											<input type="text" name="driver_name_eng2<?=$i?>" id="driver_name_eng2<?=$i?>" class="inp_txt wid2" maxlength="30" placeholder="이름">
										</p>
									</div>
								</td>
							</tr>
							<tr class="tdty1">
								<th>한국 주소</th>
								<td colspan="3">
									<div>
										<input type="hidden" name="driver_zipcode<?=$i?>" id="driver_zipcode<?=$i?>" />
										<input type="text" name="driver_addr<?=$i?>" id="driver_addr<?=$i?>" class="inp_txt" style="width:60%;"
											onclick="postCode('driver_zipcode<?=$i?>', 'driver_addr<?=$i?>', 'driver_addr_detail<?=$i?>')" placeholder="현재 거주 중인 주소를 선택해 주세요." readonly>
										<input type="text" name="driver_addr_detail<?=$i?>" id="driver_addr_detail<?=$i?>" class="inp_txt" style="width:39%" maxlength="100" placeholder="상세 주소를 입력해 주세요.">
									</div>
								</td>
							</tr>
							<tr class="tdty1">
								<th>현지 주소</th>
								<td colspan="3">
									<input type="text" name="driver_local_addr<?=$i?>" id="driver_local_addr<?=$i?>" class="inp_txt" style="width:100%" maxlength="200"
										placeholder="<?=iif($params['gubun']=='C001','괌','사이판')?> 현지에서 거주 중인 주소 또는 투숙 호텔 정보를 입력해 주세요.">
								</td>
							</tr>
							<tr>
								<th>휴대폰 번호</th>
								<td>
									<input type="text" name="driver_phone<?=$i?>" id="driver_phone<?=$i?>" class="inp_txt wid2 onlyNum" placeholder="'-' 없이 숫자만 입력" maxlength="11">
									<p class="t_info">※ 로밍 예정 핸드폰 번호 입력 요망</p>
								</td>
								<th>생년월일</th>
								<td>
									<input type="text" name="driver_birthdate<?=$i?>" id="driver_birthdate<?=$i?>" class="inp_txt wid2 onlyNum" placeholder="예) <?=date('Ymd')?>" maxlength="8">
								</td>
							</tr>
							<tr>
								<th>운전면허증 번호</th>
								<td>
									<input type="text" name="driver_license<?=$i?>" id="driver_license<?=$i?>" class="inp_txt wid1" placeholder="예) 서울 01-123456-78" maxlength="20">
								</td>
								<th>운전면허증 만료일</th>
								<td>
									<input type="text" name="driver_license_expiry_date<?=$i?>" id="driver_license_expiry_date<?=$i?>" class="inp_txt wid2 onlyNum" placeholder="예) <?=date('Ymd')?>" maxlength="8">
								</td>
							</tr>
						</tbody>
					</table>
				</div> -->
			<?}?>

			<div class="tbl_box">
				<p><em>총 대여일</em> <span class="c-color total_rental_day"><?=$params['rental_day']?>일</span></p>
				<p><em>현장 지불금액</em> <span class="c-color total_rental_amt">$0</span></p>
				<p><em>예약 대행수수료 결제 금액</em> <span class="c-color">￦<?=formatNumbers($goods_view['agency_fee'])?></span></p>
			</div>

			<div class="area_agree">
				<div><label class="inp_checkbox"><input type="checkbox" id="agree_1" value="Y"><span>(필수)이용약관에 동의합니다. <a href="/customer/terms.php" target="_blank">보기</a></span></label></div>
				<div><label class="inp_checkbox"><input type="checkbox" id="agree_2" value="Y"><span>(필수)개인정보처리방침에 동의합니다. <a href="/customer/privacy.php" target="_blank">보기</a></span></label></div>
				<div class="btn-bot">
					<a href="javascript:;" class="btn-pk nb color rv" onclick="bookingGo()"><span>예약하기</span></a>
				</div>
			</div>
			</form>

			<div class="box_infor">
				<p>※ 아동보조시트의 경우 정확한 나이와 개수를 입력해 주세요.</p>
				<p class="c-color">
                    ※ 공항 픽업 / 반납의 경우 정확한 항공편명을 기입해 주셔야 합니다. (이용 항공편명이 없는 경우 메모 란에 항공편명 기입)
					<br>※ 차량 픽업 호텔이 없는 경우에는 메모 란에 픽업 및 반납을 원하시는 호텔을 기입해 주세요(단. 호텔만 가능함)
					<br>※ 아이스박스는 출발전에 필히 예약이 되어야 이용이 가능 합니다.
				</p>
			</div>
		</section>
	</div>
</div><!--//container -->

<script>
	var enable_day = "";
	$(function(){
		$("#out_date").datepicker({
			minDate: "<?=date('Y-m-d')?>",
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png"
		});

		//출발가능 체크
		AJ.callAjax("_check_date.php", {"goods_idx": <?=$goods_view['idx']?>}, function(data){
			if (data.result == 200) {
				if (data.list) {
					setTimeout(function(){
                        var enable_day = [];
                        $.each(data.list, function(i, item){
                            enable_day.push(item.date);
                        })

						$("#rental_date").datepicker({
							minDate: "<?=$start_rent_date?>",
							dateFormat: "yy-mm-dd",
							showOn: "both",
							buttonImage: "/images/common/ico_calender.png",
							beforeShowDay: function(date) {
								var dummy = date.getFullYear() + "-" + addZero(date.getMonth() + 1) +"-"+ addZero(date.getDate());

								if ($.inArray(dummy, enable_day) > -1) {
									return [true, ""];
								} else {
                                    return [false, ""];
                                }
							}
						});
					},0)
				} else {
				}
			} else {
				alert(data.message);

				location.back();
			}
		});

		//예약금액 확인
		$("#rental_day, #infant_seat_cnt, #child_seat_cnt, #booster_seat_cnt, #airport_meeting, #return_area").change(function(){
			totalRentalAmountCalc();
		})
		$("#add_option_1, #add_option_2").click(function(){
			totalRentalAmountCalc();
		})
		$("#rental_day").trigger("change");


		//영문 대문자변환
		$("#name_eng1, #name_eng2, #driver_name_eng11, #driver_name_eng21, #driver_name_eng12, #driver_name_eng22").blur(function(){
			var this_val = $(this).val();

			$(this).val( this_val.toUpperCase() );
		})
	})

	function totalRentalAmountCalc() {
		var rental_day          = parseInt($("#rental_day").val());
		var rental_amt          = $("#rental_day").find("option:selected").data("rental-amt");
		var infant_seat_cnt     = parseInt($("#infant_seat_cnt").val());
		var infant_seat_amt     = $("#infant_seat_cnt").find("option:selected").data("seat-amt");
		var child_seat_cnt      = parseInt($("#child_seat_cnt").val());
		var child_seat_amt      = $("#child_seat_cnt").find("option:selected").data("seat-amt");
		var booster_seat_cnt    = parseInt($("#booster_seat_cnt").val());
		var booster_seat_amt    = $("#booster_seat_cnt").find("option:selected").data("seat-amt");
		var airport_meeting_amt = $("#airport_meeting").find("option:selected").data("meeting-amt");
		var add_option_1_amt    = $("#add_option_1:checked").data("option-amt");
		var add_option_2_amt    = $("#add_option_2:checked").data("option-amt");
        var return_area_amt     = 0;

		if (!infant_seat_cnt) infant_seat_cnt = 0;
		if (!infant_seat_amt) infant_seat_amt = 0;
		if (!child_seat_cnt) child_seat_cnt = 0;
		if (!child_seat_amt) child_seat_amt = 0;
		if (!booster_seat_cnt) booster_seat_cnt = 0;
		if (!booster_seat_amt) booster_seat_amt = 0;
		if (!airport_meeting_amt) airport_meeting_amt = 0;
		if (!add_option_1_amt) add_option_1_amt = 0;
		if (!add_option_2_amt) add_option_2_amt = 0;

		var total_seat_amt = 0;
		<?if ($goods_view['option_6']=='Y') {?>
			if ((infant_seat_cnt + child_seat_cnt + booster_seat_cnt) > <?=iif($params['gubun']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2)?>) {
				total_seat_amt = (infant_seat_amt + child_seat_amt + booster_seat_amt) - <?=$goods_view['option_6_amt'] * iif($params['gubun']=='C001', $CONST_CAR_SEAT_FREE, $CONST_CAR_SEAT_FREE2)?>;
			}
		<?}?>

        //차량반납위치가 공항이랑 오피스 제외 하고 호텔을 선택 하는 경우 $10 자동으로 추가
        <?if ($params['gubun']=='C001') {?>
        if ($("#return_area").val() != "" && ($("#return_area").val().indexOf("AIRPORT") == -1 && $("#return_area").val().indexOf("MAIN OFFICE") == -1)) {
            return_area_amt += <?=$CONST_RETURN_AREA_AMT?>;
        }
        <?}?>

		var total_rental_amt = rental_amt + total_seat_amt + airport_meeting_amt + add_option_1_amt + add_option_2_amt + return_area_amt;

		$(".total_rental_day").text(rental_day+"일");
		$(".total_rental_amt").text("$"+total_rental_amt);
	}

	function bookingGo() {
		var h = new clsJsHelper();

		if (!h.checkSelect("out_airline", "<?=iif($params['gubun']=='C001', "한국->괌", "한국->사이판")?> 출국 항공편")) return false;
		//if (!h.checkSelect("in_airline", "귀국일 항공사")) return false;
		if (!h.checkSelect("hotel", "투숙 호텔")) return false;
		if (!h.checkSelect("pickup_area", "인수/픽업 위치")) return false;
		if (!h.checkSelect("return_area", "차량반납 위치")) return false;
		if (!h.checkValNLen("name_kor", 2, 20, "예약자 한글 이름", "Y", "KO")) return false;
		if (!h.checkValNLen("name_eng1", 2, 10, "예약자 영문(성)", "Y", "EN")) return false;
		if (!h.checkValNLen("name_eng2", 2, 30, "예약자 영문(이름)", "Y", "EN")) return false;
		if (!h.checkValNLen("booking_phone", 10, 11, "예약자 휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("booking_phone"), "예약자 휴대폰번호", "")) return false;
		if (!h.checkValNLen("booking_email", 10, 50, "예약자 이메일", "Y", "EN")) return false;
		if (!h.checkEmail("booking_email", "예약자 이메일")) return false;
		//if (!h.checkSelect("adult_cnt", "여행인원(성인)")) return false;
		//if ((parseInt(h.objVal("adult_cnt")) + parseInt(h.objVal("child_cnt")) + parseInt(h.objVal("infant_cnt"))) >= 10) {
		//	alert("여행인원은 최대 10명까지 선택 가능합니다.");
		//	return false;
		//}

		if (h.objVal("booking_memo")) {
			if (!h.checkValNLen("booking_memo", 1, 1000, "추가요청사항", "N", "KO")) return false;
		}

		if (h.objVal("out_date") > h.objVal("rental_date")) {
			alert("렌트 시작 날짜는 출국일 보다 작을 수 없습니다.\n렌트 시작 날짜를 다시 확인해주세요.");
			return false;
		}

		/*
		var rent_period = moment(h.objVal("rental_date")).add(h.objVal("rental_day"),'days').format("YYYY-MM-DD");
		if (rent_period > h.objVal("in_date")) {
			alert("귀국일은 렌트 종료 날짜("+ rent_period +") 보다 작을 수 없습니다.\n귀국일을 다시 확인해주세요.");
			return false;
		}
		*/


		<?if ($goods_view['option_6']=='Y') {?>
			var total_seat_cnt   = 0;
			var infant_seat_cnt  = parseInt($("#infant_seat_cnt").val());
			var child_seat_cnt   = parseInt($("#child_seat_cnt").val());
			var booster_seat_cnt = parseInt($("#booster_seat_cnt").val());

			if (!infant_seat_cnt) infant_seat_cnt = 0;
			if (!child_seat_cnt) child_seat_cnt = 0;
			if (!booster_seat_cnt) booster_seat_cnt = 0;

			total_seat_cnt = infant_seat_cnt + child_seat_cnt + booster_seat_cnt;

			/*
			if (total_seat_cnt > 0) {
				var child_cnt  = parseInt($("#child_cnt").val());
				var infant_cnt = parseInt($("#infant_cnt").val());

				if (!child_cnt) child_cnt = 0;
				if (!infant_cnt) infant_cnt = 0;

				if ((child_cnt+infant_cnt) < total_seat_cnt) {
					alert("소아/유아의 인원수가 보조시트 신청 개수 보다 작습니다.\n소아/유아의 인원수를 선택한 보조시트 개수 보다 같거나 크게 선택해 주세요.");
					return false;
				}
			}
			*/
		<?}?>

		<?if ($goods_view['option_4']=='Y') {?>
            //if (!h.checkSelect("airport_meeting", "공항픽업")) return false;

            <?if ($params['gubun'] == 'C001') {?>
                if (h.objVal("pickup_area").toUpperCase().indexOf("AIRPORT") > -1) {
                    if (h.objVal("airport_meeting") != "Y") {
                        alert("인수 픽업 장소가 'Airport Office'인 경우 공항픽업 선택은 필수 입니다.");
                        return false;
                    }
                } else {
                    if (h.objVal("airport_meeting") == "Y") {
                        alert("공항픽업은 인수 픽업 장소가 'Airport Office'인 경우만 선택 가능합니다.");
                        return false;
                    }
                }
            <?}?>
		<?}?>

        /* 추후 노출 | 20211020
		for (i=1; i<=2; i++) {
			if (i==1 || h.objVal("driver_name_kor"+i) || h.objVal("driver_name_eng1"+i) || h.objVal("driver_name_eng2"+i)
				|| h.objVal("driver_addr"+i) || h.objVal("driver_addr_detail"+i) || h.objVal("driver_local_addr"+i)
				|| h.objVal("driver_phone"+i) || h.objVal("driver_birthdate"+i) || h.objVal("driver_license"+i) || h.objVal("driver_license_expiry_date"+i)
			) {
				if (!h.checkValNLen("driver_name_kor"+i, 2, 20, "운전자 "+ i +"의 한글 이름", "Y", "KO")) return false;
				if (!h.checkValNLen("driver_name_eng1"+i, 2, 10, "운전자 "+ i +"의 영문(성)", "Y", "EN")) return false;
				if (!h.checkValNLen("driver_name_eng2"+i, 2, 30, "운전자 "+ i +"의 영문(이름)", "Y", "EN")) return false;
				if (!h.checkSelect("driver_addr"+i, "운전자 "+ i +"의 주소")) return false;
				if (h.objVal("driver_addr_detail"+i)) {
					if (!h.checkValNLen("driver_addr_detail"+i, 1, 100, "운전자 "+ i +"의 상세주소", "N", "KO")) return false;
				}
				if (!h.checkValNLen("driver_local_addr"+i, 1, 200, "운전자 "+ i +"의 현지주소", "N", "KO")) return false;
				if (!h.checkValNLen("driver_phone"+i, 10, 11, "운전자 "+ i +"의 휴대폰번호", "Y", "ON")) return false;
				if (!phoneRegExpCheck(h.objVal("driver_phone"+i), "운전자 "+ i +"의 휴대폰번호", "")) return false;
				if (!h.checkValNLen("driver_birthdate"+i, 8, 8, "운전자 "+ i +"의 생년월일", "Y", "ON")) return false;
				if(!moment(h.objVal("driver_birthdate"+i), 'YYYYMMDD' , true).isValid()) {
					alert("생년월일이 유효하지 않습니다.\n생년월일을 다시 확인해주세요.")
					return false;
				}
				if (!h.checkValNLen("driver_license"+i, 2, 20, "운전자 "+ i +"의 운전면허번호", "N", "KO")) return false;
				if (!h.checkValNLen("driver_license_expiry_date"+i, 8, 8, "운전자 "+ i +"의 운전면허 만료일", "Y", "ON")) return false;
				if(!moment(h.objVal("driver_license_expiry_date"+i), 'YYYYMMDD' , true).isValid()) {
					alert("만료일이 유효하지 않습니다.\n만료일을 다시 확인해주세요.")
					return false;
				}
			}
		}
        */

		if (!$("#agree_1").is(":checked")) {
			alert("이용약관에 동의하셔야 합니다.");
			return false;
		}
		if (!$("#agree_2").is(":checked")) {
			alert("개인정보처리방침에 동의하셔야 합니다.");
			return false;
		}

		if (!confirm("입력하신 정보로 예약을 진행하시겠습니까?")) return false;

		AJ.ajaxForm($("#bookingFrm"), "booking_proc.php", function(data) {
			if (data.result == 200) {
				location.replace("reservation4.php?token="+data.token);
			} else {
				alert(data.message);
			}
		});
	}
</script>

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>