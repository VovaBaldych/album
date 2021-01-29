<?php
/**
 * @file
 * Contains \Drupal\test_twig\Controller\TestTwigController.
 */

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
      '#markup' => $this->t('Hello World!'),
    ];
  }

}
