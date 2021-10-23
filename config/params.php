<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    DEFINE("DB_HOST_NAME", 'mysql'),
    DEFINE("DB_USER_NAME", 'root'),
    DEFINE("DB_PASSWORD", ''),


    DEFINE("WEB_ROOT", __DIR__ . '/../'),
    DEFINE("WEB_FOLDER_PATH", WEB_ROOT . 'web/'),

    DEFINE("SITE_URL", 'http://localhost/sathsang/web'),
    DEFINE("DOMAIN_NAME", parse_url(SITE_URL, PHP_URL_HOST)),
    DEFINE("PORTAL_NAME", 'Sathsang'),
    DEFINE("HEADER_LOGO_NAME", 'SS'),

    DEFINE("SITE_HOST_NAME", 'http://localhost/sathsang/web/site'),
    DEFINE("SITE_HOST_SHORT_NAME", 'http://localhost/sathsang/web'),
    DEFINE('COOKIE_EXPIRY_TIME', 10 * 365 * 24 * 60 * 60),

    DEFINE("DB_HD", "hd"),
    DEFINE("MANAGE_LOGIN_ACCOUNT_DETAILS", 'cookie'),
    DEFINE("TBL_HD_PAGE_MASTER",'hd_page_master'),
    DEFINE("TBL_HD_USERS",'hd_users'),
    DEFINE("TBL_HD_PAGE_MASTER_HISTORY", 'hd_page_master_history'),
    DEFINE("TBL_HD_ROLE_MASTER", 'hd_role_master'),
    DeFINE("TBL_HD_ROLE_PAGES",'hd_role_pages'),
    DEFINE("TBL_HD_LEFTBAR_HEADING_MASTER",'hd_leftbar_heading_master'),
    DEFINE("TBL_HD_LEFTBAR_HEADING_MASTER_HISTORY",'hd_leftbar_heading_master_history'),
    DEFINE("TBL_HD_USER_ROLES",'hd_user_roles'),
    DEFINE("TBL_HD_USERS_HISTORY",'hd_users_history'),
    DEFINE("TBL_HD_ROLE_LEFTBAR",'hd_role_leftbar'),
    DEFINE("TBL_HD_ROLE_MASTER_HISTORY",'hd_role_master_history'),
    DEFINE("TBL_HD_ROLE_PAGES_HISTORY",'hd_role_pages_history'),
    DEFINE("MAIL_DOMAIN", PORTAL_NAME),
    DEFINE("TBL_HD_ROLE_LEFTBAR_HISTORY",'hd_role_leftbar_history'),
    DEFINE("EXTERNAL_USER_ROLE_ID", 2),
    DEFINE("COOKIE_OPT_EXPIRY_TIME",120),
    DEFINE("NO_REPLY_EMAIL", "no-reply@".DOMAIN_NAME),
    DEFINE("UPLOAD_FILES_SERVER_PATH", WEB_FOLDER_PATH . "uploads/"),
    DEFINE("TBL_HD_CONTENT",'hd_content'),
    DEFINE("S3_BUCKET_FILE_UPLOADS", "hd-uploaded-file"),
    DEFINE("ARR_PRE_LOGIN_COMMON_PAGES", array()),
    DEFINE("OTP_SECRETE_KEY",'4962d846-0a08-11ec-a13b-0200cd936042'),
    DEFINE("TBL_HD_USER_VISITS",'hd_user_page_visits'),
    DEFINE("TBL_HD_USER_LOGOUT_LOG",'hd_user_logout_log'),
    DEFINE("TBL_HD_USER_LOGIN_LOG",'hd_user_login_log'),
];
