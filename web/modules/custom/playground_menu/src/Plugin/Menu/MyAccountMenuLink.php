<?php

namespace Drupal\playground_menu\Plugin\Menu;

use Drupal\Core\Menu\MenuLinkDefault;
use Drupal\Core\Menu\StaticMenuLinkOverridesInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyAccountMenuLink extends MenuLinkDefault {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new MyAccountMenuLink.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Menu\StaticMenuLinkOverridesInterface $static_override
   *   The static override storage.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StaticMenuLinkOverridesInterface $static_override, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $static_override);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('menu_link.static.overrides'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    if ($this->currentUser->isAuthenticated()) {
      return $this->t('My Playground Account');
    }
    else {
      return $this->t('Create My Account');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteName() {
    if ($this->currentUser->isAuthenticated()) {
      return 'playground_menu.profile';
    }
    else {
      return 'user.register';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters() {
    return $this->pluginDefinition['route_parameters'] += ['user' => $this->currentUser->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['user.roles:authenticated'];
  }

}
