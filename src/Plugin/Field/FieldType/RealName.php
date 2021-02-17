<?php

namespace Drupal\album\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'realname' field type.
 *
 * @FieldType(
 * id = "realname",
 * label = @Translation("Real name"),
 * description = @Translation("This field stores a first and
last name."),
 * category = @Translation("General"),
 * default_widget = "realname_default",
 * default_formatter = "realname_one_line"
 * )
 */
class RealName extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(\Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'first_name' => [
          'description' => 'First name.',
          'type' => 'varchar',
          'length' => '255',
          'not null' => TRUE,
          'default' => '',
        ],
        'last_name' => [
          'description' => 'Last name.',
          'type' => 'varchar',
          'length' => '255',
          'not null' => TRUE,
          'default' => '',
        ],
      ],
      'indexes' => [
        'first_name' => ['first_name'],
        'last_name' => ['last_name'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(\Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition) {
    $properties['first_name'] = \Drupal\Core\TypedData\DataDefinition::create('string')->setLabel(t('First name'));
    $properties['last_name'] = \Drupal\Core\TypedData\DataDefinition::create('string')->setLabel(t('Last name'));
    return $properties;
 }
}
