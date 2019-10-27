<?php

namespace Drupal\faker;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining the Faker Profile entity.
 */
interface FakerProfileInterface extends ConfigEntityInterface {

  /**
   * Get the Data Samplers.
   *
   * @return array
   *   An array of data sampler id keys.
   */
  public function getDataSamplers();

  /**
   * Set the Data Samplers.
   *
   * @param array $data_samplers
   *   An array of data sampler id keys.
   *
   * @return $this
   */
  public function setDataSamplers(array $data_samplers);

}
