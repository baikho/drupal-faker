<?php

namespace Drupal\faker\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Faker data sampler annotation object.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class FakerDataSampler extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plugin label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The plugin field type IDs.
   *
   * @var array
   */
  public $field_type_ids = [];

}
