<?php

namespace Drupal\errorstyle\Routing;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteBuilderInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Modifies some route options.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The router builder.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected $routerBuilder;

  /**
   * Constructs a new NodeAdminRouteSubscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Routing\RouteBuilderInterface $router_builder
   *   The router builder service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RouteBuilderInterface $router_builder) {
    $this->configFactory = $config_factory;
    $this->routerBuilder = $router_builder;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if (
      $this->configFactory->get('errorstyle.settings')->get('admin_theme') &&
      ($route = $collection->get('errorstyle.form'))
    ) {
      $route->setOption('_admin_route', TRUE);
    }
  }

  /**
   * Rebuilds the router when errorstyle.settings:admin_theme is changed.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   A config crud event.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    if ($event->getConfig()->getName() === 'errorstyle.settings' && $event->isChanged('admin_theme')) {
      $this->routerBuilder->setRebuildNeeded();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = parent::getSubscribedEvents();
    $events[ConfigEvents::SAVE][] = ['onConfigSave', 0];
    return $events;
  }

}
