<?php

namespace api\modules\v1\controllers;

use common\models\Goods;
use common\models\SystemSetting;
use yii\web\BadRequestHttpException;
use yii\db\Expression;

class SystemSettingController extends BaseController
{
    public function actionIndex()
    {
        $systemSetting = SystemSetting::findToArray();
        $keys = [
            SystemSetting::KEY_WECHAT,
            SystemSetting::KEY_WEBSITE_TITLE,
            SystemSetting::KEY_CUSTOMER_SERVICE_EMAIL,
            SystemSetting::KEY_CUSTOMER_SERVICE_PHONE,
            SystemSetting::KEY_MOBILE_CODE,
        ];
        $data = [];
        foreach ($systemSetting as $field => $val) {
            if (!in_array($field, $keys)) {
                continue;
            }
            switch ($field) {
                case 'mobile_code':
                case 'wechat':
                    $data[$field] = \Yii::$app->params['domain'] . $val;
                    break;
                default:
                    $data[$field] = $val;
            }
        }
        return $data;
    }
}