<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class CountryCodeFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_country_code",
 *   label = @Translation("Faker CountryCode"),
 *   field_type_ids = {
 *     "address_country",
 *   }
 * )
 */
class CountryCodeFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition = NULL, $faker_locale = NULL) {
    return [
      'value' => Factory::create($faker_locale)->countryCode,
    ];
  }

}
