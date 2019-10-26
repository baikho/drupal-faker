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

    $form[FakerConstants::USE_FAKER] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Faker data'),
      '#description' => $this->t('Use Faker for content population.'),
      '#weight' => 100,
    ];

    return parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function develGenerateContentAddNode(&$results) {
    if (self::$faker === NULL) {
      self::$faker = (bool) $results[FakerConstants::USE_FAKER] === TRUE;
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
