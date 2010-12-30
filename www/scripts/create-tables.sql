-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-12-2010 a las 21:32:52
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


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_featured`
--

CREATE TABLE IF NOT EXISTS `ztv_featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL DEFAULT '0',
  `orden` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `videos_id` (`videos_id`),
  KEY `orden` (`orden`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `ztv_featured`
--

INSERT INTO `ztv_featured` (`id`, `videos_id`, `orden`) VALUES
(1, 1, 1),
(2, 4, 2);

-- --------------------------------------------------------


--
-- Volcar la base de datos para la tabla `ztv_friends`
--


-- --------------------------------------------------------

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
('category', 'category', '[:main_video:]\r\n&lt;div class="span-24-2"&gt;\r\n	&lt;div class="span-24"&gt;\r\n		&lt;!-- the tabs --&gt;\r\n		&lt;ul class="losmas"&gt;\r\n		    &lt;li&gt;&lt;a href="#"&gt;Lo + Reciente&lt;/a&gt;&lt;/li&gt;		\r\n		    &lt;li&gt;&lt;a href="#"&gt;Lo + Visto&lt;/a&gt;&lt;/li&gt;\r\n		&lt;/ul&gt;\r\n		&lt;!-- tab "panes" --&gt;\r\n\r\n        &lt;div class="contelosmas"&gt;\r\n        	&lt;div&gt;\r\n				[:latest_videos:]\r\n			&lt;/div&gt;\r\n        	&lt;div&gt;\r\n				[:mostviewed_videos:]\r\n			&lt;/div&gt;\r\n		&lt;/div&gt;\r\n		&lt;script&gt;\r\n	     $(function() {\r\n             var marcar = false;\r\n             idCertifica = 106340;\r\n             pathCertifica = "home/tabs";\r\n             $("ul.losmas").tabs("div.contelosmas &gt; div", {onClick: function(){if(marcar){trackUserAction(idCertifica, pathCertifica)}else{marcar=true};}});\r\n         });\r\n		 &lt;/script&gt;\r\n\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box2"&gt;\r\n	[:boxnoticias:]\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box3"&gt;\r\n    [:boxgoles:]\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box4"&gt;\r\n	[:boxdeportes:]\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box5"&gt;\r\n	[:boxentretencion:]\r\n\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box6"&gt;\r\n	[:boxtendencias:]\r\n&lt;/div&gt;\r\n&lt;div class="span-24 boxTv box7"&gt;\r\n 	[:boxbbcmundo:]\r\n&lt;/div&gt;'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `ztv_players`
--

INSERT INTO `ztv_players` (`id`, `type`, `code`, `embed`, `types_id`, `browser`) VALUES
(1, 'Flash', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video&amp;v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename&amp;id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video&amp;v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename&amp;id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', 0, 'firefox'),
(2, 'Backoffice', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="480" height="320"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video&amp;v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="480" height="320" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename&amp;id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="480" height="320"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=&lt;#base/&gt;index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video&amp;v=&lt;#id/&gt;" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="480" height="320" allowscriptaccess="always" allowfullscreen="true" flashvars="file=&lt;#base/&gt;index.php?m=filename&amp;id=&lt;#id/&gt;&amp;plugins=tweetit-1,fbit-1&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over&amp;fbit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;&amp;tweetit.link=&lt;#base/&gt;index.php?m=video%26v=&lt;#id/&gt;" /&gt;&lt;/object&gt;', 0, 'chrome');

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
('default_player', '', 'Flash', 'textfield', '', 1, 'Valores Predeterminados', ''),
('default_type', '', 'H.264', 'textfield', '', 2, 'Valores Predeterminados', ''),
('ffmpeg_path', '', 'ffmpeg', 'textfield', 'Ruta a ffmpeg o biblioteca sustituta.', 7, 'Video conversion', 'TestFFmpeg'),
('ffmpeg_size', '128x96,320x240,480x360,640x480,800x600,1024x768,1152x864,1600x1200', '1600x1200', 'textfield', 'Converted video frame size.', 8, 'Video conversion', ''),
('ffmpeg_thumbnails', '', '3', 'textfield', 'Cuantas miniaturas se deben crear por video.', 11, 'Video conversion', ''),
('ffmpeg_thumbnail_size', '', '132x90', 'textfield', 'Porte de las miniaturas de video.', 10, 'Video conversion', ''),
('max_upload_size', '', '170M', 'textfield', 'Maximal video file size (in bytes) allowed to upload.', 14, 'Uploading', 'TestMaxUploadSize'),
('overlay', '', 'overlay.png', 'textfield', 'Imagen para player flash.', 12, 'Flash player', ''),
('ratings', '', '1', 'checkbox', 'Habilitar/Deshabilitar puntuaciones de videos (rating).', 3, 'Features', ''),
('watermark', '', '', 'checkbox', 'Activar esta opción para activar marca de agua ''watermark.gif'' dentro de la carpeta ''files'' será usada como marca de agua. ', 14, 'Video conversion', ''),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `ztv_thumbs`
--

INSERT INTO `ztv_thumbs` (`id`, `filename`, `videos_id`) VALUES
(1, '6282ac1f5613770.jpg', 1),
(2, '32523767.jpg', 1),
(3, '6282ac1f5613772.jpg', 1),
(10, 'e37331139111170.jpg', 4),
(11, 'e37331139111171.jpg', 4),
(12, 'e37331139111172.jpg', 4),
(13, '8f144ea18430130.jpg', 2),
(14, '8f144ea18430131.jpg', 2),
(15, '8f144ea18430132.jpg', 2);

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
  `browser` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `ztv_types`
--

INSERT INTO `ztv_types` (`id`, `title`, `thumb`, `script`, `extension`, `browser`) VALUES
(1, 'H.264', '6818b277.jpg', 'ffmpeg -i &lt;#orig_file/&gt; -acodec libfaac -ab 96k -vcodec libx264 -vpre slow -crf 22 -threads 0 &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', 'mp4', 'iPhone'),
(2, 'Theora', 'd93fa75b.jpg', 'ffmpeg -i &lt;#orig_file/&gt; -acodec vorbis -strict experimental -ac 2 -vcodec libtheora -f ogg &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', 'ogv', ''),
(3, '3GP', 'ee83288a.png', 'ffmpeg -i &lt;#orig_file/&gt; -vcodec h263 -acodec libfaac -ac 1 -ar 8000 -r 25 -ab 32k -y &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', '3gp', ''),
(5, 'Flash', '1195b834.jpg', 'ffmpeg -i &lt;#orig_file/&gt; -ab 56 -ar 44100 -b 200 -r 15 -s 1600x1200 -f flv &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', 'flv', ''),
(6, 'Mpeg4', '9f6f2cee.jpg', 'ffmpeg -i &lt;#orig_file/&gt; -s 480x320 -vcodec mpeg4 -acodec libfaac -ac 1 -ar 16000 -r 13 -ab 32000 -aspect 3:2 &lt;#dest_file/&gt; 2&gt;&gt; ../files/ffmpeg.log', 'G1.mp4', 'Android');

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


--
-- Estructura de tabla para la tabla `ztv_videos_categories`
--

-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-12-2010 a las 10:39:04
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

DROP TABLE IF EXISTS `ztv_categories`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `ztv_categories`
--

INSERT INTO `ztv_categories` (`id`, `taxonomies_id`, `parent_id`, `title`, `thumb`, `approved`, `children`, `orden`) VALUES
(3, 0, 0, 'Tecnolog&iacute;a', '', '1', NULL, 2),
(4, 0, 0, 'M&uacute;sica', '', '1', ',6', 3),
(5, 0, 0, 'Entretencion', '', '1', NULL, 1),
(6, 0, 4, 'Rock', '', '0', NULL, 1),
(7, 0, 0, 'Cine', '', '1', NULL, 4),
(8, 0, 0, 'Revista', '', '1', NULL, 5),
(9, 0, 0, 'Animaci&oacute;n', '', '1', NULL, 6),
(10, 0, 0, 'Portada', '', '1', NULL, 0);



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

INSERT INTO `ztv_video_hits` (`videos_id`, `hits`) VALUES
(4, 2),
(6, 2),
(2, 31),
(1, 36);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcar la base de datos para la tabla `ztv_video_tags`
--

INSERT INTO `ztv_video_tags` (`id`, `videos_id`, `tags_id`) VALUES
(3, 2, 3),
(4, 2, 4),
(13, 4, 6),
(14, 4, 7),
(15, 4, 8),
(18, 1, 1);

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

INSERT INTO `ztv_video_types` (`videos_id`, `types_id`, `filename`) VALUES
(1, 1, 'files/2010/12/148784.mp4'),
(1, 2, 'files/2010/12/679773.mp4'),
(2, 2, 'files/2010/12/187295.mp4'),
(3, 2, 'files/2010/12/686713.mp4'),
(4, 2, 'files/2010/12/122691.mp4');

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
