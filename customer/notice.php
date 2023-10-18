<?include("../inc/config.php")?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['gubun']      = chkReqRpl("gubun", "C001", "10", "", "STR");
	$params['bbs_code']   = iif($params['gubun']=='C001', 'notice', 'notice2');
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
    $params['open_flag']  = "Y";
	$page_params = setPageParamsValue($params, "page,list_size,block_size,bbs_code,open_flag");

	$cls_board = new CLS_BOARD;

	//공지사항 목록 불러오기
	$notice_list = $cls_board->notice_list($params);

	//목록 불러오기
	$list = $cls_board->list($params, $total_cnt, $total_page);

	if ($params['gubun'] == 'C001') {
		$pageNum = "0103";
		$pageName = "공지사항";
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0203";
		$pageName = "공지사항";
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
			<form name="searchFrm" id="searchFrm" method="get">
			<input type="hidden" name="gubun" value="<?=$params['gubun']?>">
			<div class="area_sch">
				<div class="col sel">
					<select name="sch_type" id="sch_type" class="select1 ty2 w100p">
						<option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>제목</option>
						<option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>내용</option>
					</select>
				</div>
				<div class="col inp">
					<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" class="inp_txt w100p">
					<button type="button" class="btn-pk n color rv" onclick="searchGo()"><span>검색</span></button>
				</div>
			</div>
			</form>


			<div class="tbl_basic ty2">
				<table class="list no-line small">
					<colgroup>
						<col class="num">
						<col>
						<col class="file">
						<col class="day hide-m">
						<col class="cout hide-m">
					</colgroup>
					<thead>
						<tr>
							<th>No.</th>
							<th>제목</th>
							<th>파일</th>
							<th class="hide-m">날짜</th>
							<th class="hide-m">조회</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($notice_list);$i++) {?>
							<tr>
								<td class="c-color">공지</td>
								<td class="subject"><a href="notice_view.php?page=<?=$params['page'] . $page_params?>&idx=<?=$notice_list[$i]['idx']?>"><?=$notice_list[$i]['title']?></a></td>
								<td>
									<?if ($notice_list[$i]['up_file_1'] != "" || $notice_list[$i]['up_file_2'] != "") {?>
										<span class="file"><img src="../images/common/ico_file.png" alt="파일"></span>
									<?}?>
								</td>
								<td class="hide-m"><?=formatDates($notice_list[$i]['reg_date'], "Y.m.d")?></td>
								<td class="hide-m"><?=formatNumbers($notice_list[$i]['view_cnt'])?></td>
							</tr>
						<?}?>

						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td class="subject"><a href="notice_view.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>"><?=$list[$i]['title']?></a></td>
								<td>
									<?if ($list[$i]['up_file_1'] != "" || $list[$i]['up_file_2'] != "") {?>
										<span class="file"><img src="../images/common/ico_file.png" alt="파일"></span>
									<?}?>
								</td>
								<td class="hide-m"><?=formatDates($list[$i]['reg_date'], "Y.m.d")?></td>
								<td class="hide-m"><?=formatNumbers($list[$i]['view_cnt'])?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
                            <tr class="view-m">
								<td colspan="3">등록된 데이터가 없습니다.</td>
							</tr>
							<tr class="hide-m">
								<td colspan="5">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
			<div class="pagenation">
				<? frontPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
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