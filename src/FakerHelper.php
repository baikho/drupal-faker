<?php

namespace Drupal\faker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Class FakerHelper.
 *
 * @package Drupal\faker
 */
class FakerHelper {

  /**
   * Get Faker supported locales.
   *
   * @return array
   *   An array of Faker supported locales.
   */
  public static function getLocales() {
    return [
      'ar_JO',
      'ar_SA',
      'at_AT',
      'bg_BG',
      'bn_BD',
      'cs_CZ',
      'da_DK',
      'de_AT',
      'de_CH',
      'de_DE',
      'el_CY',
      'el_GR',
      'en_AU',
      'en_CA',
      'en_GB',
      'en_HK',
      'en_IN',
      'en_NG',
      'en_NZ',
      'en_PH',
      'en_SG',
      'en_UG',
      'en_US',
      'en_ZA',
      'es_AR',
      'es_ES',
      'es_PE',
      'es_VE',
      'et_EE',
      'fa_IR',
      'fi_FI',
      'fr_BE',
      'fr_CA',
      'fr_CH',
      'fr_FR',
      'he_IL',
      'hr_HR',
      'hu_HU',
      'hy_AM',
      'id_ID',
      'is_IS',
      'it_CH',
      'it_IT',
      'ja_JP',
      'ka_GE',
      'kk_KZ',
      'ko_KR',
      'lt_LT',
      'lv_LV',
      'me_ME',
      'mn_MN',
      'ms_MY',
      'nb_NO',
      'ne_NP',
      'nl_BE',
      'nl_NL',
      'pl_PL',
      'pt_BR',
      'pt_PT',
      'ro_MD',
      'ro_RO',
      'ru_RU',
      'sk_SK',
      'sl_SI',
      'sr_Cyrl_RS',
      'sr_Latn_RS',
      'sr_RS',
      'sv_SE',
      'th_TH',
      'tr_TR',
      'uk_UA',
      'vi_VN',
      'zh_CN',
      'zh_TW',
    ];
  }

  /**
   * Populate the fields on a given entity with sample values.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to be enriched with sample field values.
   * @param string $faker_profile_id
   *   The profile to use.
   * @param string $faker_locale
   *   The locale to use if specified.
   *
   * @throws \Exception
   *   If plugin not found.
   */
  public static function populateFields(EntityInterface $entity, $faker_profile_id, $faker_locale = NULL) {

    $faker_profile = \Drupal::entityTypeManager()->getStorage('faker_profile')->load($faker_profile_id);
    $faker_profile_data_samplers = $faker_profile->getDataSamplers();

    /** @var \Drupal\field\FieldConfigInterface[] $instances */
    $instances = \Drupal::entityTypeManager()
      ->getStorage('field_config')
      ->loadByProperties([
        'entity_type' => $entity->getEntityType()->id(),
        'bundle' => $entity->bundle(),
      ]);

    foreach ($instances as $instance) {
      $field_storage = $instance->getFieldStorageDefinition();
      $max = $cardinality = $field_storage->getCardinality();
      if ($cardinality === FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) {
        $max = random_int(1, 3);
      }
      $field_name = $field_storage->getName();
      $field_definition = $entity->$field_name->getFieldDefinition();
      $field_definition_type = $field_definition->getType();

      // Keep track of the sampling type.
      $faker_sampling = FALSE;

      // Match field type id with sampler.
      if (isset($faker_profile_data_samplers[$field_definition_type])) {
        $faker_sampler_id = $faker_profile_data_samplers[$field_definition_type];
        /** @var \Drupal\faker\FakerDataSamplerInterface $faker_sampler */
        $faker_sampler = \Drupal::service('plugin.manager.faker_data_sampler')->createInstance($faker_sampler_id);
        $values = [];
        for ($delta = 0; $delta < $max; $delta++) {
          $values[$delta] = $faker_sampler::generateFakerValue($field_definition, $faker_locale);
        }
        $entity->$field_name->setValue($values);
        $faker_sampling = TRUE;
      }

      // Fallback to original core sample data population if faker sampling
      // did not happen or wasn't mapped to deviate from core sampling.
      if ($faker_sampling === FALSE) {
        $entity->$field_name->generateSampleItems($max);
      }
    }
  }

}
