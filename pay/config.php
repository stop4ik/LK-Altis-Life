<?php

class Config
{
    // �������� �� ������� � ������ �������� free-kassa.ru
    const MERCHANT_ID = '';
    const SECRET_KEY_1 = '';
    const SECRET_KEY_2 = '';
    
    // ��������� ������ � ���. �.�. ������� ����� ������ 1 DonatMoney. �� ��������� 1 Donatmoney = 1 ���
    const ITEM_PRICE = 1;

    // ������� ���������� ������, �������� `users`
    const TABLE_ACCOUNT = 'players';
    // �������� ���� �� ������� ���������� ������ �� �������� ������������ ����� ��������/�����, �������� `email`
    const TABLE_ACCOUNT_NAME = 'playerid';
    // �������� ���� �� ������� ���������� ������ ������� ����� ��������� �� ��������� ���������� ������, �������� `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= 'EPoint';

    // ��������� ���������� � ��
    // ����
    const DB_HOST = '';
    // ��� ������������
    const DB_USER = '';
    // ������
    const DB_PASS = '';
    // �������� ����
    const DB_NAME = '';
}