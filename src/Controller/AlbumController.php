<?php

namespace Drupal\album\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines AlbumController class.
 */
class AlbumController extends ControllerBase {

  /**
   * Output content.
   */
  public function content() {
    return [
      '#theme' => 'album_form',
      '#form' => $form,
    ];
  }

}
