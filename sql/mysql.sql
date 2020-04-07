CREATE TABLE `club_apply` (
  `apply_id` mediumint(9) unsigned auto_increment COMMENT '填寫者編號',
  `stu_id` mediumint(9) unsigned default '0' COMMENT '學生編號',
  `stu_name` varchar(255) default '' COMMENT '學生姓名',
  `stu_grade` tinyint(3) unsigned default '0' COMMENT '年級',
  `stu_class` tinyint(3) unsigned default '0' COMMENT '班級',
  `stu_seat_no` tinyint(3) unsigned default '0' COMMENT '座號',
  `stu_no` varchar(20) default '' COMMENT '學號',
  `apply_year` tinyint(3) unsigned default '0' COMMENT '學年',
  `apply_seme` tinyint(3) unsigned default '0' COMMENT '學期',
  `stu_uid` mediumint(9) unsigned default '0' COMMENT '學生uid',
  `apply_time` datetime COMMENT '填寫日期',
PRIMARY KEY  (`apply_id`)
) ENGINE=MyISAM;

CREATE TABLE `club_choice` (
  `apply_id` mediumint(9) unsigned COMMENT '填寫者編號',
  `club_id` smallint(6) unsigned COMMENT '社團編號',
  `choice_sort` tinyint(3) unsigned COMMENT '志願序',
  `choice_result` varchar(255) default '' COMMENT '結果',
  `club_score` tinyint(3) unsigned default '0' COMMENT '成績',
  `score_date` datetime COMMENT '輸入成績日期',
PRIMARY KEY  (`apply_id`,`club_id`,`choice_sort`)
) ENGINE=MyISAM;

CREATE TABLE `club_data_center` (
  `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '模組編號',
  `col_name` varchar(100) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` mediumint(9) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `data_name` varchar(100) NOT NULL default '' COMMENT '資料名稱',
  `data_value` text NOT NULL COMMENT '儲存值',
  `data_sort` mediumint(9) unsigned NOT NULL default 0 COMMENT '排序',
  `col_id` varchar(100) NOT NULL COMMENT '辨識字串',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  PRIMARY KEY (`mid`,`col_name`,`col_sn`,`data_name`,`data_sort`)
) ENGINE=MyISAM;

CREATE TABLE `club_main` (
  `club_id` smallint(6) unsigned NOT NULL auto_increment COMMENT '社團編號',
  `club_year` tinyint(3) unsigned default '0' COMMENT '學年',
  `club_seme` tinyint(3) unsigned default '0' COMMENT '學期',
  `club_title` varchar(255) default '' COMMENT '社團名稱',
  `club_num` varchar(255) default '' COMMENT '上課人數',
  `club_tea_name` varchar(255) default '' COMMENT '授課教師',
  `club_tea_uid` mediumint(9) unsigned default '0' COMMENT '教師uid',
  `club_desc` text COMMENT '課程說明',
  `club_place` varchar(255) default '' COMMENT '上課地點',
  `club_note` text COMMENT '備註',
PRIMARY KEY  (`club_id`)
) ENGINE=MyISAM;
