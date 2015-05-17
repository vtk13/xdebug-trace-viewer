CREATE TABLE `traceview_nodes` (
  `trace_id` int(10) unsigned NOT NULL,
  `call_id` int(10) unsigned NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `time_start` decimal(10,6) NOT NULL,
  `time_end` decimal(10,6) NOT NULL,
  `function` varchar(1024) NOT NULL,
  `include_file` varchar(1024) NOT NULL,
  `file` varchar(1024) NOT NULL,
  `line` int(10) unsigned NOT NULL,
  PRIMARY KEY (`trace_id`,`call_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traceview_traces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `m_time` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traceview_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trace_id` int(10) unsigned NOT NULL,
  `call_id` int(10) unsigned NOT NULL,
  `order_id` tinyint(2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
