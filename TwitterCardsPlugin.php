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
   
    $title = '';
    $description = '';
    $image_url = '';

    // Is the curent record an exhibit?  Use its metadata.
    try {
      $exhibit = get_current_record('exhibit');
      $title = metadata($exhibit, 'title', array('no_escape' => false));
      $description = metadata($exhibit, 'description',array('no_escape' => false));
    }
    catch (Omeka_View_Exception $ove){
      //  no collection, don't do anything
    }

    // Is the curent record an item?  Use its metadata.
    try {
      $item = get_current_record('item');
      $title = metadata('item', array('Dublin Core', 'Title'));
      $description = metadata('item', array('Dublin Core', 'Description'));
      if (strlen($title) > 0 && strlen($description) > 0){
        foreach (loop('files', $item->Files) as $file){
          if($file->hasThumbnail()){
            $image_url = file_display_url($file, 'square_thumbnail');
            break;
          }
        }
      }
    }
    catch (Omeka_View_Exception $ove){
      //  no item, don't do anything
    }

    // Is the curent record an collection?  Use its metadata.
    try {
      $item = get_current_record('collection');
      $title = metadata('collection', array('Dublin Core', 'Title'));
      $description = metadata('collection', array('Dublin Core', 'Description'));
    }
    catch (Omeka_View_Exception $ove){
      //  no collection, don't do anything
    }

    // Default to the site settings if we didn't find anything else to use
    if (strlen($title) < 1 || strlen($description) < 1){
      $title = option('site_title');
      $description = option('description');
    }

    if (strlen($title) > 0 && strlen($description) > 0){
      echo '<meta property="twitter:card" content="summary" />';
      echo '<meta property="twitter:site" content="'.get_option(TWITTER_CARDS_SITE_HANDLE_OPTION).'" />';
      echo '<meta property="twitter:title" content="'.strip_tags(html_entity_decode($title)).'" />';
      echo '<meta property="twitter:description" content="'.strip_tags(html_entity_decode($description)).'" />';

      if (strlen($image_url) > 0){
        echo '<meta property="twitter:image:src" content="'.$image_url.'" />';
      }
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