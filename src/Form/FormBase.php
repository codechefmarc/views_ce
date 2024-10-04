<?php

namespace Drupal\views_ce\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views_ce\ProfileManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the Example add and edit forms.
 */
class FormBase extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\views_ce\ProfileInterface
   */
  protected $entity;

  /**
   * The views CE profile manager.
   *
   * @var \Drupal\views_ce\ProfileManager
   */
  protected $profileManager;

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs an ExampleForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entityTypeManager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info service.
   * @param \Drupal\views_ce $profile_manager
   *   The entityTypeManager.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    ProfileManager $profile_manager,
    ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    $this->profileManager = $profile_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('views_ce.profile_manager'),
    );
  }

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
      '#description' => $this->t("Label for the profile."),
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

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
      '#description' => $this->t('The text will be displayed on the <em>profile collection</em> page.'),
    ];

    $contentEntityTypes = $this->profileManager->getContentEntityTypes();
    $form['source']['content_entities'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content Entities'),
      '#options' => $contentEntityTypes,
      '#default_value' => $this->entity->get('content_entities') ?? [],
      // '#ajax' => [
      //   'callback' => '::updateBundleSettings',
      //   'wrapper' => 'bundle-settings-wrapper',
      // ],
    ];

    $form['bundle_settings'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'bundle-settings-wrapper'],
    ];

    $selected = array_filter($this->entity->get('content_entities') ?? []);

    foreach ($selected as $entity_type_id) {
      // Skip entities that do not have bundles.
      if (!$this->hasBundles($entity_type_id)) {
        continue;
      }
      $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
      $bundle_options = [];
      foreach ($bundles as $bundle_id => $bundle) {
        $bundle_options[$bundle_id] = $bundle['label'];
      }
      $form['bundle_settings'][$entity_type_id] = [
        '#type' => 'details',
        '#closed' => TRUE,
        '#title' => $contentEntityTypes[$entity_type_id] . ' ' . $this->t('Bundle Settings'),
      ];

      $form['bundle_settings'][$entity_type_id]['bundles'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Bundles'),
        '#options' => $bundle_options,
        //'#default_value' => $config->get('entities')[$entity_type_id]['bundles'] ?? [],
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $profile = $this->entity;
    $profile->setDescription($form_state->getValue('description'));

    // $entities = array_filter(
    //   $form_state->getValue(['source', 'content_entities'])
    // );
    // $selectedEntitySettings = [];
    // foreach ($entities as $entityTypeId) {
    //   dpm($form_state->getValue(['bundle_settings', $entityTypeId, 'bundles']));
    //   $selectedEntitySettings[$entityTypeId] = [
    //     'bundles' => array_filter(
    //       $form_state->getValue(['bundle_settings', $entityTypeId, 'bundles']) ?? []
    //     ),
    //   ];
    // }

    //$profile->setContentEntities($selectedEntitySettings);

    $status = $profile->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('The %label Example created.', [
        '%label' => $profile->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label Example updated.', [
        '%label' => $profile->label(),
      ]));
    }

    $form_state->setRedirect('entity.views_ce_profile.collection');
  }

  /**
   * Helper function to check whether an Example configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('views_ce_profile')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

  /**
   * Determines whether the entity type supports bundles.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return bool
   *   TRUE if the entity type supports bundles, FALSE otherwise.
   */
  protected function hasBundles($entity_type_id) {
    if (!$this->entityTypeManager->hasDefinition($entity_type_id)) {
      return FALSE;
    }
    return $this->entityTypeManager->getDefinition($entity_type_id)->hasKey('bundle');
  }

}
