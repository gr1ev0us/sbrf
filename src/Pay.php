<?php
/**
 * Created by PhpStorm.
 * User: grievous
 * Date: 26.04.16
 * Time: 22:39
 */

namespace QFive\gr1ev0us\SBRF;


final class Pay extends Action implements Interfaces\IPay
{

    protected static $actionUrl = 'register.do';


    /** @inherit */
    protected static $errors = [
        0 => 'Обработка запроса прошла без системных ошибок',
        1 => 'Заказ с таким номером уже зарегистрирован в системе3 Неизвестная (запрещенная) валюта',
        4 => 'Отсутствует обязательный параметр запроса',
        5 => 'Ошибка значение параметра запроса',
        7 => 'Системная ошибка',
    ];

    /**
     * Регистрирует заказ в платежном шлюзе сбера
     * @param string $orderNumber номер заказа в системе магазина
     * @param float $amount сумма заказа
     * @param string $returnUrl ссылка на страницу успешной оплаты
     * @param string $currency валюта заказа
     * @param string $failUrl ссылка на страницу неудачи
     * @param string $description описание заказа
     * @return array Информация о заказе от сбербанка
     * @throws ActionExceptions
     */
    public static function orderRegister(
        $orderNumber,
        $amount,
        $returnUrl,
        $currency = '',
        $failUrl = '',
        $description = ''
    ) : array
    {
        $query = [
            'userName'    => Config::gI()->userName,
            'password'    => Config::gI()->password,
            'orderNumber' => $orderNumber,
            'amount'      => $amount,
            'returnUrl'   => $returnUrl,
            'failUrl'     => $failUrl,
            'description' => $description,
            'language'    => Config::gI()->language,
            'pageView'    => Config::gI()->pageView,
        ];

        $response = self::request($query);

        static::checkForErrors($response);

        return $response;

    }

    /**
     * Проверяет, успешен ли заказ. Если указана ссылка, то отправляет редирект
     * @param string $redirectUrl ссылка на страницу результата
     * @param string $extOrderNumber номер заказа Сбербанк
     * @return bool
     */
    public static function isApprovedPay(
        $extOrderNumber,
        $redirectUrl = ''
    ) : bool
    {
        $statusResponse = static::getStatus($extOrderNumber);
        return $statusResponse['orderStatus'] === 2;
    }


    /**
     * Проверяет, заригистрирован ли заказ. Если указана ссылка, то отправляет редирект
     * @param string $redirectUrl ссылка на страницу результата
     * @param string $extOrderNumber номер заказа Сбербанк
     * @return bool
     */
    public static function isRegistered(
        $extOrderNumber,
        $redirectUrl = ''
    ) : bool
    {
        $statusResponse = static::getStatus($extOrderNumber);
        return $statusResponse['OrderStatus'] === 0;
    }


}