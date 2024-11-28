<?php
// @Auther Ahn Minsoo
$sub_menu = "920100";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
$token = get_token();

/* DATA: 전투장 목록 */
// FROM, LEFT JOIN
$sql_common = "FROM avo_battle_room br
LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id";

// WHERE
if ($stx) {
	$sql_search .= " AND ( ";
	switch ($sfl) {
		default :
			$sql_search .= " ({$sfl} LIKE '%{$stx}%') ";
			break;
	}
	$sql_search .= " ) ";
}

// ORDER BY
if (!$sst) { $sst = "br_id"; $sod = "desc"; }
$sql_order = "ORDER BY {$sst} {$sod}";

// QUERY: COUNT
$sql = "SELECT COUNT(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 페이지네이션
$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// QUERY: LIST
$sql = "
SELECT br.*,
  ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
  ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
{$sql_common}
{$sql_search}
{$sql_order}
limit $from_record, $rows
";
$result = sql_query($sql);

/* DATA: 캐릭터 목록 */
$sql_ch = "SELECT * FROM avo_character";

/* 페이지 설정 */
$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';
$g5['title'] = '전투장 관리';
include_once ('./admin.head.php');
$colspan = 18;

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">전투장 목록</a></li>
	<li><a href="#anc_002">전투장 생성</a></li>
</ul>';
?>

<!-- 전투장 목록 -->
<section id="anc_001">
	<h2 class="h2_frm">전투장 목록</h2>
	<?php echo $pg_anchor ?>
	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>
	<form name="fsidelist" id="fsidelist" method="post" action="./battle_room_list_delete.php" onsubmit="return fsidelist_submit(this);" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_head01 tbl_wrap">
		<table>
		<caption>전투장 목록</caption>
		<colgroup>
			<col/>
			<col/>
			<col/>
			<col/>
			<col/>
			<col style="width:100px"/>
			<col style="width:100px"/>
			<col style="width:120px"/>
			<col/>
			<col/>
			<col/>
			<col style="width:100px"/>
			<col style="width:100px"/>
			<col style="width:120px"/>
		</colgroup>
		<thead>
		<tr>
			<th scope="col" rowspan="2">
				<label for="chkall" class="sound_only">전체목록</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th>
			<th scope="col" rowspan="2">idx</th>
			<th scope="col" colspan="6">캐릭터1</th>
			<th scope="col" colspan="6">캐릭터2</th>
			<th scope="col" rowspan="2">상태</th>
		</tr>
		<tr>
			<th scope="col" class="col_name">이름</th>
			<th scope="col">최대체력</th>
			<th scope="col">현재에너지</th>
			<th scope="col">스킬사용여부</th>
			<th scope="col">포션사용여부</th>
			<th scope="col">현재 대미지 감소율</th>
			<th scope="col" class="col_name">이름</th>
			<th scope="col">최대체력</th>
			<th scope="col">현재에너지</th>
			<th scope="col">스킬사용여부</th>
			<th scope="col">포션사용여부</th>
			<th scope="col">현재 대미지 감소율</th>
		</tr>
		</thead>
		<tbody>
		<?for ($i=0; $row=sql_fetch_array($result); $i++) { $bg = 'bg'.($i%2); ?>
		<input type="hidden" name="br_ch1_id[<?=$i?>]" value="<?=$row['br_ch1_id']?>"/>
		<input type="hidden" name="br_ch2_id[<?=$i?>]" value="<?=$row['br_ch2_id']?>"/>
		<tr class="<?php echo $bg; ?>">
			<td style="text-align: center">
				<input type="checkbox" name="chk[]" value="<?=$i?>" id="chk_<?=$i?>"/>
			</td>
			<td>
				<input type="text" name="br_id[<?=$i?>]" value="<?=$row['br_id']?>" readonly style="width: 30px;"/>
			</td>
			<td><?=$row['ch1_name']?></td>
			<td><input type="text" name="br_ch1_hp[<?=$i?>]" value="<?=$row['br_ch1_hp']?>" class="frm_input"></td>
			<td><input type="text" name="br_ch1_energy[<?=$i?>]" value="<?=$row['br_ch1_energy']?>" class="frm_input"></td>
			<td>
				<select name="br_ch1_skill_used[<?=$i?>]">
					<option value="0" <?=$row['br_ch1_skill_used']==0?"selected":""?>>N</option>
					<option value="1" <?=$row['br_ch1_skill_used']==1?"selected":""?>>Y</option>
				</select>
			</td>
			<td>
				<select name="br_ch1_potion_used[<?=$i?>]">
					<option value="0" <?=$row['br_ch1_potion_used']==0?"selected":""?>>N</option>
					<option value="1" <?=$row['br_ch1_potion_used']==1?"selected":""?>>Y</option>
				</select>
			</td>
			<td><input type="text" name="br_ch1_dmg_cut_rate[<?=$i?>]" value="<?=$row['br_ch1_dmg_cut_rate']?>" class="frm_input"></td>
			<td><?=$row['ch2_name']?></td>
			<td><input type="text" name="br_ch2_hp[<?=$i?>]" value="<?=$row['br_ch2_hp']?>" class="frm_input"></td>
			<td><input type="text" name="br_ch2_energy[<?=$i?>]" value="<?=$row['br_ch2_energy']?>" class="frm_input"></td>
			<td>
				<select name="br_ch2_skill_used[<?=$i?>]">
					<option value="0" <?=$row['br_ch2_skill_used']==0?"selected":""?>>N</option>
					<option value="1" <?=$row['br_ch2_skill_used']==1?"selected":""?>>Y</option>
				</select>
			</td>
			<td>
				<select name="br_ch2_potion_used[<?=$i?>]">
					<option value="0" <?=$row['br_ch2_potion_used']==0?"selected":""?>>N</option>
					<option value="1" <?=$row['br_ch2_potion_used']==1?"selected":""?>>Y</option>
				</select>
			</td>
			<td><input type="text" name="br_ch2_dmg_cut_rate[<?=$i?>]" value="<?=$row['br_ch2_dmg_cut_rate']?>" class="frm_input"></td>
			<td>
				<select name="br_status[<?=$i?>]">
					<option value="READY" <?=$row['br_status']=='READY'?"selected":""?>>READY</option>
					<option value="FIGHT" <?=$row['br_status']=='FIGHT'?"selected":""?>>FIGHT</option>
					<option value="KNOCKOUT" <?=$row['br_status']=='KNOCKOUT'?"selected":""?>>KNOCKOUT</option>
				</select>
			</td>
		</tr>
		<?}?>
		<?if($i==0) echo "<tr><td colspan='".$colspan."' class='empty_table'>자료가 없습니다.</td></tr>";?>
		</tbody>
		</table>
	</div>

	<!-- 전투장 목록 조작 버튼 -->
	<div class="btn_list01 btn_list">
		<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
		<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	</div>

	</form>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
</section>

<!-- 전투장 생성 -->
<section id="anc_002">
	<h2 class="h2_frm">전투장 생성</h2>
	<?php echo $pg_anchor ?>

	<!-- 전투장 생성 FORM -->
	<form name="fsideform" method="post" id="fsideform" action="./battle_room_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<!-- 전투장 생성 FORM: 테이블 -->
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 120px;">
			<col style="width: 120px;">
			<col style="width: 120px;">
			<col>
		</colgroup>
		<tbody>
			<!-- 캐릭터1 -->
			<tr>
				<th rowspan="6">캐릭터1</th>
				<th colspan="2">이름</th>
				<td>
					<!-- 캐릭터 이름 선택 AJAX -->
					<select id="br_ch1_id" name="br_ch1_id" class="select_ch_name" data-ch="1" style="width:240px">
						<option value="">-</option>
						<?$result_ch = sql_query($sql_ch); for ($i=0; $row_ch=sql_fetch_array($result_ch); $i++) { echo "<option value=\"{$row_ch['ch_id']}\">{$row_ch['ch_name']}</option>"; } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th rowspan="5">정보</th>
				<th>현재 체력</th><td><input type="text" name="br_ch1_hp" class="frm_input"/></td>
			</tr>
			<tr>
				<th>현재 에너지</th><td><input type="text" name="br_ch1_energy" class="frm_input"/></td>
			</tr>
			<tr>
				<th>스킬 사용 여부</th><td>
					<select name="br_ch1_skill_used">
						<option value="0">N</option>
						<option value="1">Y</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>포션 사용 여부</th><td>
					<select name="br_ch1_potion_used">
						<option value="0">N</option>
						<option value="1">Y</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>현재 대미지 감소율(%)</th><td><input type="text" name="br_ch1_dmg_cut_rate" class="frm_input"/></td>
			</tr>

			<!-- 캐릭터2 -->
			<tr>
				<th rowspan="6">캐릭터2</th>
				<th colspan="2">이름</th>
				<td>
					<!-- 캐릭터 이름 선택 AJAX -->
					<select id="br_ch2_id" name="br_ch2_id" class="select_ch_name" data-ch="2" style="width:240px">
						<option value="">-</option>
						<?$result_ch = sql_query($sql_ch); for ($i=0; $row_ch=sql_fetch_array($result_ch); $i++) { echo "<option value=\"{$row_ch['ch_id']}\">{$row_ch['ch_name']}</option>"; } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th rowspan="5">정보</th>
				<th>체력</th><td><input type="text" name="br_ch2_hp" class="frm_input"/></td>
			</tr>
			<tr>
				<th>에너지</th><td><input type="text" name="br_ch2_energy" class="frm_input"/></td>
			</tr>
			<tr>
				<th>스킬 사용 여부</th><td>
					<select name="br_ch2_skill_used">
						<option value="0">N</option>
						<option value="1">Y</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>포션 사용 여부</th><td>
					<select name="br_ch2_potion_used">
						<option value="0">N</option>
						<option value="1">Y</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>현재 대미지 감소율(%)</th><td><input type="text" name="br_ch2_dmg_cut_rate" class="frm_input"/></td>
			</tr>

			<!-- 상태 -->
			<tr>
				<th colspan="3">전투 상태</th>
				<td>
					<select name="br_status">
						<option value="READY">READY</option>
						<option value="FIGHT">FIGHT</option>
						<option value="KNOCKOUT">KNOCKOUT</option>
					</select>
				</td>
			</tr>

		</tbody>
		</table>
	</div>

	<!-- 전투장 생성 FORM: 버튼영역 -->
	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>
</section>

<style>
.tbl_head01.tbl_wrap input[type="text"]{ width: 60px }
th.col_name { width: 112px; }
</style>

<script>
function fsidelist_submit(f)
{
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}

$(document).ready(function(){
  $(".select_ch_name").on("change", function(){
    var ch_id = $(this).val();

		let url = "/ajax/_search_battle_character.php"
		if(ch_id) url += `?ch_id=${ch_id}`

		$.ajax({
			type:'get', async: true, url: url,
			success: (res)=>{
				try {
					const json = JSON.parse(res);
					const ch = json?.[0]
					const n = $(this)?.data()?.ch //=>2
					const ch_n = `ch${n}` //=>ch2
					$(`input[name="br_${ch_n}_hp"]`).val(ch.ch_hp)
					$(`input[name="br_${ch_n}_energy"]`).val(0)
					$(`input[name="br_${ch_n}_skill_used"]`).val('N')
					$(`input[name="br_${ch_n}_potion_used"]`).val('N')
					$(`input[name="br_${ch_n}_dmg_cut_rate"]`).val(0)
				} catch (e) { /* Do nothing...  */ }
			},
			error: (e)=>{ /* Do nothing...  */ }
		})

  });
});
</script>

<?php
include_once ('./admin.tail.php');
?>