<?php

namespace Drupal\radicati_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;

class RadTwoColumnLayout extends RadLayoutBase {
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
        'layout_id' => '',
        'layout_classes' => '',
        'is_full_width' => FALSE,
        'has_grid_gaps' => TRUE,
      ];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $configuration = $this->getConfiguration();

    $form['layout_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout Type'),
      '#description' => $this->t('Should this use sidebars or two equal width regions?'),
      '#default_value' => $configuration['layout_type'],
      '#options' => [
        'left_sidebar' => $this->t('Left Sidebar'),
        'right_sidebar' => $this->t('Right Sidebar'),
        'half_half' => $this->t('50/50')
      ],
      '#weight' => -1,
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['layout_type'] = $form_state->getValue('layout_type');
  }
}