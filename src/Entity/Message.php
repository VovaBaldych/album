<?php

/**
 * @file Contains \Drupal\album\Entity\Message
 */
namespace Drupal\album\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\album\MessageInterface;

 /**
 * Defines the profile entity class.
 *
 * @ContentEntityType(
 * id = "message",
 * label = @Translation("Message"),
 * handlers = {
 * "list_builder" = "Drupal\album\MessageListBuilder",
 * "form" = {
 * "default" = "Drupal\Core\Entity\ContentEntityForm",
 * "add" = "Drupal\Core\Entity\ContentEntityForm",
 * "edit" = "Drupal\Core\Entity\ContentEntityForm",
 * "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 * },
 * "route_provider" = {
 * "html" = "Drupal\Core\Entity\Routing\
DefaultHtmlRouteProvider",
 * },
 * },
 * base_table = "message",
 * fieldable = TRUE,
 * entity_keys = {
 * "id" = "message_id",
 * "label" = "title",
 * "langcode" = "langcode",
  *    * "uuid" = "uuid"
  * },
  * links = {
  * "canonical" = "/messages/{message}",
  * "edit-form" = "/messages/{message}/edit",
  * "delete-form" = "/messages/{message}/delete",
  * "collection" = "/admin/content/messages"
  * },
  * )
  */

class Message extends ContentEntityBase implements MessageInterface {
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['message_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Message ID'))
      ->setDescription(t('The message ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The message language code.'))
      ->setRevisionable(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The message UUID.'))
      ->setReadOnly(TRUE);
    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE);
    $fields['content'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Content'))
      ->setDescription(t('Content of the message'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'text_textfield',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessage() {
    return $this->get('content')->value;
  }
}
