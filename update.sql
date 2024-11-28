DROP TABLE IF EXISTS avo_character_side_score;
CREATE TABLE avo_character_side_score(
	sis_id      INT AUTO_INCREMENT,
	si_id       INT           NOT NULL comment '소속 IDX',
	sis_score_1 INT DEFAULT 0 NOT NULL comment '소속 점수',
	sis_score_2 INT DEFAULT 0 NULL comment '소속 점수(예비)',
	CONSTRAINT avo_charactor_side_score_pk PRIMARY KEY (sis_id)
);

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