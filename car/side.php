<aside class="sidebar">
    <h2 class="hd_tit1"><span class="h"><?=getGoodsCateName($params['gubun'])?></span></h2>
    <div class="lnb">
        <ul>
            <li <?if (right($pageNum,2)=='01'){?>class="on"<?}?>><a href="../car/list.php?gubun=<?=$params['gubun']?>"><?=iif($params['gubun']=='C001', '괌', '사이판')?> 차량보기</a></li>
            <li <?if (right($pageNum,2)=='02'){?>class="on"<?}?>><a href="../customer/how.php?gubun=<?=$params['gubun']?>">이용안내</a></li>
            <li <?if (right($pageNum,2)=='03'){?>class="on"<?}?>><a href="../customer/notice.php?gubun=<?=$params['gubun']?>">공지사항</a></li>
            <li <?if (right($pageNum,2)=='04'){?>class="on"<?}?>><a href="../customer/faq.php?gubun=<?=$params['gubun']?>">자주하는 질문</a></li>
        </ul>
    </div>
</aside>