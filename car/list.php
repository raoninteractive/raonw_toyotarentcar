<?include("../inc/config.php")?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 12;
	$params['block_size'] = 10;
	$params['gubun']   = chkReqRpl("gubun", "C001", "10", "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    //여행상품 목록
	$goods_list = $cls_goods->goods_list($params, $total_cnt, $total_page);

	if ($params['gubun'] == 'C001') {
		$pageNum = "0101";
		$pageName = "괌 차량보기";

        //괌-차량목록 팝업 목록 불러오기
        $pop_list = $cls_pop->popup_list('2');
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0201";
		$pageName = "사이판 차량보기";

        //사이판-차량목록 팝업 목록 불러오기
        $pop_list = $cls_pop->popup_list('3');
	} else {
		fnMsgGo(500, "잘못된 요청 정보 입니다.", "BACK", "");
	}
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub customer" style="padding-bottom:0">
	<div class="inr-c">
		<? include "side.php" ?>

		<div class="contents">
			<section class="area_tour pr-pd1">
				<header class="hd_titbox">
					<h2 class="hd_tit2"><strong class="c-color"><?=$pageName?></strong></h2>
				</header>

				<div class="lst_car1">
					<?if (count($goods_list) > 0) {?>
						<ul>
							<?for ($i=0; $i<count($goods_list); $i++) {?>
								<li>
									<p class="tit"><?=$goods_list[$i]['title']?></p>
									<div class="img"><span style="background-image:url('/upload/goods/thumb/<?=getUpfileName($goods_list[$i]['up_file_1'])?>')"></span></div>
									<div class="txt">
										<?if ($goods_list[$i]['content'] != '') {?>
											<p class="t1"><?=implode('</p><p class="t1">', explode("\n",$goods_list[$i]['content']))?></p>
										<?}?>

										<p class="t_cost"><span>24시간</span><?=$goods_list[$i]['day1_amt']?> USD</p>
										<?if ($goods_list[$i]['rest_stock_cnt'] >= 1) {?>
											<a href="reservation.php?page=<?=$params['page'].$page_params?>&goods_idx=<?=$goods_list[$i]['idx']?>" class="btn-pk nb color rv w100p"><span>예약하기</span></a>
										<?} else {?>
											<a href="javascript:alert('예약이 불가능한 상품입니다.')" class="btn-pk nb gray rv w100p"><span>예약불가</span></a>
										<?}?>
									</div>
									<div class="bat">
										<?if ($goods_list[$i]['option_1'] == 'Y') {?>
											<span><img src="/images/common/ico_bat_car1.png" alt="주유포함"></span>
										<?}?>
										<?if ($goods_list[$i]['option_2'] == 'Y') {?>
											<span><img src="/images/common/ico_bat_car2.png" alt="CDW포함"></span>
										<?}?>
										<?if ($goods_list[$i]['option_7'] == 'Y') {?>
											<span><img src="/images/common/ico_bat_car3.png" alt="ZDC포함"></span>
										<?}?>
										<?if ($goods_list[$i]['option_8'] == 'Y') {?>
											<span><img src="/images/common/ico_bat_car4.png" alt="PAI포함"></span>
										<?}?>
                                        <?if ($goods_list[$i]['option_9'] == 'Y') {?>
											<span><img src="/images/common/ico_bat_car5.png" alt="SCDW포함"></span>
										<?}?>
									</div>
								</li>
							<?}?>
						</ul>
					<?} else {?>
						<div class="ta-c pt50 pb50">등록된 상품정보가 없습니다.</div>
					<?}?>
				</div>

				<div class="pagenation">
					<? frontPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
				</div>
			</section>
		</div>

		<section class="sub_footer">
			<div class="hd_titbox ta-c">
				<h2 class="hd_tit1"><span class="h">추가 선택상품</span></h2>
			</div>
			<div class="lst_box1">
				<?if ($params['gubun'] == 'C001') {?>
					<!-- 괌 선택상품 -->
					<ul>
						<li>
							<p class="h1">아이스박스</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="../images/main/ico_product1.png" alt=""></div>
							<p class="t1">요청시 무료</p>
						</li>
						<li>
							<p class="h1">주유포함 차량</p>
							<p class="h2">차종별 선택 가능</p>
							<div class="icon"><img src="../images/main/ico_product2.png" alt=""></div>
							<p class="t1">2일이상 렌트시<br>적용 가능</p>
						</li>
						<li>
							<p class="h1">아동용 보조시트</p>
							<p class="h2">$8 (24HR 기준)</p>
							<div class="icon"><img src="../images/main/ico_product3.png" alt=""></div>
							<p class="t1">차량 1대 기준 <?=$CONST_CAR_SEAT_FREE?>개 무료</p>
						</li>
						<li>
							<p class="h1">WIFI</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="../images/main/ico_product4.png" alt=""></div>
							<p class="t1">괌 국제 공항 와이파이 대여<br>20% 할인 쿠폰 제공</p>
						</li>
					</ul>
				<?} else {?>
					<!-- 사이판 선택상품 -->
					<ul>
						<li>
							<p class="h1">아이스박스</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="../images/main/ico_product1.png" alt=""></div>
							<p class="t1">현지 잔여수량이 있는 경우<br>무료 대여 (선착순)</p>
						</li>
						<li>
							<p class="h1">주유포함 차량</p>
							<p class="h2">차종별 선택 가능</p>
							<div class="icon"><img src="../images/main/ico_product2.png" alt=""></div>
							<p class="t1">1일이상 렌트시<br>적용 가능</p>
						</li>
						<li>
							<p class="h1">아동용 보조시트</p>
							<p class="h2">$14.95 (24HR 기준)</p>
							<div class="icon"><img src="../images/main/ico_product3.png" alt=""></div>
							<p class="t1">차량 1대 기준 <?=$CONST_CAR_SEAT_FREE2?>개 무료</p>
						</li>
						<li>
							<p class="h1">WIFI</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="../images/main/ico_product4.png" alt=""></div>
							<p class="t1">사이판 국제 공항 와이파이 대여<br>(할인 금액으로 대여 가능)</p>
						</li>
					</ul>
				<?}?>
			</div>
		</section>
	</div>

	<section class="area_main2 ">
		<div class="inr-c">
			<div class="hd_titbox">
				<h2 class="hd_tit1"><span class="h">토요타 렌터카 고객님만의 특별한 혜택</span></h2>
			</div>

			<?if ($params['gubun'] == 'C001') {?>
				<!-- 괌 특별 혜택 -->
				<div class="lst_rent1">
					<ul>
						<li>
							<div class="icon"><img src="../images/main/ico_rent1.png" alt=""></div>
							<div class="txt">
								<p>24시간 국제공항 픽업 및 반납가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent2.png" alt=""></div>
							<div class="txt">
								<p>호텔 차량 픽업가능 / 호텔 차량 반납가능(반납은 호텔 주차장)</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent9.png" alt=""></div>
							<div class="txt">
								<p>전차종 주유 포함 차량 선택 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent3.png" alt=""></div>
							<div class="txt">
								<p>카시트 또는 보조시트 2개 무료 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent4.png" alt=""></div>
							<div class="txt">
								<p>아이스박스 무료</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent5.png" alt=""></div>
							<div class="txt">
								<p>한국어 상담 24시간 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent6.png" alt=""></div>
							<div class="txt">
								<p>2년 미만의 신차 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent11.png" alt=""></div>
							<div class="txt">
								<p>괌 국제 공항 와이파이 유심카드 20% 할인쿠폰 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>괌 롯데 면세점 10% 할인쿠폰 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent8.png" alt=""></div>
							<div class="txt">
								<p>SCDW(슈퍼종합보험) 및 PAI(상해보험) 기본 포함</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent10.png" alt=""></div>
							<div class="txt">
								<p>각종 레스토랑 할인카드 제공(BIG CARD)</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="../images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>장기렌트 추가 할인(10%~20%)</p>
							</div>
						</li>
					</ul>
				</div>
<!--
				<div class="lst_rent1">
					<ul>
						<li>
							<div class="icon"><img src="/images/main/ico_rent1.png" alt=""></div>
							<div class="txt">
								<p>24시간 국제공항 픽업 및 반납가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent2.png" alt=""></div>
							<div class="txt">
								<p>호텔 차량 픽업 및 숙박호텔에 직접 차량반납</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent3.png" alt=""></div>
							<div class="txt">
								<p>카시트 또는 보조시트 2개 무료 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent4.png" alt=""></div>
							<div class="txt">
								<p>아이스박스 및 한국어 지도 무료제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent5.png" alt=""></div>
							<div class="txt">
								<p>한국어 상담 항시 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent6.png" alt=""></div>
							<div class="txt">
								<p>2년 미만 최신차량 제공 / 추가 운전자 1인무료</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>한국어 지원 네비게이션 추가선택 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent8.png" alt=""></div>
							<div class="txt">
								<p>풀커버리지 보험(ZDC)과 상해보험(PAI) 포함</p>
							</div>
						</li>
					</ul>
				</div>
-->
			<?} else {?>
				<!-- 사이판 특별 혜택 -->
				<div class="lst_rent1">
					<ul>
                        <li>
							<div class="icon"><img src="/images/main/ico_rent6.png" alt=""></div>
							<div class="txt">
								<p>전 차종 2021년 최신 차량 제공/추가 운전자 1명 무료</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent8.png" alt=""></div>
							<div class="txt">
								<p>풀커버리지 보험(ZDC)과 상해보험(PAI) 포함</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent1.png" alt=""></div>
							<div class="txt">
								<p>24시간 국제공항 픽업 및 반납가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent2.png" alt=""></div>
							<div class="txt">
								<p>호텔/픽업 반납 시 직원 무료 픽업</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent3.png" alt=""></div>
							<div class="txt">
								<p>카시트 또는 보조시트 1개 무료 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent4.png" alt=""></div>
							<div class="txt">
								<p>아이스박스 및 한국어 지도 무료제공 (잔여수량이 있는 경우)</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent5.png" alt=""></div>
							<div class="txt">
								<p>한국어 상담 항시 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="/images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>추가 운전자 1명 무료 (인수시 가입 가능)</p>
							</div>
						</li>
					</ul>
				</div>


			<?}?>
		</div>
	</section>

	<section class="area_main2 bd2">
		<div class="inr-c">
			<div class="hd_titbox">
				<h2 class="hd_tit1"><span class="h">서비스 이용안내</span></h2>
			</div>

			<div class="lst_service1 mb2">
				<ul>
                    <li>
						<div class="icon"><img src="/images/main/ico_service1.png" alt=""></div>
						<p class="t1">렌터카 예약 신청<br>(홈페이지)</p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service2.png" alt=""></div>
						<p class="t1">예약 가능 여부 확인<br>(예약 대행 수수료 결제)</p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service3.png" alt=""></div>
						<p class="t1">예약 확정 유무 확인<br><span class="c-color">(24시간 이내)</span></p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service4.png" alt=""></div>
						<p class="t1">예약 확정시 확정서 확인<br>(불가시 수수료 환불)</p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service5.png" alt=""></div>
						<p class="t1">현지 도착 후 <br>미팅/계약서 작성</p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service6.png" alt=""></div>
						<p class="t1">렌터카 요금 결제 및<br>차량 인수</p>
					</li>
					<li>
						<div class="icon"><img src="/images/main/ico_service7.png" alt=""></div>
						<p class="t1">이용 후<br>차량 반납</p>
					</li>
				</ul>
			</div>

		</div>
	</section>
</div><!--//container -->

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>