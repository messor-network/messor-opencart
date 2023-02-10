<?php

$_['heading_title']                 = 'Messor Security';
$_['name_left_column']              = 'Безопасность';
/* Main page */

// window modal

$_['subscription_license']                  = 'Лицензия';
$_['subscription_license_accept']           = 'Принять';
$_['directory_is_not_writable']             = 'Директория не доступна для записи';
$_['file_clean']                            = 'Размер файла более 3 мегабайт, это может замедлить работу';
$_['preloader_text']                        = 'Загрузка';
$_['save_settings']                         = 'Настройки сохранены';
$_['error_save_settings']                   = 'Ошибка.Проверьте права на запись';
$_['system_settings_cloudflare_on']         = 'Внимание, Messor определил, что у Вас включен CloudFlare. Для совместной работы Включена опция поддержки CloudFlare.';
$_['system_settings_cloudflare_off']        = 'Внимание, Messor определил, что Вы перестали использовать CloudFlare. Однако у вас включена опция совместимости. Если Вы не используете CloudFlare, отключите эту опцию!';
$_['system_settings_cloudflare_disabled']   = 'Отключить';
$_['notice_not_all_fields_filled']          = 'Не все поля заполнены';
$_['enter_ip_adress']                       = 'Введите IP-адрес';

// Peer Info

$_['peer_info_status']             = 'Статус';
$_['peer_info_trust']              = 'Доверие';
$_['peer_info_name']               = 'Имя';
$_['peer_info_company']            = 'Компания';
$_['peer_info_phone']              = 'Телефон';
$_['peer_info_lang']               = 'Язык';
$_['peer_info_date_reg']           = 'Дата регистарции';
$_['peer_info_last_online']        = 'Последний раз онлайн';
$_['peer_info_last_data']          = 'Последние данные';
$_['peer_info_client_ver']         = 'Версия клиента';
$_['peer_info_net_id']             = 'Сетевой id';
$_['peer_info_encr_alg']           = 'Алгоритм шифрования';
$_['peer_info_encr_key']           = 'Ключ шифрования';
$_['peer_info_user_agent']         = 'User agent';

// Trust

$_['mod_trust_domain']         = 'Домен';
$_['mod_trust_dns']            = 'Проверка DNS';
$_['mod_trust_email']          = 'Отправить email';
$_['mod_trust_sms']            = 'Отправить SMS';
$_['mod_trust_phone_call']     = 'Тел. звонок';
$_['mod_trust_call']           = 'Позвонить';
$_['mod_trust_about']          = 'Принять звонок';
$_['mod_trust_about_request']  = 'Запросить проверку документов';
$_['mod_trust_confirmed']      = 'Подтверждено';
$_['mod_trust_check']          = 'Проверить';
$_['mod_trust_wait']           = 'Ожидайте';
$_['mod_trust_confirm_call']   = 'Заказать обратный звонок';

// Server list

$_['server_list_online']       = 'Онлайн';
$_['server_list_country']      = 'Страна';
$_['server_list_server']       = 'Сервер';
$_['server_list_city']         = 'Город';
$_['server_list_active']       = 'Активный';

// 3 blocks

$_['block_peer_status']             = 'Статус';
$_['block_peer_trust']              = 'Доверие';
$_['block_server_hash']             = 'Хеш';
$_['block_server_servers']          = 'Серверы';
$_['block_server_last_sync']        = 'Sync';
$_['block_database_version']        = 'Версия';
$_['block_database_update']         = 'Обновление';

// Main Settings

$_['block_main_setting']                                    = 'Главные настройки';
$_['block_main_setting_ip_firewall']                        = 'IP Брандмауэр';
$_['block_main_setting_ip_firewal_text']                    = 'Блокировка по IP из базы Messor и детект списка';
$_['block_main_setting_lock_settings']                      = 'Настройка блокировки';
$_['block_main_setting_ip_firewall_add_ip']                 = 'Добавить атaкующий IP в детект список после';
$_['block_main_setting_ip_firewall_add_ip_attemps']         = 'попыток';
$_['block_main_setting_useragent_firewall']                 = 'User Agent Брандмауэр';
$_['block_main_setting_useragent_firewall_text']            = 'Блокировка пользователя по useragent из базы messor.';
$_['block_main_setting_useragent_firewall_block_attacks']   = 'Блокировка атакующего по user agent';
$_['block_main_setting_useragent_firewall_block_engines']   = 'Блокировка поисковых ботов';
$_['block_main_setting_useragent_firewall_block_tools']     = 'Блокировка сетевых утилит';
$_['block_main_setting_useragent_firewall_block_all']       = 'Блокировка всех (утилиты/боты/поисковые движки)';
$_['block_main_setting_traff_analyzer']                     = 'Анализатор трафика';
$_['block_main_setting_traff_analyzer_text']                = 'Блокировка по GET/POST запросам из базы Messor.';
$_['block_main_setting_traff_analyzer_get']                 = 'GET - Анализ GET данных и блокировка опасных запросов';
$_['block_main_setting_traff_analyzer_post']                = 'POST - Анализ POST данных и блокировка опасных запросов';
$_['block_main_setting_traff_analyzer_cookie']              = 'COOKIE - Анализ COOKIE данных и блокировка опасных запросов';
$_['block_main_setting_ddos']                               = 'Bot | DDoS Защита';
$_['block_main_setting_ddos_text']                          = 'Блокировка DDoS атак';

// Lock setting

$_['block_lock_setting']                 = 'Настройка блокировки';
$_['block_lock_setting_error_code']      = 'Код ошибки';
$_['block_lock_setting_error_code_text'] = 'Страница не найдена';
$_['block_lock_setting_redirect']        = 'Редирект';
$_['block_lock_setting_block_page']      = 'Страница блокировки';
$_['block_lock_setting_js_unlock']       = 'JS разблокировка';
$_['block_lock_setting_js_unlock_text']  = 'Происходит проверка браузера всех посетителей, если браузер определяется как настоящий, тогда ip адрес посетителя разблокируется .';

// i button 

$_['i_button_ip_firewall']      = 'Эта опция отвечает за блокирование/разблокирование по IP адресу посетителя,
                                    включая адреса из базы данных Messor и Ваши allow/block листы.
                                    Тут вы можете настроить варианты действий для заблокированных посетителей и количество обнаруженных атак , после которых ip адрес посетителя будет заблокирован.';

$_['i_button_user_agent_firewall']  = 'Эта опция отвечает за обнаружение и блокирование ботов по строке UserAgent.
                                        Вы можете выбрать нужную категорию для блокирования, от атак до поисковых ботов';

$_['i_button_traffic_analyzer']  = 'Эта опция  включает систему фильтрации трафика для обнаружения попыток вторжения.
                                    Вы можете настроить ,какие методы будут фильтроваться GET/POST/COOKIE';

$_['i_button_ddos']  = 'При включении данной опции будут заблокированы все автоматические действия, роботы и другая подозрительна активность.
                                    Каждый посетитель будет проверяться перед тем, как получит доступ к сайту.';
$_['i_button_lock_settings_block_ddos']  = 'Дополнительные настройки блокировки доступны только при отключенной опции блокировки DDoS.';


// Statistic

$_['block_statistic']                          = 'Статистика';
$_['block_statistic_attack_blocked']           = 'Атак заблокировано';
$_['block_statistic_sync_list']                = 'Список синхронизации';
$_['block_statistic_last_update']              = 'Обновлено';

// Settings

$_['block_settings']                          = 'Настройки';
$_['block_settings_detect_list']              = 'Список детектов';
$_['block_settings_detect_list_text']         = 'Список заблокированных IP или сетей';
$_['block_settings_allow_list']               = 'Список доверенных адресов';
$_['block_settings_allow_list_text']          = 'Список доверенных IP или сетей.';
$_['block_settings_sync_list']                = 'Список синхронизации';
$_['block_settings_sync_list_text']           = 'Список не синхронизированных атак.';
$_['block_settings_sync_archive']             = 'Архив';
$_['block_settings_sync_archive_text']        = 'Список всех атак.';
$_['block_settings_server_list']              = 'Список серверов';
$_['block_settings_server_list_text']         = 'Список серверов в сети Messor.';
$_['block_settings_peer_list']                = 'Список пиров';
$_['block_settings_peer_list_text']           = 'Список пиров в сети Messor';
$_['block_settings_connection_log']           = 'Лог соединения';
$_['block_settings_connection_log_text']      = 'Лог p2p соединения.';
$_['block_settings_error_log']                = 'Лог ошибок';
$_['block_settings_error_log_text']           = 'Лог системных ошибок.';
$_['block_settings_sync_log']                 = 'Лог синхронизации';
$_['block_settings_sync_log_text']            = 'Лог синхронизаций с серверами Messor.';

// Last detect

$_['block_last_detect']             = 'Последние атаки';
$_['block_last_detect_sync_list']   = '(список синхронизаций)';
$_['block_last_detect_type']        = 'Тип';
$_['block_last_detect_time']        = 'Время';
$_['block_last_detect_path']        = 'Путь';
$_['block_last_detect_remove']      = 'Удалить';
$_['block_last_detect_more']        = 'Подробнее';
$_['block_last_detect_action']      = 'Действие';
$_['block_last_detect_days']        = 'Дни';
$_['block_last_detect_search']      = 'Поиск по IP';
$_['block_last_detect_adress']      = 'IP адрес';

// button

$_['button_save']                  = 'Сохранить';
$_['button_cancel']                = 'Отмена';
$_['button_ok']                    = 'Ok';
$_['button_remove']                = 'Удалить';
$_['button_reset_all_settings']    = 'Сброс настроек';
$_['button_additional_settings']   = 'Дополнительные настройки';

/* Malware Cleaner */

// main

$_['mcl_description']                  = 'Анализ файлов для обнаружения вредоносных программ';
$_['mcl_setting_professional']         = 'Профессиональные настройки';
$_['mcl_button_scan']                  = 'Сканировать';
$_['mcl_setting_global_text']          = 'Общие настройки';
$_['mcl_setting_max_detect']           = 'Макс. детектов';
$_['mcl_setting_max_file_size']        = 'Макс. размер файла';
$_['mcl_setting_exclude_scan_file']    = 'Исключить файлы из сканирования';
$_['mcl_setting_php_settings']         = 'PHP настройки';
$_['mcl_setting_extension']            = 'Расширения';
$_['mcl_setting_check_file_size']      = 'Проверка размера файла';
$_['mcl_setting_yes']                  = 'Да';
$_['mcl_setting_my_signatures']        = 'Свои сигнатуры';
$_['mcl_setting_cgi_settings']         = 'CGI настройки';
$_['mcl_scanning']                     = 'Сканирование';
$_['mcl_i_max_detect']                 = "Максимальное количество детектов, после которых файл считается вредоносным" ;
$_['mcl_i_max_file_size']              = "Максимальный размер файла для которого будет осуществлена проверка" ;
$_['mcl_i_exclude_file_scan']          = "Исключить файлы из сканирования" ;
$_['mcl_i_list_extensions_file']       = "Список расширений файлов ,которые будут проверены" ;
$_['mcl_i_check_file_size']            = "Пропустить файлы ,у которых размер больше ,чем максимальный размер файла в общих настройках";
$_['mcl_i_add_signatures']             = "Добавьте свои сигнатуры для проверки, которые считаете потенциально опасными";


// result

$_['mcl_detect_path']                 = 'Обнаружено вредоносное содержимое по этому пути';
$_['mcl_detect_path_no']              = 'Вредосного содержимого не обнаружено';
$_['mcl_detect']                      = 'Обнаружено вредоносное содержимое';
$_['mcl_detect_no']                   = 'Вредосное содержимое не обнаружено';
$_['mcl_path']                  	  = 'путь';
$_['mcl_status']                  	  = 'статус';
$_['mcl_status_first_letter']         = 'Статус';
$_['mcl_database']                    = 'база данных';
$_['mcl_database_version']            = 'Версия';
$_['mcl_scan_time']                   = 'Время сканирования';
$_['mcl_dangerous_files']             = 'Опасные файлы';
$_['mcl_file_type']            		  = 'Тип файла';
$_['mcl_danger_path']            	  = 'Путь';
$_['mcl_danger_total']            	  = 'Всего';
$_['mcl_danger_comment']              = 'Комментарий';
$_['mcl_danger_show']                 = 'Показать';
$_['mcl_danger_detects']              = 'Обнаруженные';
$_['mcl_danger_remove']            	  = 'Удалить';
$_['mcl_statistics']            	  = 'Статистика';
$_['mcl_statistics_checked']          = 'Проверено';
$_['mcl_statistics_found']            = 'Найдено';
$_['mcl_statistics_danger']           = 'Опасные';
$_['mcl_statistics_big_files']        = 'Большие файлы';
$_['mcl_statistics_skip_files']       = 'Пропущенные файлы';
$_['mcl_statistics_sym_links']        = 'Символические ссылки';
$_['mcl_statistics_error_opendir']    = 'Ошибка открытия директории';
$_['mcl_skipped_exclude']             = 'Пропущенные и исключенные файлы';
$_['mcl_symlink']            		  = 'Символические ссылки';
$_['mcl_symlink_link']            	  = 'Ссылки';
$_['mcl_big_files_skipped']           = 'Большие файлы(пропущенные)';
$_['mcl_skip_files_skipped']          = 'Пропущеные и исключеные файлы';
$_['mcl_errors_dirs']            	  = 'Ошибки открытия директории';
$_['mcl_i_big_files_skipped']         = "Файлы, размер которых превысил максимально допустимый размер для проверки";
$_['mcl_i_symlink_files']             = 'Символические ссылки на файлы';
$_['mcl_i_skip_files']                = 'Файлы, которые были пропущены или находятся в списке исключений';
$_['mcl_i_error_open']                = 'Файлы, которые не удалось открыть';

/* File System Control */

// main
$_['fsc_max_detected']                = 'Максимальное количество обнаружений, после которого файл считается вредоносным';
$_['fsc_prev_make_fs']                = 'Создание снимка';
$_['fsc_description']                 = 'Создание снимка файловой системы';
$_['fsc_make_fs']                     = 'Создать снимок ФС';
$_['fsc_setting_professional']        = 'Профессиональные настройки';
$_['fsc_setting_global_text']         = 'Общие настройки';
$_['fsc_setting_exclude_scan_file']   = 'Исключить файлы из сканирования';

//result 

$_['fsc_one_shot']                     = 'Поздравляем! Вы сделали первый снимок';
$_['fsc_changed_files_found']          = 'Найдены изменённые файлы';
$_['fsc_changed_files_no_found']       = 'Изменённые файлы не найдены';
$_['fsc_checked']                      = 'Проверены';
$_['fsc_changed']                      = 'Изменены';
$_['fsc_new']                          = 'Новые';
$_['fsc_remove']                       = 'Удалить';
$_['fsc_removed']                      = 'Удалены';
$_['fsc_excluded']                     = 'Исключены';
$_['fsc_date']                         = 'дата';
$_['fsc_new_files']                    = 'Новые файлы';
$_['fsc_removed_files']                = 'Удалённые файлы';
$_['fsc_changed_files']                = 'Изменённые файлы';
$_['fsc_changed_not_files']            = 'Нет изменённых файлов';
$_['fsc_excluded_files']               = 'Исключённые файлы';
$_['fsc_path']                         = 'путь';
$_['fsc_choose']                       = 'Выбрать';
$_['fsc_type']                         = 'Тип';
$_['fsc_status']                       = 'статус';
$_['fsc_time_scan']                    = 'время сканирования';
$_['fsc_button_exclude']               = 'Исключить';
$_['fsc_button_remove']                = 'Удалить';
$_['fsc_action_selected']              = 'Действия с выбранными';

/* Backup */

// main

$_['backup_path']                      = 'Путь';
$_['backup_descript']                  = 'Создание бекапа файловой системы или базы данных';
$_['backup_file']                      = 'Бэкап ФС';
$_['backup_database']                  = 'Бэкап базы данных';
$_['backup_file_database']             = 'Бэкап ФС+базы данных';
$_['backup_button_database']           = 'Бэкап';
$_['backup_setting_professional']      = 'Профессиональные настройки';
$_['backup_archiving_setting']         = 'Настройки архивации';
$_['backup_file_name']                 = 'Имя файла';
$_['backup_pack']                      = 'Упаковка';
$_['backup_settings']                  = 'Настройки бекапа';
$_['backup_select_table']              = 'Выбор таблиц';
$_['backup_i_select_table']            = 'Выберите таблицы, содержимое которых будут в дампе';
$_['backup_select_all']                = 'Выбрать все';
$_['backup_remove_select']             = 'Снять выделенные';
$_['backup_action']                    = 'Действие';
$_['backup_download']                  = 'Скачать';
$_['backup_send_of_email']             = 'Отправить на email';
$_['backup_send_of_messor']            = 'Отправить на Messor Server';
$_['backup_save_on_server']            = 'Сохранить на сервере';
$_['backup_exclude_directory']         = 'Исключить директории из резервной копии';

// smtp

$_['backup_smtp_settings_host']        = 'Хост';
$_['backup_smtp_settings_port']        = 'Порт';
$_['backup_smtp_settings_login']       = 'Логин';
$_['backup_smtp_settings_password']    = 'Пароль';

//setting

$_['backup_settings_db_setting']       = 'Настройки базы данных';
$_['backup_settings_name']             = 'Имя';
$_['backup_settings_host']             = 'Хост';
$_['backup_settings_user']             = 'Юзер';
$_['backup_settings_password']         = 'Пароль';

/* File system check */

// main

$_['fscheck_descript']                     = 'Проверка прав на файлы';
$_['fscheck_setting_professional']         = 'Профессиональные настройки';
$_['fscheck_setting_global_text']          = 'Общие настройки';
$_['fscheck_setting_exclude_scan_file']    = 'Исключить файлы';
$_['fscheck_i_exclude_file_scan']          = "Исключить файлы из сканирования" ;
$_['fscheck_scan']                         = 'Сканирование';

// result

$_['fscheck_bad_permision']                = 'Неверные права';
$_['fscheck_statistic']                    = 'Статистика';
$_['fscheck_checked_files']                = 'Проверено файлов';
$_['fscheck_sym_links']                    = 'Символические ссылки';
$_['fscheck_dir']                          = 'Директории';
$_['fscheck_files']                        = 'Файлы';

/* Security settings */

// main


$_['ss_protect_admin_dir']            = 'Защита директории администратора';
$_['ss_protect_admin_dir_disc']       = 'В случае установки по умолчанию ,любой может получить доступ к каталогу администратора, используя URL-адрес администратора. Чтобы предотвратить несанкционированный доступ к таким важным файлам, вам необходимо изменить этот URL-адрес администратора на что-то более индивидуальное.';
$_['ss_instruction']                  = 'Инструкция';
$_['ss_change_login']                 = 'Изменить логин по умолчанию';
$_['ss_i_change_login']               = 'Стандартный логин и пароль подвергает опасности Ваш сайт';
$_['ss_change_login']                 = 'Изменения логина по умолчанию';
$_['ss_change_login_group']           = 'Группа';
$_['ss_change_login_login']           = 'Логин';
$_['ss_change_login_name']            = 'Имя';
$_['ss_change_login_edit']            = 'Редактировать';
$_['ss_check_perm']                   = 'Проверка прав на файлы и директории';
$_['ss_check_permissions']            = 'Права';
$_['ss_i_check_perm']                 = 'Неправильно выставлены права на файлы и каталоги,что позволяет злоумышленнику получить доступ к сайту';
$_['ss_path']                         = "Путь" ;
$_['ss_update']                       = 'Обновления';
$_['ss_update_version']               = 'Обновить OpenCart версию';
$_['ss_update_aval_version']          = 'Доступна новая версия';
$_['ss_update_last_version']          = 'Последняя версия';
$_['ss_update_del_dir']               = 'Удаление директории установки';
$_['ss_update_del_dir_disc']          = 'После завершения установки, Вам необходимо удалить папку установки. Если папка установки все еще присутствует, любой может получить доступ к папке, и после повторного запуска установки, Ваш веб-сайт может быть перезаписан.';
$_['ss_settings']                     = 'OpenCart настройки';
$_['ss_db_pref']                      = 'Нестандартный префикс базы данных';
$_['ss_disable_error']                = 'Отображение ошибок отключено';
$_['ss_status']                       = 'Статус';
$_['ss_storage']                      = 'Перемещена директория storage';
$_['ss_mysql_root']                   = 'Проверка логина базы данных';
$_['ss_version_messor']               = 'Проверка версии Messor';

/* Insall */

$_['install_completed']               = 'Установка завершена';
$_['install_congratulations']         = 'Вы стали полноправным участником сети Messor. Поздравляем!';
$_['install_user_data']               = 'Данные пользователя';
$_['install_register']                = 'Если у вас нет учетной записи Messor Network, Вы можете зарегистрировать ее на';
$_['install_account']                 = 'aккаунт';
$_['install_user_email']              = 'Email пользователя';
$_['install_user_password']           = 'Пароль пользователя';
$_['install_support']                 = 'Поддержка';
$_['install_agree']                   = 'Я согласен с Лицензионным соглашением и Пользовательским соглашением';
$_['install_agree_license']           = 'Лицензионным соглашением';
$_['install_agree_and']               = 'и';
$_['install_agree_user']              = 'Пользовательским соглашением';
$_['install_about']                   = 'Информация';
$_['install_about_desc']              = 'Будем благодарны, если вы заполните эти формы для подтверждения своего профиля. Эта информация является конфиденциальной и не будет передана третьим лицам.';
$_['install_politics']                = 'Политика конфидецильности';
$_['install_name']                    = 'Имя';
$_['install_phone']                   = 'Телефон';
$_['install_company']                 = 'Компания';
$_['install_country']                 = 'Страна';
$_['install_lang']                    = 'Язык';
$_['install_about_company']           = 'О компании';
$_['install_setting']                 = 'Настройки';
$_['install_setting_desc']            = 'Изменяйте Профессиональные настройки только в том случае, если Вы уверены в том, что вы делаете. Настройки шифрования и серверов';
$_['install_server']                  = 'Сервер';
$_['install_encr_alg']                = 'Алгоритм шифрования';
$_['install_net_pass']                = 'Сетевой пароль';
$_['install_rand_data']               = 'Случайные данные';
$_['install_start']                   = 'Начать установку';
$_['placehold_name']                  = 'Иван Иванов';
$_['placehold_company']               = 'РогаКопыта';
$_['placehold_random']                = 'Впишите любые символы  для генерации ключей';

// button

$_['btn_close']                = 'Закрыть';
$_['btn_save']                 = 'Сохранить';
$_['btn_send']                 = 'Отправить';
$_['btn_delete']               = 'Удалить';
$_['btn_add_ip']               = 'Добавить IP';

// link 

$_['btn_pers_account']                 = 'Перейти в личный аккаунт';

// upgrade

$_['page_upgrade_text']                = 'Упс... похоже вам нужно обновить';
$_['page_upgrade_link_plan']           = 'ваш план';
$_['page_upgrade_upgrade']             = 'Обновить';

$_['modal_title_attention'] = 'Внимание';
$_['block_main_setting_useragent_search_engines'] = 'При активации данной опции ,Ваш сайт будет не доступен для поисковых систем.';
$_['block_main_setting_useragent_social'] = 'При активации данной опции ,Ваш сайт будет не доступен для социальных сетей.';
$_['block_settings_signatures'] = 'Сигнатуры';
$_['block_settings_signatures_subtitle'] = 'Настройка сигнатур';
$_['signatures_list_append'] = 'Блокировка';
$_['signatures_list_except'] = 'Исключения';
$_['signature_will_be_removed'] = 'Сигнатура будет удалена';
$_['delete_unsaved_data'] = 'Удалить несохраненные данные';

$_['block_all_attention'] = 'Внимание при включении этой опции будут блокироваться все роботы/боты включая поисковые системы и социальные сети.
Ваш сайт не будет индексироваться поисковыми системами.';
$_['block_main_setting_useragent_firewall_block_social'] = 'Блокировать ботов социальных сетей и мессенджеров';
$_['block_attempts'] = 'попытки';
$_['block_days'] = 'дни';

// cron

$_['cron_instruction_tooltip'] = 'Добавьте эту задачу в cron';

//universal

$_['login_forgot_your_password'] = 'Забыли пароль';
$_['login_login_placeholder'] = 'Логин';
$_['login_password_placeholder'] = 'Пароль';
$_['login_sign_in'] = 'Вход';
$_['login_for_messor'] = 'в Messor';
$_['login_banner_title'] = 'Быстрое и безопасное подключение к сети';
$_['login_banner_subtitle'] = 'Messor — набор скриптов для обнаружения и блокировки различных сетевых атак.';

$_['side_menu_educational_videos'] = 'Обучающие видео';