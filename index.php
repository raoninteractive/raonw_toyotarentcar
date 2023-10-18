<?include("inc/config.php")?>
<?
	$db = new DB_HELPER;

	//괌 인기차종 불러오기
	$sql = "
			SELECT * FROM (
				SELECT
					idx, category, title, up_file_1, day1_amt, keyword, option_1, option_2, option_7, option_8, option_9, main_sort,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE category='C001' AND open_flag='Y' AND total_stock_cnt > 0 AND main_open_flag='Y' AND del_flag='N'
			) t
			WHERE rest_stock_cnt > 0
			ORDER BY main_sort DESC, popular_cnt DESC, idx DESC
			LIMIT 4
		";
	$goods_list1 = $db->getQuery($sql);

	//사이판 인기차종 불러오기
	$sql = "
			SELECT * FROM (
				SELECT
					idx, category, title, up_file_1, day1_amt, keyword, option_1, option_2, option_7, option_8, option_9, main_sort,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE category='C002' AND open_flag='Y' AND total_stock_cnt > 0 AND main_open_flag='Y' AND del_flag='N'
			) t
			WHERE rest_stock_cnt > 0
			ORDER BY main_sort DESC, popular_cnt DESC, idx DESC
			LIMIT 4
		";
	$goods_list2 = $db->getQuery($sql);


	//메인공통 팝업 목록 불러오기
	$pop_list = $cls_pop->popup_list('1');

	$pageNum = "0000";
	$pageName = "메인";
?>
<? include "inc/top.php" ?>
<? include "inc/header.php" ?>

<link href="css/owl.carousel.min.css" rel="stylesheet">
<script src="js/owl.carousel.min.js"></script>

<div id="container" class="container main">
	<div class="bxMain">
		<div class="owl-carousel">
			<div class="item">
				<div class="slider_img" style="background-image:url(images/main/img_slider1.jpg);"></div>
			</div>
			<div class="item">
				<div class="slider_img" style="background-image:url(images/main/img_slider2.jpg);"></div>
			</div>
		</div>
	</div><!--//bxMain -->

	<section class="area_reservation">
		<h2 class="tit">빠른예약</h2>
		<form name="quickFrm" id="quickFrm" method="get" action="/car/reservation.php">
		<fieldset class="inner">
			<ul>
				<li>
					<p><span>지역</span></p>
					<select name="gubun" id="quick_gubun" class="select1" onchange="getGoodsList(this.value)">
						<option value="">지역 선택</option>
						<option value="C001">괌(GUAM)</option>
						<option value="C002">사이판(SAIPAN)</option>
					</select>
				</li>
				<li>
					<p><span>차종</span></p>
					<select name="goods_idx" id="quick_goods" class="select1">
						<option value="">차종을 선택해 주세요.</option>
					</select>
				</li>
				<li>
					<p><span>수령일시</span></p>
					<input type="text" name="rental_date" id="quick_rental_date" class="inp_txt calender datepicker" placeholder="날짜 선택" readonly>
					<select name="rental_hour" id="quick_rental_hour" class="select1 n">
						<option value="">시간 선택</option>
						<?for ($i=0; $i<=23; $i++) {?>
							<option value="<?=addZero($i)?>"><?=addZero($i)?>시</option>
						<?}?>
					</select>
				</li>
				<li>
					<p><span>대여기간</span></p>
					<select name="rental_day" id="quick_rental_day" class="select1">
						<option value="">대여기간 선택</option>
						<?for ($i=1; $i<=30; $i++) {?>
							<option value="<?=addZero($i)?>"><?=addZero($i)?>일</option>
						<?}?>
					</select>
				</li>
			</ul>
			<button type="button" class="btn-pk color" onclick="quickBookingGo()"><span>예약하기</span></button>
		</fieldset>
		</form>
	</section>

	<div class="tab ty1">
		<ul>
			<li><a href="#tab1">괌(GUAM)</a></li>
			<li><a href="#tab2">사이판(SAIPAN)</a></li>
		</ul>
	</div>

	<!-- 1. 괌 -->
	<div id="tab1">
		<section class="area_main">
			<div class="inr-c">
				<div id="m_main1"></div>
				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">괌(GUAM) 인기 차종안내</span></h2>
					<div class="rgh">
						<a href="/car/list.php?gubun=C001" class="btn-pk nb color"><span class="hide-m">괌(GUAM) 차량 </span>전체보기</a>
					</div>
				</div>

				<div class="lst_cars1 mb1">
					<ul>
						<?for ($i=0; $i<count($goods_list1); $i++) {?>
							<li>
								<?if ($goods_list1[$i]['rest_stock_cnt'] >= 1) {?>
									<a href="/car/reservation.php?gubun=C001&goods_idx=<?=$goods_list1[$i]['idx']?>">
								<?} else {?>
									<a href="javascript:alert('예약이 불가능한 상품입니다.')">
								<?}?>

								<div class="img"><span style="background-image:url('/upload/goods/thumb/<?=getUpfileName($goods_list1[$i]['up_file_1'])?>')"></span></div>
								<div class="txt">
									<!-- <p class="h1 t-dot">괌(GUAM)</p> -->
									<p class="h2 t-dot"><?=$goods_list1[$i]['title']?></p>
									<p class="t_cost"><span>24시간</span><?=$goods_list1[$i]['day1_amt']?> USD</p>
									<div class="box">
										<?if ($goods_list1[$i]['keyword'] != '') {?>
											<span class="i-txt"><?=implode('</span><span class="i-txt">',explode(',', $goods_list1[$i]['keyword']))?></span>
										<?}?>
									</div>
								</div>
								<div class="bat">
									<?if ($goods_list1[$i]['option_1'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car1.png" alt="주유포함"></span>
									<?}?>
									<?if ($goods_list1[$i]['option_2'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car2.png" alt="CDW포함"></span>
									<?}?>
									<?if ($goods_list1[$i]['option_7'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car3.png" alt="ZDC포함"></span>
									<?}?>
									<?if ($goods_list1[$i]['option_8'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car4.png" alt="PAI포함"></span>
									<?}?>
                                    <?if ($goods_list1[$i]['option_9'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car5.png" alt="SCDW포함"></span>
									<?}?>
								</div>
							</a></li>
						<?}?>
					</ul>
				</div>

				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">추가 선택상품</span></h2>
				</div>
				<div class="lst_box1">
					<ul>
						<li>
							<p class="h1">아이스박스</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="images/main/ico_product1.png" alt=""></div>
							<p class="t1">요청시 무료</p>
						</li>
						<li>
							<p class="h1">추가 운전자 무료</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="images/main/ico_product2_1.png" alt=""></div>
							<p class="t1">차량 1대 기준<br>1명 무료 적용</p>
						</li>
						<li>
							<p class="h1">아동용 보조시트</p>
							<p class="h2">$10 (24HR 기준)</p>
							<div class="icon"><img src="images/main/ico_product3.png" alt=""></div>
							<p class="t1">차량 1대 기준 <?=$CONST_CAR_SEAT_FREE?>개 무료</p>
						</li>
						<li>
							<p class="h1">WIFI</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="images/main/ico_product4.png" alt=""></div>
							<p class="t1">괌/사이판 국제 공항 와이파이 대여<br>20% 할인 쿠폰 제공</p>
						</li>
					</ul>
				</div>
			</div>
		</section>

		<section class="area_main2">
			<div class="inr-c">
				<div id="m_main2"></div>
				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">토요타 렌터카 고객님만의 특별한 혜택</span></h2>
				</div>

				<div class="lst_rent1">
					<ul>
						<li>
							<div class="icon"><img src="images/main/ico_rent1.png" alt=""></div>
							<div class="txt">
								<p>괌 국제공항픽업반납가능 - 공항사무실운영</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent2.png" alt=""></div>
							<div class="txt">
								<p>호텔 픽업 가능 - 사무실 이동 등 무료</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>장기 렌트  15일 이상 사용 시 5% 할인</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent3.png" alt=""></div>
							<div class="txt">
								<p>카시트 또는 보조시트 <?=$CONST_CAR_SEAT_FREE1?>개 무료 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent4.png" alt=""></div>
							<div class="txt">
								<p>아이스박스 무료</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent5.png" alt=""></div>
							<div class="txt">
								<p>한국어 상담 24시간 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent6.png" alt=""></div>
							<div class="txt">
								<p>2년 미만의 신차 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent11.png" alt=""></div>
							<div class="txt">
								<p>괌 국제 공항 와이파이 유심카드 20% 할인쿠폰 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent12.png" alt=""></div>
							<div class="txt">
								<p>토니로마스 할인제공 & BBQ 전용 세트 구매 가능</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent8.png" alt=""></div>
							<div class="txt">
								<p>CDW (종합보험) 및 PAI (상해보험) 기본 포함</p>
							</div>
						</li>
						<!-- <li>
							<div class="icon"><img src="images/main/ico_rent7.png" alt=""></div>
							<div class="txt">
								<p>괌 롯데 면세점 10% 할인쿠폰 제공</p>
							</div>
						</li>
						<li>
							<div class="icon"><img src="images/main/ico_rent9.png" alt=""></div>
							<div class="txt">
								<p>전차종 주유 포함 차량 선택 가능</p>
							</div>
						</li> -->
					</ul>
				</div>
			</div>
		</section>
	</div>

	<!-- 2. 사이판 -->
	<div id="tab2">
		<section class="area_main">
			<div class="inr-c">
				<div id="m_main1"></div>
				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">사이판(SAIPAN) 인기 차종안내</span></h2>
					<div class="rgh">
						<a href="/car/list.php?gubun=C002" class="btn-pk nb color"><span class="hide-m">사이판(SAIPAN) 차량 </span>전체보기</a>
					</div>
				</div>

				<div class="lst_cars1 mb1">
					<ul>
						<?for ($i=0; $i<count($goods_list2); $i++) {?>
							<li>
								<?if ($goods_list2[$i]['rest_stock_cnt'] >= 1) {?>
									<a href="/car/reservation.php?gubun=C002&goods_idx=<?=$goods_list2[$i]['idx']?>">
								<?} else {?>
									<a href="javascript:alert('예약이 불가능한 상품입니다.')">
								<?}?>

								<div class="img"><span style="background-image:url('/upload/goods/thumb/<?=getUpfileName($goods_list2[$i]['up_file_1'])?>')"></span></div>
								<div class="txt">
									<p class="h1 t-dot">괌(GUAM)</p>
									<p class="h2 t-dot"><?=$goods_list2[$i]['title']?></p>
									<p class="t_cost"><span>24시간</span><?=$goods_list2[$i]['day1_amt']?> USD</p>
									<div class="box">
										<?if ($goods_list2[$i]['keyword'] != '') {?>
											<span class="i-txt"><?=implode('</span><span class="i-txt">',explode(',', $goods_list2[$i]['keyword']))?></span>
										<?}?>
									</div>
								</div>
								<div class="bat">
									<?if ($goods_list2[$i]['option_1'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car1.png" alt="주유포함"></span>
									<?}?>
									<?if ($goods_list2[$i]['option_2'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car2.png" alt="CDW포함"></span>
									<?}?>
									<?if ($goods_list2[$i]['option_7'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car3.png" alt="ZDC포함"></span>
									<?}?>
									<?if ($goods_list2[$i]['option_8'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car4.png" alt="PAI포함"></span>
									<?}?>
                                    <?if ($goods_list2[$i]['option_9'] == 'Y') {?>
										<span><img src="/images/common/ico_bat_car5.png" alt="SCDW포함"></span>
									<?}?>
								</div>
							</a></li>
						<?}?>
					</ul>
				</div>

				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">추가 선택상품</span></h2>
				</div>
				<div class="lst_box1">
					<ul>
						<li>
							<p class="h1">아이스박스</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="images/main/ico_product1.png" alt=""></div>
							<p class="t1">현지 잔여수량이 있는 경우<br>무료 대여 (선착순)</p>
						</li>
						<li>
							<p class="h1">추가 운전자 무료</p>
							<p class="h2">차종별 선택 가능</p>
							<div class="icon"><img src="images/main/ico_product2_1.png" alt=""></div>
							<p class="t1">차량 1대 기준<br>1명 무료 적용</p>
						</li>
						<li>
							<p class="h1">아동용 보조시트</p>
							<p class="h2">$14.95 (24HR 기준)</p>
							<div class="icon"><img src="images/main/ico_product3.png" alt=""></div>
							<p class="t1">차량 1대 기준 <?=$CONST_CAR_SEAT_FREE2?>개 무료</p>
						</li>
						<li>
							<p class="h1">WIFI</p>
							<p class="h2">&nbsp;</p>
							<div class="icon"><img src="images/main/ico_product4.png" alt=""></div>
							<p class="t1">사이판 국제 공항 와이파이 대여<br>(할인 금액으로 대여 가능)</p>
						</li>
					</ul>
				</div>
			</div>
		</section>

		<section class="area_main2">
			<div class="inr-c">
				<div id="m_main2"></div>
				<div class="hd_titbox">
					<h2 class="hd_tit1"><span class="h">토요타 렌터카 고객님만의 특별한 혜택</span></h2>
				</div>

				<div class="lst_rent1">
					<ul>
                        <li>
							<div class="icon"><img src="/images/main/ico_rent6.png" alt=""></div>
							<div class="txt">
								<p>전차종 21년 이후 최신 차량 제공</p>
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
								<p>사이판 국제 공항픽업 반납 가능 -공항사무실운영</p>
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
								<p>카시트 또는 보조시트 <?=$CONST_CAR_SEAT_FREE2?>개 무료 제공</p>
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
			</div>
		</section>
	</div>

	<section class="area_main2 bd">
		<div class="inr-c">
			<div  id="m_main3"></div>
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


	<section class="area_main3" >
		<div class="inr-c">
			<div id="m_main4" ></div>
			<div class="hd_titbox">
				<h2 class="hd_tit1"><span class="h">토요타 렌터카</span></h2>
			</div>

			<p class="h1">
                괌,사이판의 최대의 렌터카 회사
				<br>TOYOYA RENT-CAR를 찾아주셔서 감사합니다.
				<br>즐거운 여행이 되실 수 있도록 고객님의 발이 되어 드리겠습니다.
			</p>
			<p class="t1 pr-mb1">
				저희 토요타 렌터카틑 설립 100년의 역사를 가진 회사로서
				<br>다양한 최신 차량과 투몬호텔에 지점을 보유하여 어느 호텔에 묵으시더라도
				<br class="hide-m">가깝고 편안하게 고객님을 모시며, <br class="view-m">24시간 한국어 상담이 가능하여
				<br>언제든 편리하게 상담과 예약을 하여 렌터카를 이용하실 수 있습니다.
				<br>
				<br>오래된 노하우로 자유여행을 하시는 고객님의 모든 여행정보와
				<br>만족도, 안전도가 검증된 즐길거리 등을 할인받아 저렴하게 이용하실 수 있습니다.
				<br>
				<br>저희 도요타 렌터카와 함께라면 멋진 추억을 만드실 수있는 즐거운 여행을 만끽하실 수 있습니다.
				<br>저희 임직원 모두 최고의 여행이 되실 수 있도록 최선의 노력을 다할 것을 약속해 드립니다.
				<br>감사합니다.
			</p>

			<div id="m_main5"></div>
			<div class="hd_titbox">
				<h3 class="hd_tit1 c-color ta-l"><span class="h">문의 하기</span> <span>“렌터카 관련 문의 / 예약변경 및 취소 시 문의 주시면 메일로 답변 드립니다”</span></h3>
			</div>
			<form name="qnaFrm" id="qnaFrm" method="post">
			<div class="inps">
				<div class="lft">
					<p class="h">문의구분</p>
					<div class="mg">
						<select name="qna_gubun" id="qna_gubun" class="select1">
							<option value="C001">괌(GUAM)</option>
							<option value="C002">사이판(SAIPAN)</option>
						</select>
					</div>
					<p class="h">이름</p>
					<div class="mg"><input type="text" name="qna_name" id="qna_name" class="inp_txt w100p" placeholder="이름을 입력해주세요." maxlength="10"></div>
					<p class="h">휴대폰번호</p>
					<div class="mg"><input type="text" name="qna_phone" id="qna_phone" class="inp_txt w100p onlyNum" placeholder="'-' 없이 입력해주세요." maxlength="12"></div>
					<p class="h">이메일</p>
					<div class=""><input type="text" name="qna_email" id="qna_email" class="inp_txt w100p" placeholder="이메일을 입력해주세요." maxlength="50"></div>
				</div>
				<div class="rgh">
					<p class="h">문의내용</p>
					<div class="mg"><textarea name="qna_content" id="qna_content" class="textarea1" placeholder="내용은 500자 이내로 입력해주세요." maxlength="500"></textarea></div>
					<button type="button" class="btn-pk n color w100p" onclick="qnaGo()"><span>등록</span></button>
				</div>
			</div>
			</form>
		</div>
	</section>
</div><!--//container -->

<!-- 팝업 -->
<div id="popCarview" class="layerPopup pop_carview"></div>

<script>
	tab(".tab.ty1",1);//탭

	$(function(){
		//슬라이드
		var subSlider = $(".bxMain .owl-carousel");
		subSlider.owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			dots:true,
			items:1,
			smartSpeed:1500,
			autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:false,
			mouseDrag: false
		});

		$('.datepicker').datepicker("destroy");
		$('.datepicker').datepicker({
			minDate: "<?=dateAdd("day", 1, date('Y-m-d'))?>",
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png"
		});
	});

	function qnaGo() {
		var h = new clsJsHelper();

		if (!h.checkSelect("qna_gubun", "문의구분")) return false;
		if (!h.checkValNLen("qna_name", 2, 20, "이름", "Y", "KO")) return false;
		if (!h.checkValNLen("qna_phone", 10, 11, "휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("qna_phone"), "휴대폰번호", "")) return false;
		if (!h.checkValNLen("qna_email", 10, 50, "이메일", "Y", "EN")) return false;
		if (!h.checkEmail("qna_email", "이메일")) return false;
		if (!h.checkValNLen("qna_content", 1, 1000, "문의내용", "N", "KO")) return false;

		AJ.ajaxForm($("#qnaFrm"), "qna_proc.php", function(data) {
			if (data.result == 200) {
				alert("문의가 접수되었습니다.\n빠른 시일내에 답변 드리겠습니다.");

				$("#qnaFrm")[0].reset();
			} else {
				alert(data.message);
			}
		});
	}

	//지역차량 불러오기
	function getGoodsList(gubun) {
		$("#quick_goods").find("option:gt(0)").remove();

		if (gubun == "") return false;

		AJ.callAjax("_goods_list.php", {"gubun": gubun}, function(data){
			if (data.result == 200) {
				$.each(data.list, function(i, item){
					$("#quick_goods").append("<option value='"+ item.goods_idx +"'>"+ item.title +"</option>");
				})
			} else {
				alert(data.message);

				$("#quick_goods").val("");
			}
		});
	}

	//빠른예약
	function quickBookingGo() {
		var h = new clsJsHelper();

		if (!h.checkSelect("quick_gubun", "지역")) return false;
		if (!h.checkSelect("quick_goods", "차종")) return false;
		if (!h.checkSelect("quick_rental_date", "수령일자")) return false;
		if (!h.checkSelect("quick_rental_hour", "수령시간")) return false;
		if (!h.checkSelect("quick_rental_day", "대여기간")) return false;


		$("#quickFrm").submit();
	}
</script>

<?include("inc/footer.php")?>
<?include("inc/bottom.php")?>