<?php

namespace Drupal\faker\Plugin\DevelGenerate;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\devel_generate\Plugin\DevelGenerate\ContentDevelGenerate;
use Drupal\faker\FakerConstants;

/**
 * Class FakerContentDevelGenerate.
 *
 * @package Drupal\faker\Plugin\DevelGenerate
 */
class FakerContentDevelGenerate extends ContentDevelGenerate {

  /**
   * Identifier for Faker data.
   *
   * @var bool
   */
  private static $faker;

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    try {
      $entities = \Drupal::entityTypeManager()->getStorage('faker_profile')->loadMultiple();
    }
    catch (\Exception $e) {
      $entities = [];
    }

    $faker_profile_options = [
      '_none_' => $this->t('None'),
    ];

    foreach ($entities as $key => $entity) {
      $faker_profile_options[$key] = $entity->label();
    }

    $form[FakerConstants::PROFILE] = [
      '#title' => $this->t('Faker Profile'),
      '#description' => $this->t('Use a Faker Profile for content population.'),
      '#type' => 'select',
      '#options' => $faker_profile_options,
      '#weight' => 100,
    ];

    $form[FakerConstants::LOCALE] = [
      '#title' => $this->t('Faker Locale'),
      '#description' => $this->t('Use a Faker Locale for content population.'),
      '#type' => 'select',
      '#options' => [
        'en_US' => 'en_US',
        'en_GB' => 'en_GB',
        'nl_BE' => 'nl_BE',
      ],
      '#weight' => 100,
      '#states' => [
        'invisible' => [
          ':input[name="' . FakerConstants::PROFILE . '"]' => ['value' => '_none_'],
        ],
      ],
    ];

    return parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function develGenerateContentAddNode(&$results) {
    if (self::$faker === NULL) {
      self::$faker = $results[FakerConstants::PROFILE] !== '_none_';
    }
    parent::develGenerateContentAddNode($results);
  }

  /**
   * {@inheritdoc}
   */
  public static function populateFields(EntityInterface $entity) {
    // Do not proceed with default sample value population if Faker is checked.
    if (self::$faker !== TRUE) {
      parent::populateFields($entity);
    }
  }

}
