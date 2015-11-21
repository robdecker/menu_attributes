<?php

/**
 * @file
 * Contains \Drupal\menu_attributes\Form\MenuAttributesSettingsForm.
 */

namespace Drupal\menu_attributes\Form;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Menu Attributes admin settings.
 *
 * @package Drupal\menu_attributes\Form
 */
class MenuAttributesSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_attributes_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_attributes.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if (!\Drupal::currentUser()->hasPermission('administer menu attributes')) {
      return;
    }
    $config = $this->config('menu_attributes.settings');

    $form['attributes_title'] = [
      '#type' => 'item',
      '#title' => t('Menu item attribute options'),
    ];

    $form['attributes_vertical_tabs'] = [
      '#type' => 'vertical_tabs',
      '#attached' => [
        'library' => ['menu_attributes/option_summary'],
      ],
    ];

    $attributes = menu_attributes_get_menu_attribute_info();
    foreach ($attributes as $attribute => $info) {
      $form['attributes'][$attribute] = [
        '#type' => 'details',
        '#title' => $info['label'],
        '#group' => 'attributes_vertical_tabs',
        '#description' => $info['form']['#description'],
      ];
      $form['attributes'][$attribute]["menu_attributes_{$attribute}_enable"] = [
        '#type' => 'checkbox',
        '#title' => t('Enable the @attribute attribute.', ['@attribute' => Unicode::strtolower($info['label'])]),
        '#default_value' => $info['enabled'],
      ];
      $form['attributes'][$attribute]["menu_attributes_{$attribute}_default"] = [
        '#title' => t('Default'),
        '#description' => '',
        '#states' => [
          'invisible' => [
            'input[name="menu_attributes_' . $attribute . '_enable"]' => ['checked' => FALSE],
          ],
        ],
      ] + $info['form'];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->config('menu_attributes.settings')
      ->set('', $values['attributes_vertical_tabs'][''])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
