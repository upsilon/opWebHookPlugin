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
 * @subpackage  config
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opWebHookPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    if ($this->configuration instanceof opApplicationConfiguration)
    {
      $this->dispatcher->connect('op_action.post_execute_diary_create',
        array($this, 'listenToPostDiaryCreate'));
      $this->dispatcher->connect('op_action.post_execute_communityTopic_create',
        array($this, 'listenToPostTopicCreate'));
      $this->dispatcher->connect('op_action.post_execute_communityEvent_create',
        array($this, 'listenToPostEventCreate'));
    }
  }

  public function listenToPostDiaryCreate(sfEvent $event)
  {
    $diaryForm = $event['actionInstance']->form;
    if ($diaryForm->isValid())
    {
      opWebHookTwitter::hookDiaryCreate($diaryForm->getObject());
    }
  }

  public function listenToPostTopicCreate(sfEvent $event)
  {
    $topicForm = $event['actionInstance']->form;
    if ($topicForm->isValid())
    {
      opWebHookTwitter::hookTopicCreate($topicForm->getObject());
    }
  }

  public function listenToPostEventCreate(sfEvent $event)
  {
    $eventForm = $event['actionInstance']->form;
    if ($eventForm->isValid())
    {
      opWebHookTwitter::hookEventCreate($eventForm->getObject());
    }
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
