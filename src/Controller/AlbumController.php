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
    // $form = \Drupal::formBuilder() -> getForm('Drupal\album\Form\AlbumForm');
    // $form['#attached']['library'][] = 'album/form-styles';

    return [
      '#theme' => 'album_form',
      '#form' => $form,
    ];
  }

}
