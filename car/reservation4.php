<?include("../inc/config.php")?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_goods = new CLS_GOODS;
	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', $token_data['booker_phone'], '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($booking_view['goods_idx']);
	if ($goods_view == false) fnMsgGo(503, "일치하는 상품정보가 없습니다.", "BACK", "");

	//확정서 토큰 생성
    $confirm_token = $cls_jwt->hashing(array(
		'booking_num'=> $booking_view['booking_num']
	));

	$pageNum = "0304";
	$pageName = "예약확인";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
	<div class="inr-c">
		<section class="area_reser pr-pd1">
			<header class="hd_titbox">
				<h2 class="hd_tit1"><span class="h">예약확인</span></h2>
				<div class="rgh">
					<p>예약 접수일 : <?=formatDates($booking_view['reg_date'], 'Y.m.d H:i:s')?></p>
				</div>
			</header>

			<div class="tbl_basic ty2 pr-mb2 mtblty1">
				<table class="view">
					<colgroup>
						<col class="th1">
						<col class="th2">
						<col class="th1">
						<col class="th2">
					</colgroup>
					<tbody>
						<tr>
							<th>예약번호</th>
							<td><strong><?=$booking_view['booking_num']?></strong> <strong class="c-color ml10">(※ 예약확인을 위해 꼭 기억해주세요.)</strong></td>
							<th>예약상태</th>
							<td>
								<?=getResvStatusName($booking_view['status'])?>
								<?if ($booking_view['status'] == '40') {?>
									<?if ($booking_view['confirm_status'] != '30') {?>
										<a href="javascript:;" class="btn-pk s gray ml20"><span>확정서 <?=getConfirmCateName($booking_view['confirm_status'])?></span></a>
									<?} else {?>
										<a href="javascript:;" onclick="popupOpen('/car/confirm_page.php?token=<?=$confirm_token?>','pop','1000','700');" class="btn-pk s color ml20"><span>확정서 <span class="hide-m">보기</span></span></a>
									<?}?>
								<?}?>
							</td>
						</tr>
						<tr>
							<th>상품정보</th>
							<td><strong class="c-color"><?=getGoodsCateName($booking_view['goods_category'])?></strong> - <?=$booking_view['goods_title']?></td>
							<th>옵션</th>
							<td><?=$booking_view['goods_options']?></td>
						</tr>
						<tr>
							<th>출국일(항공편)</th>
							<td colspan="3"><?=formatDates($booking_view['out_date'], 'Y.m.d')?> (<?=$booking_view['out_airline']?>)</td>
							<!-- <th>귀국일(항공편)</th>
							<td><?=formatDates($booking_view['in_date'],'Y.m.d')?> (<?=$booking_view['in_airline']?>)</td> -->
						</tr>
						<tr>
							<th>수령일시</th>
							<td><?=formatDates($booking_view['rental_sdate'], 'Y.m.d')?> <?=$booking_view['rental_time']?></td>
							<th>반납일</th>
							<td><?=formatDates($booking_view['rental_edate'], 'Y.m.d')?> ($<?=formatNumbers($booking_view['rental_amt'])?>/<?=$booking_view['rental_day']?>일)</td>
						</tr>
						<tr>
							<th>인수/픽업 장소</th>
							<td><?=$booking_view['pickup_area']?></td>
							<th>차량반납 장소</th>
							<td><?=$booking_view['return_area']?></td>
						</tr>
						<tr>
							<th>예약자 이름</th>
							<td colspan="3"><?=$booking_view['name']?> (<?=$booking_view['eng_name1']?> <?=$booking_view['eng_name2']?>)</td>
						</tr>
						<tr>
							<th>연락처</th>
							<td><?=$booking_view['phone']?> <strong class="c-color ml10">(※ 예약확인을 위해 꼭 기억해주세요.)</strong></td>
							<th>이메일</th>
							<td><?=$booking_view['email']?></td>
						</tr>
						<!-- <tr>
							<th>여행인원</th>
							<td colspan="3">
								성인 <?=$booking_view['adult_cnt']?>명
								<?if ($booking_view['child_cnt'] > 0) {?>
									/ 소아 <?=$booking_view['child_cnt']?>명
								<?}?>
								<?if ($booking_view['infant_cnt'] > 0) {?>
									/ 유아 <?=$booking_view['infant_cnt']?>명
								<?}?>
							</td>
						</tr> -->
						<tr>
							<th>아동보조시트</th>
							<td colspan="3">
								<?if ($booking_view['infant_seat_cnt']>0 || $booking_view['child_seat_cnt']>0 || $booking_view['booster_seat_cnt']>0) {?>
									<?if ($booking_view['infant_seat_cnt'] > 0) {?>
										<p>유아 보조시트(~12개월) : <?=$booking_view['infant_seat_cnt']?>개 (<?=iif($booking_view['infant_seat_amt']>0, '$'.formatNumbers($booking_view['infant_seat_amt']), '무료')?>)</p>
									<?}?>
									<?if ($booking_view['child_seat_cnt'] > 0) {?>
										<p>어린이 보조시트 (12~24개월) : <?=$booking_view['child_seat_cnt']?>개 (<?=iif($booking_view['child_seat_amt']>0, '$'.formatNumbers($booking_view['child_seat_amt']), '무료')?>)</p>
									<?}?>
									<?if ($booking_view['booster_seat_cnt'] > 0) {?>
										<p>부스터 시트 (24개월~) : <?=$booking_view['booster_seat_cnt']?>개 (<?=iif($booking_view['booster_seat_amt']>0, '$'.formatNumbers($booking_view['booster_seat_amt']), '무료')?>)</p>
									<?}?>

									<span class="t_info c-color ml0 mt5" style="font-size:0.8em;">
										유아나 어린이 동반시에는 카시트, 부스터를 꼭 장착하여야 합니다.(현지교통법)<br>
										표시가격이 있어도 <?=$booking_view['seat_free_cnt']?>대까지 무료지원 됩니다.
									</span>
								<?} else {?>
									선택안함
								<?}?>
							</td>
						</tr>
						<tr>
							<th>추가선택사항</th>
							<td colspan="3">
								<?if ($booking_view['add_option_1_flag'] == 'Y') {?>
									아이스박스 (<?=iif($booking_view['add_option_1'] == 'Y', iif($booking_view['add_option_1_amt']>0, '$'.formatNumbers($booking_view['add_option_1_amt']), '무료'), '선택안함')?>)
								<?} else {?>
									<?$booking_view['add_option_1_amt'] = 0;?>
									아이스박스 (예약불가)
								<?}?>
								/
								<?if ($booking_view['add_option_2_flag'] == 'Y') {?>
									네비게이션 (<?=iif($booking_view['add_option_2'] == 'Y', iif($booking_view['add_option_2_amt']>0, '$'.formatNumbers($booking_view['add_option_2_amt']).'/24시간', '무료'), '선택안함')?>)
								<?} else {?>
									<?$booking_view['add_option_2_amt'] = 0;?>
									네비게이션 (예약불가)
								<?}?>
							</td>
						</tr>
						<tr>
							<th>공항픽업</th>
							<td colspan="3">
								<?if ($booking_view['airport_meeting_flag'] == 'Y') {?>
									<?=iif($booking_view['airport_meeting'] == 'Y', iif($booking_view['airport_meeting_amt']>0, '공항픽업 ($'.formatNumbers($booking_view['airport_meeting_amt']).')', '(무료)'), '개별이동')?>
								<?} else {?>
									<?$booking_view['airport_meeting_amt'] = 0;?>
									<?=iif($booking_view['airport_meeting'] == 'Y', '공항픽업 (예약불가-개별이동)', '개별이동')?>
								<?}?>
							</td>
						</tr>
						<tr>
							<th>현장 지불금액</th>
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
						</tr>
						<tr>
							<th>요청사항</th>
							<td colspan="3"><?=textareaDecode($booking_view['booking_memo'])?></td>
						</tr>
					</tbody>
				</table>
			</div>

			<?for($i=1; $i<=2; $i++) {?>
				<?if ($booking_view['driver_name'.$i] != '') {?>
					<!-- <div class="hd_titbox ty2">
						<h3 class="hd_tit3">운전자정보 <?=$i?></h3>
					</div>
					<div class="tbl_basic ty2 pr-mb2 mtblty1">
						<table class="view">
							<colgroup>
								<col class="th1">
								<col class="th2">
								<col class="th1">
								<col class="th2">
							</colgroup>
							<tbody>
								<tr class="tdty1">
									<th>운전자 이름</th>
									<td colspan="3"><?=$booking_view['driver_name'.$i]?>(<?=$booking_view['driver_name_eng'.$i]?>)</td>
								</tr>
								<tr class="tdty1">
									<th>한국 주소</th>
									<td colspan="3"><?=$booking_view['driver_home_addr'.$i]?></td>
								</tr>
								<tr class="tdty1">
									<th>현지 주소</th>
									<td colspan="3"><?=$booking_view['driver_local_addr'.$i]?></td>
								</tr>
								<tr>
									<th>휴대폰 번호</th>
									<td><?=$booking_view['driver_phone'.$i]?></td>
									<th>생년월일</th>
									<td><?=$booking_view['driver_birthdate'.$i]?></td>
								</tr>
								<tr>
									<th>운전면허증 번호</th>
									<td><?=$booking_view['driver_license'.$i]?></td>
									<th>운전면허증 만료일</th>
									<td><?=$booking_view['driver_license_expiry_date'.$i]?></td>
								</tr>
							</tbody>
						</table>
					</div> -->
				<?}?>
			<?}?>

			<?if ($booking_view['notice'] != '') {?>
				<div class="hd_titbox ty2">
					<h3 class="hd_tit3">담당자 안내문</h3>
				</div>
				<div class="box_infor line mt0">
					<p><?=textareaDecode($booking_view['notice'])?></p>
				</div>
			<?}?>

			<div class="box_reservation">
				<p class="h1">예약 대행수수료 결제 금액<br><span class="c-color ml10">(차량비와는 별도 입니다.)</span></p>
				<p class="h1"><strong>￦<?=formatNumbers($booking_view['booking_agency_fee'])?></strong></p>
				<div class="btn-bot">
					<?if ($booking_view['status'] == '10') {?>
						<!-- <a href="javascript:;" class="btn-pk nb color rv"><span>예약정보수정</span></a> -->
					<?} else if ($booking_view['status'] == '20') {?>
						<a href="javascript:;" class="btn-pk nb color rv" onclick="paymentGo('BANK')"><span>무통장 입금 확인요청</span></a>
						<a href="javascript:;" class="btn-pk nb color rv" onclick="paymentGo('CARD')"><span>카드결제</span></a>
					<?}?>

					<?if (strpos("10,20,30", $booking_view['status']) !== false && dateDiff("d", $booking_view['out_date'], date('Y-m-d')) < -10) {?>
						<!-- <a href="javascript:;" class="btn-pk nb gray rv" onclick="cancelReqGo()"><span>예약취소요청</span></a> -->
					<?}?>
				</div>
				<?if ($booking_view['status'] == '10') {?>
					<p class="t1">※ 결제완료 후에 예약 요청이 진행 됩니다.</p>
				<?} else if ($booking_view['status'] == '20') {?>
                    <!-- <p class="t1">※ 결제완료 후에 예약 요청이 진행 됩니다.</p>
                    <p class="t1">※ 무통장 입금은 <strong class="c-color">예약 후 6시간 이내</strong>에 입금을 해 주셔야 예약 요청이 되며 미입금시 자동 취소 처리 됩니다.</p> -->

                    <p class="t1">※카드 및 무통장 즉시 결제 해 주셔야 정상적으로 차량 예약이 진행이 됩니다.</p>
                    <p class="t1">※미결제시 차량 요청이 불가능 하며 무통장입금 후 예약 확인 페이지에 “무통장 입금 확인요청” 부탁 드리며 입금자명 및 예약자명이 동일해야 입금확인이 가능 합니다.<br>(무통장 입금은 예약시간 기준 6시간이내입금/미입금자동취소)</p>
					<p class="t1">※ 예약 불가 시 대행수수료는 환불 처리해 드립니다.</p>
					<p class="t1 c-color">※ 확정 된 예약 변경건은 홈페이지 하단 <strong>“문의하기”</strong>로 변경 요청 가능 합니다.</p>
					<p class="t1 c-color">※ 결제하신 예약대행수수료는 차량 확정 시 환불이 되지 않습니다.</p>
				<?} else {?>
					<p class="t1 c-color">※ 확정 된 예약 변경건은 홈페이지 하단 <strong>“문의하기”</strong>로 변경 요청 가능 합니다.</p>
					<p class="t1 c-color">※ 결제하신 예약대행수수료는 차량 확정 시 환불이 되지 않습니다.</p>
				<?}?>
			</div>
		</section>
	</div>
</div><!--//container -->

<script src="https://cdn.bootpay.co.kr/js/bootpay-3.3.2.min.js" type="application/javascript"></script>
<script>
	<?if ($booking_view['status'] == '20') {?>
		function paymentGo(gubun) {
			if (gubun == 'BANK') {
				if (!confirm("무통장 입금을 완료하였습니까?\n입금이 미확인 될시 예약은 취소 될수 있습니다.\n\n무통장 입금 확인요청을 하시려면 '확인'을 눌러주세요.")) return false;

				paymentCompleteGo(gubun, "");
			} else {
				var payment_tid = "";

				//실제 복사하여 사용시에는 모든 주석을 지운 후 사용하세요
				BootPay.request({
					price: '<?=$booking_view['booking_agency_fee']?>', //실제 결제되는 가격
					application_id: "60fe4f227b5ba400217bd965",
					name: '[<?=SITE_NAME?>] <?=getGoodsCateName($booking_view['goods_category'])?> <?=$booking_view['goods_title']?>', //결제창에서 보여질 이름
					pg: 'nicepay',
					method: 'card', //결제수단, 입력하지 않으면 결제수단 선택부터 화면이 시작합니다.
					show_agree_window: 0, // 부트페이 정보 동의 창 보이기 여부
					user_info: {
						username: '<?=$booking_view['name']?>',
						email: '<?=$booking_view['email']?>',
						phone: '<?=$booking_view['phone']?>'
					},
					order_id: '<?=$booking_view['booking_num']?>' //고유 주문번호로, 생성하신 값을 보내주셔야 합니다.
				}).error(function (data) {
					//결제 진행시 에러
					alert("결제 요청 처리중 오류가 발생되었습니다.\n\n["+ data.message +"]");
				}).cancel(function (data) {
					//결제가 취소
					alert("결제가 취소되었습니다.\n\n["+ data.message +"]");
				}).confirm(function (result) {
					//중복결제 체크
					AJ.callAjax("_payment_dupl_check_proc.php", {"booking_num": "<?=$booking_view['booking_num']?>"}, function(data){
						if (data.result == 200) {
							BootPay.transactionConfirm(result); // 조건이 맞으면 승인 처리를 한다.
						} else {
							alert(data.message)
							BootPay.removePaymentWindow(); // 조건이 맞지 않으면 결제 창을 닫고 결제를 승인하지 않는다.
						}
					});
				}).done(function (result) {
					//결제 승인 완료
					if (result.status == 1) {
						AJ.callAjax("_payment_check_proc.php", {"receipt_id": result.receipt_id}, function(data){
							if (data.result == 200) {
								paymentCompleteGo(gubun, result.receipt_id);
							} else {
								alert(data.message);
							}
						});
					} else {
						alert("결제 승인 처리중 오류가 발생되었습니다.\n\n에러코드: "+ result.status);
					}
				});
			}
		}

		function paymentCompleteGo(gubun, tid) {
			AJ.callAjax("_payment_proc.php", {"gubun": gubun, "booking_num": "<?=$booking_view['booking_num']?>", "tid": tid}, function(data){
				if (data.result == 200) {
					if (gubun == "BANK") {
						alert("결제 확인 요청을 하였습니다.");
					}

					location.replace('reservation5.php?token='+data.token);
				} else {
					alert(data.message);
				}
			});
		}
	<?}?>

	<?if (strpos("10,20,30,40", $booking_view['status']) !== false && dateDiff("d", $booking_view['out_date'], date('Y-m-d')) < -10) {?>
		function cancelReqGo() {
			if (!confirm("예약취소요청 하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("_cancel_proc.php", {"token": "<?=$token?>"}, function(data){
				if (data.result == 200) {
					alert("예약 취소가 접수 되었습니다.\n예약 정보 확인 후 빠른 시일 내로 처리하겠습니다.\n\n감사합니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	<?}?>
</script>

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>