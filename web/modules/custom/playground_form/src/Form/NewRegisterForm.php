<?php

namespace Drupal\playground_form\Form;

use Drupal\user\RegisterForm;
use Drupal\Core\Form\FormStateInterface;

class NewRegisterForm extends RegisterForm {

  protected function actions(array $form, FormStateInterface $form_state) {
    $element = parent::actions($form, $form_state);
    $element['submit']['#value'] = 'Submit';
    return $element;
  }

  public function form(array $form, FormStateInterface $form_state) {
    // Start with the default user account fields.
    $form = parent::form($form, $form_state);
    $form['address'] = [
      '#type' => 'textfield',
      '#title' => 'Address',
      '#weight' => 2,
    ];
    $form['suburb'] = [
      '#type' => 'textfield',
      '#title' => 'Suburb',
      '#weight' => 3,
    ];
    $form['postcode'] = [
      '#type' => 'number',
      '#title' => 'Postcode',
      '#weight' => 4,
    ];
    return $form;
  }

}
