CREATE TABLE IF NOT EXISTS `avo_quest` (
  `qu_id` int(11) NOT NULL AUTO_INCREMENT,
  `qu_cate` varchar(255) NOT NULL DEFAULT '',
  `si_id` int(11) NOT NULL DEFAULT '0',
  `qu_title` varchar(255) NOT NULL DEFAULT '',
  `qu_content` varchar(255) NOT NULL DEFAULT '',
  `qu_it_id` int(11) NOT NULL DEFAULT '0',
  `qu_point` int(11) NOT NULL DEFAULT '0',
  `qu_exp` int(11) NOT NULL DEFAULT '0',
  `qu_use` int(11) NOT NULL DEFAULT '0',
  `qu_sdate` varchar(255) NOT NULL DEFAULT '',
  `qu_edate` varchar(255) NOT NULL DEFAULT '',
  `it_id` int(11) NOT NULL DEFAULT '0',
  `qu_img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`qu_id`),
  KEY `qu_id` (`qu_id`)
) ENGINE = MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avo_quest_member` (
  `qm_id` int(11) NOT NULL AUTO_INCREMENT,
  `qu_id` int(11) NOT NULL DEFAULT '0',
  `ch_id` int(11) NOT NULL DEFAULT '0',
  `mb_id` int(11) NOT NULL DEFAULT '0',
  `qm_log` varchar(255) NOT NULL DEFAULT '',
  `qm_state` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`qm_id`),
  KEY `qm_id` (`qm_id`)
) ENGINE = MyISAM  DEFAULT CHARSET=utf8;