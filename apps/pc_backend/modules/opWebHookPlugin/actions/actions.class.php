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
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opWebHookPluginActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect(array('sf_route' => 'webhook_twitter'));
  }

  public function executeTwitter(sfWebRequest $request)
  {
    $form = new SnsConfigForm(array(), array(
      'category' => 'webhook_twitter',
    ));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        $form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect(array('sf_route' => 'webhook_twitter'));
      }
    }

    $this->form = $form;
  }

  public function executeTwitterSignin(sfWebRequest $request)
  {
    $tw = opWebHookTwitter::api();

    if (!isset($request['oauth_verifier']))
    {
      $callbackUri = sfContext::getInstance()->getConfiguration()
        ->generateAppUrl('pc_backend', array('sf_route' => 'webhook_twitter_signin'), true);
      $tw = $tw->renewWithRequestToken($callbackUri);

      $snsConfigTable = Doctrine_Core::getTable('SnsConfig');
      $snsConfigTable->set('webhook_twitter_access_token', $tw->ot);
      $snsConfigTable->set('webhook_twitter_access_secret', $tw->os);

      $this->redirect($tw->getAuthenticateUrl());
    }
    else
    {
      $tw = $tw->renewWithAccessToken($request['oauth_verifier']);

      $snsConfigTable = Doctrine_Core::getTable('SnsConfig');
      $snsConfigTable->set('webhook_twitter_access_token', $tw->ot);
      $snsConfigTable->set('webhook_twitter_access_secret', $tw->os);

      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect(array('sf_route' => 'webhook_twitter'));
    }
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
