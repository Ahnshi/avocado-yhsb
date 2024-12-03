<?php
$sub_menu = "600100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$qu = sql_fetch("select * from {$g5['quest_table']} where qu_id = '{$qu_id}'");

if(!$qu['qu_id']) { 
	alert("퀘스트정보가 존재하지 않습니다.");
}

$sql_common = " from {$g5['quest_member_table']} ";
$sql_search = " where qu_id = '{$qu_id}' ";
$sql_add_search = "";

if($s_state == '미수행') { 
	$sql_add_search .= " and (qm_state = 'R' or qm_state = '') ";
} else if($s_state == '완료') { 
	$sql_add_search .= " and qm_state = 'E' ";
} else if($s_state == '실패') { 
	$sql_add_search .= " and qm_state = 'F' ";
} 

$sql_order = " order by qm_id desc ";
$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_add_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['PHP_SELF'].'?qu_id='.$qu_id.'" class="ov_listall">전체목록</a>';

$g5['title'] = '《'.$qu['qu_title'].'》 수행 관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_add_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

// 미수행
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and (qm_state = 'R' or qm_state = '') {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 수행완료
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and qm_state = 'E' {$sql_order} ";
$row = sql_fetch($sql);
$modify_count = $row['cnt'];

// 수행실패
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and qm_state = 'F' {$sql_order} ";
$row = sql_fetch($sql);
$del_count = $row['cnt'];

$colspan = 6;
?>

<div class="local_ov01 local_ov" style="height: 100vh;">
	<?php echo $listall ?>
	총 수락한 캐릭터 수 <?php echo number_format($total_count) ?>명

	<span style="float: right;">
		<a href="?qu_id=<?=$qu_id?>&s_state=미수행">미수행 <?php echo number_format($leave_count) ?></a>명&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
		<a href="?qu_id=<?=$qu_id?>&s_state=완료">수행완료 <?php echo number_format($modify_count) ?></a>명&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
		<a href="?qu_id=<?=$qu_id?>&s_state=실패">수행실패 <?php echo number_format($del_count) ?></a>명
	</span>
</div>


<div style="position:absolute; top:50px; left:0px; right:0; bottom:0;">
	<div style="position:absolute; top:0; right:610px; left:0; bottom:0px; overflow:auto; padding:0 0px 0 25px; box-sizing:border-box;">
		<form name="fmemberlist" id="fmemberlist" action="./quest_member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
			<input type="hidden" name="sst" value="<?php echo $sst ?>">
			<input type="hidden" name="sod" value="<?php echo $sod ?>">
			<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
			<input type="hidden" name="stx" value="<?php echo $stx ?>">
			<input type="hidden" name="page" value="<?php echo $page ?>">
			<input type="hidden" name="qu_id" value="<?php echo $qu_id ?>">


			<div class="tbl_head01 tbl_wrap">
				<table>
				<caption><?php echo $g5['title']; ?> 목록</caption>
				<colgroup>
					<col style="width: 45px;" />
					<col style="width: 120px;" />
					<col style="width: 120px;" />
					<col />
					<col style="width: 100px;" />
					<col style="width: 80px;" />
				</colgroup>
				<thead>
				<tr>
					<th>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
					</th>
					<th>캐릭터</th>
					<th>오너</th>
					<th>수행로그</th>
					<th>로그확인</th>
					<th>상태</th>
				</tr>
				</thead>
				<tbody>
				<?php
				for ($i=0; $row=sql_fetch_array($result); $i++) {
					$ch_id = $row['ch_id'];
					$ch = get_character($ch_id);
					$bg = 'bg'.($i%2);

				?>

				<tr class="<?php echo $bg; ?>">
					<td>
						<input type="hidden" name="qm_id[<?php echo $i ?>]" value="<?php echo $row['qm_id'] ?>" id="qm_id_<?php echo $i ?>">
						<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					</td>
					<td class="txt-left"><?=$ch['ch_name']?></td>
					<td class="txt-left"><?=get_member_name($ch['mb_id'])?></td>
					<td class="txt-left">
						<input type="text" style="width: 100%;" name="qm_log[<?=$i?>]" value="<?=$row['qm_log']?>" />
					</td>
					<td>
					<? if($row['qm_log']) { ?>
						<a href="<?=$row['qm_log']?>" target="frm_preview">로그보기</a>
					<? } ?>
					</td>
					<td>
						<?
							switch($row['qm_state']) {
								case "" : 
									echo "대기";
								break;
								case "E" : 
									echo "완료";
								break;
								case "F" : 
									echo "실패";
								break;
								case "R" : 
									echo "대기";
								break;
							}
						?>
						
					</td>
				</tr>
			  
				<?php
				}
				if ($i == 0)
					echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
				?>
				</tbody>
				</table>
			</div>


			<div class="btn_list01 btn_list">
				<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
				<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
			</div>
		</form>

		<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;s_state='.$s_state.'&qu_id='.$qu_id.'&page='); ?>

		<script>
		function fmemberlist_submit(f)
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
	</div>

	<div style="position:relative; float:right; margin-right:20px; width:580px; height:100%; background:#333;">
		<iframe src="" name="frm_preview" style="border:none; width:100%; height:100%;"></iframe>
	</div>
</div>

<?php
include_once ('./admin.tail.php');
?>

