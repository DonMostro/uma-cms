-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-12-2010 a las 22:33:01
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ztv_categories`
--

INSERT INTO `ztv_categories` (`id`, `taxonomies_id`, `parent_id`, `title`, `thumb`, `approved`, `children`, `orden`) VALUES
(2, 0, 0, 'Entretenci&oacute;n', '', '', NULL, 1),
(3, 0, 0, 'Tecnolog&iacute;a', '', '', NULL, 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ztv_players`
--

INSERT INTO `ztv_players` (`id`, `type`, `code`, `embed`, `types_id`, `browser`) VALUES
(1, 'Flash', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="player.swf?1" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v50.swf,tweetit-1,fbit-1,gapro-1,&lt;#base/&gt;plugins/hd/hd.swf&amp;skin=ltSkin.swf&amp;autostart=&lt;#autostart/&gt;&amp;hd.file=&lt;#base/&gt;files/&lt;#filename_hd/&gt;&amp;hd.state=true&amp;repeat=list&amp;advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf&amp;controlbar=over&amp;gapro.accountid=UA-1861205-1&amp;fbit.link=&lt;#base/&gt;index.php?m=video%252526v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%2526v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf?1" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v50.swf,&lt;#base/&gt;plugins/hd/hd.swf,tweetit-1,fbit-1,gapro-1&amp;skin=ltSkin.swf&amp;autostart=&lt;#autostart/&gt;&amp;hd.file=&lt;#base/&gt;files/&lt;#filename_hd/&gt;&amp;hd.state=true&amp;repeat=list&amp;advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf&amp;controlbar=over&amp;gapro.accountid=UA-1861205-1&amp;fbit.link=&lt;#base/&gt;index.php?m=video%252526v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%2526v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="player.swf?1" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v50.swf,tweetit-1,fbit-1,gapro-1,&lt;#base/&gt;plugins/hd/hd.swf&amp;skin=ltSkin.swf&amp;autostart=&lt;#autostart/&gt;&amp;hd.file=&lt;#base/&gt;files/&lt;#filename_hd/&gt;&amp;hd.state=true&amp;repeat=list&amp;advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf&amp;controlbar=over&amp;gapro.accountid=UA-1861205-1&amp;fbit.link=&lt;#base/&gt;index.php?m=video%252526v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%2526v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf?1" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v50.swf,&lt;#base/&gt;plugins/hd/hd.swf,tweetit-1,fbit-1,gapro-1&amp;skin=ltSkin.swf&amp;autostart=&lt;#autostart/&gt;&amp;hd.file=&lt;#base/&gt;files/&lt;#filename_hd/&gt;&amp;hd.state=true&amp;repeat=list&amp;advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf&amp;controlbar=over&amp;gapro.accountid=UA-1861205-1&amp;fbit.link=&lt;#base/&gt;index.php?m=video%252526v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%2526v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', 0, '');

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
('banned_ips', '', '', 'textarea', 'Baneo de IPs . Est&aacute;n soportados estos formatos:\r\n192.168.0.1\r\n192.168.0.1-192.168.0.255', 21, 'Usuarios', ''),
('ffmpeg_path', '', 'ffmpeg', 'textfield', 'Ruta a ffmpeg o biblioteca sustituta.', 7, 'Video conversion', 'TestFFmpeg'),
('ffmpeg_size', '128x96,320x240,480x360,640x480,800x600,1024x768,1152x864,1600x1200', '1600x1200', 'textfield', 'Tama&ntilde;o de la imagen principal de los videos.', 8, 'Video conversion', ''),
('ffmpeg_thumbnails', '', '3', 'textfield', 'Cuantas miniaturas se deben crear por video.', 11, 'Video conversion', ''),
('ffmpeg_thumbnail_size', '', '132x90', 'textfield', 'Porte de las miniaturas de video.', 10, 'Video conversion', ''),
('max_upload_size', '', '170M', 'textfield', 'Cantidad de bytes m&aacute;xima permitida para subir.', 14, 'Subidas', 'TestMaxUploadSize'),
('overlay', '', 'overlay.png', 'textfield', 'Imagen para player flash.', 12, 'Flash player', ''),
('ratings', '', '1', 'checkbox', 'Habilitar/Deshabilitar puntuaciones de videos (rating).', 3, 'Agregados', ''),
('watermark', '', '', 'checkbox', 'Activar esta opci&oacute;n para activar marca de agua ''watermark.gif'' dentro de la carpeta ''files'' ser&aacute; usada como marca de agua. ', 14, 'Video conversion', ''),
('watermark_path', '', '/usr/local/lib/vhook/watermark.so', 'textfield', 'Ingresar ruta de la libreria Watermark que usualmente viene integrada con FFmpeg. Presione el boton buscar si no conoce la ruta.', 13, 'Video conversion', 'FindWatermark');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `ztv_tags`
--

INSERT INTO `ztv_tags` (`id`, `tag`) VALUES
(1, 'Patagonia'),
(2, 'Magallanes'),
(3, 'Carrera'),
(4, 'karting'),
(5, 'Patagonia'),
(6, 'Screencast'),
(7, 'Linux'),
(8, 'Beryl');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_types`
--

CREATE TABLE IF NOT EXISTS `ztv_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `thumb` varchar(256) DEFAULT NULL,
  `script` text,
  `extension` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `ztv_types`
--

INSERT INTO `ztv_types` (`id`, `title`, `thumb`, `script`, `extension`) VALUES
(1, 'H.264', '5f3839f6.jpeg', 'ffmpeg -i &lt;#orig_file/&gt; -acodec libfaac -ab 96k -vcodec libx264 -vpre slow -crf 22 -threads 0 &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', 'mp4');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;


--
-- Estructura de tabla para la tabla `ztv_video_types`
--

CREATE TABLE IF NOT EXISTS `ztv_video_types` (
  `videos_id` int(11) NOT NULL,
  `types_id` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`videos_id`,`types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

