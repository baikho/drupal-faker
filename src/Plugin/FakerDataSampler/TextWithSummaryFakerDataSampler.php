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
 *   label = @Translation("Faker Text (formatted, long, with summary)"),
 *   field_type_ids = {
 *     "text_with_summary",
 *   }
 * )
 */
class TextWithSummaryFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition, $faker_locale = NULL) {

    $faker = Factory::create($faker_locale);
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
