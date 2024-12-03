<?php
include_once('./_common.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/quest/style.css">', 0);

$g5['title'] = "퀘스트";
include_once('./_head.php');

$search_cate = explode("||", $character['ch_quest']);
$search_cate = array_filter($search_cate);
$search_cate = "qu_cate = '".implode("' or qu_cate = '", $search_cate)."'";

//$quest = sql_query("select * from {$g5['quest_table']} where (qu_cate = '' or {$search_cate}) and (si_id = 0 or si_id = '{$character['ch_side']}') and qu_sdate <= '".date('Y-m-d')."' and qu_use = 1 order by qu_sdate desc, qu_edate asc");

$quest_inprogress 	= sql_query("select * from {$g5['quest_table']} where (qu_cate = '' or {$search_cate}) and (si_id = 0 or si_id = '{$character['ch_side']}') and qu_sdate <= '".date('Y-m-d')."' and qu_edate >= '".date('Y-m-d')."' and qu_use = 1 order by qu_sdate desc, qu_edate asc");
$quest_finish 		= sql_query("select * from {$g5['quest_table']} where (qu_cate = '' or {$search_cate}) and (si_id = 0 or si_id = '{$character['ch_side']}') and qu_sdate <= '".date('Y-m-d')."' and qu_edate < '".date('Y-m-d')."' and qu_use = 1 order by qu_sdate desc, qu_edate asc");

?>
<br>
<p align="center"><div height="164px" style="padding: 10px; margin: 0 auto; text-align: center; box-sizing: border-box;  
background: rgba(0, 0, 0, 0.7); olor:#ffffff;">
＊퀘스트 완료 시 로그의 링크가 미등록 된 경우, 관리자(아이디: admin)에게 쪽지로 링크를 보내주세요.<br>
＊3컷 이상의 극화 로그는 관록란에 투고해주세요!
</div></p>
<br>

<div class="quest-tab">
	<div id="quest_inprogress" class="quest-btn quest-tab-active" onclick="tabBox(this, '#quest_inprogress_list')">
		진행중
	</div>

	<div id="quest_finish" class="quest-btn" onclick="tabBox(this, '#quest_finish_list')">
		종료
	</div>
</div>

<div class="quest-list-wrap">
	<div class="quest-list" id="quest_inprogress_list">
		<?
			$now = date('Y-m-d');
			for($i = 0; $qu = sql_fetch_array($quest_inprogress); $i++) { 
				$is_apply = false;
				$is_has = false;

				$qm = sql_fetch("select * from {$g5['quest_member_table']} where qu_id = '{$qu['qu_id']}' and ch_id = '{$character['ch_id']}'");
				if($now >= $qu['qu_sdate'] && $now <= $qu['qu_edate'] && !$qm['qm_id']) { 
					$is_apply = true;
					if($qm['qm_state'] == 'E') { 
						$is_has = true;
					}
				}

		?>
			<dl id="quest_<?=$qu['qu_id']?>" class="theme-box <?= ($qu_id == $qu['qu_id']) ? "on" : ""?> <?=($qm['qm_state'] == 'E')?'quest-finish':''?>">
				<dt>
					<button type="button" onclick="$(this).closest('dl').toggleClass('on');">
						<strong><?=$qu['qu_title']?></strong>
						
						<? if($is_apply && !$is_has) { ?>
							<span class="prev">수락</span>
						<? } else if($qm['qm_state'] == 'E') { ?>
							<span class="end">완료</span>
						<? } else if($qm['qm_state'] == 'R') { ?>
							<span class="ready">진행중</span>
						<? } else { ?>
							<span class="com">마감</span>
						<? } ?>
					</button>
				</dt>
				<dd>
					<div class="quest-content">
						<? if($qu['qu_img']) { ?>
							<div class="thumb">
								<em><img src="<?=$qu['qu_img']?>" alt="" /></em>
							</div>
						<? } ?>
						<div class="con">
							<?=nl2br($qu['qu_content'])?>

							<div class="control">
								<? if($is_apply && !$is_has) { ?>
									<a href="./quest_apply.php?qu_id=<?=$qu['qu_id']?>&list=1" onclick="return confirm('해당 퀘스트를 수락하시겠습니까?');">수락</a>
								<? } else if($qm['qm_state'] == 'E') { ?>
									<span class="end">완료</span>
								<? } else if($qm['qm_state'] == 'R') { ?>				
									<form action="quest_add.php" method="post">
										<input type="hidden" name="qu_id" value="<?=$qm['qu_id']?>" />
										<input type="hidden" name="qm_id" value="<?=$qm['qm_id']?>" />
									<? if($qu['qu_it_id'] > 0) { ?>
										<select name="sub_qu_it" id="sub_qu_it" class="make-imtem">
											<option value="">아이템 선택</option>
											<?
												$result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and inven.it_id = it.it_id and it.it_id = {$qu['qu_it_id']}");
												for($i=0; $re_row = sql_fetch_array($result); $i++) { 
											?>
												<option value="<?=$re_row['in_id']?>">
													<?=$re_row['it_name']?> <?=$re_row['se_ch_id']!=""?"(".$re_row['se_ch_name'].")":""?>
												</option>
											<? } ?>
										</select>
									<? } else { ?>
										<input type="text" name="qm_log" value="<?=$qm['qm_log']?>" style="width:70%" placeholder="로그주소"/>
									<? } ?>
										<button type="submit" class="ui-btn">제출</button>
									</form>
								<? } else { ?>
									<span class="com">마감</span>
								<? } ?>

								<div class="mem-list">
									<?
										// 퀘스트 수행 목록 확인하기
										$quest_member_list = sql_query("select * from {$g5['quest_member_table']} where qu_id = '{$qm['qu_id']}' and qm_log != ''");
										for($j=0; $qm_list = sql_fetch_array($quest_member_list); $j++) { 
									?>
										<div class="item">
											<a href="<?=$qm_list['qm_log']?>" target="_blank">
												<?=get_character_name($qm_list['ch_id'])?> [<?=get_member_name($qm_list['mb_id'])?>]
											</a>
										</div>

									<? } ?>
								</div>
							</div>
						</div>
					</div>
				</dd>
			</dl>
		<? } ?>
	</div>

	<div class="quest-list" id="quest_finish_list" style="display:none;">
		<?
			$now = date('Y-m-d');
			for($i = 0; $qu = sql_fetch_array($quest_finish); $i++) { 
				$is_apply = false;
				$is_has = false;

				$qm = sql_fetch("select * from {$g5['quest_member_table']} where qu_id = '{$qu['qu_id']}' and ch_id = '{$character['ch_id']}'");
				if($now >= $qu['qu_sdate'] && $now <= $qu['qu_edate'] && !$qm['qm_id']) { 
					$is_apply = true;
					if($qm['qm_state'] == 'E') { 
						$is_has = true;
					}
				}

		?>
			<dl id="quest_<?=$qu['qu_id']?>" class="theme-box <?= ($qu_id == $qu['qu_id']) ? "on" : ""?>">
				<dt>
					<button type="button" onclick="$(this).closest('dl').toggleClass('on');">
						<strong><?=$qu['qu_title']?></strong>
						
						<? if($is_apply && !$is_has) { ?>
							<span class="prev">수락</span>
						<? } else if($qm['qm_state'] == 'E') { ?>
							<span class="end">완료</span>
						<? } else if($qm['qm_state'] == 'R') { ?>
							<span class="ready">진행중</span>
						<? } else { ?>
							<span class="com">마감</span>
						<? } ?>
					</button>
				</dt>
				<dd>
					<div class="quest-content">
						<? if($qu['qu_img']) { ?>
							<div class="thumb">
								<em><img src="<?=$qu['qu_img']?>" alt="" /></em>
							</div>
						<? } ?>
						<div class="con">
							<?=nl2br($qu['qu_content'])?>

							<div class="control">
								<? if($is_apply && !$is_has) { ?>
									<a href="./quest_apply.php?qu_id=<?=$qu['qu_id']?>&list=1" onclick="return confirm('해당 퀘스트를 수락하시겠습니까?');">수락</a>
								<? } else if($qm['qm_state'] == 'E') { ?>
									<span class="end">완료</span>
								<? } else if($qm['qm_state'] == 'R') { ?>				
									<form action="quest_add.php" method="post">
										<input type="hidden" name="qu_id" value="<?=$qm['qu_id']?>" />
										<input type="hidden" name="qm_id" value="<?=$qm['qm_id']?>" />
									<? if($qu['qu_it_id'] > 0) { ?>
										<select name="sub_qu_it" id="sub_qu_it" class="make-imtem">
											<option value="">아이템 선택</option>
											<?
												$result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and inven.it_id = it.it_id and it.it_id = {$qu['qu_it_id']}");
												for($i=0; $re_row = sql_fetch_array($result); $i++) { 
											?>
												<option value="<?=$re_row['in_id']?>">
													<?=$re_row['it_name']?> <?=$re_row['se_ch_id']!=""?"(".$re_row['se_ch_name'].")":""?>
												</option>
											<? } ?>
										</select>
									<? } else { ?>
										<input type="text" name="qm_log" value="<?=$qm['qm_log']?>" style="width:70%" placeholder="로그주소"/>
									<? } ?>
										<button type="submit" class="ui-btn">제출</button>
									</form>
								<? } else { ?>
									<span class="com">마감</span>
								<? } ?>

								<div class="mem-list">
									<?
										// 퀘스트 수행 목록 확인하기
										$quest_member_list = sql_query("select * from {$g5['quest_member_table']} where qu_id = '{$qm['qu_id']}' and qm_log != ''");
										for($j=0; $qm_list = sql_fetch_array($quest_member_list); $j++) { 
									?>
										<div class="item">
											<a href="<?=$qm_list['qm_log']?>" target="_blank">
												<?=get_character_name($qm_list['ch_id'])?> [<?=get_member_name($qm_list['mb_id'])?>]
											</a>
										</div>

									<? } ?>
								</div>
							</div>
						</div>
					</div>
				</dd>
			</dl>
		<? } ?>
	</div>
</div>


<script>
<? if($qu_id) { ?>
	$('html, body').animate({ scrollTop: $("#quest_<?=$qu_id?>").offset().top }, 300);
<? } ?>

function tabBox(e, s){
	$(e).addClass('quest-tab-active');
	$(e).siblings().removeClass('quest-tab-active');

	$(s).siblings().hide();
	$(s).show();
}
</script>

<?
include_once('./_tail.php');
?>

