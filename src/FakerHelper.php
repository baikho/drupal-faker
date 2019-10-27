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
   *
   * @throws \Exception
   *   If plugin not found.
   */
  public static function populateFields(EntityInterface $entity) {

    // Check for fields that have a sampler plugin.
    $field_type_sampler_manager = \Drupal::service('plugin.manager.faker_data_sampler');
    $field_type_samplers_definitions = $field_type_sampler_manager->getDefinitions();

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

      // Keep track of the sampling type.
      $faker_sampling = FALSE;

      // Iterate through registered sampler definitions.
      foreach ($field_type_samplers_definitions as $faker_sampler_id => $field_type_samplers_definition) {
        // Match field type id with sampler.
        if ($field_type_samplers_definition['fieldTypeId'] === $field_definition->getType()) {
          $faker_sampler = $field_type_sampler_manager->createInstance($faker_sampler_id);
          $values = [];
          for ($delta = 0; $delta < $max; $delta++) {
            $values[$delta] = $faker_sampler::generateSampleValue($field_definition);
          }
          $entity->$field_name->setValue($values);
          $faker_sampling = TRUE;
          break;
        }
      }
      // Fallback to original core sample data population if faker sampling
      // did not happen.
      if ($faker_sampling === FALSE) {
        $entity->$field_name->generateSampleItems($max);
      }
    }
  }

}
