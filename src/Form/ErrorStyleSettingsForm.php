<?php

namespace Drupal\errorstyle\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure ErrorStyle settings for this site.
 */
class ErrorStyleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'errorstyle_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['errorstyle.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('errorstyle.settings');

    $form['disable_inline_form_errors'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable Inline Form Errors'),
      '#default_value' => $config->get('disable_inline_form_errors'),
      '#description' => $this->t('Do not show inline form errors for the ErrorStyle form.'),
    ];

    $form['admin_theme'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Test with the admin theme'),
      '#default_value' => $config->get('admin_theme'),
      '#description' => $this->t('If checked, the test form will use the admin theme instead of the default theme'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('errorstyle.settings')
      ->set('disable_inline_form_errors', $form_state->getValue('disable_inline_form_errors'))
      ->set('admin_theme', $form_state->getValue('admin_theme'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
