<?php

/* Main page */
$_['heading_title']                 = 'Messor Security';
$_['name_left_column']              = 'Sécurité';

// window modal

$_['subscription_license']                  = 'Licence';
$_['subscription_license_accept']           = 'J\'accepte';
$_['directory_is_not_writable']             = 'Le répertoire n\'est pas accessible en écriture';
$_['file_clean']                            = 'La taille du fichier est supérieure à 3 mégaoctets, cela peut ralentir le travail';
$_['preloader_text']                        = 'Chargement';
$_['error_save_settings']                   = 'Erreur, vérifiez les permissions en écriture';
$_['save_settings']                         = 'Réglages sauvegardés';
$_['system_settings_cloudflare_on']         = 'Attention, Messor a détecté que vous avez activé CloudFlare. Le support de CloudFlare est une option.';
$_['system_settings_cloudflare_off']        = 'Attention, Messor a détecté  que vous avez interrompu l\’utilisation de CloudFlare. l\’option de compatibilité toujours est active. Si vous n\’utilisez plus CloudFlare, désactivez cette option!.';
$_['system_settings_cloudflare_disabled']   = 'Désactiver';
$_['enter_ip_adress']                       = 'Entrez l\'adresse IP';

// Peer Info

$_['peer_info_status']             = 'Statut';
$_['peer_info_trust']              = 'Confiance';
$_['peer_info_name']               = 'Nom';
$_['peer_info_company']            = 'Entreprise';
$_['peer_info_phone']              = 'Téléphone';
$_['peer_info_lang']               = 'Langue';
$_['peer_info_date_reg']           = 'Date d\’enregistrement';
$_['peer_info_last_online']        = 'Derniere connexion';
$_['peer_info_last_data']          = 'Dernières données';
$_['peer_info_client_ver']         = 'Version du client';
$_['peer_info_net_id']             = 'ID de réseau';
$_['peer_info_encr_alg']           = 'Algorithme de cryptage';
$_['peer_info_encr_key']           = 'Clé de cryptage';
$_['peer_info_user_agent']         = 'Agent utilisateur';

// Trust

$_['mod_trust_domain']         = 'Domain';
$_['mod_trust_dns']            = 'Vérifier DNS';
$_['mod_trust_email']          = 'Envoyer l\’email de confirmation';
$_['mod_trust_sms']            = 'Envoyer SMS de confirmation';
$_['mod_trust_phone_call']     = 'Appel téléphonique';
$_['mod_trust_call']           = 'Confirmer l\’appel';
$_['mod_trust_about']          = 'Confirmer l\’appel';
$_['mod_trust_about_request']  = 'Demander la vérification de documents';
$_['mod_trust_confirmed']      = 'Confirmé';
$_['mod_trust_check']          = 'Vérifier';
$_['mod_trust_wait']           = 'Attendez';
$_['mod_trust_confirm_call']   = 'Demander à être rappelé';


// Server list

$_['server_list_online']       = 'En ligne';
$_['server_list_country']      = 'Pays';
$_['server_list_server']       = 'Serveur';
$_['server_list_city']         = 'Ville';
$_['server_list_active']       = 'Actif';

// 3 blocks

$_['block_peer_status']             = 'Statut';
$_['block_peer_trust']              = 'Confiance';
$_['block_server_hash']             = 'Hachage';
$_['block_server_servers']          = 'Serveurs';
$_['block_server_last_sync']        = 'Dernière synchronisation';
$_['block_database_version']        = 'Version';
$_['block_database_update']         = 'Mise à jour';

// Main Settings

$_['block_main_setting']                                    = 'Réglage principaux';
$_['block_main_setting_ip_firewall']                        = 'IP du pare-feu';
$_['block_main_setting_ip_firewal_text']                    = 'Bloquer l\’ip depuis la base de données et listes noires de Messor';
$_['block_main_setting_lock_settings']                      = 'Verrouiler les réglages';
$_['block_main_setting_ip_firewall_add_ip']                 = 'Ajouter l\’IP des attaquant à une liste noire temporaire d\’IP après';
$_['block_main_setting_ip_firewall_add_ip_attemps']         = 'tentatives';
$_['block_main_setting_useragent_firewall']                 = 'Agent utilisateur du pare-feu';
$_['block_main_setting_useragent_firewall_text']            = 'Bloquer l\’utilisateur depuis la base de données des agent utilisateurs depuis la base de données de Messor.';
$_['block_main_setting_useragent_firewall_block_attacks']   = 'Blocage des attaques par Agent Utilisateur';
$_['block_main_setting_useragent_firewall_block_engines']   = 'Bloquer les robots des moteurs de recherche';
$_['block_main_setting_useragent_firewall_block_tools']     = 'Outils de blocage';
$_['block_main_setting_useragent_firewall_block_all']       = 'Bloquer TOUT (Outils/robots/Moteurs de recherche)';
$_['block_main_setting_traff_analyzer']                     = 'Analyseur de trafic';
$_['block_main_setting_traff_analyzer_text']                = 'Bloquer les utilisateurs selon les chaînes de caractère GET/POST de blocage de la base de données messor.';
$_['block_main_setting_traff_analyzer_get']                 = 'GET - Analyse les données GET et bloque les requêtes dangereuses';
$_['block_main_setting_traff_analyzer_post']                = 'POST - Analyse les données POST et bloque les requêtes dangereuses';
$_['block_main_setting_traff_analyzer_cookie']              = 'COOKIE -  Analyse les données COOKIE et bloque les requêtes dangereuses';
$_['block_main_setting_ddos']                               = 'Protection Bot | DDoS';
$_['block_main_setting_ddos_text']                          = 'Bloquer les attaques DDoS';

// Lock setting

$_['block_lock_setting']                 = 'Réglage des blocages';
$_['block_lock_setting_error_code']      = 'Code erreur';
$_['block_lock_setting_error_code_text'] = 'Page non trouvée';
$_['block_lock_setting_redirect']        = 'Rediriger';
$_['block_lock_setting_block_page']      = 'Bloquer la page';
$_['block_lock_setting_js_unlock']       = 'Déverrouillage JS';
$_['block_lock_setting_js_unlock_text']  = 'Vérifie si le navigateur du visiteur est réel, et s\'il l\'est alors l\'adresse IP des visiteurs est débloquée.';


// i button 

$_['i_button_ip_firewall']      = 'Cette option est responsable du blocage/déblocage en fonction des adresses IP,
                                    y compris celles de la base de données et de vos listes d\'autorisation / de blocage.
                                    Ici, vous pouvez configurer les options d\'actions à réaliser contre les visiteurs bloqués et le nombre d\'attaques détectées, après quoi l\'adresse IP des utilisateurs sera bloquée.';

$_['i_button_user_agent_firewall']  = 'Cette option permet la détection et le blocage des bots en fonction de l\'agent utilisateur.
                                        Vous pouvez choisir la catégorie des attaques des robots de recherche que vous souhaitez bloquer.';

$_['i_button_traffic_analyzer']  = 'Cette option permet au système de filtrage du trafic de détecter les tentatives d\'intrusion.
                                        Vous pouvez configurer les méthodes qui seront filtrées : GET / POST / COOKIE';

$_['i_button_ddos']  = 'Lorsque cette option est activée, toutes les actions automatisées, robots et autres activités suspectes seront bloquées.
                                    Chaque visiteur sera contrôlé avant d\'accéder au site';
$_['i_button_lock_settings_block_ddos']  = 'Des paramètres de blocage supplémentaires sont disponibles uniquement lorsque l\'option de blocage DDoS est désactivée';


// Statistic

$_['block_statistic']                      = 'Statistiques';
$_['block_statistic_attack_blocked']       = 'Attaques bloquées';
$_['block_statistic_sync_list']            = 'Liste de synchronisation';
$_['block_statistic_last_update']          = 'Dernière mise à jour';

// Settings

$_['block_settings']                          = 'Réglages';
$_['block_settings_detect_list']              = 'Liste de detection';
$_['block_settings_detect_list_text']         = 'Liste d\'IP ou de réseaux bloqués.';
$_['block_settings_allow_list']               = 'Liste blanche';
$_['block_settings_allow_list_text']          = 'Liste d\'IP ou de réseaux autorisés.';
$_['block_settings_sync_list']                = 'Liste de synchronisation';
$_['block_settings_sync_list_text']           = 'Liste des attaques non synchronisées.';
$_['block_settings_sync_archive']             = 'Archives';
$_['block_settings_sync_archive_text']        = 'Lister toutes les attaques détectées.';
$_['block_settings_server_list']              = 'Liste de serveurs';
$_['block_settings_server_list_text']         = 'Liste des serveurs du réseau Messor.';
$_['block_settings_peer_list']                = 'Liste de pairs';
$_['block_settings_peer_list_text']           = 'Liste des pairs du réseau Messor.';
$_['block_settings_connection_log']           = 'Journaliser les connections';
$_['block_settings_connection_log_text']      = 'Journaliser les communications p2p.';
$_['block_settings_error_log']                = 'Journaliser les erreurs';
$_['block_settings_error_log_text']           = 'Journaliser les ereurs système.';
$_['block_settings_sync_log']                 = 'Journal de synchronisation';
$_['block_settings_sync_log_text']            = 'Synchronisation des journaux des serveurs Messor.';

// Last detect

$_['block_last_detect']             = 'Dernière détection';
$_['block_last_detect_sync_list']   = '(liste de synchronisation)';
$_['block_last_detect_type']        = 'Type';
$_['block_last_detect_time']        = 'Durée'; /* Or "heure".... it depends if it is for a laps time, a duration, so in this case it is "Durée" and if it is to tell indicate a time when an event took place, for example, "12:14PM", in this case, you have to use "Heure". By défault I supposed it was the duration of the scan, so I put Durée. Change it according to what you want to use it for */
$_['block_last_detect_path']        = 'Chemin';
$_['block_last_detect_remove']      = 'Supprimer';
$_['block_last_detect_more']        = 'Plus';
$_['block_last_detect_action']      = 'Action';
$_['block_last_detect_days']        = 'Jours';
$_['block_last_detect_search']      = 'Chercher IP';
$_['block_last_detect_adress']      = 'IP adresse';

// button

$_['button_save']                  = 'Sauvegarder';
$_['button_cancel']                = 'Annuler';
$_['button_ok']                    = 'Ok';
$_['button_remove']                = 'Retirer';
$_['button_reset_all_settings']    = 'Réinitialiser les réglages';
$_['button_additional_settings']   = 'Réglages supplémenaires';


/* Malware Cleaner */

// main

$_['mcl_description']                  = 'Analyse de fichiers pour la détection de malware';
$_['mcl_setting_professional']         = 'Réglages professionels';
$_['mcl_button_scan']                  = 'Débuter le scan';
$_['mcl_setting_global_text']          = 'Réglages généraux';
$_['mcl_setting_max_detect']           = 'Détections max';
$_['mcl_setting_max_file_size']        = 'Taille max de fichier';
$_['mcl_setting_exclude_scan_file']    = 'Exclure le fichier du scan';
$_['mcl_setting_php_settings']         = 'Réglages PHP';
$_['mcl_setting_extension']            = 'Extension';
$_['mcl_setting_check_file_size']      = 'Vérifier la taille des fichiers';
$_['mcl_setting_yes']                  = 'Oui';
$_['mcl_setting_my_signatures']        = 'Mes signatures';
$_['mcl_setting_cgi_settings']         = 'Réglages CGI';
$_['mcl_scanning']                     = 'Scan';
$_['mcl_i_max_detect']                 = "Le nombre maximum de détections après lequel un fichier est considéré comme malicieux" ;
$_['mcl_i_max_file_size']              = "Taille maximale de fichier pour la vérification" ;
$_['mcl_i_exclude_file_scan']          = "Exclure le fichier du scan" ;
$_['mcl_i_list_extensions_file']       = "Liste d'extensions de fichiers à scanner" ;
$_['mcl_i_check_file_size']            = "Ignorer les fichiers dont la taille est supérieure à la taille de fichier maximale définie dans les réglages généraux";
$_['mcl_i_add_signatures']             = "Ajoutez les signatures que vous jugez potentiellement dangereuses";

// result

$_['mcl_detect_path']                 = 'Malwarte détecté sur ce chemin';
$_['mcl_detect_path_no']              = 'Aucun malwarte détecté sur ce chemin';
$_['mcl_detect']                      = 'Malware detecté';
$_['mcl_detect_no']                   = 'Aucun malwarte détecté';
$_['mcl_path']                  	  = 'chemin';
$_['mcl_status']                  	  = 'status';
$_['mcl_status_first_letter']         = 'Statut';
$_['mcl_database']                    = 'base de données';
$_['mcl_database_version']            = 'Version';
$_['mcl_scan_time']                   = 'durée de scan';
$_['mcl_dangerous_files']             = 'Fichier dangereux';
$_['mcl_file_type']            		  = 'Type de fichier';
$_['mcl_danger_path']            	  = 'Chemin';
$_['mcl_danger_total']            	  = 'Total';
$_['mcl_danger_comment']              = 'Commenter';
$_['mcl_danger_show']         		  = 'Montrer';
$_['mcl_danger_detects']         	  = 'Detectés';
$_['mcl_danger_remove']            	  = 'Suupprimer';
$_['mcl_statistics']            	  = 'Statistiques';
$_['mcl_statistics_checked']          = 'Vérifié';
$_['mcl_statistics_found']            = 'Trouvé';
$_['mcl_statistics_danger']           = 'Danger';
$_['mcl_statistics_big_files']        = 'Fichiers volumineux';
$_['mcl_statistics_skip_files']       = 'Ignorer les fichiers';
$_['mcl_statistics_sym_links']        = 'Liens symboliques';
$_['mcl_statistics_error_opendir']    = 'Erreur à l\'ouverture du répertoire';
$_['mcl_skipped_exclude']             = 'Fichiers ignorés et exclus';
$_['mcl_symlink']            		  = 'Lien symbolique';
$_['mcl_symlink_link']            	  = 'Lien';
$_['mcl_big_files_skipped']           = 'Fichiers volumineux (Ignorer)';
$_['mcl_skip_files_skipped']          = 'Ignorer et exclure les fichiers';
$_['mcl_errors_dirs']            	  = 'Répertoires des erreurs';
$_['mcl_i_big_files_skipped']         = 'Fichiers dont la taille est supérieure à la taille maximale autorisée pour le scan';
$_['mcl_i_symlink_files']             = 'Liens symboliques vers les fichiers';
$_['mcl_i_skip_files']                = 'Fichiers ignorés ou figurant sur une liste d\'exclusion';
$_['mcl_i_error_open']                = 'Fichiers qui ne peuvent pas être ouverts';

/* File System Control */

// main

$_['fsc_max_detected']                 = 'Le nombre maximum de détections après lequel un fichier est considéré comme malveillant';
$_['fsc_prev_make_fs']                 = 'Réaliser l\'image';
$_['fsc_description']                  = 'Créer une image des fichiers système';
$_['fsc_make_fs']                      = 'Réaliser une image  FS';
$_['fsc_setting_professional']         = 'Réglages professionels';
$_['fsc_setting_global_text']          = 'Réglages généraux';
$_['fsc_setting_exclude_scan_file']    = 'Exclure le fichier du scan';

//result 

$_['fsc_one_shot']                     = 'Félicitations ! Vous avez fait le premier instantané';
$_['fsc_changed_files_found']          = 'Fichier modifié trouvé';
$_['fsc_changed_files_no_found']       = 'Aucun fichier modifié trouvé';
$_['fsc_checked']                      = 'Vérifié';
$_['fsc_changed']                      = 'Modifié';
$_['fsc_new']                          = 'Nouveau';
$_['fsc_remove']                       = 'Supprimér';
$_['fsc_removed']                      = 'Supprimés';
$_['fsc_excluded']                     = 'Exlcuds';
$_['fsc_date']                         = 'date';
$_['fsc_new_files']                    = 'Nouveaux fichiers';
$_['fsc_removed_files']                = 'Fichiers supprimés';
$_['fsc_changed_files']                = 'Fichiers modifiés';
$_['fsc_changed_not_files']            = 'Aucun changement de fichier';
$_['fsc_excluded_files']               = 'Fichiers exclus';
$_['fsc_path']                         = 'chemin';
$_['fsc_choose']                       = 'Cgoisir';
$_['fsc_type']                         = 'Type';
$_['fsc_status']                       = 'statut';
$_['fsc_time_scan']                    = 'Durée de scan';
$_['fsc_button_exclude']               = 'Exclure';
$_['fsc_button_remove']                = 'Supprimer';
$_['fsc_action_selected']              = 'Action avec selection';

/* Backup */

// main

$_['backup_path']                      = 'Chemin';
$_['backup_descript']                  = 'Création de la sauvegarde des fichiers système ou de la base de données';
$_['backup_file']                      = 'Sauvegarde FS';
$_['backup_database']                  = 'Sauvegardes de la base de donnée';
$_['backup_file_database']             = 'Sauvegarde FS+base de données';
$_['backup_button_database']           = 'Sauvegardes';
$_['backup_setting_professional']      = 'Réglages professionnels';
$_['backup_archiving_setting']         = 'Réglages d\'archivage';
$_['backup_file_name']                 = 'Nom de fichier';
$_['backup_pack']                      = 'Pack';
$_['backup_settings']                  = 'Réglages des sauvegardes';
$_['backup_select_table']              = 'Selectionner la table table';
$_['backup_i_select_table']            = 'Sélectionnez les tables dont le contenu sera vidé';
$_['backup_select_all']                = 'Selectionner tout';
$_['backup_remove_select']             = 'Retirer selection';
$_['backup_action']                    = 'Action';
$_['backup_download']                  = 'Téléchargement';
$_['backup_send_of_email']             = 'Envoi d\'email';
$_['backup_send_of_messor']            = 'Envoyé sur le serveur de Messor';
$_['backup_save_on_server']            = 'Sauvegardé sur le serveur';
$_['backup_exclude_directory']         = 'Exclure les répertoires de la sauvegarde';

// smtp

$_['backup_smtp_settings_host']        = 'Hôte';
$_['backup_smtp_settings_port']        = 'Port';
$_['backup_smtp_settings_login']       = 'le login';
$_['backup_smtp_settings_password']    = 'Mot de passe';

//setting

$_['backup_settings_db_setting']       = 'Paramétrage de la base de données';
$_['backup_settings_name']             = 'Nom';
$_['backup_settings_host']             = 'Hôte';
$_['backup_settings_user']             = 'Utilisateur';
$_['backup_settings_password']         = 'Mot de passe';

/* File system check */

// main

$_['fscheck_descript']                     = 'vérification des permissions de fichier';
$_['fscheck_setting_professional']         = 'Réglages professionnels';
$_['fscheck_setting_global_text']          = 'Réglages généraux';
$_['fscheck_setting_exclude_scan_file']    = 'Exclure le fichier du scan';
$_['fscheck_i_exclude_file_scan']          = "Exclure des fichiers du scan" ;
$_['fscheck_scan']                         = 'Scan';

// result

$_['fscheck_bad_permision']                = 'Mauvaise autorisation';
$_['fscheck_statistic']                    = 'Statistique';
$_['fscheck_checked_files']                = 'Fichiers vérifiés';
$_['fscheck_sym_links']                    = 'Liens symboliques';
$_['fscheck_dir']                          = 'Dossiers';
$_['fscheck_files']                        = 'Fichiers';

/* Security settings */

// main

$_['ss_protect_admin_dir']            = 'Protection du répertoire d\'administration';
$_['ss_protect_admin_dir_disc']       = 'Dans le cas d\'une installation par défaut, n\'importe qui peut accéder au répertoire admin en utilisant l\'URL admin. Ainsi, afin d\'empêcher tout accès non autorisé à des fichiers aussi importants, vous devez modifier cette URL d\'accès à l\'administration en la personnalisant.';
$_['ss_instruction']                  = 'Instruction';
$_['ss_change_login']                 = 'Changer l\'idetifiant par défaut';
$_['ss_i_change_login']               = 'Identifiant et mot de passe standard pour pirater votre site'; /* Is it correct? is it really about the login to "hack", or "access"? it's not the same, a hack is an attack, an access is... simple entry on the website. If you talk about the login to manipulate ftp file, it would be better to talk about ftp... for this translation, I would need explaination... because, for example, it's an information I couldn't give you because I don't understand it*/
$_['ss_change_login']                 = 'Changer l\'identifiant par défaut';
$_['ss_change_login_group']           = 'Groupe';
$_['ss_change_login_login']           = 'Identifiant';
$_['ss_change_login_name']            = 'Nom';
$_['ss_change_login_edit']            = 'Editer';
$_['ss_check_perm']                   = 'Vérification des permissions d\'accès aux fichiers et dossiers';
$_['ss_check_permissions']            = 'Permissions';
$_['ss_i_check_perm']                 = 'définis de manière incorrecte les droits sur les fichiers et les répertoires, permettent à un attaquant d\'accéder aux fichiers du site';
$_['ss_path']                         = "Chemin" ;
$_['ss_update']                       = 'Mise à jour';
$_['ss_update_version']               = 'Mise à jour d\'OpenCart disponible';
$_['ss_update_aval_version']          = 'Nouvelle version disponible';
$_['ss_update_last_version']          = 'Dernière version';
$_['ss_update_del_dir']               = 'Effacement du dossier d\'installation';
$_['ss_update_del_dir_disc']          = 'Une fois l\'installation terminée, vous devez supprimer le dossier d\'installation. Si le dossier d\'installation est toujours présent, n\'importe qui pourra accéder au dossier et relancer une installation, qui écrasera votre site Web.';
$_['ss_settings']                     = 'Réglages d\'OpenCart';
$_['ss_db_pref']                      = 'Préfixe de base de données par défaut';
$_['ss_disable_error']                = 'Désactiver l\'affichage d\'erreurs';
$_['ss_status']                       = 'Statut';
$_['ss_storage']                      = 'Emplacement déplacé du répertoire de stockage';
$_['ss_mysql_root']                   = 'Vérification de l\'identifiant de la base de donnée';
$_['ss_version_messor']               = 'Vérification de la version de Messor';


/* Insall */

$_['install_completed']               = 'Installation achevée';
$_['install_congratulations']         = 'Vous êtes devenu membre à part entière de Messor. Toutes nos félicitations';
$_['install_user_data']               = 'Informations sur l\'utilisateur';
$_['install_register']                = 'Si vous n\'avez pas de compte Messor, vous pouvez vous enregistrer sur';
$_['install_account']                 = 'compte';
$_['install_user_email']              = 'Email utilisateur';
$_['install_user_password']           = 'Mot de passe utilisateur';
$_['install_support']                 = 'Assistance';
$_['install_agree']                   = 'je suis d\'accord avec l\''; /* It dpends on the word that follow. For the end of the sentence, this can change. If it's a word that starts with a vowel, then it's "l\'", if it's a masculine word then it's "le" and if it's a feminine word then it's "la" and for a plural word then use "les". For example, if the next word is "accord", then it should be "je suis d'accord avec l\'accord...". But, it's incorrect to say that in french. It's right, from a grammatical point of view, but it's not said, it's a repetition... you could say "I give my consent" (="Je donne mon accord", or you could use another sentence : "I accept the license agreement" (="J\'accepte l\'accord de licence"). In the last case, that is the most correct in french, you could use "j\'accepte l\'" for the line 346*/
$_['install_agree_license']           = 'Accord de licence ';
$_['install_agree_user']              = 'Accord de l\'utilisateur';
$_['install_agree_and']               = '&';
$_['install_about']                   = 'Au sujet de';
$_['install_about_desc']              = 'Merci de bien vouloir remplir ces formulaires afin de confirmer votre profil. Ces informations sont confidentielles et ne seront cédées à aucun tiers';
$_['install_politics']                = 'Consulter notre politique de confidentialité';
$_['install_name']                    = 'Nom';
$_['install_phone']                   = 'Téléphone';
$_['install_company']                 = 'Entreprise';
$_['install_country']                 = 'Pays';
$_['install_lang']                    = 'Langue';
$_['install_about_company']           = 'Concernant l\'entreprise';
$_['install_setting']                 = 'Réglages';
$_['install_setting_desc']            = 'Modifiez les réglages pro si vous êtes sûr de ce que vous faites. Cryptage et paramètres du serveur';
$_['install_server']                  = 'Serveur';
$_['install_encr_alg']                = 'Algorithme de cryptage';
$_['install_net_pass']                = 'Mot de passe de réseau';
$_['install_rand_data']               = 'Donnée alléatoires';
$_['install_start']                   = 'Débuter l\'installation';
$_['placehold_name']                  = 'Pierre Dupont';
$_['placehold_company']               = 'Mon entreprise';
$_['placehold_random']                = 'Saisissez n\'importe quel symbole pour générer une clé';


// button

$_['btn_close']                = 'Fermer';
$_['btn_save']                 = 'Sauvegarder';
$_['btn_send']                 = 'Envoyer';
$_['btn_delete']               = 'Supprimer';
$_['btn_add_ip']               = 'Ajouter';

// link 

$_['btn_pers_account']                 = 'Accéder à votre compte';

// upgrade

$_['page_upgrade_text']                = 'Oups ... Il semblerait que vous ayez besoin de promouvoir votre plan';
$_['page_upgrade_link_plan']           = 'votre plan';
$_['page_upgrade_upgrade']             = 'Promouvoir';

$_['modal_title_attention'] = 'Attention';
$_['block_main_setting_useragent_search_engines'] = 'Lorsque cette option est activée, votre site sera indisponible pour les moteurs de recherche.';
$_['block_main_setting_useragent_social'] = 'Lorsque cette option est activée, votre site sera indisponible pour les réseaux sociaux.';
$_['block_settings_signatures'] = 'Signatures';
$_['block_settings_signatures_subtitle'] = 'Paramètres des signatures';
$_['signatures_list_append'] = 'Blocage';
$_['signatures_list_except'] = 'Exceptions';
$_['signature_will_be_removed'] = 'La signature sera supprimée';
$_['delete_unsaved_data'] = 'Supprimer les données non enregistrées';

$_['block_all_attention'] = 'Attention, lorsque cette option est activée, tous les robots/bots seront bloqués, y compris les moteurs de recherche et les réseaux sociaux.
Votre site ne sera pas indexé par les moteurs de recherche.';
$_['block_main_setting_useragent_firewall_block_social'] = 'Bloquer les réseaux sociaux et les robots de messagerie';
$_['block_attempts'] = 'tentatives';
$_['block_days'] = 'journées';

// cron

$_['cron_instruction_tooltip'] = 'Ajouter cette tâche à cron';

//universal

$_['login_forgot_your_password'] = 'Mot de passe oublié';
$_['login_login_placeholder'] = 'Connexion';
$_['login_password_placeholder'] = 'Mot de passe';
$_['login_sign_in'] = 's\'identifier';
$_['login_for_messor'] = 'pour Messor';
$_['login_banner_title'] = 'Rejoindre un réseau rapide et sécurisé maintenant';
$_['login_banner_subtitle'] = 'Messor est un ensemble de scripts pour détecter et bloquer diverses attaques réseau';

$_['side_menu_educational_videos'] = 'Vidéos éducatives';