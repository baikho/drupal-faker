<?php

namespace Drupal\faker;

use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Interface FakerDataSamplerInterface.
 *
 * @package Drupal\faker
 */
interface FakerDataSamplerInterface {

  /**
   * Get relevant field type id.
   */
  public function getFieldTypeId();

  /**
   * Generate sample data with Faker.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Field definition.
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition);

}
