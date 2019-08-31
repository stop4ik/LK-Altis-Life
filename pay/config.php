<?php

class Config
{
    // Настроек от проекта в личном кабинете free-kassa.ru
    const MERCHANT_ID = '';
    const SECRET_KEY_1 = '';
    const SECRET_KEY_2 = '';
    
    // Стоимость товара в руб. т.е. сколько будет стоить 1 DonatMoney. По умолчанию 1 Donatmoney = 1 руб
    const ITEM_PRICE = 1;

    // Таблица начисления товара, например `users`
    const TABLE_ACCOUNT = 'players';
    // Название поля из таблицы начисления товара по которому производится поиск аккаунта/счета, например `email`
    const TABLE_ACCOUNT_NAME = 'playerid';
    // Название поля из таблицы начисления товара которое будет увеличено на колличево оплаченого товара, например `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= 'EPoint';

    // Параметры соединения с бд
    // Хост
    const DB_HOST = '';
    // Имя пользователя
    const DB_USER = '';
    // Пароль
    const DB_PASS = '';
    // Назывние базы
    const DB_NAME = '';
}