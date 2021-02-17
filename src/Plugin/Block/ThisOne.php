<?php

namespace Drupal\album\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Define ThisIne block
 *
 * @Block(
 * id = "thisone_block",
 * admin_label = @Translation("ThisOne"),
 * category = @Translation("Custom")
 * )
 */
class ThisOne extends BlockBase {
  public function build() {
    $date = new \DateTime();
    return [
      '#markup' => t('Copyright @year&copy; @company', [
        '@year' => $date->format('Y'),
        '@company' => $this->configuration['company_name'],
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'company_name' => '',
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['company_name'] = [
      '#type' => 'textfield',
      '#title' => t('Company name'),
      '#default_value' => $this->configuration['company_name'],
    ];
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $this->configuration['company_name'] = $form_state->getValue('company_name');
 }
}
