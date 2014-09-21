<h2>Twitter</h2>

<?php echo $form->renderFormTag(url_for(array('sf_route' => 'webhook_twitter')), array('method' => 'POST')) ?>

<?php echo $form->renderGlobalErrors() ?>

<p>
  <a href="<?php echo url_for(array('sf_route' => 'webhook_twitter_signin')) ?>">
    <?php echo op_image_tag('/opWebHookPlugin/images/sign-in-with-twitter-gray.png', array('alt' => 'Sign in with Twitter')) ?>
  </a>
</p>

<table>
  <?php echo $form ?>
  <tr>
    <td colspan="2">
      <input type="submit" value="<?php echo __('Send') ?>"/>
    </td>
  </tr>
</table>

</form>
