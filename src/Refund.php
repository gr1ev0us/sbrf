<?php
/**
 * Created by PhpStorm.
 * User: grievous
 * Date: 27.04.16
 * Time: 20:49
 */

namespace QFive\gr1ev0us\SBRF;


final class Refund extends Action
{
    protected static $actionUrl = 'refund.do';


    /** @inherit */
    protected static $errors = [
        0 => 'Обработка запроса прошла без системных ошибок',
        5 => 'Ошибка значение параметра запроса',
        6 => 'Незарегистрированный OrderId',
        7 => 'Системная ошибка',
    ];

    /**
     * @param string $orderId ID заказа в платежной системе СБРФ
     * @return array
     * @throws Exception
     */
    public static function refund(
        $orderId
    ) : array
    {
        $query = [
            'userName' => Config::gI()->userName,
            'password' => Config::gI()->password,
            'orderId'  => $orderId,
            'language' => Config::gI()->currency,
        ];

        $response = self::request($query);

        static::checkForErrors($response);

        return $response;

    }

    /**
     * Проверяет, возвращен ли заказ. Если указана ссылка, то отправляет редирект
     * @param string $redirectUrl ссылка на страницу результата
     * @param string $extOrderNumber номер заказа Сбербанк
     * @return bool
     */
    public static function isRefunded(
        $extOrderNumber,
        $redirectUrl = ''
    ) : bool
    {
        $statusResponse = static::getStatus($extOrderNumber);
        return $statusResponse['OrderStatus'] === 4;
    }

}