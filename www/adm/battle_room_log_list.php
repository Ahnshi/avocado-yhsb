<?php
// @Auther Ahn Minsoo
$sub_menu = "920200";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
$token = get_token();

/* DATA: 전투장 목록 */
// FROM, LEFT JOIN
$sql_common = "FROM avo_battle_room_log brl
LEFT JOIN (
	SELECT br.*,
  	ch1.ch_name AS ch1_name, ch1.ch_thumb AS ch1_thumb, ch1.ch_head AS ch1_head, ch1.ch_body AS ch1_body, ch1.ch_side AS ch1_side,
  	ch2.ch_name AS ch2_name, ch2.ch_thumb AS ch2_thumb, ch2.ch_head AS ch2_head, ch2.ch_body AS ch2_body, ch2.ch_side AS ch2_side
	FROM avo_battle_room br
		LEFT JOIN avo_character ch1 ON br.br_ch1_id = ch1.ch_id
		LEFT JOIN avo_character ch2 ON br.br_ch2_id = ch2.ch_id
) br ON brl.br_id = br.br_id";

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
if (!$sst) { $sst = "brl_id"; $sod = "desc"; }
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
SELECT
	brl.brl_id,
	brl.brl_ch1_command,
	brl.brl_ch1_damaged,
	brl.brl_ch1_used_item_id,
	brl.brl_ch1_text,
	brl.brl_ch2_command,
	brl.brl_ch2_damaged,
	brl.brl_ch2_used_item_id,
	brl.brl_ch2_text,
	brl.brl_created_datetime,
	br.*
{$sql_common}
{$sql_search}
{$sql_order}
LIMIT $from_record, $rows
";
$result = sql_query($sql);

/* DATA: 캐릭터 목록 */
$sql_ch = "SELECT * FROM avo_character";

/* 페이지 설정 */
$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';
$g5['title'] = '전투장 기록 관리';
include_once ('./admin.head.php');
$colspan = 11;

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">전투장 기록 목록</a></li>
	<li><a href="#anc_002">전투장 기록 생성</a></li>
</ul>';
?>

<!-- 전투장 기록 목록 -->
<section id="anc_001">
	<h2 class="h2_frm">전투장 기록 목록</h2>
	<?php echo $pg_anchor ?>
	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>
	<form name="fsidelist" id="fsidelist" method="post" action="./battle_room_log_list_delete.php" onsubmit="return fsidelist_submit(this);" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_head01 tbl_wrap">
		<table>
		<caption>전투장 기록 목록</caption>
		<thead>
		<tr>
			<th scope="col" rowspan="2">
				<label for="chkall" class="sound_only">전체목록</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th>
			<th scope="col" rowspan="2">idx</th>
			<th scope="col" rowspan="2">전투장idx</th>
			<th scope="col" colspan="4">캐릭터1</th>
			<th scope="col" colspan="4">캐릭터2</th>
		</tr>
		<tr>
			<th scope="col">행동</th>
			<th scope="col">받은 대미지</th>
			<th scope="col">사용한 아이템 idx</th>
			<th scope="col" class="col-text">문구</th>
			<th scope="col">행동</th>
			<th scope="col">받은 대미지</th>
			<th scope="col">사용한 아이템 idx</th>
			<th scope="col" class="col-text">문구</th>
		</tr>
		</thead>
		<tbody>
		<?for ($i=0; $row=sql_fetch_array($result); $i++) { $bg = 'bg'.($i%2); ?>
		<tr class="<?=$bg?>">
			<td style="text-align: center">
				<input type="checkbox" name="chk[]" value="<?=$i?>" id="chk_<?=$i?>"/>
			</td>
			<td>
				<input type="text" name="brl_id[<?=$i?>]" value="<?=$row['brl_id']?>" readonly style="width: 30px;"/>
			</td>
			<td>
				<input type="text" name="br_id[<?=$i?>]" value="<?=$row['br_id']?>" style="width: 30px;"/>
			</td>
			<td>
				<select name="brl_ch1_command[<?=$i?>]">
					<option value="" <?=$row['brl_ch1_command']==''?"selected":""?>>-</option>
					<option value="공격" <?=$row['brl_ch1_command']=='공격'?"selected":""?>>공격</option>
					<option value="회상" <?=$row['brl_ch1_command']=='회상'?"selected":""?>>회상</option>
					<option value="필살기" <?=$row['brl_ch1_command']=='필살기'?"selected":""?>>필살기</option>
					<option value="아이템" <?=$row['brl_ch1_command']=='아이템'?"selected":""?>>아이템</option>
				</select>
			</td>
			<td><input type="text" name="brl_ch1_damaged[<?=$i?>]" value="<?=$row['brl_ch1_damaged']?>" class="frm_input"></td>
			<td><input type="text" name="brl_ch1_used_item_id[<?=$i?>]" value="<?=$row['brl_ch1_used_item_id']?>" class="frm_input"></td>
			<td><input type="text" name="brl_ch1_text[<?=$i?>]" value="<?=$row['brl_ch1_text']?>" class="frm_input col_text"></td>
			<td>
				<select name="brl_ch2_command[<?=$i?>]">
					<option value="" <?=$row['brl_ch2_command']==''?"selected":""?>>-</option>
					<option value="공격" <?=$row['brl_ch2_command']=='공격'?"selected":""?>>공격</option>
					<option value="회상" <?=$row['brl_ch2_command']=='회상'?"selected":""?>>회상</option>
					<option value="필살기" <?=$row['brl_ch2_command']=='필살기'?"selected":""?>>필살기</option>
					<option value="아이템" <?=$row['brl_ch2_command']=='아이템'?"selected":""?>>아이템</option>
				</select>
			</td>
			<td><input type="text" name="brl_ch2_damaged[<?=$i?>]" value="<?=$row['brl_ch2_damaged']?>" class="frm_input"></td>
			<td><input type="text" name="brl_ch2_used_item_id[<?=$i?>]" value="<?=$row['brl_ch2_used_item_id']?>" class="frm_input"></td>
			<td><input type="text" name="brl_ch2_text[<?=$i?>]" value="<?=$row['brl_ch2_text']?>" class="frm_input col_text"></td>
		</tr>
		<?}?>
		<?if($i==0) echo "<tr><td colspan='".$colspan."' class='empty_table'>자료가 없습니다.</td></tr>";?>
		</tbody>
		</table>
	</div>

	<!-- 전투장 기록 목록 조작 버튼 -->
	<div class="btn_list01 btn_list">
		<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
		<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	</div>

	</form>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
</section>

<!-- 전투장 기록 생성 -->
<section id="anc_002">
	<h2 class="h2_frm">전투장 기록 생성</h2>
	<?php echo $pg_anchor ?>

	<!-- 전투장 기록 생성 FORM -->
	<form name="fsideform" method="post" id="fsideform" action="./battle_room_log_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<!-- 전투장 기록 생성 FORM: 테이블 -->
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 210px;">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th>전투장idx</th><td><input type="text" name="br_id" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터1 행동</th>
				<td>
					<select name="brl_ch1_command">
						<option value="">-</option>
						<option value="공격">공격</option>
						<option value="회상">회상</option>
						<option value="필살기">필살기</option>
						<option value="아이템">아이템</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>캐릭터1 받은 대미지</th><td><input type="text" name="brl_ch1_damaged" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터1 사용 아이템 idx</th><td><input type="text" name="brl_ch1_used_item_id" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터1 출력 텍스트</th><td><input type="text" name="brl_ch1_text" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터2 행동</th>
				<td>
					<select name="brl_ch2_command">
						<option value="">-</option>
						<option value="공격">공격</option>
						<option value="회상">회상</option>
						<option value="필살기">필살기</option>
						<option value="아이템">아이템</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>캐릭터2 받은 대미지</th><td><input type="text" name="brl_ch2_damaged" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터2 사용 아이템 idx</th><td><input type="text" name="brl_ch2_used_item_id" class="frm_input"/></td>
			</tr>
			<tr>
				<th>캐릭터2 출력 텍스트</th><td><input type="text" name="brl_ch2_text" class="frm_input"/></td>
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
input[type="text"].col_text { width: 196px; }
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
</script>

<?php
include_once ('./admin.tail.php');
?>