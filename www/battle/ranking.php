<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.php');

$ch_arr = get_character_ranking_list();

$my_ranking = get_character_ranking($character['ch_id']);
?>

<div id="exp-ranking-wrap">
  <div class="title-bar"><span>급식서열</span></div>
  <ul class="exp-ranking-box">
    <li>
      <div style="flex: 1 1 auto"><span>내 캐릭터 순위</span></div>
      <div style="flex: 1 1 auto"><span><?=$my_ranking?>위</span></div>
    </li>
    <?for ($i=0; $i < sizeof($ch_arr); $i++) { ?>
      <li>
        <div class="ranking"><span><?=$i+1?>위</span></div>
        <div class="thumb"><img class="thumb" src="<?=$ch_arr[$i]['ch_thumb']?>"/></div>
        <div class="name"><span><?=$ch_arr[$i]['ch_name']?></span></div>
      </li>
    <?}?>
  </ul>
</div>

<script>
$().ready(()=>{

})
</script>

<style>
/* COMMON */
section#body { transition: all 0s; margin-left: 168px; }
section#body * { box-sizing:border-box; }
#body > div.fix-layout { position: relative; padding: 0; max-width:1178px; transition: all 0s; color: #333333; }
div.title-bar { flex: 0 0 60px; line-height: 60px; font-weight: 800; font-size: 16px; text-align: center; color: #FFF; background-color: #000; border: 1.5px solid #AAAAAA; }

#exp-ranking-wrap { display: flex; flex-direction: column; background-color: #E3E3E3; }
#exp-ranking-wrap .exp-ranking-box { display: flex; flex: 1 1 auto; flex-direction: column; }
.exp-ranking-box li { display: flex; flex: 0 0 96px; flex-direction: row;  overflow: hidden; }
.exp-ranking-box li > div { display: flex; justify-content: center; align-items: center; }
.exp-ranking-box li .ranking { flex: 0 0 200px; }
.exp-ranking-box li .thumb { flex: 1 1 auto; text-align: center; }
.exp-ranking-box li .thumb > img { max-width: 96px; max-height: 96px; }
.exp-ranking-box li .name { flex: 5 5 auto; }  
</style>