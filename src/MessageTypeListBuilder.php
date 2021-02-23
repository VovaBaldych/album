<?php

/**
 * @file Contains \Drupal\mymodule\MessageListBuilder
 */

namespace Drupal\admin;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;

class MessageTypeListBuilder extends EntityListBuilder {
  public function buildHeader() {
    $header['label'] = t('Label');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }
}
