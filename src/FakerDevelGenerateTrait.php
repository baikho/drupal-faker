<?php

namespace Drupal\faker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Trait FakerDevelGenerateTrait.
 *
 * @package Drupal\faker
 */
trait FakerDevelGenerateTrait {

  /**
   * An initial optional select option.
   *
   * @return array
   *   Single keyed valued array.
   */
  public function buildDefaultSelectOption() {
    return [
      FakerConstants::OPTION_NONE => $this->t('None'),
    ];
  }

  /**
   * Initialise default Faker settings form elements.
   *
   * @param array $form
   *   The settings form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state object.
   *
   * @return mixed
   *   Form array.
   */
  public function initFakerSettingsForm(array $form, FormStateInterface $form_state) {

    try {
      $entities = \Drupal::entityTypeManager()->getStorage('faker_profile')->loadMultiple();
    }
    catch (\Exception $e) {
      $entities = [];
    }

    $faker_profile_options = $faker_locale_options = $faker_title_options = $this->buildDefaultSelectOption();

    foreach ($entities as $key => $entity) {
      $faker_profile_options[$key] = $entity->label();
    }

    $form['faker'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Faker'),
      '#collapsible' => TRUE,
      '#weight' => 100,
    ];

    $form['faker'][FakerConstants::PROFILE] = [
      '#title' => $this->t('Faker Profile'),
      '#description' => $this->t('Use a Faker Profile for content population.'),
      '#type' => 'select',
      '#options' => $faker_profile_options,
    ];

    $form['faker'][FakerConstants::LOCALE] = [
      '#title' => $this->t('Locale'),
      '#description' => $this->t('Use a Locale for content population.'),
      '#type' => 'select',
      '#options' => $faker_locale_options + array_combine(FakerHelper::getLocales(), FakerHelper::getLocales()),
      '#states' => [
        'invisible' => [
          ':input[name="' . FakerConstants::PROFILE . '"]' => ['value' => FakerConstants::OPTION_NONE],
        ],
      ],
    ];

    /** @var \Drupal\faker\FakerDataSamplerInterface $faker_sampler */
    $faker_sampler_definitions = \Drupal::service('plugin.manager.faker_data_sampler')->getDefinitions();
    foreach ($faker_sampler_definitions as $faker_sampler_id => $faker_sampler_definition) {
      if (in_array('string', $faker_sampler_definition['field_type_ids'], TRUE)) {
        $faker_title_options[$faker_sampler_id] = $faker_sampler_definition['label'];
      }
    }

    $form['faker'][FakerConstants::ENTITY_TITLE] = [
      '#type' => 'select',
      '#options' => $faker_title_options,
      '#states' => [
        'invisible' => [
          ':input[name="' . FakerConstants::PROFILE . '"]' => ['value' => FakerConstants::OPTION_NONE],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Sets entity specific field values.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to apply the changes to.
   * @param \Drupal\faker\FakerDataSamplerInterface $faker_sampler
   *   The sampler to use for data population.
   */
  public static function setEntityFields(EntityInterface $entity, FakerDataSamplerInterface $faker_sampler) {}

}
