<?php

namespace app\components;

use yii\base\Component;

/**
 * Class Constant
 * @package app\components
 */
class Constant extends Component
{
    public function __construct(array $config = [])
    {

        //DB Access
        DEFINE("DB_HOST_NAME", 'mysql');
        DEFINE("DB_USER_NAME", 'root');
        DEFINE("DB_PASSWORD", '');


        DEFINE("WEB_ROOT", __DIR__ . '/../');
		DEFINE("WEB_FOLDER_PATH", WEB_ROOT . 'web/');

        DEFINE("SITE_URL", 'http://localhost/sathsang/web');
		DEFINE("DOMAIN_NAME", parse_url(SITE_URL, PHP_URL_HOST));
		DEFINE("PORTAL_NAME", 'Sathsang');
		DEFINE("HEADER_LOGO_NAME", 'SS');
		
		DEFINE("SITE_HOST_NAME", 'http://localhost/sathsang/web');
		DEFINE('COOKIE_EXPIRY_TIME', 43200);

        DEFINE("DB_HD", "hd");



        parent::__construct($config);
    }
}
