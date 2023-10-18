<?include("../inc/config.php")?>
<?
	$params['idx']        = chkReqRpl("idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['gubun']      = chkReqRpl("gubun", "C001", "10", "", "STR");
	$params['bbs_code']   = iif($params['gubun']=='C001', 'notice', 'notice2');
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
    $params['open_flag']  = "Y";
	$page_params          = setPageParamsValue($params, "page,list_size,block_size,bbs_code,open_flag");

	$cls_board = new CLS_BOARD;

	$view = $cls_board->view($params);
	if ($view == false) fnMsgGo(501, "일치하는 게시글 정보가 없습니다.", "BACK", "");

	//에이터 사용여부
	$is_html = $cls_board->isHtml($params['bbs_code']);

	if ($is_html) {
		$view['content'] = htmlDecode($view['content']);
	} else {
		$view['content'] = textareaDecode($view['content']);
	}

	//조회수 업데이트
	$cls_board->view_check($params['idx']);

	//이전글
	$view_prev = $cls_board->view_prev_next($params, 'prev', $view['notice_flag']);

	//다음글
	$view_next = $cls_board->view_prev_next($params, 'next', $view['notice_flag']);

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

			<div class="tbl_view">
				<div class="tit">
					<p class="h"><?=$view['title']?></p>
					<div class="lft">
						<p class="t1"><span>등록일</span><?=formatDates($view['reg_date'], "Y.m.d")?></p>
					</div>
					<div class="rgh">
						<p class="t1"><span>조회수</span><?=formatNumbers($view['view_cnt'])?></p>
					</div>
				</div>
				<div class="cont">
					<?=$view['content']?>
				</div>
				<?if ($view['up_file_1'] != '' || $view['up_file_2'] != '') {?>
					<div class="link">
						<?for ($i=1; $i<=2; $i++) {?>
							<?if ($view['up_file_'.$i] != '') {?>
								<p class="t1 <?if ($i>1){?>mt5<?}?>">
									<span>첨부파일</span><a href="/module/board/file_down.php?idx=<?=$params['idx']?>&fnum=<?=$i?>" class="c-color"><?=getUpfileOriName($view['up_file_'.$i])?></a>
								</p>
							<?}?>
						<?}?>
					</div>
				<?}?>
			</div>
			<div class="btn-bot ta-r">
				<a href="notice.php?page=<?=$params['page'] . $page_params?>" class="btn-pk nb color rv mw100p">목록으로</a>
			</div>
		</section>
	</div>
</div><!--//container -->

<?include("../inc/footer.php")?>
<?include("../inc/bottom.php")?>