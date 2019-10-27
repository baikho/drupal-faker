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
