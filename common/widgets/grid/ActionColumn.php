<?php
namespace common\widgets\grid;

use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class ActionColumn extends \kartik\grid\ActionColumn
{
    public $showActions = ['update', 'delete'];
    public $showAsIcons = false;

    public $width = '170px';

    /**
     * Render default action buttons
     *
     * @return string
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view']) && in_array('view', $this->showActions)) {
            $this->buttons['view'] = function ($url) {
                $options = $this->viewOptions;
                $title = Yii::t('kvgrid', 'View');
                $icon = '<span class="glyphicon glyphicon-eye-open"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    if ($this->showAsIcons) {
                        return Html::a($label, $url, $options);
                    } else {
                        return Html::a(
                            $title,
                            $url,
                            ArrayHelper::merge(['class' => 'btn default btn-xs blue-stripe'], $options)
                        );
                    }
                }
            };
        }
        if (!isset($this->buttons['update']) && in_array('update', $this->showActions)) {
            $this->buttons['update'] = function ($url) {
                $options = $this->updateOptions;
                $title = Yii::t('kvgrid', 'Update');
                $icon = '<span class="glyphicon glyphicon-pencil"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    if ($this->showAsIcons) {
                        return Html::a($label, $url, $options);
                    } else {
                        return Html::a(
                            $title,
                            $url,
                            ArrayHelper::merge(['class' => 'btn default btn-xs green-stripe'], $options)
                        );
                    }
                }
            };
        }
        if (!isset($this->buttons['delete']) && in_array('delete', $this->showActions)) {
            $this->buttons['delete'] = function ($url) {
                $options = $this->deleteOptions;
                $title = Yii::t('kvgrid', 'Delete');
                $icon = '<span class="glyphicon glyphicon-trash"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(
                    [
                        'title' => $title,
                        'data-confirm' => Yii::t('kvgrid', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0'
                    ],
                    $options
                );
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    if ($this->showAsIcons) {
                        return Html::a($label, $url, $options);
                    } else {
                        return Html::a(
                            $title,
                            $url,
                            ArrayHelper::merge(['class' => 'btn default btn-xs red-stripe'], $options)
                        );
                    }
                }
            };
        }
    }
}
