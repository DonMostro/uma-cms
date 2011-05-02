<?php

if(!defined('CACHE'))define('CACHE','cache');

if(!defined('SESSION_NAME'))define('SESSION_NAME','cms');

class CacheConfig{

	static $config = array(

		'MCategoryList' => array('category', 'categories', 'categoryrss'),

		'MChannelElements' => array('channel'),

		'MChannels' => array('channels', 'channel'),

		'MComments' => array('channel', 'video'),

		'MDiscussionPosts' => array('group', 'groups', 'discussions', 'discussion'),

		'MFavorites' => array('favorites', 'channel'),

		'MFiletypes' => array('', 'video', 'channel'),

		'MFriends' => array('friends', 'channel'),

		'MGroupDiscussions' => array('group', 'groups', 'discussions', 'discussion'),

		'MGroups' => array('group', 'groups'),

		'MLanguages' => array(),

		'MMenu' => array(),

		'MPageElements' => array('page'),

		'MPages' => array('page'),

		'MPermissions' => array(),

		'MPlayers' => array('', 'video', 'channel'),

		'MPlaylists' => array('playlists', 'playlist'),

		'MSettings' => array(),

		'MSubscriptions' => array('subscriptions', 'subscription', 'subscription_thumbs'),

		'MTags' => array('', 'video', 'videos', 'category', 'channel'),

		'MText' => array(),

		'MThemes' => array('channel'),

		'MThumbnails' => array('', 'video', 'videos', 'category', 'channel', 'video_thumb', 'friends', 'subscribers', 'subscriptions', 'subscription', 'vlog', 'group', 'groups'),

		'MUser' => array('channel', 'channels'),

		'MUploads' => array('', 'video', 'videos', 'categories', 'category', 'channels', 'channel', 'subscriptions', 'subscription', 'subscription_thumbs', 'favorites', 'playlists', 'playlist', 'groups', 'group', 'search', 'similar', 'channelpreview', 'searchpreview', 'searchsimilar', 'channelvideos', 'vlog', 'vlogrss', 'latest', 'latestrss', 'categoryrss', 'video_thumb', 'filename'),

		'MVideos' => array('', 'video', 'videos', 'categories', 'category', 'channels', 'channel', 'subscriptions', 'subscription', 'subscription_thumbs', 'favorites', 'playlists', 'playlist', 'groups', 'group', 'search', 'similar', 'channelpreview', 'searchpreview', 'searchsimilar', 'channelvideos', 'vlog', 'vlogrss', 'latest', 'latestrss', 'categoryrss', 'video_thumb', 'filename'),

		'MVideoTypes' => array('', 'video', 'videos', 'categories', 'category', 'channels', 'channel', 'subscriptions', 'subscription', 'subscription_thumbs', 'favorites', 'playlists', 'playlist', 'groups', 'group', 'search', 'similar', 'channelpreview', 'searchpreview', 'searchsimilar', 'channelvideos', 'vlog', 'vlogrss', 'latest', 'latestrss', 'categoryrss', 'video_thumb', 'filename'),

		'MVLog' => array('vlog', 'vlogrss', 'channel')

	);

	

}

?>