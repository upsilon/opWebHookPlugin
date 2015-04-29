<?php

/**
 * Copyright (C) 2014 Kimura Youichi <kim.upsilon@bucyou.net>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license AGPL-3.0
 */

/**
 * @package     opWebHookPlugin
 * @subpackage  hook
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opWebHookTwitter
{
  const TWITTER_CONSUMER_KEY = 'M19tIbCRd65qUUFYbD0lnC7b5';
  const TWITTER_CONSUMER_SECRET = 'LF8Dk9qm7uS5ninFxQzj4Ipbw1vJ7jwKwl3n29HJlaz0v2RWf9';

  public static function api()
  {
    $snsConfigTable = Doctrine_Core::getTable('SnsConfig');
    $accessToken = $snsConfigTable->get('webhook_twitter_access_token', null);
    $accessSecret = $snsConfigTable->get('webhook_twitter_access_secret', null);

    if (null !== $accessToken && null !== $accessSecret)
    {
      return new TwistOAuth(self::TWITTER_CONSUMER_KEY, self::TWITTER_CONSUMER_SECRET,
        $accessToken, $accessSecret);
    }
    else
    {
      return new TwistOAuth(self::TWITTER_CONSUMER_KEY, self::TWITTER_CONSUMER_SECRET);
    }
  }

  public static function hookDiaryCreate($diary)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'opUtil'));

    $uri = sfContext::getInstance()->getConfiguration()
      ->generateAppUrl('pc_frontend', array('sf_route' => 'diary_show', 'id' => $diary->id), true);
    $title = op_truncate($diary->title, 140 - 23 - 4 - 2);

    self::api()->post('statuses/update', array(
      'status' => sprintf('[日記] %s %s', $title, $uri),
    ));
  }

  public static function hookTopicCreate($topic)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'opUtil'));

    $uri = sfContext::getInstance()->getConfiguration()
      ->generateAppUrl('pc_frontend', array('sf_route' => 'communityTopic_show', 'id' => $topic->id), true);
    $title = op_truncate($topic->name, 140 - 23 - 4 - 2);

    self::api()->post('statuses/update', array(
      'status' => sprintf('[トピック] %s %s', $title, $uri),
    ));
  }

  public static function hookEventCreate($event)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'opUtil'));

    $uri = sfContext::getInstance()->getConfiguration()
      ->generateAppUrl('pc_frontend', array('sf_route' => 'communityEvent_show', 'id' => $event->id), true);
    $title = op_truncate($event->name, 140 - 23 - 4 - 2);

    self::api()->post('statuses/update', array(
      'status' => sprintf('[イベント] %s %s', $title, $uri),
    ));
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
