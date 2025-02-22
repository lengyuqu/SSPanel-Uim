<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

// Config is singleton instance store all config
final class Config
{
    private static $instnace = null;
    private $kv = [];
    private function __construct()
    {
    }

    public static function getConfigInstance(): Config
    {
        if (! isset(self::$instnace)) {
            self::$instnace = new static();

            $settings = Setting::all();

            foreach ($settings as $setting) {
                self::$instnace->kv[$setting->item] = $setting->value;
            }
        }

        return self::$instnace;
    }

    public static function getConf($key): mixed
    {
        return self::getConfigInstance()->getConfigCache($key);
    }

    // TODO: remove
    public static function get($key)
    {
        return $_ENV[$key];
    }

    public static function getPublicConfig()
    {
        $public_configs = Setting::getPublicConfig();

        // 鉴于还未完成配置的全面数据库化，先这么用着

        return [
            'version' => VERSION,
            'appName' => $_ENV['appName'],
            'baseUrl' => $_ENV['baseUrl'],
            // 充值
            'stripe_min_recharge' => $public_configs['stripe_min_recharge'],
            'stripe_max_recharge' => $public_configs['stripe_max_recharge'],
            // 客服系统
            'live_chat' => $public_configs['live_chat'],
            'tawk_id' => $public_configs['tawk_id'],
            'crisp_id' => $public_configs['crisp_id'],
            'livechat_id' => $public_configs['livechat_id'],
            'mylivechat_id' => $public_configs['mylivechat_id'],
            // 联系方式
            'enable_admin_contact' => $public_configs['enable_admin_contact'],
            'admin_contact1' => $public_configs['admin_contact1'],
            'admin_contact2' => $public_configs['admin_contact2'],
            'admin_contact3' => $public_configs['admin_contact3'],
            // 验证码
            'captcha_provider' => $public_configs['captcha_provider'],
            'enable_reg_captcha' => $public_configs['enable_reg_captcha'],
            'enable_login_captcha' => $public_configs['enable_login_captcha'],
            'enable_checkin_captcha' => $public_configs['enable_checkin_captcha'],
            'enable_reset_password_captcha' => $public_configs['enable_reset_password_captcha'],
            // 注册
            'register_mode' => $public_configs['reg_mode'],
            'enable_email_verify' => $public_configs['reg_email_verify'],
            'enable_reg_im' => $public_configs['enable_reg_im'],
            'min_port' => $public_configs['min_port'],
            'max_port' => $public_configs['max_port'],
            'invite_price' => $public_configs['invite_price'],
            'custom_invite_price' => $public_configs['custom_invite_price'],
            // 邀请
            'invite_get_money' => $public_configs['invitation_to_register_balance_reward'],
            'invite_gift' => $public_configs['invitation_to_register_traffic_reward'],
            'code_payback' => $public_configs['rebate_ratio'],
            // EPay
            'epay_alipay' => $public_configs['epay_alipay'],
            'epay_wechat' => $public_configs['epay_wechat'],
            'epay_qq' => $public_configs['epay_qq'],
            'epay_usdt' => $public_configs['epay_usdt'],
            // 待处理
            'enable_checkin' => $_ENV['enable_checkin'],
            'checkinMin' => $_ENV['checkinMin'],
            'checkinMax' => $_ENV['checkinMax'],

            'jump_delay' => $_ENV['jump_delay'],
            'enable_analytics_code' => $_ENV['enable_analytics_code'],
            'enable_ticket' => $_ENV['enable_ticket'],

            'enable_kill' => $_ENV['enable_kill'],
            'enable_change_email' => $_ENV['enable_change_email'],

            'enable_telegram' => $_ENV['enable_telegram'],
            'telegram_bot' => $_ENV['telegram_bot'],

            'enable_telegram_login' => $_ENV['enable_telegram_login'],

            'subscribeLog' => $_ENV['subscribeLog'],
            'subscribeLog_show' => $_ENV['subscribeLog_show'],
            'subscribeLog_keep_days' => $_ENV['subscribeLog_keep_days'],

            'enable_auto_detect_ban' => $_ENV['enable_auto_detect_ban'],
            'auto_detect_ban_type' => $_ENV['auto_detect_ban_type'],
            'auto_detect_ban_number' => $_ENV['auto_detect_ban_number'],
            'auto_detect_ban_time' => $_ENV['auto_detect_ban_time'],
            'auto_detect_ban' => $_ENV['auto_detect_ban'],

            'sentry_dsn' => ! isset($_ENV['sentry_dsn']) ? $_ENV['sentry_dsn'] : null,
        ];
    }

    public static function getDbConfig()
    {
        return [
            'driver' => $_ENV['db_driver'],
            'host' => $_ENV['db_host'],
            'unix_socket' => $_ENV['db_socket'],
            'database' => $_ENV['db_database'],
            'username' => $_ENV['db_username'],
            'password' => $_ENV['db_password'],
            'charset' => $_ENV['db_charset'],
            'collation' => $_ENV['db_collation'],
            'prefix' => $_ENV['db_prefix'],
        ];
    }

    public static function getMuKey()
    {
        $muKeyList = \array_key_exists('muKeyList', $_ENV) ? $_ENV['muKeyList'] : ['　'];
        return array_merge(explode(',', $_ENV['muKey']), $muKeyList);
    }

    public static function getSupportParam($type)
    {
        switch ($type) {
            case 'ss_aead_method':
                return [
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305',
                ];
            case 'ss_obfs':
                return [
                    'simple_obfs_http',
                    'simple_obfs_http_compatible',
                    'simple_obfs_tls',
                    'simple_obfs_tls_compatible',
                ];
            case 'ss_2022':
                return [
                    '2022-blake3-aes-128-gcm',
                    '2022-blake3-aes-256-gcm',
                    '2022-blake3-chacha20-poly1305',
                ];
            default:
                return [
                    'rc4-md5',
                    'rc4-md5-6',
                    'aes-128-cfb',
                    'aes-192-cfb',
                    'aes-256-cfb',
                    'aes-128-ctr',
                    'aes-192-ctr',
                    'aes-256-ctr',
                    'camellia-128-cfb',
                    'camellia-192-cfb',
                    'camellia-256-cfb',
                    'bf-cfb',
                    'cast5-cfb',
                    'des-cfb',
                    'des-ede3-cfb',
                    'idea-cfb',
                    'rc2-cfb',
                    'seed-cfb',
                    'salsa20',
                    'chacha20',
                    'xsalsa20',
                    'chacha20-ietf',
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305',
                    'none',
                    '2022-blake3-aes-128-gcm',
                    '2022-blake3-aes-256-gcm',
                    '2022-blake3-chacha20-poly1305',
                ];
        }
    }

    private function getConfigCache($key): mixed
    {
        if (isset($kv[$key])) {
            return $kv[$key];
        }

        return null;
    }
}
