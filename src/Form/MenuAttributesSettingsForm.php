<?php

/**
 * @file
 * Contains \Drupal\menu_attributes\Form\MenuAttributesSettingsForm.
 */

namespace Drupal\menu_attributes\Form;

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
    $config = $this->config('menu_attributes.settings');

    $form['attributes_title'] = array(
      '#type' => 'item',
      '#title' => $this->t('Menu item attribute options'),
    );

    $form['attributes_vertical_tabs'] = array(
      '#type' => 'vertical_tabs',
    );

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
