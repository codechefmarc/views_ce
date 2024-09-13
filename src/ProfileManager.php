<?php

namespace Drupal\views_ce;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Plugin\ViewsPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a Views CE profile manager object.
 */
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
    ViewsPluginManager $wizard_manager,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->wizardManager = $wizard_manager;
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

  /**
   * Gets core content-based entity types. Similar to Views UI wizard.
   *
   * @return array
   *   Array of content-based entity options with labels.
   */
  public function getContentEntityTypes(): array {
    $wizardPlugins = $this->wizardManager->getDefinitions();
    $options = [];
    foreach ($wizardPlugins as $key => $wizard) {
      // Do not add revisions to make things simpler.
      if (!str_contains($key, 'revision')) {
        $options[$key] = $wizard['title'];
      }
    }

    // Remove additional complex options.
    $removeComplexOptions = [
      'watchdog',
      'file_managed',
    ];

    $simplifiedOptions = array_diff_key($options, array_flip($removeComplexOptions));

    return $simplifiedOptions;
  }

  public function getContentEntityLabel($entities) {
    $labels = [];
    $wizardPlugins = $this->wizardManager->getDefinitions();
    foreach ($entities as $contentEntity) {
      if ($contentEntity) {
        $labels[] = $wizardPlugins[$contentEntity]['title']->__toString();
      }
    }
    return $labels;
  }
}
