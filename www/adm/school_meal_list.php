<?php
$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

/**** *
2z 24.12.1
용호상박 급식표 관리
테이블 avo_school_meal
							config
sm_id		pk				0
sm_config	설정 여부(다른테이블 더 안만드려고..)
sm_name		이름			종류(항목이름&카테고리||..) 
sm_cate		카테고리		카테고리(카테고리||..)
****/

$sql_common = " from avo_school_meal";
$sql_search = " where sm_config != 1";

if (!$sst) {
	$sst  = "sm_id";
	$sod = "desc";
}

if ($stx) {
	$sql_search .= " and sm_name like '%{$stx}%' ";
}

if ($sm_cate) {
	$sql_search .= " and sm_cate = '{$sm_cate}' ";
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

$sql = " select *
			{$sql_common}
			{$sql_search}
			{$sql_order}
			limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$sql = " select sm_name, sm_cate {$sql_common} where sm_config = 1 ";
$sm_config = sql_fetch($sql);
$sm_names = explode("||", $sm_config['sm_name']);
$sm_cates = explode("||", $sm_config['sm_cate']);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '급식 관리';
include_once ('./admin.head.php');

$colspan = 7;

$anchor_name = ['메뉴 목록', '메뉴 등록', '메뉴 설정'];
$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">'.$anchor_name[0].'</a></li>
	<li><a href="#anc_002">'.$anchor_name[1].'</a></li>
	<li><a href="#anc_003">'.$anchor_name[2].'</a></li>
</ul>';

// 카테고리 select html
$sm_cate_select = '<select name="sm_names[]" style="width: 100px;">';
for ($j=0; $j < count($sm_cates); $j++) {
	$sm_cate_select .= '<option value="'.$j.'"'.get_selected($j, 0).'>'.$sm_cates[$j].'</option>';
}
$sm_cate_select .= '</select>';
?>

<section id="anc_001">
	<h2 class="h2_frm"><?php echo $anchor_name[0]?></h2>
	<?php echo $pg_anchor ?>

	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>

	<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
		<label for="sfl" class="sound_only">검색대상</label>
	
		<select name="sm_cate" style="width: 150px;">
			<option value="" <?php echo get_selected($_GET['sm_cate'], ""); ?>>카테고리 선택</option>
			<? for ($j=0; $j < count($sm_cates); $j++) { ?>
				<option value="<?=$sm_cates[$j]?>" <?php echo get_selected($_GET['sm_cate'], $sm_cates[$j]); ?>><?=$sm_cates[$j]?></option>
			<? } ?>
		</select>

		<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
		<input type="submit" class="btn_submit" value="검색">
	</form>

	<form name="fsidelist" id="fsidelist" method="post" action="./school_meal_list_delete.php" onsubmit="return fsidelist_submit(this);"  enctype="multipart/form-data">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="token" value="<?php echo $token ?>">

		<div class="tbl_head01 tbl_wrap">
			<table>
			<caption><?php echo $anchor_name[0]?></caption>
			<colgroup>
				<col style="width: 50px;" />
				<col style="width: 120px;"/>
				<col style="width: 120px;"/>
			</colgroup>
			<thead>
			<tr>
				<th scope="col">
					<label for="chkall" class="sound_only"><?=$config['cf_side_title']?> 내역 전체</label>
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
				</th>
				<th scope="col">카테고리</th>
				<th scope="col">메뉴이름</th>
			</tr>
			</thead>
			<tbody>
			<?php
			 for ($i=0; $row=sql_fetch_array($result); $i++) {
			 	$bg = 'bg'.($i%2);
			?>
			<tr class="<?php echo $bg; ?>">
				<td style="text-align: center">
					<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					<input type="hidden" name="sm_id[<?php echo $i ?>]" value="<?php echo $row['sm_id'] ?>" id="si_id_<?php echo $i ?>">
				</td>
				<td>
					<select name="sm_cate[<?php echo $i ?>]" style="width: 100px;">
					<? for ($j=0; $j < count($sm_cates); $j++) { ?>
						<option value="<?=$sm_cates[$j]?>" <?php echo get_selected($row['sm_cate'], $sm_cates[$j]); ?>><?=$sm_cates[$j]?></option>
					<? } ?>
					</select>
				</td>
				<td>
					<input type="text" name="sm_name[<?php echo $i ?>]" value="<?php echo $row['sm_name'] ?>" class="frm_input" style="width: 98%;">
				</td>
			</tr>

			<?php
			}

			if ($i == 0)
				echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
			?>
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

<section id="anc_002">
	<h2 class="h2_frm"><?php echo $anchor_name[1]?></h2>
	<?php echo $pg_anchor ?>

	<form name="fsideform" method="post" id="fsideform" action="./school_meal_list_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 120px;">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">카테고리</th>
			<td>
				<select name="sm_cate" style="width: 100px;">
					<? for ($j=0; $j < count($sm_cates); $j++) { ?>
						<option value="<?=$sm_cates[$j]?>"><?=$sm_cates[$j]?></option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="sm_name">이름<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="sm_name"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>

</section>

<section id="anc_003">
	<h2 class="h2 _frm"><?=$anchor_name[2]?></h2>
	<?php echo $pg_anchor ?>

	<form name="fsideform" method="post" id="fsideform" action="./school_meal_list_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">
	<input type="hidden" name="sm_config" value="1">
	<input type="hidden" name="sm_cate_origin" value="<?=$sm_config['sm_cate']?>" readonly>
	
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 120px;">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="sm_cate">카테고리</label></th>
			<td><input type="text" name="sm_cate" id="sm_cate" class="required frm_input" style="width:500px;" value="<?php echo $sm_config['sm_cate'] ?>" required> *||로 구분 </td>
		</tr>
		<tr>
			<th scope="row"><label for="sm_cate">메뉴 구성</label></th>
			<td id="sm_names">
				<? for ($i=0; $i < count($sm_names); $i++) { ?>
				<div class="sm-name-select-box">
					<select name="sm_names[]" style="width: 100px;">
					<? for ($j=0; $j < count($sm_cates); $j++) { ?>
					<option value="<?=$j?>" <?php echo get_selected($sm_names[$i], $sm_cates[$j]); ?>><?=$sm_cates[$j]?></option>
					<? } ?>
					</select> <span class="sm_names_remove" style="cursor:pointer;">삭제</span>
				</div>
				<? } ?>
				<span class="sm_names_add" style="cursor:pointer;">추가</span>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>
	</form>
	<div id="sm_cate_select_box" style="display:none;">
		<div class="sm-name-select-box">
			<? echo $sm_cate_select?>
			<span class="sm_names_remove" style="cursor:pointer;">삭제</span>
		</div>
	</div>
</section>

<script>
$(document).ready(function() {
    $(document).on('click', '.sm_names_add', function() {
		var select = $('#sm_cate_select_box').html();
		$(this).before(select);
	});

	$(document).on('click', '.sm_names_remove', function() {
		$(this).parent('div').remove();
	});
});

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
