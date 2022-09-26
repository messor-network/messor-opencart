<?php

namespace src\Config;

/**
 * Messor constants
 */
class Path
{
    const USERCONF = BASE_PATH . "/data/config.txt";
    const VERSION = "2.1";
    const VERSION_BD = BASE_PATH . "/data/version.txt";
    const LICENSE = BASE_PATH . "/data/license.txt";
    const UPDATE  = BASE_PATH . "/data/update.txt";
    const SERVERS = BASE_PATH . "/data/servers.txt";
    const SPEED_SERVERS = BASE_PATH . "/data/speed_servers.txt";
    const WHITE_LIST = BASE_PATH . "/data/white.txt";
    const SYNC_LIST = BASE_PATH . "/data/sync.txt";
    const DETECT_LIST = BASE_PATH . "/data/detect_list.txt";
    const ARCHIVE = BASE_PATH . "/data/archive.txt";
    const DB_TREE = BASE_PATH . "/data/database/tree/";
    const DB_IPTABLES = BASE_PATH . "/data/database/iptables.txt";
    const DB_HTACCESS = BASE_PATH . "/data/database/htaccess.txt";
    const DB_IPLIST = BASE_PATH . "/data/database/iplist.txt";
    const PATH_TLIST = BASE_PATH . "/data/tmp/";
    const PATH_DATABASE = BASE_PATH . "/data/database/";
    const PEERS = BASE_PATH . "/data/peers.txt";
    const ERROR = BASE_PATH . "/data/log/error.txt";
    const PEER_LOG = BASE_PATH . "/data/log/peer.txt";
    const SYNC_LOG = BASE_PATH . "/data/log/sync.txt";
    const SYNC_LAST = BASE_PATH . "/data/log/last_sync.txt";
    const INFO = BASE_PATH . "/data/info.txt";
    const SETTINGS = BASE_PATH . "/data/settings.txt";
    const SYSTEM_SETTINGS = BASE_PATH . "/data/system_settings.txt";
    const ATTACK_URL = BASE_PATH . "/data/database/attack_url.txt";
    const RULES = BASE_PATH . "/data/database/rules.txt";
    const DAY = BASE_PATH . "/data/day.txt";
    const IPHASH = BASE_PATH . "/data/ip/";
    const SIGNATURE = BASE_PATH . "/data/signature.txt";
}
