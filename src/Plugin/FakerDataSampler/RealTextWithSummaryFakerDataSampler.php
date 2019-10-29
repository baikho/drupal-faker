<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\faker\FakerDataSamplerBase;
use Faker\Factory;

/**
 * Class RealTextWithSummaryFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_real_text_with_summary",
 *   label = @Translation("Faker Real Text (formatted, long, with summary)"),
 *   field_type_ids = {
 *     "text_with_summary",
 *   }
 * )
 */
class RealTextWithSummaryFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition = NULL, $faker_locale = NULL) {

    $faker = Factory::create($faker_locale);
    $settings = $field_definition->getSettings();

    // Run garbage collector to reduce memory exhaustion.
    gc_collect_cycles();

    if (empty($settings['max_length'])) {
      $value = $faker->realText(600);
    }
    else {
      // Textfield handling.
      $value = substr($faker->realText(random_int(1, $settings['max_length'] / 3)), 0, $settings['max_length']);
    }

    return [
      'value' => $value,
      'summary' => $value,
      'format' => filter_fallback_format(),
    ];
  }

}
