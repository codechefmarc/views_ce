<?php

namespace Drupal\views_ce\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for the Example add and edit forms.
 */
class EditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Update profile');
    $actions['delete']['#value'] = $this->t('Delete profile');
    return $actions;
  }

}
