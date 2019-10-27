<?php

namespace Drupal\faker\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for adding Faker Profiles.
 */
class FakerProfileForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the Faker Profile.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['data_samplers'] = [
      '#type' => 'table',
      '#header' => [
        [
          'data' => $this->t('Field Type'),
        ],
        [
          'data' => $this->t('Field Type ID'),
        ],
        [
          'data' => $this->t('Data Sampler'),
        ],
      ],
      '#empty' => $this->t('No field types found.'),
    ];

    $field_type_definitions = \Drupal::service('plugin.manager.field.field_type')->getDefinitions();
    $field_type_sampler_definitions = \Drupal::service('plugin.manager.faker_data_sampler')->getDefinitions();
    $rows = [];
    foreach ($field_type_definitions as $field_type => $field_type_definition) {
      $faker_sampler_options = [];
      foreach ($field_type_sampler_definitions as $faker_sampler_id => $field_type_sampler_definition) {
        // Match field type id with sampler.
        if (in_array($field_type, $field_type_sampler_definition['field_type_ids'], TRUE)) {
          $faker_sampler_options[$faker_sampler_id] = $field_type_sampler_definition['label'];
        }
      }
      $rows[$field_type] = [
        [
          'data' => [
            '#markup' => $field_type_definition['label'],
          ],
        ],
        [
          'data' => [
            '#markup' => $field_type_definition['id'],
          ],
        ],
        [
          'data' => [
            '#type' => 'select',
            '#options' => [
              '_none_' => $this->t('Default'),
            ] + $faker_sampler_options,
            '#default_value' => $this->entity->getDataSamplers()[$field_type] ?? NULL,
            '#parents' => ['data_samplers', $field_type_definition['id']],
          ],
        ],
      ];
    }

    $form['data_samplers'] += $rows;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $data_samplers = $form_state->getValue('data_samplers', NULL);
    $data_samplers = array_filter($data_samplers, static function ($value) {
      return $value !== '_none_';
    });
    $this->entity->setDataSamplers($data_samplers);

    $edit_link = $this->entity->toLink($this->t('Edit'), 'edit-form')->toString();

    if ($this->entity->save() === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('Created new Faker Profile.'));
      $this->logger('faker')->notice('Created new Faker Profile %name.', ['%name' => $this->entity->label(), 'link' => $edit_link]);
    }
    else {
      $this->messenger()->addMessage($this->t('Updated Faker Profile.'));
      $this->logger('faker')->notice('Updated Faker Profile %name.', ['%name' => $this->entity->label(), 'link' => $edit_link]);
    }
    $form_state->setRedirect('entity.faker_profile.collection');
  }

  /**
   * Helper function to check whether the configuration entity exists.
   */
  public function exist($id) {
    return (bool) $this->entityTypeManager
      ->getStorage('faker_profile')
      ->getQuery()
      ->condition('id', $id)
      ->execute();
  }

}
