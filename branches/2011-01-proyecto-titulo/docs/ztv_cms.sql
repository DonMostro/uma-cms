DROP DATABASE ztv_cms;
CREATE DATABASE  `ztv_cms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-12-2010 a las 13:06:38
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `ztv_cms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_categories`
--

CREATE TABLE IF NOT EXISTS `ztv_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taxonomies_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `approved` enum('0','1') NOT NULL DEFAULT '0',
  `children` text,
  `orden` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `approved` (`approved`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `ztv_categories`
--

INSERT INTO `ztv_categories` (`id`, `taxonomies_id`, `parent_id`, `title`, `thumb`, `approved`, `children`, `orden`) VALUES
(2, 0, 0, 'Entretenci&oacute;n', '', '', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_channel_elements`
--

CREATE TABLE IF NOT EXISTS `ztv_channel_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `elements_id` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_channel_elements`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_featured`
--

CREATE TABLE IF NOT EXISTS `ztv_featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL DEFAULT '0',
  `orden` int(2) NOT NULL DEFAULT '1',
  `videos_id1` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_id_2` (`videos_id`),
  KEY `videos_id` (`videos_id`),
  KEY `orden` (`orden`),
  KEY `videos_id_3` (`videos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_featured`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_filetypes`
--

CREATE TABLE IF NOT EXISTS `ztv_filetypes` (
  `type` varchar(255) NOT NULL DEFAULT '',
  `players_id` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_types_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `players_id` (`players_id`),
  KEY `video_types_id` (`video_types_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_filetypes`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_freehtml`
--

CREATE TABLE IF NOT EXISTS `ztv_freehtml` (
  `id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` varchar(45) DEFAULT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_freehtml`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_friends`
--

CREATE TABLE IF NOT EXISTS `ztv_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friendof` varchar(255) NOT NULL,
  `users_username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_friends`
--


-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `ztv_latests_more_viewed_videos`
--
CREATE TABLE IF NOT EXISTS `ztv_latests_more_viewed_videos` (
`id` int(11)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_menu`
--

CREATE TABLE IF NOT EXISTS `ztv_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `header` enum('0','1') NOT NULL DEFAULT '0',
  `footer` enum('0','1') NOT NULL DEFAULT '0',
  `approved` enum('0','1') NOT NULL DEFAULT '0',
  `menu_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_menu`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_menu-item`
--

CREATE TABLE IF NOT EXISTS `ztv_menu-item` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `order` smallint(6) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_menu-item`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_pages`
--

CREATE TABLE IF NOT EXISTS `ztv_pages` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_pages`
--

INSERT INTO `ztv_pages` (`id`, `title`, `text`) VALUES
('category', 'category', ''),
('home', 'home', ''),
('video', 'video', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_players`
--

CREATE TABLE IF NOT EXISTS `ztv_players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  `code` longtext NOT NULL,
  `embed` text NOT NULL,
  `types_id` int(11) NOT NULL,
  `browser` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_players`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_playlists`
--

CREATE TABLE IF NOT EXISTS `ztv_playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `public` enum('0','1') NOT NULL DEFAULT '0',
  `users_username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_playlists`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_profiles`
--

CREATE TABLE IF NOT EXISTS `ztv_profiles` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_profiles`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_ratings`
--

CREATE TABLE IF NOT EXISTS `ztv_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `videos_id` (`videos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_ratings`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_reports`
--

CREATE TABLE IF NOT EXISTS `ztv_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text NOT NULL,
  `resource_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  `tt` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_reports`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_servers`
--

CREATE TABLE IF NOT EXISTS `ztv_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_servers`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_sessions`
--

CREATE TABLE IF NOT EXISTS `ztv_sessions` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_sessions`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_sessions_profiles`
--

CREATE TABLE IF NOT EXISTS `ztv_sessions_profiles` (
  `id` int(11) NOT NULL,
  `read` enum('0','1') DEFAULT NULL,
  `write` enum('0','1') DEFAULT NULL,
  `edit` enum('0','1') DEFAULT NULL,
  `sessions_id` int(11) NOT NULL,
  `profiles_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_sessions_profiles`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_settings`
--

CREATE TABLE IF NOT EXISTS `ztv_settings` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `enum` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `ord` int(11) NOT NULL DEFAULT '0',
  `group` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='\n';

--
-- Volcar la base de datos para la tabla `ztv_settings`
--

INSERT INTO `ztv_settings` (`id`, `enum`, `value`, `type`, `description`, `ord`, `group`, `function`) VALUES
('banned_ips', '', '', 'textarea', 'Baneo de IPs . Estas soportados estos formatos:\r\n192.168.0.1\r\n192.168.0.1-192.168.0.255', 21, 'Usuarios', ''),
('ffmpeg_ar', '8000,11025,22050,44100', '22050', 'textfield', 'ffmpeg audio conversion rate. Change this setting to tweak the audio quality of a converted video.', 9, 'Video conversion', ''),
('ffmpeg_bitrate', '50000,100000,200000,300000,400000,500000,600000,700000,800000,1000000,1300000,1700000,2000000,3000000', '500000', 'textfield', 'Bit rate of the converted videos.', 9, 'Video conversion', ''),
('ffmpeg_path', '', '/usr/bin/ffmpeg', 'textfield', 'Path to ffmpeg. Clear this field to disable video conversion and keep the original format of the uploaded videos.', 7, 'Video conversion', 'TestFFmpeg'),
('ffmpeg_size', '128x96,320x240,480x360,640x480,800x600,1024x768,1152x864,1600x1200', '1600x1200', 'textfield', 'Converted video frame size.', 8, 'Video conversion', ''),
('ffmpeg_thumbnails', '', '3', 'textfield', 'How many thumbnails to create for each video.', 11, 'Video conversion', ''),
('ffmpeg_thumbnail_size', '', '132x90', 'textfield', 'Size of the video thumbnails.', 10, 'Video conversion', ''),
('max_upload_size', '', '170M', 'textfield', 'Maximal video file size (in bytes) allowed to upload.', 14, 'Uploading', 'TestMaxUploadSize'),
('overlay', '', 'overlay.png', 'textfield', 'Overlay image to display while playing videos.', 12, 'Flash player', ''),
('ratings', '', '1', 'checkbox', 'Enable/disable video ratings. If you disable this feature, the members will not be able to rate videos.', 3, 'Features', ''),
('watermark', '', '', 'checkbox', 'Activar esta opción para activar marca de agua ''watermark.gif'' dentro de la carpeta ''files'' será usada como marca de agua. ', 14, 'Video conversion', ''),
('watermark_path', '', '/usr/local/lib/vhook/watermark.so', 'textfield', 'Enter the path to Watermark library. Watermark library usually comes bundled with Ffmeg. Click the Find button if you don''t know the path.', 13, 'Video conversion', 'FindWatermark');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_skinplayer`
--

CREATE TABLE IF NOT EXISTS `ztv_skinplayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_id` int(11) NOT NULL,
  `file` varchar(200) DEFAULT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_skinplayer`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_tags`
--

CREATE TABLE IF NOT EXISTS `ztv_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_tags`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_taxonomies`
--

CREATE TABLE IF NOT EXISTS `ztv_taxonomies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(45) DEFAULT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_taxonomies`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_thumbs`
--

CREATE TABLE IF NOT EXISTS `ztv_thumbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `videos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_thumbs`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_types`
--

CREATE TABLE IF NOT EXISTS `ztv_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `thumb` varchar(256) DEFAULT NULL,
  `script` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ztv_types`
--

INSERT INTO `ztv_types` (`id`, `title`, `thumb`, `script`) VALUES
(2, 'H264 Mobile', 'fe497b24.jpg', 'zsdsd'),
(3, '3GP Mobile ', '9b2c294e.jpg', 'aswdasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_users`
--

CREATE TABLE IF NOT EXISTS `ztv_users` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `joined` int(10) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `banned` enum('0','1') NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `email_token` varchar(255) NOT NULL,
  `profiles_id` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_users`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_videos`
--

CREATE TABLE IF NOT EXISTS `ztv_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `tags` text NOT NULL,
  `description` text NOT NULL,
  `frame` varchar(255) NOT NULL,
  `orig_file` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `duration` varchar(8) NOT NULL DEFAULT '',
  `tt` int(10) NOT NULL DEFAULT '0',
  `rate` float NOT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ztv_videos`
--

INSERT INTO `ztv_videos` (`id`, `title`, `tags`, `description`, `frame`, `orig_file`, `size`, `type`, `duration`, `tt`, `rate`, `approved`, `categories_id`) VALUES
(1, 'Azafatas Protestaron', 'Azafata, Protesta', '', '', '', 0, '', '', 1290988351, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_videos_categories`
--

CREATE TABLE IF NOT EXISTS `ztv_videos_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_videos_categories`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_videos_playlist`
--

CREATE TABLE IF NOT EXISTS `ztv_videos_playlist` (
  `id` int(11) NOT NULL,
  `playlists_id` int(11) NOT NULL,
  `videos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_videos_playlist`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_comments`
--

CREATE TABLE IF NOT EXISTS `ztv_video_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `videos_id` (`videos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_video_comments`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_hits`
--

CREATE TABLE IF NOT EXISTS `ztv_video_hits` (
  `videos_id` int(10) unsigned NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`videos_id`),
  KEY `hits` (`hits`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_video_hits`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_tags`
--

CREATE TABLE IF NOT EXISTS `ztv_video_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_id` (`tags_id`),
  KEY `videos_id` (`videos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_video_tags`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_types`
--

CREATE TABLE IF NOT EXISTS `ztv_video_types` (
  `videos_id` int(11) NOT NULL,
  `types_id` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`videos_id`,`types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_video_types`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_watched`
--

CREATE TABLE IF NOT EXISTS `ztv_watched` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `videos_id` (`videos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_watched`
--


-- --------------------------------------------------------

--
-- Estructura para la vista `ztv_latests_more_viewed_videos`
--
DROP TABLE IF EXISTS `ztv_latests_more_viewed_videos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ztv_latests_more_viewed_videos` AS select `ztv_videos`.`id` AS `id` from (`ztv_videos` left join `ztv_video_hits` on((`ztv_video_hits`.`videos_id` = `ztv_video_hits`.`videos_id`))) where (`ztv_videos`.`tt` >= (unix_timestamp(now()) - 604800)) order by `ztv_video_hits`.`hits` desc limit 0,16;




