# 추가 스탯 설정
DROP TABLE IF EXISTS avo_status_extra;
CREATE TABLE avo_status_extra (
	ex_id                  INT AUTO_INCREMENT PRIMARY KEY,
	ex_name                VARCHAR(255) DEFAULT '' NOT NULL,
	ex_main_min            INT          DEFAULT 0  NOT NULL,
	ex_main_max            INT          DEFAULT 0  NOT NULL,
	ex_is_main_status      INT          DEFAULT 0  NOT NULL,
	ex_main_status_type    VARCHAR(255) DEFAULT '' NOT NULL,
	ex_main_status_per     VARCHAR(255) DEFAULT '' NOT NULL,
	ex_cri                 INT          DEFAULT 0  NOT NULL,
	ex_is_cri_status       INT          DEFAULT 0  NOT NULL,
	ex_cri_status_type     VARCHAR(255) DEFAULT '' NOT NULL,
	ex_cri_status_per      VARCHAR(255) DEFAULT '' NOT NULL,
	ex_cri_add_per         VARCHAR(255) DEFAULT '' NOT NULL,
	ex_is_cri_add_status   INT          DEFAULT 0  NOT NULL,
	ex_cri_add_status_type VARCHAR(255) DEFAULT '' NOT NULL,
	ex_cri_add_status_per  VARCHAR(255) DEFAULT '' NOT NULL,
	ex_all_per             VARCHAR(255) DEFAULT '' NOT NULL
);
ALTER TABLE yhsb.avo_config ADD `cf_status_select_type` text NOT NULL AFTER `cf_10`;
UPDATE yhsb.avo_config SET cf_status_select_type = '체력반영||공격력반영';

# 진영 점수 테이블
DROP TABLE IF EXISTS avo_character_side_score;
CREATE TABLE avo_character_side_score(
	sis_id      INT AUTO_INCREMENT,
	si_id       INT           NOT NULL comment '소속 IDX',
	sis_score_1 INT DEFAULT 0 NOT NULL comment '소속 점수',
	sis_score_2 INT DEFAULT 0 NULL comment '소속 점수(예비)',
	CONSTRAINT avo_charactor_side_score_pk PRIMARY KEY (sis_id)
);

# 전투: 전투방 생성
DROP TABLE IF EXISTS avo_battle_room;
CREATE TABLE avo_battle_room (
	br_id               INT AUTO_INCREMENT PRIMARY KEY,
	br_ch1_id           INT           DEFAULT 0                   NOT NULL,
	br_ch1_hp           INT           DEFAULT 0                   NOT NULL,
	br_ch1_energy       INT           DEFAULT 0                   NOT NULL,
	br_ch1_skill_used   INT           DEFAULT 0                   NOT NULL,
	br_ch1_potion_used  INT           DEFAULT 0                   NOT NULL,
	br_ch1_dmg_cut_rate DECIMAL(6, 3) DEFAULT 0.000               NOT NULL,
	br_ch1_turn         TINYINT(4)    DEFAULT 0                   NOT NULL,
	br_ch2_id           INT           DEFAULT 0                   NOT NULL,
	br_ch2_hp           INT           DEFAULT 0                   NOT NULL,
	br_ch2_energy       INT           DEFAULT 0                   NOT NULL,
	br_ch2_skill_used   INT           DEFAULT 0                   NOT NULL,
	br_ch2_potion_used  INT           DEFAULT 0                   NOT NULL,
	br_ch2_dmg_cut_rate DECIMAL(6, 3) DEFAULT 0.000               NOT NULL,
	br_ch2_turn         TINYINT(4)    DEFAULT 0                   NOT NULL,
	br_status           VARCHAR(255)  DEFAULT ''                  NOT NULL,
	br_created_datetime DATETIME      DEFAULT CURRENT_TIMESTAMP() NULL
) CHARSET = utf8mb3;

# 전투: 전투방 기록 생성
DROP TABLE IF EXISTS avo_battle_room_log;
CREATE TABLE avo_battle_room_log (
	brl_id               INT AUTO_INCREMENT PRIMARY KEY,
	br_id                INT          DEFAULT 0                   NOT NULL,
	brl_ch1_command      VARCHAR(255) DEFAULT ''                  NOT NULL,
	brl_ch1_damaged      INT          DEFAULT 0                   NOT NULL,
	brl_ch1_used_item_id INT          DEFAULT 0                   NOT NULL,
	brl_ch1_text         VARCHAR(255) DEFAULT ''                  NOT NULL,
	brl_ch2_command      VARCHAR(255) DEFAULT ''                  NOT NULL,
	brl_ch2_damaged      INT          DEFAULT 0                   NOT NULL,
	brl_ch2_used_item_id INT          DEFAULT 0                   NOT NULL,
	brl_ch2_text         VARCHAR(255) DEFAULT ''                  NOT NULL,
	brl_created_datetime DATETIME     DEFAULT CURRENT_TIMESTAMP() NULL
) CHARSET = utf8mb3;

# 기본 설정 업데이트
UPDATE yhsb.avo_config SET cf_title = '용호상박', cf_theme = '', cf_admin = 'admin', cf_admin_email = 'admin@domain.com', cf_admin_email_name = '아보카도 에디션', cf_add_script = '', cf_use_point = 1, cf_point_term = 0, cf_use_copy_log = 1, cf_use_email_certify = 0, cf_login_point = 0, cf_cut_name = 15, cf_nick_modify = 0, cf_new_skin = 'basic', cf_new_rows = 15, cf_search_skin = 'basic', cf_connect_skin = 'basic', cf_faq_skin = 'basic', cf_read_point = 0, cf_write_point = 0, cf_comment_point = 0, cf_download_point = 0, cf_write_pages = 10, cf_mobile_pages = 5, cf_link_target = '_blank', cf_delay_sec = 30, cf_filter = '', cf_possible_ip = '', cf_intercept_ip = '', cf_analytics = '', cf_add_meta = '', cf_syndi_token = '', cf_syndi_except = '', cf_member_skin = 'basic', cf_use_homepage = 0, cf_req_homepage = 0, cf_use_tel = 0, cf_req_tel = 0, cf_use_hp = 0, cf_req_hp = 0, cf_use_addr = 0, cf_req_addr = 0, cf_use_signature = 0, cf_req_signature = 0, cf_use_profile = 0, cf_req_profile = 0, cf_register_level = 2, cf_register_point = 0, cf_icon_level = 2, cf_use_recommend = 0, cf_recommend_point = 0, cf_leave_day = 30, cf_search_part = 10000, cf_email_use = 1, cf_email_wr_super_admin = 0, cf_email_wr_group_admin = 0, cf_email_wr_board_admin = 0, cf_email_wr_write = 0, cf_email_wr_comment_all = 0, cf_email_mb_super_admin = 0, cf_email_mb_member = 0, cf_email_po_super_admin = 0, cf_prohibit_id = 'admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객', cf_prohibit_email = '', cf_new_del = 30, cf_memo_del = 180, cf_visit_del = 180, cf_popular_del = 180, cf_optimize_date = '2024-11-28', cf_use_member_icon = 2, cf_member_icon_size = 5000, cf_member_icon_width = 22, cf_member_icon_height = 22, cf_login_minutes = 10, cf_image_extension = 'gif|jpg|jpeg|png', cf_flash_extension = 'swf', cf_movie_extension = 'asx|asf|wmv|wma|mpg|mpeg|mov|avi|mp3', cf_formmail_is_member = 1, cf_page_rows = 15, cf_mobile_page_rows = 15, cf_visit = '', cf_max_po_id = 0, cf_stipulation = '해당 커뮤니티에 맞는 커뮤니티 활동 규칙을 입력합니다. 회원 가입시 오너 동의 사항으로 출력됩니다.', cf_privacy = '해당 커뮤니티에 맞는 캐릭터 생성 유의사항을 입력합니다. 회원 가입시 오너 동의 사항으로 출력됩니다.', cf_open_modify = 0, cf_memo_send_point = 0, cf_mobile_new_skin = 'basic', cf_mobile_search_skin = 'basic', cf_mobile_connect_skin = 'basic', cf_mobile_faq_skin = 'basic', cf_mobile_member_skin = 'basic', cf_captcha_mp3 = 'basic', cf_editor = 'smarteditor2', cf_cert_use = 0, cf_cert_ipin = '', cf_cert_hp = '', cf_cert_kcb_cd = '', cf_cert_kcp_cd = '', cf_lg_mid = '', cf_lg_mert_key = '', cf_cert_limit = 2, cf_cert_req = 0, cf_sms_use = '', cf_css_version = '', cf_add_fonts = '', cf_sms_type = '', cf_icode_id = '', cf_icode_pw = '', cf_icode_server_ip = '', cf_icode_server_port = '', cf_googl_shorturl_apikey = '', cf_facebook_appid = '', cf_facebook_secret = '', cf_twitter_key = '', cf_twitter_secret = '', cf_kakao_js_apikey = '', cf_bgm = '', cf_open = 1, cf_twitter = 'yonghohigh', cf_side_title = '소속', cf_class_title = '', cf_shop_category = '일반', cf_item_category = '일반||프로필수정||아이템추가||스탯회복||뽑기||에너지회복||체력회복', cf_site_descript = '', cf_site_img = '', cf_favicon = '', cf_character_count = 1, cf_search_count = 5, cf_status_point = 0, cf_money = '화폐', cf_money_pice = '원', cf_exp_name = '경험치', cf_exp_pice = 'exp', cf_rank_name = '랭킹', cf_1 = '1', cf_2 = '1', cf_3 = '1', cf_4 = '', cf_5 = '', cf_6 = '', cf_7 = '', cf_8 = '', cf_9 = '', cf_10 = '', cf_profile_group = '', cf_profile_group_use = '';

# 캐릭터 프로필: 기본
UPDATE yhsb.avo_article_default SET ad_use_thumb = 1, ad_use_head = 1, ad_use_body = 1, ad_use_name = 1, ad_text_thumb = '두상', ad_text_head = '흉상', ad_text_body = '전신', ad_text_name = '이름', ad_help_thumb = '', ad_help_head = '', ad_help_body = '', ad_help_name = '', ad_url_thumb = 1, ad_url_head = 1, ad_url_body = 1, ad_use_title = 1, ad_use_closet = 0, ad_use_inven = 1, ad_use_money = 1, ad_use_rank = 1, ad_use_exp = 1, ad_use_status = 1 WHERE ad_id = 1;

# 캐릭터 프로필: 추가 요소
DELETE FROM yhsb.avo_article WHERE ar_id > 0;
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (1, '', 'phrase', '한마디', 'text', 0, '', '캐릭터 한마디', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (2, '', 'eng_name', '영문명', 'text', 200, '', '캐릭터의 이름을 영문으로 입력해주세요.', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (3, '', 'gender', '성별', 'select', 0, '남||여', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (4, '', 'age', '나이', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (5, '', 'height', '신장', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (6, '', 'grade', '학년', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (7, '', 'others', '성격 및 기타', 'textarea', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (8, '', 'conversation_attack', '대사-공격', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (9, '', 'conversation_defense', '대사-방어', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (10, '', 'conversation_win', '대사-승리', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (11, '', 'conversation_defeat', '대사-패배', 'text', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (12, '', 'flashback_img', '회상 이미지 링크', 'url', 0, '', '', 0, '');
INSERT INTO yhsb.avo_article (ar_id, ar_theme, ar_code, ar_name, ar_type, ar_size, ar_text, ar_help, ar_order, ar_secret) VALUES (13, '', 'flashback_text', '회상 멘트', 'text', 0, '', '', 0, '');

# 캐릭터 추가 요소
ALTER TABLE yhsb.avo_character ADD IF NOT EXISTS `ch_score` INT DEFAULT 0 NOT NULL AFTER `ch_point`;

# 랭킹(level) 설정
DELETE FROM yhsb.avo_level_setting WHERE lv_id > 0;
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (1, 'E', 0, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (2, 'D', 50, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (3, 'C', 100, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (4, 'B', 150, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (5, 'A', 200, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (6, 'S', 250, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (7, 'SS', 300, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_level_setting (lv_id, lv_name, lv_exp, lv_add_state, lv_1, lv_2, lv_3, lv_4, lv_5) VALUES (8, 'SSS', 99999, 0, '', '', '', '', '');

# 아이템 설정
DELETE FROM yhsb.avo_item WHERE it_id > 0;
INSERT INTO yhsb.avo_item (it_id, it_name, it_category, it_content, it_content2, it_use_class, it_use_side, it_use_able, it_use_mmb_able, it_img, it_has, it_sell, it_use_sell, it_use_ever, it_use, it_type, it_value, it_use_recepi, it_seeker, it_seeker_per_s, it_seeker_per_e, st_id, it_1, it_2, it_3, it_4, it_5) VALUES (1, '에너지회복약', '일반', '에너지회복약', '', '', '', '', 0, '', 0, 0, 0, 0, 'Y', '에너지회복', '1', 0, 0, 0, 0, 0, '', '', '', '', '');
INSERT INTO yhsb.avo_item (it_id, it_name, it_category, it_content, it_content2, it_use_class, it_use_side, it_use_able, it_use_mmb_able, it_img, it_has, it_sell, it_use_sell, it_use_ever, it_use, it_type, it_value, it_use_recepi, it_seeker, it_seeker_per_s, it_seeker_per_e, st_id, it_1, it_2, it_3, it_4, it_5) VALUES (2, '체력회복약', '개인', '체력회복약', '', '', '', '', 0, '', 0, 0, 0, 0, 'Y', '체력회복', '30', 0, 0, 0, 0, 0, '', '', '', '', '');

# 스탯 설정
DELETE FROM yhsb.avo_status WHERE st_id > 0;
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (1, '꺾이지않는 마음', 50, 0, 1, 0, 0, '캐릭터의 체력에 영향을 줍니다', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (2, '맷집', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (3, '근성', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (4, '교양', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (5, '주먹', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (6, '패기', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO yhsb.avo_status (st_id, st_name, st_max, st_min, st_use_max, st_use_hp, st_order, st_help, st_type1, st_type2, st_type3, st_type4, st_type5, st_type6, st_type7, st_type8, st_type9, st_type10) VALUES (7, '직감', 50, 0, 1, 0, 0, '캐릭터의 공격력에 영향을 줍니다', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);

DELETE FROM yhsb.avo_status_extra WHERE ex_id > 0;
INSERT INTO yhsb.avo_status_extra (ex_id, ex_name, ex_main_min, ex_main_max, ex_is_main_status, ex_main_status_type, ex_main_status_per, ex_cri, ex_is_cri_status, ex_cri_status_type, ex_cri_status_per, ex_cri_add_per, ex_is_cri_add_status, ex_cri_add_status_type, ex_cri_add_status_per, ex_all_per) VALUES (1, 'HP', 100, 100, 1, '체력반영', '0.1', 0, 0, '', '', '', 0, '', '', '1');
INSERT INTO yhsb.avo_status_extra (ex_id, ex_name, ex_main_min, ex_main_max, ex_is_main_status, ex_main_status_type, ex_main_status_per, ex_cri, ex_is_cri_status, ex_cri_status_type, ex_cri_status_per, ex_cri_add_per, ex_is_cri_add_status, ex_cri_add_status_type, ex_cri_add_status_per, ex_all_per) VALUES (2, 'DAMAGE', 1, 30, 1, '공격력반영', '0.1', 0, 0, '', '', '', 0, '', '', '1');

# 기본 캐릭터 추가
DELETE FROM yhsb.avo_character WHERE ch_id > 0;
INSERT INTO yhsb.avo_character (ch_id, ch_name, ch_thumb, ch_head, ch_body, ch_title, mb_id, ch_side, ch_class, ch_rank, ch_exp, ch_point, ch_type, ch_search_date, ch_search, ch_state, ma_id, ch_order) VALUES (1, '김아신', 'https://placehold.co/185x250', 'https://placehold.co/500X500', 'https://placehold.co/600X800', '', 'admin', '1', '', '', 0, 0, 'main', '2024-11-28', 0, '승인', 0, 0);
INSERT INTO yhsb.avo_character (ch_id, ch_name, ch_thumb, ch_head, ch_body, ch_title, mb_id, ch_side, ch_class, ch_rank, ch_exp, ch_point, ch_type, ch_search_date, ch_search, ch_state, ma_id, ch_order) VALUES (2, '고국화', 'https://placehold.co/185x250', 'https://placehold.co/450X450', 'https://placehold.co/500X800', '', 'admin2', '2', '', '', 0, 0, 'main', '2024-11-28', 0, '승인', 0, 0);

DELETE FROM yhsb.avo_article_value WHERE av_id > 0;
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (1, 1, '', 'phrase', '캐릭터 한마디', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (2, 1, '', 'eng_name', 'kimasin', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (3, 1, '', 'gender', '남', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (4, 1, '', 'age', '16', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (5, 1, '', 'height', '160', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (6, 1, '', 'grade', '1', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (7, 1, '', 'others', '성격 및 기타	
성격 및 기타	', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (8, 1, '', 'conversation_attack', '대사-공격', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (9, 1, '', 'conversation_defense', '대사-방어', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (10, 1, '', 'conversation_win', '대사-승리', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (11, 1, '', 'conversation_defeat', '대사-패배', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (12, 2, '', 'phrase', '캐릭터 한마디', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (13, 2, '', 'eng_name', 'KOKUKA', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (14, 2, '', 'gender', '여', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (15, 2, '', 'age', '10', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (16, 2, '', 'height', '160', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (17, 2, '', 'grade', '1', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (18, 2, '', 'others', '성격 및 기타	
성격 및 기타	
', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (19, 2, '', 'conversation_attack', '대사-공격', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (20, 2, '', 'conversation_defense', '대사-방어', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (21, 2, '', 'conversation_win', '대사-승리', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (22, 2, '', 'conversation_defeat', '대사-패배', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (23, 1, '', 'flashback_img', 'https://placehold.co/600X800', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (24, 1, '', 'flashback_text', '회상 멘트', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (25, 2, '', 'flashback_img', 'https://placehold.co/500X800', '', '', '', '', '');
INSERT INTO yhsb.avo_article_value (av_id, ch_id, ar_theme, ar_code, av_value, av_1, av_2, av_3, av_4, av_5) VALUES (26, 2, '', 'flashback_text', '회상 멘트', '', '', '', '', '');

# 초기화
DELETE FROM yhsb.avo_point WHERE po_id > 0;
DELETE FROM yhsb.avo_battle_room WHERE br_id > 0;
DELETE FROM yhsb.avo_battle_room_log WHERE brl_id > 0;

# 241204 추가
ALTER TABLE avo_battle_room ADD br_ch1_dmg_buff INT DEFAULT 0 NULL AFTER br_ch1_dmg_cut_rate;
ALTER TABLE avo_battle_room ADD br_ch2_dmg_buff INT DEFAULT 0 NULL AFTER br_ch2_dmg_cut_rate;
ALTER TABLE avo_battle_room ADD br_ch1_flashback_used INT DEFAULT 0 NULL AFTER br_ch1_potion_used;
ALTER TABLE avo_battle_room ADD br_ch2_flashback_used INT DEFAULT 0 NULL AFTER br_ch2_potion_used;