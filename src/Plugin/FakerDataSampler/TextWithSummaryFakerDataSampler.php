<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class TextWithSummaryFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_text_with_summary",
 *   fieldTypeId = "text_with_summary",
 * )
 */
class TextWithSummaryFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition) {

    $faker = Factory::create();
    $settings = $field_definition->getSettings();

    if (empty($settings['max_length'])) {
      $value = $faker->paragraph();
    }
    else {
      // Textfield handling.
      $value = substr($faker->paragraph(random_int(1, $settings['max_length'] / 3)), 0, $settings['max_length']);
    }

    return [
      'value' => $value,
      'summary' => $value,
      'format' => filter_fallback_format(),
    ];
  }

}
