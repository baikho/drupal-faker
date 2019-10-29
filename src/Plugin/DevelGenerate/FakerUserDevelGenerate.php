<?php

namespace Drupal\faker\Plugin\DevelGenerate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\devel_generate\Plugin\DevelGenerate\UserDevelGenerate;
use Drupal\faker\FakerConstants;
use Drupal\faker\FakerDevelGenerateTrait;

/**
 * Class FakerUserDevelGenerate.
 *
 * @package Drupal\faker\Plugin\DevelGenerate
 */
class FakerUserDevelGenerate extends UserDevelGenerate {

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
    $form['faker'][FakerConstants::ENTITY_TITLE]['#title'] = $this->t('User name');
    $form['faker'][FakerConstants::ENTITY_TITLE]['#description'] = $this->t('Use a Data Sampler for the user names.');

    return parent::settingsForm($form, $form_state);
  }

}
