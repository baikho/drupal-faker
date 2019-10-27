<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class EmailFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_country",
 *   label = @Translation("Faker Country"),
 *   field_type_ids = {
 *     "string",
 *   }
 * )
 */
class CountryFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition, $faker_locale = NULL) {
    return Factory::create($faker_locale)->country;
  }

}
