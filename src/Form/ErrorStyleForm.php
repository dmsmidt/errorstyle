<?php

namespace Drupal\errorstyle\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a forms with (all) common form elements.
 *
 * @ingroup form_api
 */
class ErrorStyleForm extends FormBase {

  /**
   * Constructs a \Drupal\errorstyle\ErrorStyleForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->setConfigFactory($config_factory);
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
    return 'error_style_form';
  }

  /**
   * {@inheritdoc}
   *
   * Builds a form for a single entity field.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('errorstyle.settings');

    // Disable Inline Form Errors as neceessary.
    if ($config->get('disable_inline_form_errors')) {
      $form['#disable_inline_form_errors'] = TRUE;
    }

    // Prevent browsers HTML5 error checking.
    $form['#attributes'] += ['novalidate' => TRUE];

    // Set of form elements with default error handling.
    // Errors are set on the first level element (and bubble up to child
    // elements by default).
    foreach ($this->getFormElements() as $type => $defaults) {
      $element_name = 'test_' . $type;
      $form[$element_name] = [
        '#title' => ucfirst($type),
        '#type' => $type,
        '#description' => ucfirst($type) . ' description.',
      ];

      if (is_array($defaults)) {
        $form[$element_name] += $defaults;
      }
    }

    // Additional fields, without default error handling.
    $form += [
      'fieldset_without_error' => [
        '#type' => 'fieldset',
        '#title' => $this->t('Fieldset without error'),
        'fieldset_textfield' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield without errors'),
        ],
      ],
      'fieldset_with_error' => [
        '#type' => 'fieldset',
        '#title' => $this->t('Fieldset with direct error'),
        '#description' => $this->t('Fieldset with an error on the fieldset itself.'),
        'fieldset_textfield' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield without errors'),
        ],
      ],
      'fieldset_child_error' => [
        '#type' => 'fieldset',
        '#title' => $this->t('Fieldset with child error'),
        '#description' => $this->t('Fieldset with an error on the child field.'),
        'textfield_with_error' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield with error'),
          '#description' => $this->t('Error on field inside a fieldset description'),
        ],
      ],
      'fieldset_parent' => [
        '#type' => 'fieldset',
        '#title' => $this->t('Fieldset parent with tree'),
        '#tree' => TRUE,
        '#description' => $this->t('Fieldset with #tree => true'),
        'test_child_required' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child required'),
          '#description' => $this->t('Textfield child required description'),
          '#required' => TRUE,
        ],
        'test_child_custom_error' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child width custom error'),
          '#description' => $this->t('Textfield child width custom error description'),
        ],
      ],
      'text_format_content' => [
        '#type' => 'text_format',
        '#required' => TRUE,
        '#title' => $this->t('Text area with filter selection (required)'),
        '#description' => $this->t('Text area with format switcher description'),
      ],
      'managed_file' => [
        '#type' => 'managed_file',
        '#required' => TRUE,
        '#title' => $this->t('Managed file'),
        '#description' => $this->t('Upload widget description'),
      ],
      'details_closed' => [
        '#type' => 'details',
        '#title' => $this->t('Details closed'),
        '#open' => FALSE,
        '#description' => $this->t('Details description'),
        'test_child_required_2' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child required'),
          '#required' => TRUE,
        ],
        'test_child_custom_error_2' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child width custom error 2'),
        ],
        'nested_details_closed' => [
          '#type' => 'details',
          '#title' => $this->t('Nested details closed'),
          '#open' => FALSE,
          'test_details_child_child_required' => [
            '#type' => 'textfield',
            '#title' => $this->t('Nested details child textfield required'),
            '#required' => TRUE,
          ],
        ],
      ],
      'container' => [
        '#type' => 'container',
        '#description' => $this->t('Container description'),
        'test_child_required_3' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child title'),
          '#description' => $this->t('Textfield child in a container'),
        ],
      ],
      'vertical_tabs' => [
        '#title' => $this->t('Vertical tabs'),
        '#type' => 'vertical_tabs',
      ],
      'vertical_tabs_details_1' => [
        '#type' => 'details',
        '#title' => $this->t('First group element'),
        '#group' => 'vertical_tabs',
        '#required' => TRUE,
        'test_child_required_2' => [
          '#type' => 'textfield',
          '#title' => $this->t('Textfield child required'),
          '#required' => TRUE,
        ],
      ],
      'vertical_tabs_details_2' => [
        '#type' => 'details',
        '#title' => $this->t('Second group element'),
        '#group' => 'vertical_tabs',
        '#required' => TRUE,
        'test_child_required_3' => [
          '#type' => 'textfield',
          '#title' => $this->t('Second textfield child required'),
          '#required' => TRUE,
        ],
      ],
      'vertical_tabs_details_3' => [
        '#type' => 'details',
        '#title' => $this->t('Third group element'),
        '#group' => 'vertical_tabs',
        'nothing' => [
          '#markup' => $this->t('Nothing here'),
        ],
      ],
      'submit' => [
        '#type' => 'submit',
        '#value' => 'Submit',
        '#weight' => 100,
        '#prefix' => '<br />',
      ],
    ];

    //
    // Vertical tabs.
    //
    $form['vertical_tabs'] = [
      '#type' => 'vertical_tabs',
    ];
    $form['meta'] = [
      '#type' => 'details',
      '#title' => 'First group element',
      '#group' => 'vertical_tabs',
    ];
    $form['meta']['element'] = [
      '#type' => 'textfield',
      '#title' => 'First nested element in details element',
      '#required' => TRUE,
    ];
    $form['meta_2'] = [
      '#type' => 'details',
      '#title' => 'Second group element',
      '#group' => 'vertical_tabs',
    ];
    $form['meta_2']['element_2'] = [
      '#type' => 'textfield',
      '#title' => 'Second nested element in details element',
      '#required' => FALSE,
    ];
    $form['meta_3'] = [
      '#type' => 'details',
      '#title' => 'Second group element',
      '#group' => 'vertical_tabs',
    ];
    $form['meta_3']['element_2'] = [
      '#type' => 'textfield',
      '#title' => 'Second nested element in details element',
      '#required' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Default error handling.
    // Supersedes errors from 'required' fields.
    $elements = $this->getFormElements();
    foreach ($elements as $type => $defaults) {
      $form_state->setErrorByName('test_' . $type, $this->t('Invalid @type', ['@type' => $type]));
    }

    if ($form_state->getValue('container_error')) {
      $form_state->setErrorByName('container', $this->t('Invalid container'));
    }

    // Additional field validations.
    $form_state->setErrorByName('fieldset_with_error', $this->t('Invalid fieldset'));
    $form_state->setErrorByName('textfield_with_error', $this->t('Invalid textfield'));
    $form_state->setErrorByName('fieldset_parent][test_child_custom_error', $this->t('Invalid textfield with custom error'));
    $form_state->setErrorByName('test_child_custom_error_2', $this->t('Invalid textfield with custom error 2 inside closed details'));

    $form_state->setErrorByName('', $this->t('Test error which is not related to a real element'));

    $form_state->setErrorByName('container', $this->t('Error called against Container.'));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Create a set of basic form elements excluding common properties.
   *
   * @return array
   *   Form elements.
   */
  protected function getFormElements() {
    return [
      'entity_autocomplete' => [
        '#target_type' => 'user',
      ],
      'color' => '',
      'date' => '',
      'datelist' => [
        '#default_value' => new DrupalDateTime('2000-01-01 00:00:00'),
        '#date_part_order' => ['month', 'day', 'year', 'hour', 'minute', 'ampm'],
        '#date_text_parts' => ['year'],
        '#date_year_range' => '2010:2020',
        '#date_increment' => 15,
      ],
      'datetime' => [
        '#default_value' => new DrupalDateTime('2000-01-01 00:00:00'),
        '#date_date_element' => 'date',
        '#date_time_element' => 'none',
        '#date_year_range' => '2010:+3',
        '#required' => TRUE,
      ],
      'email' => '',
      'file' => '',
      'machine_name' => [
        '#required' => FALSE,
        '#machine_name' => [
          'exists' => [$this, 'exists'],
        ],
      ],
      'number' => '',
      'password' => '',
      'password_confirm' => '',
      'path' => '',
      'radio' => [
        '#title' => 'Select me',
      ],
      'radios' => [
        '#options' => [
          'Check me',
          'Check him',
          'Check her',
        ],
      ],
      'checkbox' => [
        '#title' => 'Check me',
      ],
      'checkboxes' => [
        '#options' => [
          'Check me',
          'Check him',
          'Check her',
        ],
      ],
      'range' => '',
      'search' => '',
      'select' => [
        '#options' => [
          'Check me',
          'Check him',
          'Check her',
        ],
      ],
      'textfield' => '',
      'textarea' => [
        '#rows' => 3,
      ],
      'url' => '',
      'weight' => '',
    ];
  }

  /**
   * Callback to check, if machine name exist.
   *
   * @param string $id
   *   The machine name to check.
   *
   * @return bool
   *   Always returns FALSE.
   */
  public function exists($id) {
    return FALSE;
  }

}
