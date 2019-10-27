<?php

namespace Drupal\faker;

use Drupal\Core\Plugin\PluginBase;

/**
 * Class FakerDataSamplerBase.
 *
 * @package Drupal\faker
 */
abstract class FakerDataSamplerBase extends PluginBase implements FakerDataSamplerInterface {

  /**
   * {@inheritdoc}
   */
  public function getFieldTypeId() {
    $plugin_definition = $this->getPluginDefinition();
    return !empty($plugin_definition['fieldTypeId']) ? $plugin_definition['fieldTypeId'] : FALSE;
  }

}
