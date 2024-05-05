<?php

namespace Drupal\playground_keysafe\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\playground_keysafe\PlaygroundKeyManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a form to delete Key from Keysafe.
 *
 * @internal
 */
class KeysafeDelete extends ConfirmFormBase {

  /**
   * The keySafe key.
   *
   * @var string
   */
  protected $keySafeKey;

  /**
   * The KeySafe manager.
   *
   * @var PlaygroundKeyManagerInterface
   */
  protected PlaygroundKeyManagerInterface $keySafeManager;

  /**
   * Constructs a new keySafe Delete object.
   *
   * @param PlaygroundKeyManagerInterface $key_store
   *   The KeySafe manager.
   */
  public function __construct(PlaygroundKeyManagerInterface $key_store) {
    $this->keySafeManager = $key_store;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): KeysafeDelete|static {
    return new static(
      $container->get('playground_keysafe.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'keysafe_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %key?', ['%key' => $this->keySafeKey]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('playground_keysafe.admin_page');
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   A nested array form elements comprising the form.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   * @param string $keysafe_id
   *   The KeySafe key ID.
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $keysafe_id = '') {
    if (!$this->keySafeKey = $this->keySafeManager->findById($keysafe_id)) {
      $this->logger('user')->notice('Keysafe id %key not found', ['%key' => $keysafe_id]);
      $this->messenger()->addError($this->t('The KeySafe id %key not found.', ['%key' => $keysafe_id]));
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $result = $this->keySafeManager->deleteKey($this->keySafeKey);
    if ($result) {
      $this->logger('user')->notice('Deleted %key', ['%key' => $this->keySafeKey]);
      $this->messenger()->addStatus($this->t('The KeySafe %key was deleted.', ['%key' => $this->keySafeKey]));
    } else {
      $this->logger('user')->notice('Error deleting %key', ['%key' => $this->keySafeKey]);
      $this->messenger()->addStatus($this->t('The KeySafe %key was not deleted.', ['%key' => $this->keySafeKey]));
    }
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
