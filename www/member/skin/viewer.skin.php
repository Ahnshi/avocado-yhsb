<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.yhsb.css">', 0);

?>

<div id="member-view-wrap">

<div id="bg-wrap">
	<div class="bg-portrait" style="background-image: url('<?=$ch['ch_body']?>')"></div>
	<div class="bg-void"></div>
</div>

<div id="info-phrase-wrap">
	<div class="phrase-wrap">
		<div class="phrase-box"><span id="av_value">″ <?=$ch['phrase']?> ″</span></div>
		<div class="phrase-tail"></div>
	</div>
	<div class="info-wrap">
		<div class="flashback-box"></div>
		<div class="info-box">
			<div id="info-paper">
				<div class="info-top-wrap">
					<div class="info-thumb-box">
						<div class="info-thumb" style="background-image:url('<?=$ch['ch_thumb']?>')"></div>
					</div>
					<div class="info-basic-box">
						<table class="info-basic">
							<colgroup>
								<col style="width:50px"/>
								<col/>
								<col style="width:50px"/>
								<col/>
							</colgroup>
							<tr><th colspan="4">인적사항</th></tr>
							<tr>
								<td>이름</td><td><?=$ch['ch_name']?></td>
								<td>성별</td><td><?=$ch['gender']?></td>
							</tr>
							<tr>
								<td>나이</td><td><?=$ch['age']?></td>
								<td>신장</td><td><?=$ch['height']?>cm</td>
							</tr>
							<tr>
								<td>학년</td><td><?=$ch['grade']?></td>
								<td>반</td><td><?=get_side_name($ch['ch_side'])?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="info-bottom-wrap">
					<div class="info-add-box">
						<table class="info-add">
							<colgroup>
								<col style="width:72px"/>
								<col/>
								<col/>
							</colgroup>
							<tr style="height:25px"><th colspan="3">학생정보</th></tr>
							<tr style="height:200px">
								<th>성격<br/>및<br/>기타</th>
								<td colspan="2"><?=$ch['others']?></td>
							</tr>
							<tr style="height:38px">
								<th rowspan="2">전투<br/>능력</th>
								<td rowspan="2">
									<div id="ad_status">
									<canvas id="myChart"></canvas>
								</td>
								<th>종합 평가</th>
							</tr>
							<tr>
								<th style="font-size:120px; color:#B10000; font-weight:900"><?=get_rank_name($ch['ch_rank'])?></th>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>

<style>
#body { padding: 0; }
.fix-layout{ max-width: 1200px; padding: 0; }
</style>

<script>
</script>