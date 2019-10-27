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
   * Get relevant field type ids.
   */
  public function getFieldTypeIds();

  /**
   * Generate sample data with Faker.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Field definition.
   * @param string $faker_locale
   *   The locale to use if specified.
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition, $faker_locale = NULL);

}
