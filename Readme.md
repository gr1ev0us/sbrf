# Схема работы
1. Нажимая кнопку оплатить, запускается скрипт создания заказа в БД магазина
2. ID заказа, сумма и endpoint (успеха и фейла) передается в метод IPay::orderRegister который создает заказ в Платежном шлюзе СБРФ (get запрос) и отсылает пользователю редирект на полученный от шлюза адрес, вместе со сформированным запросом.
3. Происходит оплата на платежном шлюзе.
3.1. В случае неудачи, пользователя редиректит на endpoint неудачи, в котором статус заказа в БД мазагина меняется на "неудачен".
3.2. В случае успеха, пользователь попадает на endpoint успеха, запуская скрипт, который вызывает метод IPay::isApprovedPay с переданным номером заказ (можно использовать номер заказа магазина, либо номер, полученный от Платежного шлюза СБРФ), который проверяет статус заказа на платежном шлюзе (get запрос).
3.2.1. Если платежный шлюз отклонил оплату, то возвращаем false, меняем статус заказа в БД магазина, либо делаем редирект на соответствующую страницу.
3.2.2. Если платежный шлюз утвердил платеж, то возвращаем true, меняем статус заказа в БД магазина и отправляем пользователя на страницу успеха, либо показываем сообщение об успехе.