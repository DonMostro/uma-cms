DROP DATABASE ztv_cms;

CREATE DATABASE  `ztv_cms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-11-2010 a las 20:50:12
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `ztv_cms`
--


--
-- Volcar la base de datos para la tabla `latests_more_viewed_videos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_categories`
--

CREATE TABLE IF NOT EXISTS `ztv_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taxonomies_id` int(11) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `approved` enum('0','1') NOT NULL DEFAULT '0',
  `children` text,
  `orden` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `approved` (`approved`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_categories`
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
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('access_control', '', '', 'checkbox', 'Enable/disable site access control. Disable this option if you''re not using site access control to speed up performance.', 3, 'Membership', ''),
('auto_approve', '', '1', 'checkbox', 'Automatically approve member uploaded videos.', 15, 'Videos', ''),
('auto_downloadable', '', '', 'checkbox', 'Make videos available for download automatically upon upload.', 16, 'Videos', ''),
('bad_words', '', '', 'textarea', 'Enter the words to be censored in all public posts.', 18, 'Videos', ''),
('banned_ips', '', '', 'textarea', 'Enter the IP addresses or IP ranges to ban from your website. The following formats are supported:\n192.168.0.1\n192.168.0.1-192.168.0.255', 21, 'Membership', ''),
('buffer_time', '', '6', 'textfield', 'The amount of time to buffer videos before playing.', 13, 'Flash player', ''),
('cache_time', '', '60', 'textfield', 'Maximal lifespan of the cached pages.', 1, 'Cache', ''),
('captcha', '', '', 'checkbox', 'Use CAPTCHA on sign-up form to prevent automated sign-ups.', 1, 'Membership', ''),
('clear_cache', '', '', 'idbox', '', 2, 'Cache', 'ClearCache'),
('comments', '', '1', 'checkbox', 'Enable/disable video and channel comments. If you disable this feature, the members will not be able to post comments on videos and channels.', 6, 'Features', ''),
('confirm_email', '', '', 'checkbox', 'Request for e-mail confirmation to activate new account.', 1, 'Membership', ''),
('conversion_queue', '', '', 'checkbox', 'Enable/Disable video conversion queue. This setting reduces server load on heavy traffic websites. NOTE: Make sure you have the conversion queue processor scheduled to run on your server. Check the documentation for more details.', 12, 'Video conversion', ''),
('default_language', 'EN,ES,DE,FR', 'ES', 'textfield', 'Select a default language', 0, '', ''),
('default_level', '', '0', 'textfield', '[Deprecated] Default membership level. This level will be automatically set to users during the sign up process.', 20, 'Membership', ''),
('email_amount', '', '100', 'textfield', 'How many e-mails should be sent at a time.', 2, 'Mailing', ''),
('email_frequency', '', '600', 'textfield', 'How frequent the e-mails should be sent (in seconds).', 1, 'Mailing', ''),
('email_queue', '', '', 'checkbox', 'Enable/Disable e-mail queuing. Enable this option if you have thousands of members. Make sure you have set up the queue processor.', 0, 'Mailing', ''),
('embed', '', '1', 'checkbox', 'Enable/disable your visitors to embed videos on their sites.', 12, 'Videos', ''),
('enable_cache', '', '', 'checkbox', 'Enable/disable page caching. Enable this option for better performance.', 0, 'Cache', ''),
('external_embed', '', '', 'checkbox', 'Allow members to paste embed video codes from other websites, instead of uploading files.', 0, 'Videos', ''),
('favorites', '', '', 'checkbox', 'Enable/disable favorite videos. If you disable this feature, the members will not be able to save their favorite videos.', 4, 'Features', ''),
('ffmpeg_ar', '8000,11025,22050,44100', '22050', 'textfield', 'ffmpeg audio conversion rate. Change this setting to tweak the audio quality of a converted video.', 9, 'Video conversion', ''),
('ffmpeg_bitrate', '50000,100000,200000,300000,400000,500000,600000,700000,800000,1000000,1300000,1700000,2000000,3000000', '500000', 'textfield', 'Bit rate of the converted videos.', 9, 'Video conversion', ''),
('ffmpeg_path', '', '/usr/bin/ffmpeg', 'textfield', 'Path to ffmpeg. Clear this field to disable video conversion and keep the original format of the uploaded videos.', 7, 'Video conversion', 'TestFFmpeg'),
('ffmpeg_size', '128x96,320x240,480x360,640x480,800x600,1024x768,1152x864,1600x1200', '1600x1200', 'textfield', 'Converted video frame size.', 8, 'Video conversion', ''),
('ffmpeg_thumbnails', '', '3', 'textfield', 'How many thumbnails to create for each video.', 11, 'Video conversion', ''),
('ffmpeg_thumbnail_size', '', '132x90', 'textfield', 'Size of the video thumbnails.', 10, 'Video conversion', ''),
('friends', '', '', 'checkbox', 'Enable/disable the friends. If you disable this feature, the members will not be able to have friends.', 2, 'Features', ''),
('ftp_dir', '', '', 'textfield', 'Enter the local path to the FTP directory (ex. /home/codemight/public_ftp)', 4, 'FTP Uploader', ''),
('ftp_password', '', 'video3TV', 'textfield', 'Enter the password to log-in to the FTP server.', 3, 'FTP Uploader', ''),
('ftp_path', '', '/opt/apache/htdocs/files', 'textfield', 'Enter the path on the FTP server to upload files to (leave empty for root directory)', 1, 'FTP Uploader', ''),
('ftp_server', '', 'videocms.copesa.cl', 'textfield', 'Enter the FTP server name (ex. ftp.codemight.com)', 0, 'FTP Uploader', ''),
('ftp_username', '', 'videocms', 'textfield', 'Enter the username to log-in to the FTP server.', 2, 'FTP Uploader', ''),
('groups', '', '', 'checkbox', 'Enable/disable groups.', 7, 'Features', ''),
('levels', '', '', 'checkbox', '[Deprecated] Enable the membership level system. Set the ''permissions'' field for users to allow/restrict member accessibility of certain features. Go to Permissions section to manage the permission levels.', 19, 'Membership', ''),
('license', '', '', 'idbox', 'Press the button above to synchronize your license information with CodeMight.com server.', 0, 'License', 'CheckLicense'),
('logo', '', 'logo.jpg', 'textfield', 'Logo image to be displayed in the Flash Video player.', 11, 'Flash player', ''),
('max_upload_size', '', '170M', 'textfield', 'Maximal video file size (in bytes) allowed to upload.', 14, 'Uploading', 'TestMaxUploadSize'),
('membership', '', '', 'checkbox', 'Enable/Disable membership. If you disable this feature, the visitors will not be able to sign up and login.', 0, 'Membership', ''),
('notify_pending_video', '', '1', 'checkbox', 'Send notifications about pending videos.', 0, 'Notifications', ''),
('notify_reported_video', '', '1', 'checkbox', 'Send notifications about reported videos.', 1, 'Notifications', ''),
('overlay', '', 'overlay.png', 'textfield', 'Overlay image to display while playing videos.', 12, 'Flash player', ''),
('photos', '', '', 'checkbox', 'Enable/disable photo uploading.', 9, 'Features', ''),
('photo_category', '', '0', 'categories', 'Which category to upload photos to.', 10, 'Features', ''),
('player_home', 'Embed,Mogulus', 'Mogulus', 'textfield', 'Select what player will be displayed in home site.', 2, 'Videos', ''),
('playlists', '', '', 'checkbox', 'Enable/disable playlists. If you disable this feature, the members will not be able to create video playlists.', 5, 'Features', ''),
('ratings', '', '1', 'checkbox', 'Enable/disable video ratings. If you disable this feature, the members will not be able to rate videos.', 3, 'Features', ''),
('red5_server', '', 'rtmp://192.168.150.20/oflaDemo', 'textfield', 'Red5 RTMP server URL', 23, 'Webcam', 'TestRed5Server'),
('red5_streams', '', '/opt/red5/webapps/oflaDemo/streams', 'textfield', 'Absolute path to Red5 streams', 22, 'Webcam', 'TestRed5Streams'),
('remove_links', '', '1', 'checkbox', 'Remove links from the public posts.', 17, 'Videos', ''),
('remove_videos', '', '', 'checkbox', 'Allow members to remove approved videos', 20, 'Videos', ''),
('skip_flv_conversion', '', '1', 'checkbox', 'Enable this option to skip the re-conversion of FLV video files.', 13, 'Video conversion', ''),
('skip_mp4_conversion', '', '', 'checkbox', 'Enable this option to skip the re-conversion of MP4 video files', 13, 'Video Conversion', ''),
('stats', '', '', 'checkbox', 'Enable/disable user statistics.', 8, 'Features', ''),
('subscriptions', '', '', 'checkbox', 'Enable/disable video subscriptions. If you disable this feature, the members will not be able to subscribe to channels or tags.', 2, 'Features', ''),
('subscription_requests', '', '', 'checkbox', 'Enable this setting for video subscriptions to be approved by their owners.', 2, 'Membership', ''),
('tag', '', '', 'checkbox', 'Allow visitors to add tags to videos.', 17, 'Videos', ''),
('tellafriend', '', '1', 'checkbox', 'Enable/disable "Tell a Friend" form on video pages.', 4, 'Features', ''),
('track_online', '', '300', 'textfield', 'For how many seconds inactive users are considered to be online.', 4, 'Membership', ''),
('uploader', 'Standard,Flash,FTP', 'Standard', 'textfield', 'Select what uploader to use on your site. Please check the documentation on how to configure each uploader.', 16, 'Uploading', ''),
('uploads', '', '', 'checkbox', 'Enable/disable video uploading. If you disable this feature, the members will not be able to upload new videos.', 1, 'Uploading', ''),
('watermark', '', '', 'checkbox', 'Enable this option to add permanent watermarks to your uploaded videos. A file named ''watermark.gif'' which resides in ''files'' directory will be used as the watermark. ', 14, 'Video conversion', ''),
('watermark_path', '', '/usr/local/lib/vhook/watermark.so', 'textfield', 'Enter the path to Watermark library. Watermark library usually comes bundled with Ffmeg. Click the Find button if you don''t know the path.', 13, 'Video conversion', 'FindWatermark'),
('webcam', '', '1', 'checkbox', 'Enable/disable webcam video recording.', 21, 'Webcam', '');



--
-- Volcar la base de datos para la tabla `ztv_skinbranding`
--


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



--
-- Volcar la base de datos para la tabla `ztv_themes`
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




-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ztv_types`
--

CREATE TABLE IF NOT EXISTS `ztv_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `thumb` varchar(256) DEFAULT NULL,
  `script` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_types`
--



--
-- Volcar la base de datos para la tabla `ztv_uploads`
--


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
  `frame` varchar(255),
  `orig_file` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `duration` varchar(8) NOT NULL DEFAULT '',
  `tt` int(10) NOT NULL DEFAULT '0',
  `rate` float NOT NULL,
  `approved` enum('0','1') DEFAULT NULL,
  `categories_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ztv_videos`
--


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
  PRIMARY KEY (`videos_id`, `types_id`)
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ztv_latests_more_viewed_videos` AS select `ztv_videos`.`id` AS `id` from `ztv_videos` left join ztv_video_hits on ztv_video_hits.videos_id = videos_id where (`ztv_videos`.`tt` >= (unix_timestamp(now()) - 604800)) order by `ztv_video_hits`.`hits` desc limit 0,16;


CREATE USER 'ztvuser'@'localhost' IDENTIFIED BY 'ztvpassword';

GRANT USAGE ON * . * TO 'ztvuser'@'localhost' IDENTIFIED BY 'ztvpassword' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON `ztv_cms` . * TO 'ztvuser'@'localhost';


