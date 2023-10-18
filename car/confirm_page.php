<?include("../inc/config.php")?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "WCLOSE", "");

	$cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "WCLOSE", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', '', '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "WCLOSE", "");


	$guide_notice = getBookingSettingInfoView($booking_view['goods_category'])['guide_notice'];

	$pageNum = "0003";
	$pageName = "확정서메인";
?>
<? include "../inc/top.php" ?>

<section id="container" class="container">
	<div class="inr-c">
		<header class="hd_titbox">
			<div class="hd_tit1"><img src="../images/common/logo.png" alt=""></div>
			<div class="rgh">
				<h2 class="hd_tit1"><span class="h">도요타 렌터카 예약 확정서</span></h2>
			</div>
		</header>

		<h2 class="hd_tit2"><span class="h">예약정보</span></h2>
		<div class="tbl_basic ty2">
			<table class="view">
				<colgroup>
					<col style="width:20%;">
					<col style="width:20%;">
					<col style="width:20%;">
					<col style="width:20%;">
					<col style="width:20%;">
				</colgroup>
				<tbody>
					<tr>
						<th class="bdln">고객명<br><span class="fz-s2 c-gray">(NAME)</span></th>
						<td colspan="4" class="ta-l"><?=$booking_view['name']?> (<?=$booking_view['eng_name1']?> <?=$booking_view['eng_name2']?>)</td>
					</tr>
					<tr>
						<th class="bdln">수령일시<br><span class="fz-s2 c-gray">(PICK UP DATE)</span></th>
						<th>인수/픽업 장소<br><span class="fz-s2 c-gray">(PICK UP PLACE)</span></th>
						<th>반납일<br><span class="fz-s2 c-gray">(RETURN DATE)</span></th>
						<th>반납장소<br><span class="fz-s2 c-gray">(RETURN PLACE)</span></th>
						<th>차종<br><span class="fz-s2 c-gray">(MODEL)</span></th>
					</tr>
					<tr>
						<td class="bdln"><?=formatDates($booking_view['rental_sdate'], 'Y.m.d')?> <?=$booking_view['rental_time']?></td>
						<td><?=$booking_view['pickup_area']?></td>
						<td><?=formatDates($booking_view['rental_edate'], 'Y.m.d')?></td>
						<td><?=$booking_view['return_area']?></td>
						<td><?=$booking_view['goods_title']?></td>
					</tr>
					<tr>
						<th class="bdln">이용기간<br><span class="fz-s2 c-gray">(PERIOD)</span></th>
						<th colspan="3">렌트비용<br><span class="fz-s2 c-gray">(TOTAL FEE)</span></th>
						<th>컨펌번호<br><span class="fz-s2 c-gray">(CONFIRM NR)</span></th>
					</tr>
					<tr>
						<td class="bdln"><?=$booking_view['rental_day']?> DAY(S)</td>
						<td colspan="3">
							<strong class="c-color">$<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?></strong>
							<span style="font-size:0.8em;">
								(렌트비 : $<?=formatNumbers($booking_view['rental_amt'])?>  /
								아동보조시트 : $<?=formatNumbers($booking_view['total_seat_amt'])?> /
								추가선택사항 : $<?=formatNumbers($booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt'])?> /
								공항픽업 : $<?=formatNumbers($booking_view['airport_meeting_amt'])?>
                                <?if ($booking_view['return_area_amt'] != 0) {?>
									/ 반납장소비용 : $<?=formatNumbers($booking_view['return_area_amt'])?>
								<?}?>
								<?if ($booking_view['total_add_amt'] != 0) {?>
									/ 추가・할인 : $<?=formatNumbers($booking_view['total_add_amt'])?>
								<?}?>)
							</span>
						</td>
						<td><?=$booking_view['confirm_num']?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="bind">
			<div class="tbl_basic ty2">
				<table class="view">
					<tr>
						<th colspan="2" class="bdln">추가 선택사항</th>
					</tr>
					<tr>
						<td class="bdln">아이스박스</td>
						<td>
							<?if ($booking_view['add_option_1_flag'] == 'Y') {?>
								<?=iif($booking_view['add_option_1'] == 'Y', iif($booking_view['add_option_1_amt']>0, '$'.formatNumbers($booking_view['add_option_1_amt']), '무료'), '선택없음')?>
							<?} else {?>
								선택없음
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="bdln">네비게이션</td>
						<td>
							<?if ($booking_view['add_option_2_flag'] == 'Y') {?>
								<?=iif($booking_view['add_option_2'] == 'Y', iif($booking_view['add_option_2_amt']>0, '$'.formatNumbers($booking_view['add_option_2_amt']), '무료'), '선택없음')?>
							<?} else {?>
								선택없음
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="bdln">공항픽업</td>
						<td>
							<?if ($booking_view['airport_meeting_flag'] == 'Y') {?>
								<?=iif($booking_view['airport_meeting'] == 'Y', iif($booking_view['airport_meeting_amt']>0, '$'.formatNumbers($booking_view['airport_meeting_amt']), '무료'), '개별이동')?>
							<?} else {?>
								<?$booking_view['airport_meeting_amt'] = 0;?>
								<?=iif($booking_view['airport_meeting'] == 'Y', '선택없음', '개별이동')?>
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="bdln">유아 보조시트</td>
						<td>
							<?if ($booking_view['infant_seat_cnt'] > 0) {?>
								<?=$booking_view['infant_seat_cnt']?>개 (<?=iif($booking_view['infant_seat_amt']>0, '$'.formatNumbers($booking_view['infant_seat_amt']), '무료')?>)
							<?} else {?>
								선택없음
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="bdln">어린이 보조시트</td>
						<td>
							<?if ($booking_view['child_seat_cnt'] > 0) {?>
								<?=$booking_view['child_seat_cnt']?>개 (<?=iif($booking_view['child_seat_amt']>0, '$'.formatNumbers($booking_view['child_seat_amt']), '무료')?>)
							<?} else {?>
								선택없음
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="bdln">부스터 시트</td>
						<td>
							<?if ($booking_view['booster_seat_cnt'] > 0) {?>
								<?=$booking_view['booster_seat_cnt']?>개 (<?=iif($booking_view['booster_seat_amt']>0, '$'.formatNumbers($booking_view['booster_seat_amt']), '무료')?>)
							<?} else {?>
								선택없음
							<?}?>
						</td>
					</tr>
				</table>
			</div>
			<div class="tbl_basic ty2">
				<table class="view">
					<tr>
						<th class="bdln">TOTAL</th>
					</tr>
					<tr>
						<td class="bdln hei4"><strong class="c-color">$<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?></strong></td>
					</tr>
				</table>
			</div>
		</div>

		<h2 class="hd_tit2"><span class="h">여행 안내 사항</span></h2>
		<div class="notice_cont">
			<?=htmlDecode($guide_notice)?>
		</div>

		<!-- <p>
			렌터카 사용 안내문(필독사항) : 현지비상연락처, 차량보험, 이용방법 등 확인 가능 하세요.
			<br><a href="#">http://www.toyota-rentcar.co.kr/information/?gubun=guam</a>
			<br><a href="#" class="c-color">자세히보기 클릭</a>
		</p> -->

		<div class="btn-bot">
			<a href="javascript:;" class="btn-pk nb color rv w100p" onclick="printGo()"><span>프린트 하기</span></a>
		</div>
	</div>
</section><!--//container -->

<script>
	function printGo() {
		$(".btn-bot").hide();

		window.print()

		setTimeout(function(){
			$(".btn-bot").show();
		},100)
	}
</script>

<?include("../inc/bottom.php")?>