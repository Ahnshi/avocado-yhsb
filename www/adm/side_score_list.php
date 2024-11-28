<?php
$sub_menu = "920300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from avo_character_side_score ";

$sql_search = " where (1) ";

if (!$sst) {
	$sst = "sis_id";
	$sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
	{$sql_common}
	{$sql_search}
	{$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select sis.*, si.si_name, si.si_img
	{$sql_common} sis
	LEFT JOIN {$g5['side_table']} si ON sis.si_id = si.si_id
	{$sql_search}
	{$sql_order}
	LIMIT {$from_record}, {$rows}
";
$result = sql_query($sql);

$sql_sum = "
	SELECT si.*, IFNULL(SUM(sis.sis_score_1), 0) as sis_score_1, IFNULL(SUM(sis.sis_score_2), 0) as sis_score_2
	FROM {$g5['side_table']} si
	LEFT JOIN avo_character_side_score sis
	ON si.si_id = sis.si_id
	GROUP BY si.si_id
	ORDER BY si.si_id DESC
	LIMIT {$from_record}, {$rows};
";
$result_sum = sql_query($sql_sum);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = $config['cf_side_title'].' 점수 관리';

include_once ('./admin.head.php');

$colspan = 6;
$colspan_sum = 5;

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">'.$config['cf_side_title'].' 점수 통계</a></li>
	<li><a href="#anc_002">'.$config['cf_side_title'].' 점수 내역</a></li>
	<li><a href="#anc_003">'.$config['cf_side_title'].' 점수 지급</a></li>
</ul>';
?>

<!-- 소속 점수 통계 -->
<section id="anc_001">
	<h2 class="h2_frm"><?=$config['cf_side_title']?> 점수 통계</h2>
	<?php echo $pg_anchor ?>
	<div class="tbl_head01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?> 점수 통계</caption>
	<colgroup>
		<col style="width: 120px;" />
		<col style="width: 120px;"/>
		<col style="width: 220px;"/>
		<col />
		<col />
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><?=$config['cf_side_title']?>IDX</th>
		<th scope="col"><?=$config['cf_side_title']?>명</th>
		<th scope="col"><?=$config['cf_side_title']?>이미지</th>
		<th scope="col"><?=$config['cf_side_title']?>점수</th>
		<th scope="col"><?=$config['cf_side_title']?>점수(예비)</th>
	</tr>
	</thead>

	<tbody>
	<?for ($i=0; $row=sql_fetch_array($result_sum); $i++) {?>
	<tr>
		<td><span><?=$row['si_id']?></span></td>
		<td><span><?=$row['si_name']?></span></td>
		<td>
		<?if($row['si_img']) { ?>
			<img class='si_img' src='<?=$row['si_img']?>'/>
		<?}?>
		</td>
		<td><span><?=$row['sis_score_1']?></span></td>
		<td><span><?=$row['sis_score_2']?></span></td>
	</tr>
	<?}?>
	<?if($i==0) echo "<tr><td colspan='".$colspan_sum."' class='empty_table'>자료가 없습니다.</td></tr>";?>
	</tbody>

	</table>
	</div>
</section>

<!-- 소속 점수 내역 -->
<section id="anc_002">
	<h2 class="h2_frm"><?=$config['cf_side_title']?> 점수 내역</h2>
	<?php echo $pg_anchor ?>

	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>

	<form name="fsidelist" id="fsidelist" method="post" action="./side_score_list_delete.php" onsubmit="return fsidelist_submit(this);"  enctype="multipart/form-data">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="token" value="<?php echo $token ?>">

		<div class="tbl_head01 tbl_wrap">
		<table>
		<caption><?php echo $g5['title']; ?> 점수 내역</caption>
		<colgroup>
			<col style="width: 50px;" />
			<col style="width: 120px;"/>
			<col style="width: 200px;"/>
			<col style="width: 200px;"/>
			<col />
			<col />
		</colgroup>
		<thead>
		<tr>
			<th scope="col">
				<label for="chkall" class="sound_only"><?=$config['cf_side_title']?> 점수 내역 전체</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th>
			<th scope="col">점수IDX</th>
			<th scope="col"><?=$config['cf_side_title']?>IDX</th>
			<th scope="col"><?=$config['cf_side_title']?>명</th>
			<th scope="col"><?=$config['cf_side_title']?>점수</th>
			<th scope="col"><?=$config['cf_side_title']?>점수(예비)</th>
		</tr>
		</thead>

		<tbody>
		<?for ($i=0; $row=sql_fetch_array($result); $i++) { $bg = 'bg'.($i%2); ?>
		<tr class="<?php echo $bg; ?>">
			<td style="text-align: center">
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>"/>
			</td>
			<td>
				<input type="text" name="sis_id[<?php echo $i ?>]" value="<?php echo $row['sis_id'] ?>" id="sis_id_<?php echo $i ?>" readonly style="width: 30px;"/>
			</td>
			<td>
				<input type="text" name="si_id[<?php echo $i ?>]" value="<?php echo $row['si_id'] ?>" class="frm_input" style="width: 30px;">
			</td>
			<td><?=$row['si_name']?></td>
			<td>
				<input type="text" name="sis_score_1[<?php echo $i ?>]" value="<?php echo $row['sis_score_1'] ?>" class="frm_input" style="width: 200px;">
			</td>
			<td>
			<input type="text" name="sis_score_2[<?php echo $i ?>]" value="<?php echo $row['sis_score_2'] ?>" class="frm_input" style="width: 200px;">
			</td>
		</tr>
		<?}?>
		<?if($i==0) echo "<tr><td colspan='".$colspan."' class='empty_table'>자료가 없습니다.</td></tr>";?>
		</tbody>

		</table>
		</div>

		<div class="btn_list01 btn_list">
			<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
			<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
		</div>

	</form>

	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
</section>

<!-- 소속 점수 지급 -->
<section id="anc_003">
	<h2 class="h2_frm"><?=$config['cf_side_title']?> 점수 지급</h2>
	<?php echo $pg_anchor ?>

	<!-- 점수 추가 FORM -->
	<form name="fsideform" method="post" id="fsideform" action="./side_score_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<!-- 점수 추가 FORM: 테이블 -->
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 120px;">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="si_id"><?=$config['cf_side_title']?>IDX<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="si_id" id="si_id" class="required frm_input" required></td>
		</tr>
		<tr>
			<th scope="row"><label for="sis_score_1">점수</label></th>
			<td><input type="text" name="sis_score_1" id="sis_score_1" class="frm_input"></td>
		</tr>
		<tr>
			<th scope="row"><label for="sis_score_2">점수(예비)</label></th>
			<td><input type="text" name="sis_score_2" id="sis_score_2" class="frm_input"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<!-- 점수 추가 FORM: 버튼영역 -->
	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>
</section>

<style>
img.si_img { max-width: 100px; max-height:100px }
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

<style>
.tbl_wrap tbody img{
	max-height: 180px;
}
.tbl_wrap tbody input:read-only{
	color:#AAA;
	background-color: #EEE;
	cursor: not-allowed;
}
</style>

<?php
include_once ('./admin.tail.php');
?>