<?php

namespace Drupal\playground_menu\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller routines for user routes.
 */
class UserController extends ControllerBase {
  public function userPage($user) {
    // return $this->redirect('entity.user.canonical', ['user' => $this->currentUser()->id()]);
    $url = Url::fromRoute('view.my_playground_account.account_page', ['arg_0' => $this->currentUser()->id()]);
    return new RedirectResponse($url->toString());
  }
}
