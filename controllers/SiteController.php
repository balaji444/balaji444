<?php

namespace app\controllers;

use app\models\CommonMethods;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $arrLoginUserDetails = CommonMethods::GetLoginUserFullDetails();
        if (empty($arrLoginUserDetails->UserId)) {
            return $this->render('login');
        } else {
            return $this->redirect(SITE_URL."/".CommonMethods::GetLoginUserDefaultCntrlAndAction());
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $arrLoginUserDetails = CommonMethods::GetLoginUserFullDetails();
        if (empty($arrLoginUserDetails->UserId)) {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        } else {
            $session = Yii::$app->session;
            $UserRoleId = Users::getRoleIdFromUserId($arrLoginUserDetails->UserId);

            $rsUserRolePages = Users::getPageIdFromRoleId($UserRoleId);
            $arrUserRolePages = array();
            foreach ($rsUserRolePages as $arrRsUserRolePages) {
                $arrUserRolePages[] = $arrRsUserRolePages['page_id'];
            }

            $arrUserRolePages = json_encode($arrUserRolePages);

            $UserDefaultRolePageId = Users::getDefaultPageIdFromRoleId($UserRoleId);

            $UserRoleDefaultPage = Users::getRoleDefaultPageWithRoleId($UserDefaultRolePageId);

            if (empty($UserRoleDefaultPage)) {

                $session->setFlash('ErrorMessage',
                    'Your default logged in page has been deactivated. Please contact the administrator.');
                $this->GoToLoginPage();
            }


            //Page Controles And Actions Start
            $UserRoleDefaultPages = Users::getRoleDefaultPageWithPageIdAndRoleId($UserRoleId);

            $arrUserRolePageControlesAndActions = array();
            foreach ($UserRoleDefaultPages as $arrPageCntrlAndAction) {
                $arrUserRolePageControlesAndActions[] = $arrPageCntrlAndAction['controller_name'] . '###' . $arrPageCntrlAndAction['action_name'];
            }

            $arrUserRolePageControlesAndActions = json_encode($arrUserRolePageControlesAndActions);

            if ($session->hasFlash('RequestedURLBeforLogin')) {
                return $this->redirect($session->getFlash('RequestedURLBeforLogin'));
            } else {
                return $this->redirect(SITE_HOST_SHORT_NAME.$UserRoleDefaultPage);
            }
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        echo "Logout";exit;
        $LoggedUserId = CommonMethods::GetLoginUserId();

            $LoggedUserRoleId = CommonMethods::GetLoginUserRoleId();
            $QueryResult = Users::SaveLogoutUserDetails($LoggedUserId, $LoggedUserRoleId);

            if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
                $session = Yii::$app->session;
                $session->open();
                $session->destroy();
            } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {

                if (isset($_COOKIE['LoggedUserTime'])) {
                    unset($_COOKIE['LoggedUserTime']);
                    setcookie('LoggedUserTime', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserId'])) {
                    unset($_COOKIE['LoggedUserId']);
                    setcookie('LoggedUserId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserEmail'])) {
                    unset($_COOKIE['LoggedUserEmail']);
                    setcookie('LoggedUserEmail', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserFirstName'])) {
                    unset($_COOKIE['LoggedUserFirstName']);
                    setcookie('LoggedUserFirstName', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserLastName'])) {
                    unset($_COOKIE['LoggedUserLastName']);
                    setcookie('LoggedUserLastName', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserPic'])) {
                    unset($_COOKIE['LoggedUserPic']);
                    setcookie('LoggedUserPic', '', time() - 3600, '/');
                }

                if (isset($_COOKIE['LoggedUserRoleId'])) {
                    unset($_COOKIE['LoggedUserRoleId']);
                    setcookie('LoggedUserRoleId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRolePageIds'])) {
                    unset($_COOKIE['LoggedUserRolePageIds']);
                    setcookie('LoggedUserRolePageIds', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRoleDefaultPageId'])) {
                    unset($_COOKIE['LoggedUserRoleDefaultPageId']);
                    setcookie('LoggedUserRoleDefaultPageId', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserRoleDefaultCntrlAndAction'])) {
                    unset($_COOKIE['LoggedUserRoleDefaultCntrlAndAction']);
                    setcookie('LoggedUserRoleDefaultCntrlAndAction', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['LoggedUserPageControlesAndActions'])) {
                    unset($_COOKIE['LoggedUserPageControlesAndActions']);
                    setcookie('LoggedUserPageControlesAndActions', '', time() - 3600, '/');
                }

                if (isset($_COOKIE['LoggedUserTheme'])) {
                    unset($_COOKIE['LoggedUserTheme']);
                    setcookie('LoggedUserTheme', '', time() - 3600, '/');
                }

                Yii::$app->cache->flush();
            }

            //$QryLastActive = Users::updateUserLastActiveTime($LoggedUserId);

        $this->GoToLoginPage();
    }

    public function GoToLoginPage()
    {
        return $this->redirect(SITE_URL.'/login');
    }

    public function actionLoginSubmit()
    {
        $session = Yii::$app->session;
        $userFormValues = Yii::$app->request->post();
        $userOpt = $userFormValues['userOtp'];
        $cookies = Yii::$app->request->cookies;

        if (isset($cookies['GeneratedOtp'])) {
            $generatedOpt = $cookies['GeneratedOtp']->value;
        } else {
            $generatedOpt = '';
        }
        if(empty($generatedOpt)) {
            return $this->render('login', [
                'mobileNumber' => $userFormValues['phone'],
                'otp' => $generatedOpt,
                'otpInvalid' => 'OTP is expired'
            ]);
        }
        if($userOpt != $generatedOpt) {
            return $this->render('login', [
                'mobileNumber' => $userFormValues['phone'],
                'otp' => $generatedOpt,
                'otpInvalid' => 'Please enter valid OTP'
            ]);
        }
        $arrResultCheckedCredentials = LoginForm::validateUserLoginCredentials(Yii::$app->request->post());

        if (!empty($arrResultCheckedCredentials))
        {

            if ($arrResultCheckedCredentials->is_active == 'Y') //User Active and proceed to User Dashboard
            {
                $UserIdFromDb = $arrResultCheckedCredentials->user_id;
                $UserRoleId = Users::getRoleIdFromUserId($arrResultCheckedCredentials->user_id);

                $rsUserRolePages = Users::getPageIdFromRoleId($UserRoleId);
                $arrUserRolePages = array();
                foreach ($rsUserRolePages as $arrRsUserRolePages) {
                    $arrUserRolePages[] = $arrRsUserRolePages['page_id'];
                }

                $arrUserRolePages = json_encode($arrUserRolePages);

                $UserDefaultRolePageId = Users::getDefaultPageIdFromRoleId($UserRoleId);

                $UserRoleDefaultPage = Users::getRoleDefaultPageWithRoleId($UserDefaultRolePageId);

                if (empty($UserRoleDefaultPage)) {
                    $session->setFlash('ErrorMessage',
                        'Your default logged in page has been deactivated. Please contact the administrator.');
                    $this->GoToLoginPage();
                }


                //Page Controles And Actions Start
                $UserRoleDefaultPages = Users::getRoleDefaultPageWithPageIdAndRoleId($UserRoleId);

                $arrUserRolePageControlesAndActions = array();
                foreach ($UserRoleDefaultPages as $arrPageCntrlAndAction) {
                    $arrUserRolePageControlesAndActions[] = $arrPageCntrlAndAction['controller_name'] . '###' . $arrPageCntrlAndAction['action_name'];
                }

                $arrUserRolePageControlesAndActions = json_encode($arrUserRolePageControlesAndActions);

                $CookieLiveDuration = time() + COOKIE_EXPIRY_TIME;

                if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {

                    //$session = new Session;
                    $session->open();
                    $session['LoggedUserTime'] = $CookieLiveDuration;
                    $session['LoggedUserId'] = $arrResultCheckedCredentials->user_id;
                    $session['LoggedUserEmail'] = $arrResultCheckedCredentials->user_email;
                    $session['LoggedUserFirstName'] = $arrResultCheckedCredentials->first_name;
                    $session['LoggedUserLastName'] = $arrResultCheckedCredentials->last_name;
                    $session['LoggedUserPic'] = $arrResultCheckedCredentials->profile_pic_filename;

                    $session['LoggedUserRoleId'] = $UserRoleId;
                    $session['LoggedUserRolePageIds'] = $arrUserRolePages;
                    $session['LoggedUserRoleDefaultPageId'] = $UserDefaultRolePageId;
                    $session['LoggedUserRoleDefaultCntrlAndAction'] = $UserRoleDefaultPage;
                    $session['LoggedUserPageControlesAndActions'] = $arrUserRolePageControlesAndActions;

                    $session['LoggedUserTheme'] = "";
                    $session['LoggedUserTermsConditionsWarantyStatus'] = "terms-conditions-start";

                    $session->close();
                } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserTime',
                        'value' => CommonMethods::CookieElementEncrypt($CookieLiveDuration),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserId',
                        'value' => CommonMethods::CookieElementEncrypt($arrResultCheckedCredentials->user_id),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserEmail',
                        'value' => CommonMethods::CookieElementEncrypt($arrResultCheckedCredentials->user_email),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserFirstName',
                        'value' => CommonMethods::CookieElementEncrypt($arrResultCheckedCredentials->first_name),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserLastName',
                        'value' => CommonMethods::CookieElementEncrypt($arrResultCheckedCredentials->last_name),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserPic',
                        'value' => CommonMethods::CookieElementEncrypt($arrResultCheckedCredentials->profile_pic_filename),
                        'expire' => $CookieLiveDuration
                    ]));

                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserRoleId',
                        'value' => CommonMethods::CookieElementEncrypt($UserRoleId),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserRolePageIds',
                        'value' => CommonMethods::CookieElementEncrypt($arrUserRolePages),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserRoleDefaultPageId',
                        'value' => CommonMethods::CookieElementEncrypt($UserDefaultRolePageId),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserRoleDefaultCntrlAndAction',
                        'value' => CommonMethods::CookieElementEncrypt($UserRoleDefaultPage),
                        'expire' => $CookieLiveDuration
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'LoggedUserPageControlesAndActions',
                        'value' => CommonMethods::CookieElementEncrypt($arrUserRolePageControlesAndActions),
                        'expire' => $CookieLiveDuration
                    ]));

                }

                //Login Log
                $QueryResult = Users::SaveLoginUserDetails($arrResultCheckedCredentials->user_id, $UserRoleId);

                //$QryLastActive = Users::updateUserLastActiveTime($arrResultCheckedCredentials->user_id);
                Users::fnUpdateUserAuthentication($userFormValues['phone']);
                    if ($session->hasFlash('RequestedURLBeforLogin')) {
                        return $this->redirect($session->getFlash('RequestedURLBeforLogin'));
                    } else {
                        return $this->redirect(SITE_HOST_SHORT_NAME.$UserRoleDefaultPage);
                    }

            } else //User Inactive
            {

                $session->setFlash('ErrorMessage', 'This account is In-active. Please contact administrator!');
                $this->GoToLoginPage();

            }

        } else //Wrong Credentials
        {
            $session->setFlash('ErrorMessage', 'The user OTP you entered is incorrect!');
            $this->GoToLoginPage();

        }

    }

    public function actionLoginForOtp()
    {
        $arrLoginFormData = Yii::$app->request->post();
        $arrResultCheckedCredentials = LoginForm::validateUserLoginCredentials(Yii::$app->request->post());
        $otp = mt_rand(1111,9999);
        Users::fnSendOTP($arrLoginFormData['phone'],$otp);
        $CookieLiveDuration = time() + COOKIE_OPT_EXPIRY_TIME;
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('GeneratedOtp');
        $cookies->add(new \yii\web\Cookie([
            'name' => 'GeneratedOtp',
            'value' => $otp,
            'expire' => $CookieLiveDuration
        ]));
        return $this->render('login', [
            'mobileNumber' => $arrLoginFormData['phone'],
            'otp' => $otp
        ]);
    }

    public function actionOtpResendAjax()
    {

        $request = Yii::$app->request;
        if ($request->isAjax && $request->isPost) {
            $arrLoginFormData = Yii::$app->request->post();
            $otp = mt_rand(1111, 9999);
            Users::fnSendOTP($arrLoginFormData['phone'], $otp);

            $CookieLiveDuration = time() + COOKIE_OPT_EXPIRY_TIME;
            $cookies = Yii::$app->response->cookies;
            $cookies->remove('GeneratedOtp');
            $cookies->add(new \yii\web\Cookie([
                'name' => 'GeneratedOtp',
                'value' => $otp,
                'expire' => $CookieLiveDuration
            ]));

            return '';
        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {

        return $this->render('about');
    }
}
