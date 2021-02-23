<?php
/**
 * @file Contains \Drupal\album\Entity\MessageInterface.
 */
namespace Drupal\album;

use Drupal\Core\Entity\ContentEntityInterface;

interface MessageInterface extends ContentEntityInterface {
  /**
   * Gets the message value.
   *
   * @return string
   */
  public function getMessage();

}
