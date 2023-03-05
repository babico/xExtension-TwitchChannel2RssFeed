<?php
/*
from: https://www.twitch.tv/babicop
to: https://twitchrss.appspot.com/vod/babicop
*/
class TwitchChannel2RssFeedExtension extends Minz_Extension {
	const CNT_REQUIRDED_FRESHRSS_VERSION = '1.16';
	public static $channelID = '';

	public function init() {
		self::$channelID = '';
		$this->registerHook('check_url_before_add', array('TwitchChannel2RssFeedExtension', 'CntTWRssHookCheckURL'));
		$this->registerHook('feed_before_insert', array('TwitchChannel2RssFeedExtension', 'CntTWRssHookBeforeInsertFeed'));
	}

	public function install() {
        if (version_compare(FRESHRSS_VERSION, self::CNT_REQUIRDED_FRESHRSS_VERSION , '<')){
            $this->registerTranslates();
            return _t('ext.TwitchChannel2RssFeed.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
		return true;
	}

	public static function CntTWRssHookBeforeInsertFeed($feed) {
		if (self::$channelID != '') {
			$lTxt = $feed->name() ? $feed->name() : 'TwitchChannel2Rss by Babico';
			$url = 'https://twitchrss.appspot.com/vod/' . self::$channelID;
			$feed->_url($url);
		}
		return $feed;
	}
	
	public static function CntTWRssHookCheckURL($url) {
        $matches = [];

        if (preg_match('#^https?://(www\.|)twitch\.tv/([0-9a-zA-Z_-]{4,25})#', $url, $matches) === 1) {
			self::$channelID = $matches[2];
            return 'https://twitchrss.appspot.com/vod/' . $matches[2];
        }
        return $url;
	}
}
