<?php

namespace Drupal\faker\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\faker\FakerProfileInterface;

/**
 * Defines the Faker Profile entity.
 *
 * @ConfigEntityType(
 *   id = "faker_profile",
 *   label = @Translation("Faker Profile"),
 *   handlers = {
 *     "list_builder" = "Drupal\faker\FakerProfileListBuilder",
 *     "form" = {
 *       "add" = "Drupal\faker\Form\FakerProfileForm",
 *       "edit" = "Drupal\faker\Form\FakerProfileForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     }
 *   },
 *   config_prefix = "faker_profile",
 *   admin_permission = "administer faker profile configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "data_samplers" = "data_samplers",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "data_samplers",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/development/faker-profile/{faker_profile}",
 *     "delete-form" = "/admin/config/development/faker-profile/{faker_profile}/delete",
 *   }
 * )
 */
class FakerProfile extends ConfigEntityBase implements FakerProfileInterface {

  /**
   * The Faker Profile ID.
   *
   * @var string
   */
  public $id;

  /**
   * The Faker Profile label.
   *
   * @var string
   */
  public $label;

  /**
   * The Faker Profile data samplers.
   *
   * @var mixed
   */
  public $data_samplers;

  /**
   * {@inheritdoc}
   */
  public function getDataSamplers() {
    return $this->data_samplers;
  }

  /**
   * {@inheritdoc}
   */
  public function setDataSamplers(array $data_samplers) {
    $this->data_samplers = $data_samplers;
    return $this;
  }

}
