<?php

namespace Drupal\views_ce;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining an Example entity.
 */
interface ProfileInterface extends ConfigEntityInterface {

  /**
   * Gets the profile description.
   *
   * @return string
   *   The profile description.
   */
  public function getDescription();

  /**
   * Sets the profile description.
   *
   * @param string $description
   *   The profile description.
   *
   * @return $this
   */
  public function setDescription($description);

  /**
   * Gets the profile content entities.
   *
   * @return array
   *   An array of content entities.
   */
  public function getContentEntities();

  /**
   * Sets the profile content entities.
   *
   * @param string $contentEntities
   *   An array of content entities.
   *
   * @return $this
   */
  public function setContentEntities($contentEntities);

  /**
   * Gets the profile content bundles.
   *
   * @return array
   *   An array of content bundles.
   */
  public function getBundles();

  /**
   * Sets the profile content bundles.
   *
   * @param string $bundles
   *   An array of content bundles.
   *
   * @return $this
   */
  public function setBundles($bundles);

}
