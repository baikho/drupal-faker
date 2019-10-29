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
 *   label = @Translation("Faker Email"),
 *   field_type_ids = {
 *     "email",
 *     "string",
 *   }
 * )
 */
class EmailFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition = NULL, $faker_locale = NULL) {
    return [
      'value' => Factory::create($faker_locale)->safeEmail,
    ];
  }

}
