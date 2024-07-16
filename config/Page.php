<?php

namespace config;

class Page
{
    public const LINK_HOMEPAGE = '/';
    public const LINK_TABLE = '/table';
    public const LINK_FORM = '/form';


    public const LINKS = [
        'Домашняя страница' => self::LINK_HOMEPAGE,
        'Таблица' => self::LINK_TABLE,
        'Форма добавления в таблицу' => self::LINK_FORM,
    ];
}