<?php
include_once 'setting.inc.php';
include_once 'metrics.inc.php';

$_lang['modsizecontrol'] = 'Контроль размера сайта';
$_lang['modsizecontrol_desc'] = 'Виджет для отображения размера сайта или отдельной папки';

// TODO: Переделать

$_lang['modsizecontrol_total'] = 'Общий размер файлов:';
$_lang['modsizecontrol_available'] = 'Доступно:';
$_lang['modsizecontrol_limit_out_header'] = 'Превышен лимит объёма сайта';
$_lang['modsizecontrol_limit_out_text'] = 'Обратитесь к разработчику для расширения пакета обслуживания.';
$_lang['modsizecontrol_refresh'] = 'Обновить данные';
$_lang['modsizecontrol_load_text'] = 'Загрузка';

$_lang['modsizecontrol_err_error'] = 'Ошибка';
$_lang['modsizecontrol_err_class_exist'] = 'Не загружен класс';
$_lang['modsizecontrol_err_filesystem_not_specified'] = 'Не заданы источники файлов';
$_lang['modsizecontrol_err_filesystem_not_get'] = 'Не удалось получить источники';
$_lang['modsizecontrol_err_directory_size'] = 'Проблема с получением размеров директорий';
$_lang['modsizecontrol_err_cache_setting'] = 'Не найдена кэш-настройка';
$_lang['modsizecontrol_err_save_setting'] = 'Не удалось сохранить настройку';
$_lang['modsizecontrol_err_frequent_update'] = 'Слишком частый запрос обновления';
$_lang['modsizecontrol_err_file_exist'] = 'Не передан массив с данными файла';

$_lang['modsizecontrol_success_update'] = 'Обновление завершено успешно';