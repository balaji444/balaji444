<?php

use \yii\web\Request;
use app\models\CommonMethods;
use app\models\Users;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'params'          => ['group_concat_max_len' => 100000],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'constant'            => [
            'class' => 'app\components\Constant',
        ],
        'debug'               => [
            'class' => 'app\components\Debug',
        ],
        /*'session'             => [
            'class'        => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ],
            'cookieParams' => [
                'httpOnly' => true,
                'secure'   => isset($_SERVER['HTTPS']) ? true:false,
            ],
            'timeout'      => 43200,
            'useCookies'   => true,
            'savePath' =>  '/tmp',
        ],*/
        'urlManager'          => [
            'class'           => 'yii\web\UrlManager',
            'showScriptName'  => false,
            'enablePrettyUrl' => true,
            'rules'           => [
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
                "login"                                  => "site/index",
                'baseUrl'                                => '/',
                "Logout"                                 => "site/logout",
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Wito8BTfrj2hkTgo6GVVoR-DF5GpuYAV',
            //'baseUrl'              => $baseUrl,
            'enableCsrfValidation' => true,
            //'enableCookieValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'hd' => $db,
    ],
    'params' => $params,
    /*'on beforeAction' => function ($event) {
        //Login Checking
        $arrLoginUserDetails = CommonMethods::GetLoginUserFullDetails();

        if (empty($arrLoginUserDetails->UserId)) {
            //Not Loggedin

            if (Yii::$app->controller->id != 'site' && Yii::$app->controller->action->id != 'index') {

                //Ajax request when logged out
                $isAjax = false;

                if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        $isAjax = true;
                    }
                }

                if ($isAjax) {
                    echo 'Session Expired';
                    exit;
                }

                $session = Yii::$app->session;
                $session->setFlash('RequestedURLBeforLogin', SITE_URL . $_SERVER['REQUEST_URI']);
                ob_start();
                header('Location: '.SITE_HOST_NAME.'/login');
                ob_end_flush();
                exit;

            }
        } else {
            //After Login Checking Page Permission
            //Checking Session exist but CSRF Toaken expired
            if (!Yii::$app->request->validateCsrfToken()) {
                echo 'CSRF Token Mismatch';
                exit;
            }

            $arrMyRolePermissionPageIds = CommonMethods::GetLoginUserPageControlesAndActions();

            $CombControlerAndAction = Yii::$app->controller->id . '###' . Yii::$app->controller->action->id;

            //Common Access Pages Post Login
            $arrPostLoginCommonAccessPages  = ARR_PRE_LOGIN_COMMON_PAGES;
            $arrMyRolePermissionPageIds     = array_merge($arrMyRolePermissionPageIds,$arrPostLoginCommonAccessPages);

            //User Visits Tracking
            $VisitedpagId = CommonMethods::getUserVisitedPageId(
                Yii::$app->controller->id,
                Yii::$app->controller->action->id
            );

            $AppendVisit = $arrLoginUserDetails->UserId . "#" . $arrLoginUserDetails->LoggedUserRoleId . '#' . $_SERVER['REMOTE_ADDR'] . "#" . $VisitedpagId . "#" . date("Y") . "#" . date("m") . "#" . date("d") . "#" . date("H:i:s");

            //file_put_contents(FILE_USER_VISITS_FLAT_FILE_WITH_PATH, $AppendVisit.PHP_EOL , FILE_APPEND);
            $ResultSaveVisitedPageDetails = Users::SaveUserPageVisit(explode('#', $AppendVisit));

            //User Visits Tracking
            if ( ! empty($arrMyRolePermissionPageIds))
            {
                if ( ! in_array($CombControlerAndAction,$arrMyRolePermissionPageIds)) //There is no permission to this Page
                {

                    if (Yii::$app->controller->id != 'site' && Yii::$app->controller->action->id != 'fault')
                    {

                        $RoleDefaultPage = CommonMethods::GetLoginUserDefaultCntrlAndAction();

                        $isAjax = false;

                        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                            if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                                $isAjax = true;
                            }
                        }

                        if ( ! $isAjax) {
                            echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content=""><meta name="author" content=""><link rel="icon" type="image/png" sizes="16x16" href="'.SITE_URL.'favicon.ico"><title>Access Denied!</title><link href="'.SITE_URL.'/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"><link href="'.SITE_URL.'/css/style.css" rel="stylesheet"><style>.error-box{height:100%;position:fixed;width:100%}.error-box .footer{width:100%;left:0px;right:0px}.error-body{padding-top:5%}.error-body h1{font-size:210px;font-weight:900;text-shadow:4px 4px 0 #ffffff, 6px 6px 0 #263238;line-height:210px}</style></head><body class="fix-header card-no-border fix-sidebar"> <section id="wrapper" class="error-page"><div class="error-box" style="background:none"><div class="error-body text-center"><h3 class="text-uppercase" style=" color:#CC0000">Access Denied.</h3><p class="m-t-30 m-b-30">You do not have permission to access this.</p> <a href="'.SITE_URL.'" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a></div> <footer class="footer text-center">&copy; '.date("Y").' '.MAIL_DOMAIN.'</footer></div> </section></body></html>';
                            exit;
                        }
                        else
                        {
                            echo '<div style="color:red;text-align:justify"><i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:16px;"></i>You do not have permission to access this.</div>';
                            exit;
                        }

                    }

                }
            }

        }
    },*/
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
