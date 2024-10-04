<?php

namespace Drupal\views_ce\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\views_ce\ProfileInterface;

/**
 * Defines the Example entity.
 *
 * @ConfigEntityType(
 *   id = "views_ce_profile",
 *   label = @Translation("Views CE profile"),
 *   handlers = {
 *     "list_builder" = "Drupal\views_ce\Controller\ProfileListBuilder",
 *     "form" = {
 *       "add" = "Drupal\views_ce\Form\AddForm",
 *       "edit" = "Drupal\views_ce\Form\EditForm",
 *       "delete" = "Drupal\views_ce\Form\ProfileDeleteForm",
 *     }
 *   },
 *   config_prefix = "profile",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "content_entities",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/content/views_ce/{views_ce_profile}",
 *     "delete-form" = "/admin/config/content/views_ce/{views_ce_profile}/delete",
 *     "collection" = "/admin/config/content/views_ce",
 *   }
 * )
 */
class Profile extends ConfigEntityBase implements ProfileInterface, EntityWithPluginCollectionInterface {

  /**
   * The Example ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Example label.
   *
   * @var string
   */
  protected $label;

  /**
   * Description of this profile.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description');
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->set('description', trim($description));
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentEntities() {
    return $this->get('content_entities');
  }

  /**
   * {@inheritdoc}
   */
  public function setContentEntities($contentEntities) {
    $this->set('content_entities', $contentEntities);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundles() {
    return $this->get('bundles');
  }

  /**
   * {@inheritdoc}
   */
  public function setBundles($bundles) {
    $this->set('bundles', $bundles);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [];
  }

}
