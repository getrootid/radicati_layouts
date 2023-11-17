<?php

namespace Drupal\radicati_layouts\Plugin\Layout;

use Drupal\Component\Utility\Html;
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
      'layout_inner_width' => '',
      'layout_background' => '',
      'has_grid_gaps' => TRUE,
    ];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();

    // Load the taxonomy term children of the term Background, in the settings vocab (term 13)
    // and put them in an array to use as options for a select list.
    $vid = 'settings';
    $tid = 13;
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $tid, NULL, TRUE);
    $backgrounds = [];
    foreach ($terms as $term) {
      $key = $term->get('field_setting_class')->value;
      if(!empty($key)) {
        $backgrounds[$key] = $term->name->value;
      }
    }

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
      ],
      '#attributes' => [
        'id' => 'layout-width',
      ]
    ];

    // Inner with is only used in when layout-width--full is selected above.
    $form['layout_inner_width'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout Inner Width'),
      '#description' => $this->t('Width for content placed in this section. Useful for having a full width background and keeping content in the content column.'),
      '#default_value' => $configuration['layout_inner_width'],
      '#options' => [
        'layout-inner-width--normal' => $this->t('Normal'),
        'layout-inner-width--narrow' => $this->t('Narrow'),
        'layout-inner-width--wide' => $this->t('Wide'),
        'layout-inner-width--full' => $this->t('Full'),
      ],
      '#states' => [
        'visible' => [
          ':input[id="layout-width"]' => ['value' => 'layout-width--full'],
        ],
      ],
    ];

    $form['layout_background'] = [
      '#type' => 'radios',
      '#title' => $this->t('Background'),
      '#description' => $this->t('What background should this layout have?'),
      '#default_value' => $configuration['layout_background'],
      '#options' => $backgrounds
    ];

    $form['has_grid_gaps'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Has Grid Gaps'),
      '#description' => $this->t('If using a grid inside this container, should it have gaps between the columns?'),
      '#default_value' => $configuration['has_grid_gaps']
    ];

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

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['layout_id'] = $form_state->getValue('layout_id');
    $this->configuration['layout_classes'] = $this->spaceSeparate($form_state->getValue('layout_classes'));
    $this->configuration['layout_width'] = $form_state->getValue('layout_width');
    $this->configuration['layout_inner_width'] = $form_state->getValue('layout_inner_width');
    $this->configuration['has_grid_gaps'] = $form_state->getValue('has_grid_gaps');
    $this->configuration['layout_background'] = $form_state->getValue('layout_background');
  }

  public function spaceSeparate($string)
  {
    return str_replace(',', ' ', str_replace(', ', ' ', $string));
  }
}
