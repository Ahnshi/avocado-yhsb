<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.yhsb.css">', 0);

$empty_item = [
	"ch_thumb" => "",
	"ch_name" => "",
];

$ch_arr = get_character_list();

$MIN_SIZE = 16;
$list_arr = array( array(), array(), array(), array() );
$size = sizeof($list_arr) >= $MIN_SIZE ? sizeof($list_arr) : $MIN_SIZE;
for ($i=0; $i < $size; $i++) {
	if($ch_arr[$i]){
		array_push($list_arr[$i % 4], $ch_arr[$i]);
	}else if($i<16){
		array_push($list_arr[$i % 4], $empty_item);
	}
}
?>

<div id="member-list-wrap">
	<div class="pc-member" id="member-portrait-box">
		<div class="member-portrait" style="">
			<div class="name-phrase-box">
				<div class="name-box"><span></span><span></span><span></span></div>
				<div class="phrase-box"><span></span></div>
			</div>
		</div>
		<div class="logo-box"></div>
	</div>
	<div class="pc-member member-list-box">
		<?for ($i=0; $i < sizeof($list_arr); $i++) { ?>
			<ul class="member-item-list">
				<?for ($ii=0; $ii < sizeof($list_arr[$i]); $ii++) { ?>
					<li class="member-item" data-ch_id="<?=$list_arr[$i][$ii]['ch_id']?>">
						<div class="thumb-box" style="background-image:url(<?=$list_arr[$i][$ii]['ch_thumb']?>)"></div>
						<div class="name-box"><span><?=$list_arr[$i][$ii]['ch_name']?></span></div>
					</li>
				<?}?>
			</ul>
		<?}?>
	</div>
	<div class="mobile-member member-list-box">
		<ul class="member-item-list">
		<?for ($i=0; $i < sizeof($list_arr); $i++) { 
			if($i%2 == 0) { 
				for ($ii=0; $ii < sizeof($list_arr[$i]); $ii++) { 
					if($list_arr[$i][$ii]['ch_id']) { ?>
					<li class="member-item" data-ch_id="<?=$list_arr[$i][$ii]['ch_id']?>">
						<div class="thumb-box" style="background-image:url(<?=$list_arr[$i][$ii]['ch_thumb']?>)"></div>
						<div class="name-box"><span><?=$list_arr[$i][$ii]['ch_name']?></span></div>
					</li>
				<?}}?>
			<?}
		}?>
		</ul>
		<ul class="member-item-list">
		<?for ($i=0; $i < sizeof($list_arr); $i++) { 
			if($i%2 != 0) { 
				for ($ii=0; $ii < sizeof($list_arr[$i]); $ii++) { 
					if($list_arr[$i][$ii]['ch_id']) { ?>
					<li class="member-item" data-ch_id="<?=$list_arr[$i][$ii]['ch_id']?>">
						<div class="thumb-box" style="background-image:url(<?=$list_arr[$i][$ii]['ch_thumb']?>)"></div>
						<div class="name-box"><span><?=$list_arr[$i][$ii]['ch_name']?></span></div>
					</li>
				<?}}?>
			<?}
		}?>
		</ul>
	</div>
</div>

<style>
#body { padding: 0; }
.fix-layout { max-width:1400px; padding-left: 148px; }
</style>

<script>
$().ready(()=>{
	$('.member-item').on('click', function() {
		const ch_id = $(this)?.data('ch_id')
		let url = "/member/viewer.php"
		if(ch_id){
			url += `?ch_id=${ch_id}`
			location.href = url
		}
	})

	$('.member-item').on('mouseenter', function() {
		const ch_id = $(this)?.data('ch_id')
		let url = "/ajax/yhsb/search_member_character.php"
		if(ch_id) url += `?ch_id=${ch_id}`

		$.ajax({
			type:'get', async: true, url: url,
			success: (res)=>{
				try {
					const ch = JSON.parse(res)

					/* 캐릭터이름 */
					const chName = ch['ch_name']  ?? ''
					let firstText = '', othersText = ''

					try{
						firstText = chName.slice(0,1)
						othersText = chName.slice(1)
					}catch(e){ /* Do Nothing... */ }

					/* 캐릭터 한마디 */
					const avValue = ch['phrase']  ?? ''

					/* 캐릭터 전신 URL */
					const chBody = ch['ch_body'] ?? ''

					/* 오너 정보 */
					const memberId = ch['mb_id'] ?? ''
					let ownerValue = '';
					if(memberId != '') {
						ownerValue = (ch['ch_type'] == 'npc')?'NPC. ':'OWNER. ';
						ownerValue += ch['mb_nick'];
					}

					// JQUERY: 변경내용 적용
					const nameBox = $('.name-phrase-box').first()
					const first = $('.name-box > span').eq(0)
					const others = $('.name-box > span').eq(1)
					const owner = $('.name-box > span').eq(2)

					first.html(firstText)
					others.html(othersText)
					owner.html(ownerValue)
					
					nameBox.addClass('active')

					const phrase = $('.phrase-box > span').first()
					phrase.html(avValue)

					const img = $('.member-portrait').first()
					img.css({'background-image': `url(${chBody})`})

					/* 캐릭터 정보가 없는 경우(빈칸 등) removeClass */
					if(!ch?.ch_id) nameBox.removeClass('active')

				} catch (e) { /* Do nothing...  */ }
			},
			error: (e)=>{ /* Do nothing...  */ }
		})
	})
})
</script>