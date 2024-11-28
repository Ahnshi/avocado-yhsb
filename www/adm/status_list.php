<?php
$sub_menu = "400420";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from {$g5['status_config_table']} ";

$sql_search = " where (1) ";

$sql_order = " order by st_order asc ";

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

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '스탯설정 관리';
include_once ('./admin.head.php');

$status_type = explode("||", $config['cf_status_select_type']);
$status_type = array_filter($status_type);
$status_type = array_values($status_type);

$colspan = 7 + count($status_type);

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_003">스탯 타입 설정</a></li>
	<li><a href="#anc_001">스탯 설정 목록</a></li>
	<li><a href="#anc_002">스탯 설정 등록</a></li>
</ul>';

?>

<div class="local_desc02 local_desc">
	<p>캐릭터 생성 시 지급되는 전체 스탯포인트 설정은 [ <a href="./community_form.php">환경설정 > 커뮤니티 설정</a> ] 에서 설정해 주시길 바랍니다.</p>
</div>


<section id="anc_003">
	<h2 class="h2_frm">스탯 타입 설정</h2>
	<?php echo $pg_anchor ?>

	<div class="local_ov01 local_ov">
		최대 10개까지 타입을 설정할 수 있습니다.
	</div>

	<form name="fstatuslist" id="fstatustype" method="post" action="./status_form_update.php" enctype="multipart/form-data">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="token" value="<?php echo $token ?>">
		<input type="hidden" name="add_type" value="status_type">

		<div id="status_type_list">
			<div class="inner">
			<?
				for($i=0; $i < count($status_type); $i++) { 
					if($status_type[$i] != "") { 
						echo "<div class='item'><input type='text' name='status_type[]' value='{$status_type[$i]}' />";
						echo "<button type='button' onclick='fn_status_type_del(this);' class='btn-del'>X</button></div>";
					}
				}
			?>
			</div>
			<div class="btn_list">
				<button type="button" onclick="fn_status_type_add();" class="btn-add">추가</button>
				<button type="submit" class="btn_submit">저장</button>

				<button type="button" onclick="location.href='./status_extra_list.php';" style="float:right;">연동코드 설정 바로가기</button>
			</div>
		</div>
	</form>

</section>
<style>
	#status_type_list .inner {min-height:40px;}
	#status_type_list .inner .item {display:inline-block; vertical-align:middle; margin:5px;}
	#status_type_list .inner .item input {width:120px;}
	#status_type_list .inner .item button {width:28px; height:28px; border:none; color:#fff; background:#bf5a5a;}
	#status_type_list .btn_list {border-top:1px solid #efeff1; padding-top:10px;}
	#status_type_list .btn_list button {padding:0 15px; height:30px;}
</style>
<script>
function fn_status_type_del(obj) {
	$(obj).closest('.item').remove();
}
function fn_status_type_add() {
	var count = $('#status_type_list .item').length;

	if(count + 1 > 10) {
		alert("더이상 추가할 수 없습니다.");
	} else {
		var item = $("<div class='item'><input type='text' name='status_type[]' value='' /><button type='button' onclick='fn_status_type_del(this);' class='btn-del'>X</button></div>");
		$('#status_type_list .inner').append(item);
	}
}


</script>


<section id="anc_001">
	<h2 class="h2_frm">스탯 설정 목록</h2>
	<?php echo $pg_anchor ?>

	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>

	<form name="fstatuslist" id="fstatuslist" method="post" action="./status_list_update.php" onsubmit="return fstatuslist_submit(this);"  enctype="multipart/form-data">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_head01 tbl_wrap">
		<table>
		<caption><?php echo $g5['title']; ?> 목록</caption>
		<colgroup>
			<col style="width: 50px;" />
			<col style="width: 100px;" />
			<col style="width: 100px;"/>
			<col style="width: 100px;" />
			<col style="width: 100px;" />
			<col />

			<?for($k=0; $k < count($status_type); $k++) { ?>
			<col style="width:80px;" />
			<? } ?>

			<col style="width: 100px;" />
		</colgroup>
		<thead>
		<tr>
			<th scope="col">
				<label for="chkall" class="sound_only">스탯설정 내역 전체</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th>
			<th scope="col">스탯명</th>
			<th scope="col">최소값</th>
			<th scope="col">최대값</th>
			<th scope="col">순서</th>
			<th scope="col">도움말</th>
			<?for($k=0; $k < count($status_type); $k++) { ?>
				<th scope="col"><?=$status_type[$k]?></th>
			<? } ?>
			<th scope="col">그래프</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'bg'.($i%2);
		?>

		<tr class="<?php echo $bg; ?>">
			<td style="text-align: center">
				<input type="hidden" name="st_id[<?php echo $i ?>]" value="<?php echo $row['st_id'] ?>" id="st_id_<?php echo $i ?>">
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			</td>
			<td>
				<input type="text" name="st_name[<?php echo $i ?>]" value="<?php echo $row['st_name'] ?>" style="width: 98%;">
			</td>
			<td>
				<input type="text" name="st_min[<?php echo $i ?>]" value="<?php echo $row['st_min'] ?>" style="width: 98%;"> 
			</td>
			<td>
				<input type="text" name="st_max[<?php echo $i ?>]" value="<?php echo $row['st_max'] ?>" style="width: 98%;"> 
			</td>
			<td>
				<input type="text" name="st_order[<?php echo $i ?>]" value="<?php echo $row['st_order'] ?>" style="width: 98%;"> 
			</td>
			<td>
				<input type="text" name="st_help[<?php echo $i ?>]" value="<?php echo $row['st_help'] ?>" style="width: 98%;"> 
			</td>
			<?for($k=0; $k < count($status_type); $k++) { ?>
				<td>
					<input type="checkbox" name="st_type<?=($k+1)?>[<?php echo $i ?>]]" value="1" <?=$row['st_type'.($k+1)] ? "checked" : ""?> title="<?=$status_type[$k]?>" />
				</td>
			<? } ?>
			<td>
				<input type="checkbox" name="st_use_max[<?php echo $i ?>]" id="st_use_max_<?=$i?>" value="1" <?=$row['st_use_max'] ? "checked" : ""?>/>
				<label for="st_use_max_<?=$i?>">스탯값 기준</label>
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
	<h2 class="h2_frm">스탯 설정 등록</h2>
	<?php echo $pg_anchor ?>

	<form name="fstatuslist2" method="post" id="fstatuslist2" action="./status_form_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 130px;">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="st_name">스탯명</label></th>
			<td><input type="text" name="st_name" id="st_name" class="required" required></td>
		</tr>
		<tr>
			<th scope="row"><label for="st_max">스탯설정</label></th>
			<td>최소 <input type="text" name="st_min" style="width:80px;"/> ~ 최대 <input type="text" name="st_max" style="width:80px;"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="st_help">도움말</label></th>
			<td><input type="text" name="st_help" style="width: 80%;"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="st_order">순서</label></th>
			<td><input type="text" name="st_order" style="width:80px;"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="st_use_max">그래프</label></th>
			<td>
				<?php echo help("※ 그래프 출력에 필요한 전체 기준값을 설정합니다.");?>
				<?php echo help("※ 최대값 기준 체크 시, 최대값을 기준으로 그래프가 출력됩니다.");?>
				<?php echo help("※ 체크하지 않을 시, 캐릭터가 보유 하고 있는 보유량과 소모량을 계산하여 설정됩니다.");?>
				<input type="checkbox" name="st_use_max" id="st_use_max" value="1" />
				<label for="st_use_max">스탯 최대값 기준</label>
			</td>
		</tr>
		
		</tbody>
		</table>
	</div>

	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>

</section>

<script>
function fstatuslist_submit(f)
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
