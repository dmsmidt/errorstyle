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

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('errorstyle.settings')
      ->set('disable_inline_form_errors', $form_state->getValue('disable_inline_form_errors'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
