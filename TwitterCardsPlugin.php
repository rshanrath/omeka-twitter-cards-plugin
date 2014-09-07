<?php

define('TWITTER_CARDS_PLUGIN_DIR', dirname(__FILE__));
define('TWITTER_CARDS_SITE_HANDLE_OPTION', 'twittercards_site_handle');

class TwitterCardsPlugin extends Omeka_Plugin_AbstractPlugin
{
  protected $_hooks =array(
    'uninstall',
    'public_head',
    'config',
    'config_form'
  );

  public function hookUninstall()
  {
    delete_option(TWITTER_CARDS_SITE_HANDLE_OPTION);
  }

  public function hookPublicHead($args)
  {
    
    try {
      $item = get_current_record("item");
      $title = metadata('item', array('Dublin Core', 'Title'));
      $description = metadata('item', array('Dublin Core', 'Description'));
      if (strlen($title) > 0 && strlen($description) > 0){
        echo '<meta property="twitter:card" content="summary" />';
        echo '<meta property="twitter:site" content="'.get_option(TWITTER_CARDS_SITE_HANDLE_OPTION).'" />';
        echo '<meta property="twitter:title" content="'.$title.'" />';
        echo '<meta property="twitter:description" content="'.$description.'" />';

        foreach (loop('files', $item->Files) as $file){
          if($file->hasThumbnail()){
            $url = file_display_url($file, 'square_thumbnail');
            echo '<meta property="twitter:image:src" content="'.$url.'" />';
            break;
          }
        }
      }
    }
    catch (Omeka_View_Exception $ove){
      //  no item, don't do anything
    }
  }

  public function hookConfig($args)
  {
    $post = $args['post'];
    set_option(
      TWITTER_CARDS_SITE_HANDLE_OPTION,
      $post[TWITTER_CARDS_SITE_HANDLE_OPTION]
    );
  }

  public function hookConfigForm()
  {
    include TWITTER_CARDS_PLUGIN_DIR . '/config_form.php';
  }
}