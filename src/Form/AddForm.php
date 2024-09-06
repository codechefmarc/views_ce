<?php

namespace Drupal\views_ce\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for the Example add and edit forms.
 */
class AddForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Save Views CE profile');
    return $actions;
  }

}
