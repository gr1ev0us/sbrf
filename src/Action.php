<?php
/**
 * Created by PhpStorm.
 * User: grievous
 * Date: 27.04.16
 * Time: 20:41
 */

namespace QFive\gr1ev0us\SBRF;

use GuzzleHttp\Client;
use Monolog\Logger;

abstract class Action
{

    /** @var string Адрес экшена Сбербанка */
    protected static $actionUrl;

    protected static $statusUrl = 'getOrderStatusExtended.do';


    /** @var array Ошибки */
    protected static $errors = [];

    /** @var array Возможные статусы заказа */
    protected static $orderStatuses = [
        0 => 'Заказ зарегистрирован, но не оплачен',
        1 => 'Предавторизованная сумма захолдирована (для двухстадийных платежей)',
        2 => 'Проведена полная авторизация суммы заказа',
        3 => 'Авторизация отменена',
        4 => 'По транзакции была проведена операция возврата',
        5 => 'Инициирована авторизация через ACS банка-эмитента',
        6 => 'Авторизация отклонена',
    ];
    /**
     * Основной метод действия
     */
    public function do()
    {
    }

    /**
     * Метод запроса к серверу
     * @param array $query массив параметров запроса
     * @param null|string $url целевой URL
     * @return array|object
     * @param [] $query массив параметров GET запроса
     */
    protected static function request($query, $url = null)
    {
        $client = new Client([
            'base_uri' => Config::gI()->baseUrl,
            'timeout'  => Config::gI()->timeout
        ]);
        $url = ifNoEmpty($url, static::$actionUrl);
        $response = $client->request('GET', $url, [
            'query' => $query
        ]);

        $ret = $response->getBody()->getContents();
        return json_decode($ret, true);


    }

    protected static function checkForErrors($response){
        if(is_object($response)){
            $response = (array) $response;
        }
        if(isset($response['errorCode']) && intval($response['errorCode'])!== 0){
            $response['errorCode'] = intval($response['errorCode']);
            if(array_key_exists($response['errorCode'], static::$errors)){
                throw new ActionExceptions(static::$errors[$response['errorCode']], $response['errorCode']);
            }
            throw new ActionExceptions("Неизвестная ошибка", -1);
        }
    }


    /**
     * Получает статус заказа сбербанка
     * @param string $orderId номер заказа Сбербанк
     * @return array
     */
    public static function getStatus(
        $orderId
    ) : array
    {
        $query = [
            'userName' => Config::gI()->userName,
            'password' => Config::gI()->password,
            'orderId'  => $orderId,
            'language' => Config::gI()->currency,
        ];

        $response = self::request($query, static::$statusUrl);

        static::checkForErrors($response);



        return $response;
    }

}
class ActionExceptions extends \Exception{}