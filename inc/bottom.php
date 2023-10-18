</div><!--//wrap -->

<?if (count($pop_list) > 0) {?>
    <div class="wrap_mainPopup">
        <?for ($i=0; $i<count($pop_list); $i++) {?>
            <?
                $img_pc = $pop_list[$i]['up_file_1'];
                $img_mo = $pop_list[$i]['up_file_2'];
                if (chkBlank($img_mo)) $img_mo = $img_pc;

                $link_pc = $pop_list[$i]['link_pc'];
                $link_mo = $pop_list[$i]['link_mobile'];
                if (chkBlank($link_mo)) $link_mo = $link_pc;

                $target_pc = $pop_list[$i]['target_pc'];
                $target_mo = $pop_list[$i]['target_mobile'];
                if (chkBlank($target_mo)) $target_mo = $target_pc;

                $img    = iif(returnMobileCheck() == false, $img_pc, $img_mo);
                $link   = iif(returnMobileCheck() == false, $link_pc, $link_mo);
                $target = iif(returnMobileCheck() == false, $target_pc, $target_mo);
            ?>
            <div id="mainPopup<?=$i?>" class="mainPopup">
                <div class="info">
                    <?if ($link != '') {?>
                        <a href="<?=$link?>" target="<?=$target?>"><img src="/upload/popup/<?=getUpfileName($img)?>"></a>
                    <?} else {?>
                        <img src="/upload/popup/<?=getUpfileName($img)?>">
                    <?}?>
                </div>
                <div class="botm">
                    <label class="inp_checkbox"><input type="checkbox" id="todayPopupHide<?=$i?>"><span>오늘 하루 안 보기</span></label>
                    <button type="button" onclick="closePopup(this);">닫기</button>
                </div>
            </div>
        <?}?>
    </div>
<?}?>

<script src="/js/popup.js"></script>
<script>
    $(function(){
        <?if (count($pop_list) > 0) {?>
            var pop_cnt = <?=count($pop_list)?>

            //팝업쿠키
            for(i=0; i<pop_cnt; i++){
                if(getCookie("notToday"+i)!="Y"){
                    $("#mainPopup"+i).css("display","inline-block");
                } else {
                    $("#mainPopup"+i).css("display","none");
                }
            }
        <?}?>
    })
</script>



<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript" src="/module/js/jquery.form.js"></script>
<script type="text/javascript" src="/module/js/jquery.tmpl.js"></script>
<script type="text/javascript" src="/module/js/jquery.moment.js"></script>
<script type="text/javascript" src="/module/js/fn.util.js"></script>
<script type="text/javascript" src="/module/js/fn.check.field.js"></script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
if(!wcs_add) var wcs_add = {};
wcs_add["wa"] = "2d687be1465fb";
if(window.wcs) {
wcs_do();
}
</script>
</body>
</html>