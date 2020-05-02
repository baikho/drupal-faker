<?php

namespace Drupal\faker\Plugin\DevelGenerate;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\devel_generate\Plugin\DevelGenerate\ContentDevelGenerate;
use Drupal\faker\FakerConstants;
use Drupal\faker\FakerDataSamplerInterface;
use Drupal\faker\FakerDevelGenerateTrait;

/**
 * Class FakerContentDevelGenerate.
 *
 * @package Drupal\faker\Plugin\DevelGenerate
 */
class FakerContentDevelGenerate extends ContentDevelGenerate {

  use FakerDevelGenerateTrait;

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

    $form = $this->initFakerSettingsForm($form, $form_state);
    $form['faker'][FakerConstants::ENTITY_TITLE]['#title'] = $this->t('Node title');
    $form['faker'][FakerConstants::ENTITY_TITLE]['#description'] = $this->t('Use a Data Sampler for the node titles.');

    return parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function generateElements(array $values) {
    if (self::$faker === NULL) {
      self::$faker = $values[FakerConstants::PROFILE] !== FakerConstants::OPTION_NONE;
    }
    parent::generateElements($values);
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

  /**
   * {@inheritdoc}
   */
  public static function setEntityFields(EntityInterface $entity, FakerDataSamplerInterface $faker_sampler) {
    // Set the entity (node) title.
    $entity->setTitle($faker_sampler::generateFakerValue(NULL, $entity->devel_generate[FakerConstants::LOCALE]));
  }

}
