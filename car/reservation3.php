<?include("../inc/config.php")?>
<?
	$pageNum = "0303";
	$pageName = "예약확인 및 결제";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
	<div class="inr-c">
		<section class="area_reser box-line mt-ty1">
			<header class="hd_titbox">
				<h2 class="hd_tit1 mb5"><span class="h">예약확인 및 결제</span></h2>
				<p class="hd_tit3">예약시 작성하신 예약번호와 연락처 입력 후 확인을 클릭하세요.</p>
			</header>

			<div class="tbl_basic ty2">
				<form name="bookingFrm" id="bookingFrm" method="post">
				<table class="view">
					<tbody>
						<tr>
							<th>예약번호</th>
							<td><input type="text" name="booking_num" id="booking_num" class="inp_txt w100p" maxlength="15" placeholder="예약번호를 입력해 주십시오."></td>
						</tr>
						<tr>
							<th>휴대폰번호</th>
							<td><input type="number" name="booker_phone" id="booker_phone" class="inp_txt w100p" maxlength="11" placeholder="'-'없이 입력해 주십시오."></td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>

			 <div class="btn-bot">
			 <a href="javascript:;" class="btn-pk nb color rv w100p" onclick="bookingSearchGo()"><span>확인</span></a>
			</div>
		</section>
	</div>
</div><!--//container -->

<script>
	function bookingSearchGo() {
		var h = new clsJsHelper();

		if (!h.checkValNLen("booking_num", 15, 15, "예약번호", "Y", "ON")) return false;
		if (!h.checkValNLen("booker_phone", 10, 11, "휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("booker_phone"), "휴대폰번호", "")) return false;

		AJ.ajaxForm($("#bookingFrm"), "booking_check_proc.php", function(data) {
			if (data.result == 200) {
				location = "reservation4.php?token="+data.token;
			} else {
				alert(data.message);
			}
		});
	}
</script>

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>