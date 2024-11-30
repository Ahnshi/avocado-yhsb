<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.yhsb.css">', 0);

?>

<div id="member-view-wrap">

<div id="bg-wrap">
	<div class="bg-portrait" id="ch-portrait" style="background-image: url('<?=$ch['ch_body']?>')"></div>
	<div class="bg-void"></div>
</div>
<div id="scroll-layer" style="position: absolute; width: 50%; height: 100%; z-index: 10;">
	<img src="/static/img/ch_portrait_icon.png" onclick="window.open('<?=G5_URL?>/member/closet.php?ch_id=<?=$ch['ch_id']?>', 'big_viewer', 'width=800 height=800 menubar=no status=no toolbar=no location=no scrollbars=yes resizable=yes');" style="cursor:pointer; position: relative; left: 450px; top: 80%;" />
</div>
<div id="info-phrase-wrap">
	<div class="phrase-wrap">
		<div class="phrase-box"><span id="av_value">″ <?=$ch['phrase']?> ″</span></div>
		<div class="phrase-tail"></div>
	</div>
	<div class="mobile-member">
		<img src="<?=$ch['ch_body']?>">
	</div>
	<div class="info-wrap">
		<div class="flashback-box"></div>
		<div class="info-box">
			<div id="info-paper">
				<div class="info-tab" id="character-info">
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
									<td class="table-scroll-td" colspan="2">
										<div class>
											<?=$ch['others']?>
										</div>
									</td>
								</tr>
								<tr style="height:38px">
									<th rowspan="2">전투<br/>능력</th>
									<td rowspan="2" style="vertical-align: middle;">
									<!--<div id="ad_status">-->
										<canvas id="myChart"></canvas>
									<!--</div>-->
									</td>
									<th>종합 평가</th>
								</tr>
								<tr>
									<th id="rank" style=""><?=get_rank_name($ch['ch_rank'])?></th>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="info-tab" id="character-item" style="display:none;">
					<div class="info-half-wrap">
						<div class="info-add-box">
							<table class="info-add">
								<thead>
									<tr style="height:25px"><th>아이템</th></tr>
								</thead>
								<tbody class="item-list-box">
									<tr>
										<td class="table-scroll-td">
											<div>
												<? include(G5_PATH."/inventory/list.inc.php"); ?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="info-half-wrap">
						<div class="info-add-box">
							<table class="info-add">
								<thead>
									<tr style="height:25px"><th>교과서</th></tr>
								</thead>
								<tbody class="item-list-box">
									<tr>
										<td class="table-scroll-td">
											<div class="no-data">신청기간 이후 공개됩니다.</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="info-tab" id="character-relation" style="display:none;">
					<div class="info-add-box" style="overflow-y: auto;"><!--임시.. 차후 tbody에 스크롤 적용-->
						<table class="info-add" id="character-relation-table">
							<colgroup>
								<col style="width:120px"/>
								<col/>
							</colgroup>
							<thead>
								<tr style="height:32px"><th colspan="2">연관 캐릭터 리스트</th></tr>
							</thead>
							<tbody class="relation-member-list">
								<?
									for($i=0; $i < count($relation); $i++) { 
										$re_ch = get_character($relation[$i]['re_ch_id']);
										if($relation[$i]['rm_memo'] == '') { continue; }
								?>
								<tr style="height:25px">
									<td class="name">
										<div><?=$re_ch['ch_name']?></div>
									</td>
									<td rowspan="3">
										<div class="rm-memo">
											<?=nl2br($relation[$i]['rm_memo'])?>
										</div>

										<ol>
											<?
												$relation[$i]['rm_link'] = nl2br($relation[$i]['rm_link']);
												$link_list = explode('<br />', $relation[$i]['rm_link']);
												for($j=0; $j < count($link_list); $j++) {
													$r_row = $link_list[$j];
													if(!$r_row) continue;
											?>
												<li>
													<a href="<?=$r_row?>" class="btn-log" target="_blank"></a>
												</li>
											<? } ?>
										</ol>
									</td>
								</tr>
								<tr>
									<td class="thumb">
										<a href="<?=G5_URL?>/member/viewer.php?ch_id=<?=$re_ch['ch_id']?>" target="_blank">
											<img src="<?=$re_ch['ch_thumb']?>" />
										</a>
									</td>
								</tr>
								<tr>
									<td style="padding: 5px 20px;">
										<div class="rm-like-style">
											<p>
											<? for($j=0; $j < 5; $j++) { 
												$class="";
												$style = "";
												if($j < $relation[$i]['rm_like']) {
													$class="txt-point";
												} else {
													$style="opacity: 0.2;";
												}

												echo "<i class='{$class}' style='{$style}'></i>";
											} ?>
											</p>
										</div>
									</td>
								</tr>
								<? }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="info-tab-button-box">
				<div class="mobile-member" id="tab-stickynote-top" style="background-image:url('/static/img/ch_stickynote_top.png')"></div>
				<div class="info-tab-button" id="character-info-button" onclick="tabBox('#character-info')"></div>
				<div class="info-tab-button" id="character-item-button" onclick="tabBox('#character-item')"></div>
				<div class="info-tab-button" id="character-relation-button" onclick="tabBox('#character-relation')"></div>
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
	$().ready(()=>{
		var current = 0;
		$("#scroll-layer").on("mousewheel", function (e) {
			var wheel = e.originalEvent.wheelDelta;
			if (wheel > 0) {
				current -= 10;
			} else {
				current += 10;
			}

			if(current < 0) current = 0;
			else if(current > 100) current = 100;

			$('#ch-portrait').css('background-position-y', current+'%');
		});
	});


	function tabBox(s){
		//var div = $(e).attr('href');
		$(s).siblings().hide();
		$(s).show();
	}

	const ctx = document.getElementById('myChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'radar',
		data: {
			labels: [
				<?	for($i = 0; $i < count($status); $i++) { ?>
						'<?=$status[$i]['name']?>'
						<?=($i < count($status) - 1) ? ',' : ''?>
				<?	} ?>
			],
			datasets: [{
				data: [
					<?	for($i = 0; $i < count($status); $i++) { ?>
						'<?=$status[$i]['has'] ? $status[$i]['has'] : $status[$i]['min'];?>'
						<?=($i < count($status) - 1) ? ',' : ''?>
					<?	} ?>
				],
				backgroundColor: [
					'rgba(116, 29, 31, 0.5)'
				],
				borderColor: [
					//'rgba(0, 0, 0, 1)'
					'#751e1e'
				],
				borderWidth: 1,
				pointHoverBorderWidth: 3
			}]
		},
		options: {
			scale: {
				ticks: {
					beginAtZero: true,
					maxTicksLimit: 6,
				}
			},
			scales: {
				r: {
					angleLines: {
						display: false
					},
					suggestedMin: 0,
					suggestedMax: 50,
					ticks: {
						showLabelBackdrop: false,
						textStrokeColor: '#fff',
					},
					grid: {
						color: 'rgba(96, 80, 68, 0.15)'
					},
					ticks: {
						backdropColor: 'rgba(255, 255, 255, 0)',
						color: 'rgba(0, 0, 0, 1)',
					},
					pointLabels: {
						color: 'rgba(0,0,0,1)',
						font: {
							family: "Pretendard Variable",
							size: 10,
							weight: 400
						}
					}
				}
			},
			elements: {
				line: {
					borderWidth: 2,
				},
				point: {
					pointRadius: 1,
					pointBackgroundColor: 'rgba(151, 115, 196, 1)',
					hoverRadius: 2,
				}
			},
			plugins: {
				legend: {
					display: false,
					title: {
						display: false,
						text: 'Legend Title',
					}
				}
			},
		},
	});
</script>