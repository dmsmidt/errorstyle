<?php

namespace Drupal\errorstyle\Form;



use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ErrorStyleForm extends FormBase {

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
    // Prevent browsers HTML5 error checking
    $form['#attributes'] += array('novalidate' => TRUE);

    // Set of form elements with default error handling
    // Errors are set on the first level element (and bubble up to child elements by default)
    foreach ($this->getFormElements() as $type => $defaults) {
      $element_name = 'test_' . $type;
      $form[$element_name] = array(
        '#title' => ucfirst($type),
        '#type' => $type,
        '#description' => ucfirst($type) . ' description.',
      );

      if (is_array($defaults)) {
        $form[$element_name] += $defaults;
      }
    }

    // Additional fields, without default error handling
    $form += array(
      'fieldset_without_error' => array(
        '#type' => 'fieldset',
        '#title' => t('Fieldset without error'),
        '#description' => ' Normal fieldset without an error on the fieldset itself.',
        'textfield_with_error' => array(
          '#type' => 'textfield',
          '#title' => 'Textfield with error',
          '#description' => 'Error on field inside a fieldset',
        ),
      ),
      'text_format_content' => array(
        '#type' => 'text_format',
        '#required' => TRUE,
        '#title' => 'Text area with filter selection (required)',
        '#description' => 'Text area with format switcher',
      ),
      'managed_file' => array(
        '#type' => 'managed_file',
        '#required' => TRUE,
        '#title' => 'Managed file',
        '#description' => 'Upload widget',
      ),
    );

    return $form;
  }

  /**
   *  {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Default error handling
    // Supersedes errors from 'required' fields
    $elements = $this->getFormElements();
    foreach ($elements as $type => $defaults) {
      $form_state->setErrorByName('test_' . $type,  t('Invalid @type', array('@type' => $type)));
    }

    // Additional field validations
    $form_state->setErrorByName('fieldset_with_error',  t('Invalid fieldset'));
    $form_state->setErrorByName('textfield_with_error',  t('Invalid textfield'));
  }

  /**
   *  {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  protected function getFormElements() {
    return array(
      'datelist' => array(
        '#default_value' => new DrupalDateTime('2000-01-01 00:00:00'),
        '#date_part_order' => array('month', 'day', 'year', 'hour', 'minute', 'ampm'),
        '#date_text_parts' => array('year'),
        '#date_year_range' => '2010:2020',
        '#date_increment' => 15,
      ),
      'datetime' => array(
        '#default_value' => new DrupalDateTime('2000-01-01 00:00:00'),
        '#date_date_element' => 'date',
        '#date_time_element' => 'none',
        '#date_year_range' => '2010:+3',
      ),
      'entity_autocomplete' => array(
        '#target_type' => 'user',
      ),
//      'button' => array(
//        '#value' => 'Submit',
//      ),
      'checkbox' => array(
        '#title' => 'Check me',
      ),
      'checkboxes' => array(
        '#options' => array(
          'Check me',
          'Check him',
          'Check her',
        )
      ),
      'color' => '',
      'date' => '',
      'email' => '',
      'file' => '',
//      'hidden' => '',
      'image_button' => '',
//      'item' => array(
//        '#markup' => 'Item text',
//      ),
      'language_select' => '',
      'machine_name' => array(
        '#required' => FALSE,
        '#machine_name' => array(
          'exists' => array($this, 'exists'),
        ),
      ),
      'number' => '',
      'password' => '',
      'password_confirm' => '',
      'path' => '',
      'radio' => array(
        '#title' => 'Select me',
      ),
      'radios' => array(
        '#options' => array(
          'Check me',
          'Check him',
          'Check her',
        )
      ),
      'range' => '',
      'search' => '',
      'select' => array(
        '#options' => array(
          'Check me',
          'Check him',
          'Check her',
        )
      ),
      'submit' => array(
        '#value' => 'Submit',
        '#weight' => 100,
      ),
//      'table' => array(
//        '#header' => array(
//          'row1',
//          'row2',
//        ),
//        '#empty' => t('There is no label yet.'),
//        '#tabledrag' => array(
//          array(
//            'action' => 'order',
//            'relationship' => 'sibling',
//            'group' => 'weight',
//          ),
//        ),
//      ),
//      'tableselect' => array(
//        '#header' => array(
//          'row1',
//          'row2',
//        ),
//        '#empty' => t('There is no label yet.'),
//        '#tabledrag' => array(
//          array(
//            'action' => 'order',
//            'relationship' => 'sibling',
//            'group' => 'weight',
//          ),
//        ),
//      ),
      'textfield' => '',
      'textarea' => array(
        '#rows' => 3,
      ),
//      'token' => '',
      'url' => '',
//      'value' => '',
//      'vertical_tabs' => array(
//        '#parents' => ['visibility_tabs'],
//        '#attached' => [
//          'library' => [
//            'block/drupal.block',
//          ],
//        ],
//      ),
      'weight' => '',
 //     'managed_file' => '',
      'language_configuration' => array(
        '#entity_information' => array(
          'entity_type' => 'block_content',
          'bundle' => 'article',
        ),
      ),
//      'text_format' => '',
      'fieldset' => array(
        'textfield' => array(
          '#type' => 'textfield',
          '#title' => 'Textfield without errors',
        ),
      ),
    );
  }

  public function exists($id) {
    return FALSE;
  }

}
