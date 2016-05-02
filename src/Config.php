<?php
/**
 * Created by PhpStorm.
 * User: grievous
 * Date: 27.04.16
 * Time: 21:02
 */

namespace QFive\gr1ev0us\SBRF;
use JSMin\JSMin;


/**
 * @property string baseUrl Url для REST запросов
 * @property int timeout Таймаут REST запросов
 * @property string userName имя пользователя
 * @property string password пароль пользователя
 * @property string currency валюта
 * @property string language язык
 * @property string pageView режим отображения
 *
 * @property string testSuccessURL ссылка на страницу успешной оплаты (для тестов)
 * @property string testFailURL ссылка на страницу успешной оплаты (для тестов)
 * @property string testPayedOrderId id оплаченного заказа (для тестов)
 */
final class Config
{
    private $settings = array();
    private static $_instance = null;

    /**
     * Config constructor.
     */
    private function __construct()
    {
        $configFile = __DIR__ . '/configs/config.json';
        $localConfigFile = __DIR__ . '/configs/local.config.json';

        $configs = json_decode(
            JSMin::minify(file_get_contents($configFile)),
            true);
        if (file_exists($localConfigFile)) {
            $localConfig = json_decode(
                JSMin::minify(file_get_contents($localConfigFile)),
                true);
            $configs = array_merge($configs, $localConfig);
        }

        foreach ($configs as $k => $config) {
            $this->{$k} = $config;
        }

    }


    protected function __clone()
    {
    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    static public function gI()
    {
        return self::getInstance();
    }


    public function get()
    {
    }

    private function __wakeup()
    {
    }
}
