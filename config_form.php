<?php $view = get_view(); ?>

<div id="twittercards_form">
  <div class="field">
    <?php echo $view->formLabel(TWITTER_CARDS_SITE_HANDLE_OPTION, 'Twitter Handle for the site:'); ?>
    <div class="inputs">
      <?php echo $view->formText(TWITTER_CARDS_SITE_HANDLE_OPTION, get_option(TWITTER_CARDS_SITE_HANDLE_OPTION));?>
    </div>
  </div>
</div>



