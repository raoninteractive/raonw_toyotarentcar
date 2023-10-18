<?
	$db = new DB_HELPER;

	//괌 공지사항 목록
	$sql = "SELECT idx, title, reg_date FROM board WHERE bbs_code='notice' AND open_flag='Y' AND del_flag='N' ORDER BY groups DESC, sort ASC LIMIT 8";
	$notice_list1 = $db->getQuery($sql);

	//사이판 공지사항 목록
	$sql = "SELECT idx, title, reg_date FROM board WHERE bbs_code='notice2' AND open_flag='Y' AND del_flag='N' ORDER BY groups DESC, sort ASC LIMIT 8";
	$notice_list2 = $db->getQuery($sql);
?>
<footer id="footer" class="footer">
	<div class="foo_bind">
		<div class="inr-c">
			<div class="col c1">
				<div class="tab ty2 tab_notice">
					<ul>
						<li><a href="#notice1">괌 공지사항</a></li>
						<li><a href="#notice2">사이판 공지사항</a></li>
					</ul>
				</div>
				<div id="notice1">
					<ul class="list">
						<?for ($i=0; $i<count($notice_list1); $i++) {?>
							<li><a href="/customer/notice_view.php?page=1&gubun=C001&idx=<?=$notice_list1[$i]['idx']?>"><span class="t"><?=$notice_list1[$i]['title']?></span><span class="d"><?=formatDates($notice_list1[$i]['reg_date'], 'Y.m.d')?></span></a></li>
						<?}?>
					</ul>
				</div>
				<div id="notice2">
					<ul class="list">
						<?for ($i=0; $i<count($notice_list2); $i++) {?>
							<li><a href="/customer/notice_view.php?page=1&gubun=C002&idx=<?=$notice_list2[$i]['idx']?>"><span class="t"><?=$notice_list2[$i]['title']?></span><span class="d"><?=formatDates($notice_list2[$i]['reg_date'], 'Y.m.d')?></span></a></li>
						<?}?>
					</ul>
				</div>
			</div>
			<div class="col c2">
				<!-- <div class="b">
					<h2 class="tit">고객상담문의</h2>
					<div class="t_time m1">
						<p><strong>한국에서 전화하실 때</strong></p>
						<p>[통화가능시간 : 오전 8시 ~ 오후 7시]</p>
						<p class="c-color">070)7838-0130</p>
					</div>
					<div class="t_time m2">
						<p><strong>해외에서 전화하실 때</strong></p>
						<p>[통화가능시간 : 오전 8시 ~ 오후 7시]</p>
						<p class="c-color">070)747-0060</p>
					</div>
				</div> -->
                <div class="b">
					<h2 class="tit">예약대행 수수료 입금전용 계좌</h2>
					<p><img src="/images/common/ico_bank.png" alt="우리은행">원종식(제이오투어)</p>
					<p class="c-color">1005-203-883824</p>
				</div>
			</div>
			<div class="col c3">
				<!-- <div class="b">
					<h2 class="tit">예약대행 수수료 입금전용 계좌</h2>
					<p><img src="/images/common/ico_bank.png" alt="우리은행">원종식(제이오투어)</p>
					<p class="c-color">1005-203-883824</p>
				</div> -->
				<div class="t_kakao mt0">
					<h2 class="tit">괌 카카오톡 고객상담</h2>
					<p><span class="d">ID : hit9157</span></p>
					<p><i>친구추가하고 간편하게 1:1 상담 받으세요!</i></p>
				</div>

                <div class="t_kakao mt10">
					<h2 class="tit">사이판 카카오톡 고객상담</h2>
					<p><span class="d">ID : saipantoyota</span></p>
					<p><i>친구추가하고 간편하게 1:1 상담 받으세요!</i></p>
				</div>
			</div>
		</div>
	</div>
	<div class="foo_link">
		<div class="inr-c">
			<ul>
				<li><a href="/customer/terms.php" id="flink">이용약관</a></li>
				<li><a href="/customer/privacy.php">개인정보처리방침</a></li>
			</ul>
		</div>
	</div>
	<div class="foo_cont">
		<div class="inr-c">
			<div class="f_logo"><a href="/"><img src="/images/common/logo_footer.png" alt="제이오투어"></a></div>
			<p>제이오투어</p>
			<p><span>서울특별시 마포구 잔다리로 48, 3층 979호(서교동) </span><span>대표자 : 원종식</span></p>
			<p><span>Tel : 02-745-8162</span><span>Email : : toyotarent@naver.com</span></p>
			<p><span>사업자등록번호 : 517-60-00370</span><span>관광사업자번호 : 제 2015-000027호 </span></p>
			<p><span>통신판매업신고 : 2019-서울마포-2452</span><span>공제영업보증보험 : 100-000-2021 0234 5663 호</span></p>
			<p class="copy">개인정보관리책임자 : 원종식<br>Copyright &copy; Toyota rent a car All Rights Reserved</p>
		</div>
	</div>

	<div class="foo_fix_menu view-m">
		<ul>
			<li><a href="/car/list.php?gubun=C001"><span class="ico"><img src="/images/common/ico_foo_fix1.png" alt="괌"></span><span class="t">괌</span></a></li>
			<li><a href="/car/list.php?gubun=C002"><span class="ico"><img src="/images/common/ico_foo_fix1.png" alt="사이판"></span><span class="t">사이판</span></a></li>
			<li><a href="/car/reservation3.php"><span class="ico"><img src="/images/common/ico_foo_fix2.png" alt="예약확인"></span><span class="t">예약확인</span></a></li>
		</ul>
	</div>
</footer>
<script>
	tab(".tab_notice",<?=iif($params['gubun']=='C002', '2', '1')?>);// 풋터 공지사항 탭
</script>