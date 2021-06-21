<?php
/**
 * @link https://github.com/engine-core/config-db-setting
 * @copyright Copyright (c) 2021 engine-core
 * @license BSD 3-Clause License
 */

declare(strict_types=1);

namespace EngineCore\config\db\setting;

use EngineCore\Ec;
use EngineCore\extension\repository\info\ConfigInfo;
use Yii;
use yii\web\Application;

class Info extends ConfigInfo
{

    const EXT_RAND_CODE = 'xSbzVD_';

    protected $id = 'config-db-setting';

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        return [
            'container' => [
                'definitions' => [
                    'SettingProvider' => [
                        'class' => 'EngineCore\extension\setting\DbProvider',
                        'tableName' => '{{%' . parent::EXT_RAND_CODE . 'setting}}',
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMigrationPath(): array
    {
        return ['@EngineCore/extension/setting/migrations'];
    }

    /**
     * @inheritdoc
     */
    public function install(): bool
    {
        if (false === parent::install()) {
            return false;
        }

        return Ec::$service->getMigration()->table($this->getMigrationTable())
            ->interactive(false)
            ->path($this->getMigrationPath())
            ->compact(Yii::$app instanceof Application)
            ->up(0);
    }

    /**
     * @inheritdoc
     */
    public function uninstall(): bool
    {
        if (false === parent::uninstall()) {
            return false;
        }

        $res = Ec::$service->getMigration()->table($this->getMigrationTable())
            ->interactive(false)
            ->path($this->getMigrationPath())
            ->compact(Yii::$app instanceof Application)
            ->down('all');
        if ($res) {
            Ec::$service->getMigration()->getMigrate()->db->createCommand()->dropTable($this->getMigrationTable())->execute();
        }

        return $res;
    }

}