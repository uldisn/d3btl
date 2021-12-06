<?php
namespace d3yii2/d3btl;

use Yii;

class LeftMenu {

    public function list()
    {
        $user = Yii::$app->user;
        return [
            [
                'label' => Yii::t('d3btl', '????'),
                'type' => 'submenu',
                //'icon' => 'truck',
                'url' => ['/d3btl/????/index'],
            ],
        ];
    }
}
