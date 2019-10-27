<?php

namespace Drupal\faker\Plugin\FakerDataSampler;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystemInterface;
use Drupal\faker\FakerDataSamplerBase;
use Drupal\file\Entity\File;
use Faker\Factory;

/**
 * Class ImageFakerDataSampler.
 *
 * @FakerDataSampler(
 *   id = "faker_image",
 *   label = @Translation("Faker Image"),
 *   field_type_ids = {
 *     "image",
 *   }
 * )
 */
class ImageFakerDataSampler extends FakerDataSamplerBase {

  /**
   * {@inheritdoc}
   */
  public static function generateFakerValue(FieldDefinitionInterface $field_definition, $faker_locale = NULL) {

    $faker = Factory::create($faker_locale);
    $settings = $field_definition->getSettings();
    static $images = [];

    $min_resolution = empty($settings['min_resolution']) ? '100x100' : $settings['min_resolution'];
    $max_resolution = empty($settings['max_resolution']) ? '600x600' : $settings['max_resolution'];
    $extensions = array_intersect(explode(' ', $settings['file_extensions']), ['png', 'gif', 'jpg', 'jpeg']);
    $extension = array_rand(array_combine($extensions, $extensions));

    // Generate a max of 5 different images.
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');
    $tmp_file = $file_system->tempnam('temporary://', 'generateImage_');
    $destination = $tmp_file . '.' . $extension;
    try {
      $file_system->move($tmp_file, $destination);
    }
    catch (FileException $e) {
      // Ignore failed move.
    }

    if ($path = self::image($file_system->realpath($destination), $min_resolution, $max_resolution, $faker)) {
      $image = File::create();
      $image->setFileUri($path);
      $image->setOwnerId(\Drupal::currentUser()->id());
      $image->setMimeType(\Drupal::service('file.mime_type.guesser')->guess($path));
      $image->setFileName($file_system->basename($path));
      $destination_dir = static::doGetUploadLocation($settings);
      $file_system->prepareDirectory($destination_dir, FileSystemInterface::CREATE_DIRECTORY);
      $destination = $destination_dir . '/' . basename($path);
      $file = file_move($image, $destination);
      $images[$extension][$min_resolution][$max_resolution][$file->id()] = $file;
    }
    else {
      return [];
    }

    list($width, $height) = getimagesize($file->getFileUri());

    return [
      'target_id' => $file->id(),
      'alt' => $faker->sentence(),
      'title' => $faker->sentence(),
      'width' => $width,
      'height' => $height,
    ];
  }

  /**
   * Create a placeholder image.
   */
  private static function image($destination, $min_resolution, $max_resolution, $faker) {

    $min = explode('x', $min_resolution);
    $max = explode('x', $max_resolution);

    $width = random_int((int) $min[0], (int) $max[0]);
    $height = random_int((int) $min[1], (int) $max[1]);

    return $faker->image($destination, $width, $height, 'cats', TRUE, TRUE, 'Faker');
  }

}
