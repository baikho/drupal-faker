<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class CountryFakerDataSampler.
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
  public static function generateFakerValue(FieldDefinitionInterface $field_definition = NULL, $faker_locale = NULL) {
    return [
      'value' => Factory::create($faker_locale)->country,
    ];
  }

}
