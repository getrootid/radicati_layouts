<?php

namespace Drupal\radicati_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

abstract class RadLayoutBase extends LayoutDefault implements PluginFormInterface
{

  public function defaultConfiguration()
  {
    return parent::defaultConfiguration() + [
      'layout_id' => '',
      'layout_classes' => '',
      'layout_width' => 'layout-width--normal',
      'has_grid_gaps' => TRUE,
    ];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $configuration = $this->getConfiguration();

    $form['layout_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section ID'),
      '#description' => $this->t('This can be used for the target of an anchor tag, and can be directly linked to.'),
      '#default_value' => $configuration['layout_id']
    ];

    $form['layout_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section Classes'),
      '#description' => $this->t('Classes that can be used for styling.'),
      '#default_value' => $configuration['layout_classes']
    ];

    $form['layout_width'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout Width'),
      '#description' => $this->t('How wide should this layout be?'),
      '#default_value' => $configuration['layout_width'],
      '#options' => [
        'layout-width--normal' => $this->t('Normal'),
        'layout-width--narrow' => $this->t('Narrow'),
        'layout-width--wide' => $this->t('Wide'),
        'layout-width--full' => $this->t('Full'),
      ]
    ];

    $form['has_grid_gaps'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Has Grid Gaps'),
      '#description' => $this->t('If using a grid inside this container, should it have gaps between the columns?'),
      '#default_value' => $configuration['has_grid_gaps']
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['layout_id'] = $form_state->getValue('layout_id');
    $this->configuration['layout_classes'] = $this->spaceSeparate($form_state->getValue('layout_classes'));
    $this->configuration['layout_width'] = $form_state->getValue('layout_width');
    $this->configuration['has_grid_gaps'] = $form_state->getValue('has_grid_gaps');
  }

  public function spaceSeparate($string)
  {
    return str_replace(',', ' ', str_replace(', ', ' ', $string));
  }
}
