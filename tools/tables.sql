CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `attempts` int(10) NOT NULL,
  `department` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `caty` smallint(6) NOT NULL DEFAULT '1',
  `priority` smallint(6) NOT NULL DEFAULT '1',
  `department` smallint(6) NOT NULL DEFAULT '1',
  `status` smallint(6) NOT NULL DEFAULT '1',
  `tag` int(11) NOT NULL DEFAULT '1',
  `pro` int(4) NOT NULL DEFAULT '1',
  `author` int(11) NOT NULL,
  `leader` int(11) NOT NULL,
  `changer` int(11) NOT NULL,
  `tester` int(10) NOT NULL DEFAULT '0',
  `deadline` int(11) NOT NULL DEFAULT '0',
  `isonline` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp,
  `updated_at` timestamp,
  `related` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_related_index` (`related`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tasklogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `caty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pro` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `leader` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tester` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `changer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deadline` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp,
  PRIMARY KEY (`id`),
  KEY `tasklogs_pid_index` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `author` int(11) NOT NULL,
  `changer` int(11) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp,
  PRIMARY KEY (`id`),
  KEY `feedbacklogs_pid_index` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedbacklogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `changer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp,
  PRIMARY KEY (`id`),
  KEY `feedbacklogs_pid_index` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `pros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `t_start` timestamp,
  `t_end` timestamp,
  `pro` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `titles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caty` tinyint(4) NOT NULL,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp,
  `updated_at` timestamp,
  PRIMARY KEY (`id`),
  KEY `titles_caty_index` (`caty`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




