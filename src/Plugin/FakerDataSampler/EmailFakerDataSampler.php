<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class EmailFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_email",
 *   fieldTypeId = "email",
 * )
 */
class EmailFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition) {
    $faker = Factory::create();
    $values['value'] = $faker->safeEmail;
    return $values;
  }

}
