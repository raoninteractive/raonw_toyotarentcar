<? include "../inc/config.php" ?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', '', '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");


	$pageNum = "0305";
	$pageName = "예약 결제완료";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
	<div class="inr-c">
		<section class="area_reser box-line non">
			<header class="hd_titbox">
				<div class="img mb20"><img src="../images/common/img_comp2.png" alt=""></div>
				<h2 class="hd_tit1 mb10"><span class="h"><?=iif($booking_view['payment_method']=='BANK', '결제 확인 요청이 완료되었습니다.', '결제가 완료되었습니다.')?></span></h2>
			</header>

			<div class="tbl_basic">
				<table class="view">
					<tbody>
						<tr>
							<th>예약번호</th>
							<td><?=$booking_view['booking_num']?></td>
						</tr>
						<tr>
							<th>상품명</th>
							<td><strong class="c-color"><?=getGoodsCateName($booking_view['goods_category'])?></strong> &gt; <?=$booking_view['goods_title']?></td>
                        </tr>
						<tr>
							<th>현장 지불금액</th>
							<td colspan="3">
                                <?
                                    //아이스박스 예약불가
                                    if ($booking_view['add_option_1_flag'] == 'N') {
                                        $booking_view['add_option_1_amt'] = 0;
                                    }
                                    //네이게이션 예약불가
                                    if ($booking_view['add_option_2_flag'] == 'N') {
                                        $booking_view['add_option_2_amt'] = 0;
                                    }
                                    //공항픽업 예약불가
                                    if ($booking_view['airport_meeting_flag'] == 'N') {
                                        $booking_view['airport_meeting_amt'] = 0;
                                    }
                                ?>

                                <strong class="c-color">$<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?></strong>
								<span style="font-size:0.8em;">(자세한 이력은 '예약확인'에서 확인해주세요.)</span>
							</td>
                        </tr>
						<tr>
							<th>결제금액</th>
							<td><strong class="c-color">￦<?=formatNumbers($booking_view['booking_agency_fee'])?></strong></td>
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

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>