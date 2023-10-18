<?include("../inc/config.php")?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

	//예약접수 정보 불러오기(예약번호 조회)
	$booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', '', '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");

	$pageNum = "0302";
	$pageName = "예약접수 완료";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
	<div class="inr-c">
		<section class="area_reser box-line non">
			<header class="hd_titbox">
				<div class="img mb20"><img src="../images/common/img_comp.png" alt=""></div>
				<h2 class="hd_tit1 mb10"><span class="h">예약접수가 완료되었습니다.</span></h2>
				<p class="hd_tit3">
					24시간 이내에 예약가능여부를 안내 드리겠습니다.
					<br>예약확정 메시지를 받으신 후
					<br>“예약확인” 접속하시어 예약금 결제를 진행해 주시기 바랍니다.
				</p>
			</header>

			<div class="tbl_basic">
				<table class="view">
					<tbody>
						<tr>
							<th>예약번호</th>
							<td><?=$booking_view['booking_num']?> <strong class="c-color ml10">(※ 예약확인을 위해 꼭 기억해주세요.)</strong></td>
						</tr>
						<tr>
							<th>상품명</th>
							<td><strong class="c-color"><?=getGoodsCateName($booking_view['goods_category'])?></strong> &gt; <?=$booking_view['goods_title']?></td>
						</tr>
						<tr>
							<th>렌트일정</th>
							<td>
								<?=formatDates($booking_view['rental_sdate'],'Y.m.d')?> (<?=$booking_view['rental_time']?>) ~
								<?=formatDates($booking_view['rental_edate'],'Y.m.d')?>
								(<?=$booking_view['rental_day']?>일)
							</td>
						</tr>
						<tr>
							<th>예약자 정보</th>
							<td><?=$booking_view['name']?> / <?=$booking_view['phone']?> / <?=$booking_view['email']?></td>
						</tr>
						<tr>
							<th>예약접수일</th>
							<td><?=formatDates($booking_view['reg_date'],'Y.m.d H:i:s')?></td>
						</tr>
					</tbody>
				</table>
			</div>

			 <div class="btn-bot">
				<a href="/" class="btn-pk nb color rv w100p"><span>홈으로</span></a>
			</div>
		</section>
	</div>
</div><!--//container -->

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>