<?php

/* Main page */
$_['heading_title']                 = 'Messor Security';
$_['name_left_column']              = 'Security';


// window modal

$_['subscription_license']            = 'License';
$_['subscription_license_accept']           = 'Accept';
$_['directory_is_not_writable']             = 'Directory is not writable';
$_['file_clean']                            = 'The file size is more than 3 megabytes, it can slow down the work';
$_['preloader_text']                        = 'Loading';
$_['error_save_settings']                   = 'Error, check write permissions';
$_['save_settings']                         = 'Settings saved';
$_['system_settings_cloudflare_on']         = 'Warning, Messor has detected that you have CloudFlare enabled. For Collaboration CloudFlare support option included.';
$_['system_settings_cloudflare_off']        = 'Attention, Messor has determined that you have stopped using CloudFlare. However, you have the compatibility option enabled. If you are not using CloudFlare, disable this option!.';
$_['system_settings_cloudflare_disabled']   = 'Disable';
$_['notice_not_all_fields_filled']          = 'Not all fields are filled';
$_['enter_ip_adress']                       = 'Enter IP address';


// Peer Info

$_['peer_info_status']             = 'Status';
$_['peer_info_trust']              = 'Trust';
$_['peer_info_name']               = 'Name';
$_['peer_info_company']            = 'Company';
$_['peer_info_phone']              = 'Phone';
$_['peer_info_lang']               = 'Language';
$_['peer_info_date_reg']           = 'Date of registration';
$_['peer_info_last_online']        = 'Last online';
$_['peer_info_last_data']          = 'Last data';
$_['peer_info_client_ver']         = 'Client version';
$_['peer_info_net_id']             = 'Network id';
$_['peer_info_encr_alg']           = 'Encryption algoritm';
$_['peer_info_encr_key']           = 'Encryption key';
$_['peer_info_user_agent']         = 'User agent';

// Trust

$_['mod_trust_domain']         = 'Domain';
$_['mod_trust_dns']            = 'Check DNS';
$_['mod_trust_email']          = 'Send confirm email';
$_['mod_trust_sms']            = 'Send confirm SMS';
$_['mod_trust_phone_call']     = 'Phone call';
$_['mod_trust_call']           = 'Confirm call';
$_['mod_trust_about']          = 'Confirm call';
$_['mod_trust_about_request']  = 'Request verification of documents';
$_['mod_trust_confirmed']      = 'Confirmed';
$_['mod_trust_check']          = 'Check';
$_['mod_trust_wait']           = 'Wait';
$_['mod_trust_confirm_call']   = 'Request a call back';

// Server list

$_['server_list_online']       = 'Online';
$_['server_list_country']      = 'Country';
$_['server_list_server']       = 'Server';
$_['server_list_city']         = 'City';
$_['server_list_active']       = 'Active';

// 3 blocks

$_['block_peer_status']             = 'Status';
$_['block_peer_trust']              = 'Trust';
$_['block_server_hash']             = 'Hash';
$_['block_server_servers']          = 'Servers';
$_['block_server_last_sync']        = 'Last Sync';
$_['block_database_version']        = 'Version';
$_['block_database_update']         = 'Update';

// Main Settings

$_['block_main_setting']                                    = 'Main settings';
$_['block_main_setting_ip_firewall']                        = 'IP firewall';
$_['block_main_setting_ip_firewal_text']                    = 'Block ip from messor database and black lists.';
$_['block_main_setting_lock_settings']                      = 'Lock settings';
$_['block_main_setting_ip_firewall_add_ip']                 = 'Add IP attackers to a temporary blacklist after';
$_['block_main_setting_ip_firewall_add_ip_attemps']         = 'attemps';
$_['block_main_setting_useragent_firewall']                 = 'User Agent firewall';
$_['block_main_setting_useragent_firewall_text']            = 'Block users by useragent from messor database.';
$_['block_main_setting_useragent_firewall_block_attacks']   = 'Block attacks through user agent';
$_['block_main_setting_useragent_firewall_block_engines']   = 'Block search engines bots';
$_['block_main_setting_useragent_firewall_block_tools']     = 'Block tools';
$_['block_main_setting_useragent_firewall_block_all']       = 'Block ALL (tools/bots/search engines)';
$_['block_main_setting_traff_analyzer']                     = 'Traffic analyzer';
$_['block_main_setting_traff_analyzer_text']                = 'Block users by GET/POST block string from messor database.';
$_['block_main_setting_traff_analyzer_get']                 = 'GET - Analyze GET data and block dangerous requests';
$_['block_main_setting_traff_analyzer_post']                = 'POST - Analyze POST data and block dangerous requests';
$_['block_main_setting_traff_analyzer_cookie']              = 'COOKIE -  Analyze COOKIE data and block dangerous requests';
$_['block_main_setting_ddos']                               = 'Bot | DDoS Protection';
$_['block_main_setting_ddos_text']                          = 'Block DDoS attack';

// Lock setting

$_['block_lock_setting']                 = 'Lock Settings';
$_['block_lock_setting_error_code']      = 'Error code';
$_['block_lock_setting_error_code_text'] = 'Page not found';
$_['block_lock_setting_redirect']        = 'Redirect';
$_['block_lock_setting_block_page']      = 'Block Page';
$_['block_lock_setting_js_unlock']       = 'JS unlock';
$_['block_lock_setting_js_unlock_text']  = 'The browser of all visitors is checked, if the browser is determined as real, then the visitors ip address is unblocked.';


// i button 

$_['i_button_ip_firewall']      = 'This option is responsible for blocking / unblocking by IP address,
                                    including addresses from the database and your allow / block lists.
                                    Here you can configure options for actions for blocked visitors and the number of attacks detected, after which the users IP address will be blocked.';

$_['i_button_user_agent_firewall']  = 'This option is responsible for detecting and blocking bots by the UserAgent line.
                                        You can choose the category you want to block from attacks to search bots.';

$_['i_button_traffic_analyzer']  = 'This option enables a traffic filtering system to detect intrusion attempts.
                                        You can configure which methods will be filtered by GET / POST / COOKIE';

$_['i_button_ddos']  = 'When this option is enabled, all automatic actions, robots and other suspicious activity will be blocked.
                                    Every visitor will be checked before accessing the site';
$_['i_button_lock_settings_block_ddos']  = 'Additional blocking settings are available only when the DDoS blocking option is disabled';


// Statistic

$_['block_statistic']                      = 'Statistic';
$_['block_statistic_attack_blocked']       = 'Attack blocked';
$_['block_statistic_sync_list']            = 'Sync list';
$_['block_statistic_last_update']          = 'Last update';

// Settings

$_['block_settings']                          = 'Settings';
$_['block_settings_detect_list']              = 'List detect';
$_['block_settings_detect_list_text']         = 'List blocked ip or network.';
$_['block_settings_allow_list']               = 'Allow list';
$_['block_settings_allow_list_text']          = 'List allow ip or network.';
$_['block_settings_sync_list']                = 'Sync list';
$_['block_settings_sync_list_text']           = 'List not synchronized attack.';
$_['block_settings_sync_archive']             = 'Archive';
$_['block_settings_sync_archive_text']        = 'List all detected attack.';
$_['block_settings_server_list']              = 'Server list';
$_['block_settings_server_list_text']         = 'List of network servers Messor.';
$_['block_settings_peer_list']                = 'Peer list';
$_['block_settings_peer_list_text']           = 'List peer of network Messor.';
$_['block_settings_connection_log']           = 'Connection log';
$_['block_settings_connection_log_text']      = 'Log about p2p communication.';
$_['block_settings_error_log']                = 'Error log';
$_['block_settings_error_log_text']           = 'Log system error.';
$_['block_settings_sync_log']                 = 'Sync log';
$_['block_settings_sync_log_text']            = 'Log synchronization of servers Messor.';

// Last detect

$_['block_last_detect']             = 'Last detect';
$_['block_last_detect_sync_list']   = '(sync list)';
$_['block_last_detect_type']        = 'Type';
$_['block_last_detect_time']        = 'Time';
$_['block_last_detect_path']        = 'Path';
$_['block_last_detect_remove']      = 'Remove';
$_['block_last_detect_more']        = 'More';
$_['block_last_detect_action']      = 'Action';
$_['block_last_detect_days']        = 'Days';
$_['block_last_detect_search']      = 'Search IP';
$_['block_last_detect_adress']      = 'IP address';

// button

$_['button_save']                  = 'Save';
$_['button_cancel']                = 'Cancel';
$_['button_ok']                    = 'Ok';
$_['button_remove']                = 'Remove';
$_['button_reset_all_settings']    = 'Reset all settings';
$_['button_additional_settings']   = 'Additional settings';


/* Malware Cleaner */

// main

$_['mcl_description']                  = 'File analysis to detect malware types';
$_['mcl_setting_professional']         = 'Professional settings';
$_['mcl_button_scan']                  = 'Start scan';
$_['mcl_setting_global_text']          = 'Global settings';
$_['mcl_setting_max_detect']           = 'Max detect';
$_['mcl_setting_max_file_size']        = 'Max file size';
$_['mcl_setting_exclude_scan_file']    = 'Exclude scan file';
$_['mcl_setting_php_settings']         = 'PHP settings';
$_['mcl_setting_extension']            = 'Extension';
$_['mcl_setting_check_file_size']      = 'Check file size';
$_['mcl_setting_yes']                  = 'Yes';
$_['mcl_setting_my_signatures']        = 'My signatures';
$_['mcl_setting_cgi_settings']         = 'CGI settings';
$_['mcl_scanning']                     = 'Scanning';
$_['mcl_i_max_detect']                 = "The maximum number of detections after which a file is considered malicious";
$_['mcl_i_max_file_size']              = "The maximum file size for which the check will be performed";
$_['mcl_i_exclude_file_scan']          = "Exclude file from scanning";
$_['mcl_i_list_extensions_file']       = "List of file extensions to scan";
$_['mcl_i_check_file_size']            = "Skips files that are larger than the maximum file size from the general settings";
$_['mcl_i_add_signatures']             = "Add your signatures that you think are potentially dangerous";

// result

$_['mcl_detect_path']                 = 'Malware detected this path';
$_['mcl_detect_path_no']              = 'No malware detected this path';
$_['mcl_detect']                      = 'Malware detected';
$_['mcl_detect_no']                   = 'No malware detected';
$_['mcl_path']                  	  = 'path';
$_['mcl_status']                  	  = 'status';
$_['mcl_status_first_letter']         = 'Status';
$_['mcl_database']                    = 'database';
$_['mcl_database_version']            = 'Version';
$_['mcl_scan_time']                   = 'scan time';
$_['mcl_dangerous_files']             = 'Dangerous files';
$_['mcl_file_type']            		  = 'File type';
$_['mcl_danger_path']            	  = 'Path';
$_['mcl_danger_total']            	  = 'Total';
$_['mcl_danger_comment']              = 'Comment';
$_['mcl_danger_show']         		  = 'Show';
$_['mcl_danger_detects']         	  = 'Detects';
$_['mcl_danger_remove']            	  = 'Remove';
$_['mcl_statistics']            	  = 'Statistics';
$_['mcl_statistics_checked']          = 'Checked';
$_['mcl_statistics_found']            = 'Found';
$_['mcl_statistics_danger']           = 'Danger';
$_['mcl_statistics_big_files']        = 'Big files';
$_['mcl_statistics_skip_files']       = 'Skip files';
$_['mcl_statistics_sym_links']        = 'Sym links';
$_['mcl_statistics_error_opendir']    = 'Error open dir';
$_['mcl_skipped_exclude']             = 'Skipped and exclude files';
$_['mcl_symlink']            		  = 'Symlink';
$_['mcl_symlink_link']            	  = 'Link';
$_['mcl_big_files_skipped']           = 'Big files(skipped)';
$_['mcl_skip_files_skipped']          = 'Skip and Exclude Files';
$_['mcl_errors_dirs']            	  = 'Errors dirs';
$_['mcl_i_big_files_skipped']         = 'Files whose size has exceeded the maximum size allowed for scanning';
$_['mcl_i_symlink_files']             = 'Symbolic links to files';
$_['mcl_i_skip_files']                = 'Files that were skipped or are on the exclusion list';
$_['mcl_i_error_open']                = 'Files that could not be opened';

/* File System Control */

// main

$_['fsc_max_detected']                 = 'The maximum number of detections after which a file is considered malicious';
$_['fsc_prev_make_fs']                 = 'Make snapshot';
$_['fsc_description']                  = 'Create a snapshot of the file system';
$_['fsc_make_fs']                      = 'Make FS Shot';
$_['fsc_setting_professional']         = 'Professional settings';
$_['fsc_setting_global_text']          = 'Global settings';
$_['fsc_setting_exclude_scan_file']    = 'Exclude scan file';

//result 

$_['fsc_one_shot']                     = 'Congratulations! You made the first snapshot';
$_['fsc_changed_files_found']          = 'Changed files found';
$_['fsc_changed_files_no_found']       = 'Changed files no found';
$_['fsc_checked']                      = 'Checked';
$_['fsc_changed']                      = 'Changed';
$_['fsc_new']                          = 'New';
$_['fsc_remove']                       = 'Remove';
$_['fsc_removed']                      = 'Removed';
$_['fsc_excluded']                     = 'Exlcuded';
$_['fsc_date']                         = 'date';
$_['fsc_new_files']                    = 'New files';
$_['fsc_removed_files']                = 'Removed files';
$_['fsc_changed_files']                = 'Changed files';
$_['fsc_changed_not_files']            = 'No changed files';
$_['fsc_excluded_files']               = 'Excluded files';
$_['fsc_path']                         = 'path';
$_['fsc_choose']                       = 'Choose';
$_['fsc_type']                         = 'Type';
$_['fsc_status']                       = 'status';
$_['fsc_time_scan']                    = 'time scan';
$_['fsc_button_exclude']               = 'Exlcude';
$_['fsc_button_remove']                = 'Remove';
$_['fsc_action_selected']              = 'Action with selected';

/* Backup */

// main

$_['backup_path']                      = 'Path';
$_['backup_descript']                  = 'Creating a backup of the file system or database';
$_['backup_file']                      = 'Backup FS';
$_['backup_database']                  = 'Backup database';
$_['backup_file_database']             = 'Backup FS+database';
$_['backup_button_database']           = 'Backup';
$_['backup_setting_professional']      = 'Professional settings';
$_['backup_archiving_setting']         = 'Archiving settings';
$_['backup_file_name']                 = 'File name';
$_['backup_pack']                      = 'Pack';
$_['backup_settings']                  = 'Backup settings';
$_['backup_select_table']              = 'Select table';
$_['backup_i_select_table']            = 'Select the tables whose contents will be dumped';
$_['backup_select_all']                = 'Select all';
$_['backup_remove_select']             = 'Remove selection';
$_['backup_action']                    = 'Action';
$_['backup_download']                  = 'Download';
$_['backup_send_of_email']             = 'Send of email';
$_['backup_send_of_messor']            = 'Send of Messor server';
$_['backup_save_on_server']            = 'Save on server';
$_['backup_exclude_directory']         = 'Exclude directories from backup';

// smtp

$_['backup_smtp_settings_host']        = 'Host';
$_['backup_smtp_settings_port']        = 'Port';
$_['backup_smtp_settings_login']       = 'Login';
$_['backup_smtp_settings_password']    = 'Password';

//setting

$_['backup_settings_db_setting']       = 'Database settings';
$_['backup_settings_name']             = 'Name';
$_['backup_settings_host']             = 'Host';
$_['backup_settings_user']             = 'User';
$_['backup_settings_password']         = 'Password';

/* File system check */

// main

$_['fscheck_descript']                     = 'file permissions check';
$_['fscheck_setting_professional']         = 'Professional settings';
$_['fscheck_setting_global_text']          = 'Global settings';
$_['fscheck_setting_exclude_scan_file']    = 'Exclude scan file';
$_['fscheck_i_exclude_file_scan']          = "Exclude file from scanning";
$_['fscheck_scan']                         = 'Scan';

// result

$_['fscheck_bad_permision']                = 'Bad permision';
$_['fscheck_statistic']                    = 'Statistic';
$_['fscheck_checked_files']                = 'Checked files';
$_['fscheck_sym_links']                    = 'Sym links';
$_['fscheck_dir']                          = 'Directory';
$_['fscheck_files']                        = 'Files';

/* Security settings */

// main

$_['ss_protect_admin_dir']            = 'Protecting the administrator directory';
$_['ss_protect_admin_dir_disc']       = 'In the case of a default installation, anyone can access the admin directory by using the admin URL. Thus, in order to prevent unauthorized access to such important files, you need to change this admin URL to something more customized.';
$_['ss_instruction']                  = 'Instruction';
$_['ss_change_login']                 = 'Change default login';
$_['ss_i_change_login']               = 'Standart login and password hack your site';
$_['ss_change_login']                 = 'Change default login';
$_['ss_change_login_group']           = 'Group';
$_['ss_change_login_login']           = 'Login';
$_['ss_change_login_name']            = 'Name';
$_['ss_change_login_edit']            = 'Edit';
$_['ss_check_perm']                   = 'Check permissions of files and folders';
$_['ss_check_permissions']            = 'Permissions';
$_['ss_i_check_perm']                 = 'incorrectly set rights to files and directories, allows an attacker to gain access to the site';
$_['ss_path']                         = "Path";
$_['ss_update']                       = 'Update';
$_['ss_update_version']               = 'Update OpenCart version';
$_['ss_update_aval_version']          = 'Avalible new version';
$_['ss_update_last_version']          = 'Last version';
$_['ss_update_del_dir']               = 'Deleting install folder';
$_['ss_update_del_dir_disc']          = 'Once you complete the installation, you need to delete the install folder. If the install folder is still present, anyone can access the folder and once they re-launch the installation, it can overwrite your website.';
$_['ss_settings']                     = 'OpenCart settings';
$_['ss_db_pref']                      = 'Default databse perfix';
$_['ss_disable_error']                = 'Disable show error';
$_['ss_status']                       = 'Status';
$_['ss_storage']                      = 'Moved the storage directory';
$_['ss_mysql_root']                   = 'Check database login';
$_['ss_version_messor']               = 'Check Messor version';


/* Insall */

$_['install_completed']               = 'Installation completed';
$_['install_congratulations']         = 'You have become a full member of Messor. Congratulations';
$_['install_user_data']               = 'User data';
$_['install_register']                = 'If you do not have a Messor Network Account, you can register it at';
$_['install_account']                 = 'account';
$_['install_user_email']              = 'User email';
$_['install_user_password']           = 'User password';
$_['install_support']                 = 'Support';
$_['install_agree']                   = 'I agree to the';
$_['install_agree_license']           = 'License agreement ';
$_['install_agree_user']              = 'User agreement';
$_['install_agree_and']               = '&';
$_['install_about']                   = 'About';
$_['install_about_desc']              = 'We will be grateful if you fill out these forms to confirm your profile. This information is confidential and will not be transferred to third parties';
$_['install_politics']                = 'View Privacy Policy';
$_['install_name']                    = 'Name';
$_['install_phone']                   = 'Phone';
$_['install_company']                 = 'Company';
$_['install_country']                 = 'Country';
$_['install_lang']                    = 'Language';
$_['install_about_company']           = 'About company';
$_['install_setting']                 = 'Setting';
$_['install_setting_desc']            = 'Change the professional settings if you are sure of what you are doing. Encryption and server settings';
$_['install_server']                  = 'Server';
$_['install_encr_alg']                = 'Encryption Algoritm';
$_['install_net_pass']                = 'Network password';
$_['install_rand_data']               = 'Random data';
$_['install_start']                   = 'Start install';
$_['placehold_name']                  = 'John Doe';
$_['placehold_company']               = 'My company';
$_['placehold_random']                = 'Enter any symbols to generate keys';


// button

$_['btn_close']                = 'Close';
$_['btn_save']                 = 'Save';
$_['btn_send']                 = 'Send';
$_['btn_delete']               = 'Delete';
$_['btn_add_ip']               = 'Add IP';

// link 

$_['btn_pers_account']                 = 'Go to your personal account';

// upgrade

$_['page_upgrade_text']                = 'Oops ... Looks like you need to upgrade';
$_['page_upgrade_link_plan']           = 'your plan';
$_['page_upgrade_upgrade']             = 'Upgrade';

$_['modal_title_attention'] = 'Attention';
$_['block_main_setting_useragent_search_engines'] = 'When this option is activated, your site will be unavailable for search engines.';
$_['block_main_setting_useragent_social'] = 'When this option is activated, your site will be unavailable for social networks.';
$_['block_settings_signatures'] = 'Signatures';
$_['block_settings_signatures_subtitle'] = 'Signature settings';
$_['signatures_list_append'] = 'Blocking';
$_['signatures_list_except'] = 'Exceptions';
$_['signature_will_be_removed'] = 'Signature will be removed';
$_['delete_unsaved_data'] = 'Delete unsaved data';

$_['block_all_attention'] = 'Attention, when this option is enabled, all robots/bots will be blocked, including search engines and social networks.
Your site will not be indexed by search engines.';
$_['block_main_setting_useragent_firewall_block_social'] = 'Block social network and messenger bots';
$_['block_attempts'] = 'attempts';
$_['block_days'] = 'days';

// cron

$_['cron_instruction_tooltip'] = 'Add this task to cron';

//universal

$_['login_forgot_your_password'] = 'Forgot your password';
$_['login_login_placeholder'] = 'Login';
$_['login_password_placeholder'] = 'Password';
$_['login_sign_in'] = 'Sign in';
$_['login_for_messor'] = 'for Messor';
$_['login_banner_title'] = 'Fast and secure network join now';
$_['login_banner_subtitle'] = 'Messor is a set of scripts to detect and block various network attacks';

$_['side_menu_educational_videos'] = 'Educational videos';