-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-12-2010 a las 23:19:22
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
(10, 0, 0, 'Portada', '', '1', NULL, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_featured`
--

DROP TABLE IF EXISTS `ztv_featured`;
CREATE TABLE IF NOT EXISTS `ztv_featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `orden` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `videos_id` (`videos_id`),
  KEY `orden` (`orden`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_menu`
--

DROP TABLE IF EXISTS `ztv_menu`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `ztv_menu`
--

INSERT INTO `ztv_menu` (`id`, `parent_id`, `title`, `url`, `header`, `footer`, `approved`, `menu_order`) VALUES
(2, 0, 'M&uacute;sica', 'index.php?m=category&amp;c=4', '1', '1', '1', 1),
(6, 0, 'Tecnologia', 'index.php?m=category&amp;c=3', '1', '1', '1', 2),
(7, 0, 'Cine', 'index.php?m=category&amp;c=7', '1', '1', '1', 3),
(8, 0, 'Entretenci&oacute;n', 'index.php?m=category&amp;c=5', '1', '1', '1', 4),
(9, 0, 'Animaci&oacute;n', 'index.php?m=category&amp;c=9', '1', '1', '1', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_menu-item`
--

DROP TABLE IF EXISTS `ztv_menu-item`;
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

DROP TABLE IF EXISTS `ztv_pages`;
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

DROP TABLE IF EXISTS `ztv_players`;
CREATE TABLE IF NOT EXISTS `ztv_players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  `code` longtext NOT NULL,
  `embed` text NOT NULL,
  `types_id` int(11) NOT NULL,
  `browser` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `ztv_players`
--

INSERT INTO `ztv_players` (`id`, `type`, `code`, `embed`, `types_id`, `browser`) VALUES
(1, 'Flash', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;/object&gt;', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="640" height="480"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="640" height="480" allowscriptaccess="always" allowfullscreen="true" flashvars="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;/object&gt;', 0, ''),
(2, 'Backoffice', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="480" height="320"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="480" height="320" allowscriptaccess="always" allowfullscreen="true" flashvars="file=/index.php?m=filename%26id=&lt;#id/&gt;%26type=Flash&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;/object&gt;', '&lt;object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="480" height="320"&gt;&lt;param name="movie" value="/swf/players/jwplayer4/player.swf" /&gt;&lt;param name="allowfullscreen" value="true" /&gt;&lt;param name="allowscriptaccess" value="always" /&gt;&lt;param name="flashvars" value="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;embed type="application/x-shockwave-flash" id="player2" name="player2" src="/swf/players/jwplayer4/player.swf" width="480" height="320" allowscriptaccess="always" allowfullscreen="true" flashvars="file=/index.php?m=filename%26id=&lt;#id/&gt;&amp;plugins=&amp;autostart=&lt;#autostart/&gt;&amp;repeat=list&amp;controlbar=over" /&gt;&lt;/object&gt;', 0, ''),
(3, 'HTML5', '&lt;a href="&lt;#filename/&gt;"&gt;&lt;img src="&lt;#frame/&gt;" height="480" width="640" alt="&lt;#filename/&gt;"/&gt;&lt;/a&gt;', '&lt;video id="video" autobuffer height="480" width="640" poster="http://umacms.no-ip.org/files/thumbnails/5ed464df2440931.jpg" controls="controls"&gt;\r\n&lt;source src="&lt;#filename/&gt;" type="video/mpeg4"&gt;\r\n&lt;/video&gt;', 0, 'Android'),
(4, 'Quicktime', '&lt;a href="&lt;#filename/&gt;"&gt;&lt;img src="&lt;#frame/&gt;" height="480" width="640" alt="&lt;#filename/&gt;"/&gt;&lt;/a&gt;', '&lt;object width="640" height="480"\r\nclassid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B"\r\ncodebase="http://www.apple.com/qtactivex/qtplugin.cab"&gt;\r\n&lt;param name="src" value="&lt;#filename/&gt;"&gt;\r\n&lt;param name="autoplay" value="true"&gt;\r\n&lt;param name="controller" value="false"&gt;\r\n&lt;embed src="&lt;#filename/&gt;" width="640" height="480"\r\nautoplay="true" controller="false"\r\npluginspage="http://www.apple.com/quicktime/download/"&gt;\r\n&lt;/embed&gt;\r\n&lt;/object&gt;', 0, 'iPhone,iPod,iPad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_settings`
--

DROP TABLE IF EXISTS `ztv_settings`;
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
('banned_ips', '', '234.255.123.2', 'textarea', 'Baneo de IPs . Est&aacute;n soportados estos formatos:\r\n192.168.0.1\r\n192.168.0.1-192.168.0.255', 21, 'Usuarios', ''),
('default_player', 'Flash, HTML5', 'Flash', 'textfield', '', 1, 'Valores Predeterminados', ''),
('default_type', '', 'H.264', 'textfield', '', 3, 'Valores Predeterminados', ''),
('ffmpeg_path', '', 'ffmpeg', 'textfield', 'Ruta a ffmpeg o biblioteca sustituta.', 7, 'Video conversion', 'TestFFmpeg'),
('ffmpeg_size', '128x96,320x240,480x360,640x480,800x600,1024x768,1152x864,1600x1200', '1600x1200', 'textfield', 'Tama&ntilde;o de la imagen principal de los videos.', 8, 'Video conversion', ''),
('ffmpeg_thumbnails', '', '3', 'textfield', 'Cuantas miniaturas se deben crear por video.', 11, 'Video conversion', ''),
('ffmpeg_thumbnail_size', '', '132x90', 'textfield', 'Porte de las miniaturas de video.', 10, 'Video conversion', ''),
('max_upload_size', '', '170M', 'textfield', 'Cantidad de bytes m&aacute;xima permitida para subir.', 14, 'Subidas', 'TestMaxUploadSize');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_tags`
--

DROP TABLE IF EXISTS `ztv_tags`;
CREATE TABLE IF NOT EXISTS `ztv_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_thumbs`
--

DROP TABLE IF EXISTS `ztv_thumbs`;
CREATE TABLE IF NOT EXISTS `ztv_thumbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `videos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=138 ;



--
-- Estructura de tabla para la tabla `ztv_types`
--

DROP TABLE IF EXISTS `ztv_types`;
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
-- Estructura de tabla para la tabla `ztv_videos`
--


DROP TABLE IF EXISTS `ztv_videos`;
CREATE TABLE IF NOT EXISTS `ztv_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `tags` text,
  `description` text,
  `frame` varchar(255) NOT NULL DEFAULT '',
  `orig_file` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT '',
  `duration` varchar(8) NOT NULL DEFAULT '',
  `tt` int(10) NOT NULL DEFAULT '0',
  `rate` float NOT NULL DEFAULT '0',
  `approved` enum('0','1') DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `ztv_video_hits`
--

DROP TABLE IF EXISTS `ztv_video_hits`;
CREATE TABLE IF NOT EXISTS `ztv_video_hits` (
  `videos_id` int(10) unsigned NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`videos_id`),
  KEY `hits` (`hits`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_tags`
--

DROP TABLE IF EXISTS `ztv_video_tags`;
CREATE TABLE IF NOT EXISTS `ztv_video_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_id` (`tags_id`),
  KEY `videos_id` (`videos_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=330 ;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_video_types`
--

DROP TABLE IF EXISTS `ztv_video_types`;
CREATE TABLE IF NOT EXISTS `ztv_video_types` (
  `videos_id` int(11) NOT NULL,
  `types_id` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`videos_id`,`types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

