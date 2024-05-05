<?php

namespace Drupal\playground_keysafe\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\playground_keysafe\PlaygroundKeyManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KeysafeAdmin extends FormBase {

  /**
   * @var PlaygroundKeyManagerInterface
   */
  protected PlaygroundKeyManagerInterface $keysafeManager;

  public function __construct(PlaygroundKeyManagerInterface $keysafe_manager) {
    $this->keysafeManager = $keysafe_manager;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('playground_keysafe.manager'));
  }

  public function getFormId() {
    return 'keysafe_admin_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $default_key = '') {
    $rows = [];
    $header = [$this->t('Keys in storage'), $this->t('Operations')];
    $result = $this->keysafeManager->fetchAll();
    foreach ($result as $item) {
      $row = [];
      $row[] = $item->keysafe_key;
      $links = [];
      $links['delete'] = [
        'title' => $this->t('Delete'),
        'url' => Url::fromRoute('playground_keysafe.delete', ['keysafe_id' => $item->iid]),
      ];
      $row[] = [
        'data' => [
          '#type' => 'operations',
          '#links' => $links,
        ],
      ];
      $rows[] = $row;
    }
    $form['keysafe_key'] = [
      '#title' => $this->t('Keysafe key'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#size' => 48,
      '#maxlength' => 255,
      '#default_value' => $default_key,
      '#description' => $this->t('Enter a Key. Typically an API name, please use only a mix of lowercase alphabet, numbers and _ characters'),
    ];
    $form['keysafe_value'] = [
      '#title' => $this->t('Keysafe value'),
      '#type' => 'textfield',
      '#size' => 48,
      '#maxlength' => 255,
      '#default_value' => '',
      '#required' => TRUE,
      '#description' => $this->t('Enter a Value, typically an API secret.'),
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add to Keysafe.'),
    ];
    $form['keysafe_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Safe is empty.'),
      '#weight' => 120,
    ];
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $key = trim($form_state->getValue('keysafe_key'));

    if ($this->keysafeManager->keyExists($key)) {
      $form_state->setErrorByName('keysafe_key', $this->t('This Key %key already exists.', ['%key' => $key]));
    }
    if (!preg_match('/^[a-z0-9_]+$/', $key)) {
      $form_state->setErrorByName('keysafe_key', $this->t('Please use only a mix of lowercase alphabet, numbers and _ characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $key = trim($form_state->getValue('keysafe_key'));
    $value = trim($form_state->getValue('keysafe_value'));
    $result = $this->keysafeManager->setKey($key, $value);
    if (!is_null($result)) {
      $this->messenger()->addStatus($this->t('The Key %key has been added.', ['%key' => $key]));
    } else {
      $this->messenger()->addError($this->t('Error adding Key %key.', ['%key' => $key]));
    }
    $form_state->setRedirect('playground_keysafe.admin_page');
  }
}
