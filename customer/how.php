<?include("../inc/config.php")?>
<?
	$params['gubun']   = chkReqRpl("gubun", "C001", "10", "", "STR");


	if ($params['gubun'] == 'C001') {
		$pageNum = "0102";
		$pageName = "이용안내";
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0202";
		$pageName = "이용안내";
	} else {
		fnMsgGo(500, "잘못된 요청 정보 입니다.", "BACK", "");
	}
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub customer">
	<div class="inr-c">
		<? include "side.php" ?>

		<section class="contents">
			<h2 class="hd_tit2"><strong class="c-color"><?=$pageName?></strong></h2>

			<?if ($params['gubun'] == 'C001') {?>
				<?include("_how_gaum.php")?>
			<?} else {?>
				<?include("_how_saipan.php")?>
			<?}?>
		</section>
	</div>
</div><!--//container -->

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>