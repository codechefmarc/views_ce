<?php

namespace Drupal\views_ce\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\views_ce\ProfileManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a listing of Views CE profiles.
 */
class ProfileListBuilder extends ConfigEntityListBuilder {

  /**
   * The profile manager.
   *
   * @var \Drupal\views_ce\ProfileManager
   */
  protected $profileManager;

  public function __construct(
    EntityTypeInterface $entity_type,
    EntityStorageInterface $storage,
    ProfileManager $profile_manager,
  ) {
    parent::__construct($entity_type, $storage);

    $this->profileManager = $profile_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('views_ce.profile_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Example');
    $header['id'] = $this->t('Machine name');
    $header['content_entities'] = $this->t('Content entities');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();

    $labels = $this->profileManager->getContentEntityLabel($entity->content_entities);
    $row['content_entities'] = implode(", ", $labels);

    return $row + parent::buildRow($entity);
  }

}
