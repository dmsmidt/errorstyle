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
//      'submit_top' => array(
//        '#type' => 'submit',
//        '#value' => 'Submit',
//        '#weight' => -100,
//      ),
      'fieldset_without_error' => array(
        '#type' => 'fieldset',
        '#title' => t('Fieldset without error'),
        '#description' => 'Normal fieldset without an error on the fieldset itself description',
        'textfield_with_error' => array(
          '#type' => 'textfield',
          '#title' => 'Textfield with error',
          '#description' => 'Error on field inside a fieldset description',
        ),
      ),
      'text_format_content' => array(
        '#type' => 'text_format',
        '#required' => TRUE,
        '#title' => 'Text area with filter selection (required)',
        '#description' => 'Text area with format switcher description',
      ),
      'managed_file' => array(
        '#type' => 'managed_file',
        '#required' => TRUE,
        '#title' => 'Managed file',
        '#description' => 'Upload widget description',
      ),
      'fieldset_parent' => array(
        '#type' => 'fieldset',
        '#title' => 'Fieldset parent with tree',
        '#tree' => TRUE,
        '#description' => 'Fieldset with #tree => true',
        'test_child_required' => array(
          '#type' => 'textfield',
          '#title' => t('Textfield child required'),
          '#description' => 'Textfield child required description',
          '#required' => TRUE,
        ),
        'test_child_custom_error' => array(
          '#type' => 'textfield',
          '#title' => t('Textfield child width custom error'),
          '#description' => 'Textfield child width custom error description',
        ),
      ),
      'details_closed' => array(
        '#type' => 'details',
        '#title' => 'Details closed',
        '#open' => FALSE,
        '#description' => 'Details description',
        'test_child_required_2' => array(
          '#type' => 'textfield',
          '#title' => t('Textfield child required'),
          '#description' => 'Textfield child required description',
          '#required' => TRUE,
        ),
        'test_child_custom_error_2' => array(
          '#type' => 'textfield',
          '#title' => t('Textfield child width custom error 2'),
          '#description' => 'Textfield child width custom error 2 description',
        ),
      ),
      'container' => array(
        '#type' => 'container',
        '#description' => 'Container description',
        'test_child_required_3' => array(
          '#type' => 'textfield',
          '#title' => t('Textfield child title'),
          '#description' => 'Textfield child -- in a container',
        ),c
      ),
      'submit' => array(
        '#type' => 'submit',
        '#value' => 'Submit',
        '#weight' => 100,
        '#prefix' => '<br />'
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
    $form_state->setErrorByName('textfield_with_error',  t('Invalid textfield'));
    $form_state->setErrorByName('fieldset_parent][test_child_custom_error',  t('Invalid textfield with custom error'));
    $form_state->setErrorByName('test_child_custom_error_2',  t('Invalid textfield with custom error 2 inside closed details'));

    $form_state->setErrorByName('', $this->t('Test error which is not related to a real element'));

    $form_state->setErrorByName('container',  $this->t('Error called against Container.'));
 }



  /**
   *  {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  protected function getFormElements() {
    return array(
      'entity_autocomplete' => array(
        '#target_type' => 'user',
      ),
      'color' => '',
      'date' => '',
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
        '#required' => TRUE,
      ),
      'email' => '',
      'file' => '',
//      'hidden' => '',
//      'image_button' => '',
//      'item' => array(
//        '#markup' => 'Item text',
//      ),
//      'language_select' => '',
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
      'range' => '',
      'search' => '',
      'select' => array(
        '#options' => array(
          'Check me',
          'Check him',
          'Check her',
        )
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
//      'language_configuration' => array(
//        '#entity_information' => array(
//          'entity_type' => 'block_content',
//          'bundle' => 'article',
//        ),
//      ),
//      'text_format' => '',
      'fieldset' => array(
        '#name' => 'fieldset',
        'fieldset_textfield' => array(
          '#type' => 'textfield',
          '#title' => 'Textfield without errors',
          '#description' => 'Textfield without errors description',
        ),
      ),
    );
  }

  public function exists($id) {
    return FALSE;
  }

}
