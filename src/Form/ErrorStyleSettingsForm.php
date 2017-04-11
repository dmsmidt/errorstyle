<?php

namespace Drupal\errorstyle\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure ErrorStyle settings for this site.
 */
class ErrorStyleSettingsForm extends ConfigFormBase {

  /**
   * Constructs a \Drupal\errorstyle\ErrorStyleSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

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
      '#title' => t('Disable Inline Form Errors'),
      '#default_value' => $config->get('disable_inline_form_errors'),
      '#description' => t('Do not show inline form errors for the ErrorStyle form.'),
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
