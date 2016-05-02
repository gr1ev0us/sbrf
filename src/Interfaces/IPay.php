<?php

namespace QFive\gr1ev0us\SBRF\Interfaces;

interface IPay
{


    /**
     * Регистрирует заказ в платежном шлюзе сбера
     * @param string $orderNumber номер заказа в системе магазина
     * @param float $amount сумма заказа
     * @param string $returnUrl ссылка на страницу успешной оплаты
     * @param string $currency валюта заказа
     * @param string $failUrl ссылка на страницу неудачи
     * @param string $description описание заказа
     * @return array Информация о заказе от сбербанка
     * @throws \QFive\gr1ev0us\SBRF\Exception

     */
    public static function orderRegister(
        $orderNumber,
        $amount,
        $returnUrl,
        $currency = '',
        $failUrl = '',
        $description = ''
    ) : array ;

    /**
     * Проверяет, успешен ли заказ. Если указана ссылка, то отправляет редирект
     * @param string $redirectUrl ссылка на страницу результата
     * @param string $extOrderNumber номер заказа Сбербанк
     * @return bool
     */
    public static function isApprovedPay(
        $extOrderNumber,
        $redirectUrl = ''
    ) : bool;

    /**
     * Получает статус заказа сбербанка
     * @param string $extOrderNumber номер заказа Сбербанк
     * @return array
     */
    public static function getStatus(
        $extOrderNumber
    ) : array;

}