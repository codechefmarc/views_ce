<?php

namespace Drupal\views_ce;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Plugin\ViewsPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfileManager extends ControllerBase implements ContainerInjectionInterface {

/**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

/**
   * The wizard plugin manager.
   *
   * @var \Drupal\views\Plugin\ViewsPluginManager
   */
  protected $wizardManager;

/**
   * Constructs a new ViewsBasicManager object.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ViewsPluginManager $views_plugin_manager,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->wizardManager = $views_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.views.wizard'),
    );
  }

  public function getContentEntityTypes() {
    $wizard_plugins = $this->wizardManager->getDefinitions();
    $options = [];
    foreach ($wizard_plugins as $key => $wizard) {
      if (!str_contains($key, 'revision')) {
        $options[$key] = $wizard['title'];
      }
    }

    $removeComplexOptions = [
      'watchdog',
      'file_managed',
    ];

    $newoptions = array_diff_key($options, array_flip($removeComplexOptions));

    kint($newoptions);
    return $newoptions;
  }


}
