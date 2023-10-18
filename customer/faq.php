<?include("../inc/config.php")?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['gubun']      = chkReqRpl("gubun", "C001", "10", "", "STR");
	$params['bbs_code']   = iif($params['gubun']=='C001', 'faq', 'faq2');
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$params['open_flag']  = "Y";
	$page_params = setPageParamsValue($params, "page,list_size,block_size,open_flag");

	$cls_board = new CLS_BOARD;

	//에이터 사용여부
	$is_html = $cls_board->isHtml($params['bbs_code']);

	//목록 불러오기
	$list = $cls_board->list($params, $total_cnt, $total_page);

	//카테고리 불러오기
	$category_list = $cls_board->category_list($params['bbs_code'], 1);

	if ($params['gubun'] == 'C001') {
		$pageNum = "0104";
		$pageName = "자주하는 질문";
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0204";
		$pageName = "자주하는 질문";
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

			<div class="tab ty2">
				<ul>
					<li <?if (chkBlank($params['sch_cate'])) {?>class="on"<?}?>><a href="?gubun=<?=$params['gubun']?>"><span>전체</span></a></li>
					<?for ($i=0; $i<count($category_list); $i++) {?>
						<li <?if ($params['sch_cate']==$category_list[$i]['category_idx']) {?>class="on"<?}?>><a href="?gubun=<?=$params['gubun']?>&sch_cate=<?=$category_list[$i]['category_idx']?>"><?=$category_list[$i]['category_name']?></a></li>
					<?}?>
				</ul>
			</div>
			<form name="searchFrm" id="searchFrm" method="get">
			<input type="hidden" name="sch_cate" value="<?=$params['sch_cate']?>">
			<div class="area_sch">
				<div class="col inp">
					<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" class="inp_txt w100p" placeholder="검색어를 입력해주세요.">
					<button type="button" class="btn-pk n color rv" onclick="searchGo()"><span>검색</span></button>
				</div>
			</div>
			</form>

			<div class="tbl_basic ty2">
				<table class="list no-line small">
					<colgroup>
						<col class="trq">
						<col>
					</colgroup>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr class="tr_q">
								<td>Q</td>
								<td class="subject"><?=$list[$i]['title']?></td>
							</tr>
							<tr class="tr_a">
								<td class="c-color">A</td>
								<td class="ta-l">
									<?
										if ($is_html) {
											echo htmlDecode($list[$i]['content']);
										} else {
											echo textareaDecode($list[$i]['content']);
										}
									?>
								</td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="2">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div><!--//container -->

<script>
	$(function(){
		$("#sch_word").keyup(function(){
			enters(function(){ searchGo(); });
		})
	})

	function searchGo() {
		var h = new clsJsHelper();

		if (h.objVal("sch_word")) {
			if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
		}

		$("#searchFrm").submit();
	}
</script>

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>