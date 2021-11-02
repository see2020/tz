CREATE TABLE IF NOT EXISTS `tz_form` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tz_name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
	`tz_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '',
	`tz_email` varchar(60) NOT NULL DEFAULT '' COMMENT '',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='';