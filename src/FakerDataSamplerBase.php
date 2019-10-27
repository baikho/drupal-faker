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
  public function getFieldTypeIds() {
    $plugin_definition = $this->getPluginDefinition();
    if (isset($plugin_definition['field_type_ids']) && $plugin_definition($plugin_definition['field_type_ids'])) {
      return $plugin_definition['field_type_ids'];
    }
    return [];
  }

}
