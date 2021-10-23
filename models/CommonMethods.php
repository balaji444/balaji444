<?php

namespace app\models;

use Yii;
use yii\db\Connection;
use yii\web\Response;
use yii\web\JsExpression;
use yii\base\Model;
use yii\web\Session;
use yii\web\Cookie;
use yii\web\View;
use Exception;
use fpdi\FPDI;
use fpdf\FPDF;
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
use Symfony\Component\Filesystem\Filesystem,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;


/**
 * Class CommonMethods
 * @package app\models
 */
class CommonMethods extends Model
{
    /**
     * @param string $dbName
     *
     * @return Connection
     */
    public function connectDb($dbName)
    {
        return Yii::$app->$dbName;
    }

    public function ArrayObjectFormat($inputArray)
    {

        return $inputArray = (object)$inputArray;

    }


    /**
     * @param $inputElement
     * @return string
     */
    public function CookieElementEncrypt($inputElement)
    {


        $EncryptedValue = openssl_encrypt($inputElement, 'AES-128-CTR',
            'AHGDF8768HDJ', 0, '1234567891011121');

        return $EncryptedValue;

    }


    /**
     * @param $inputElement
     * @return string
     */
    public function CookieElementDecrypt($inputElement)
    {

        if (!empty($inputElement)) {
            $DecryptedValue = openssl_decrypt($inputElement, 'AES-128-CTR',
                'AHGDF8768HDJ', 0, '1234567891011121');
        } else {
            $DecryptedValue = '';
        }

        return $DecryptedValue;

    }

    /**
     * @return int|object
     */
    public function GetLoginUserFullDetails()
    {

        $arrLoginUserDetails = array();
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserId')) {
                $arrLoginUserDetails['UserTime'] = $session->get('LoggedUserTime');
                $arrLoginUserDetails['UserId'] = $session->get('LoggedUserId');
                $arrLoginUserDetails['Email'] = $session->get('LoggedUserEmail');
                $arrLoginUserDetails['FirstName'] = $session->get('LoggedUserFirstName');
                $arrLoginUserDetails['LastName'] = $session->get('LoggedUserLastName');
                $arrLoginUserDetails['UserPic'] = $session->get('LoggedUserPic');
                $arrLoginUserDetails['LoggedUserRoleId'] = $session->get('LoggedUserRoleId');
                $arrLoginUserDetails['LoggedUserRolePageIds'] = $session->get('LoggedUserRolePageIds');
                $arrLoginUserDetails['LoggedUserRolePageId'] = $session->get('LoggedUserRoleDefaultPageId');
                $arrLoginUserDetails['LoggedUserRoleDefaultCntrlAndAction'] = $session->get('LoggedUserRoleDefaultCntrlAndAction');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserId'))) {
                $arrLoginUserDetails['UserTime'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserTime'));
                $arrLoginUserDetails['UserId'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserId'));
                $arrLoginUserDetails['Email'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserEmail'));
                $arrLoginUserDetails['FirstName'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserFirstName'));
                $arrLoginUserDetails['LastName'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserLastName'));
                $arrLoginUserDetails['UserPic'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserPic'));

                $arrLoginUserDetails['LoggedUserRoleId'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserRoleId'));
                $arrLoginUserDetails['LoggedUserRolePageIds'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserRolePageIds'));
                $arrLoginUserDetails['LoggedUserRoleDefaultPageId'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserRoleDefaultPageId'));
                $arrLoginUserDetails['LoggedUserRoleDefaultCntrlAndAction'] = self::CookieElementDecrypt($cookies->getValue('LoggedUserRoleDefaultCntrlAndAction'));
            }
        }

        if (!empty($arrLoginUserDetails)) {
            return self::ArrayObjectFormat($arrLoginUserDetails);
        } else {
            return 0;
        }
    }

    /**
     * @return int|mixed|string
     */
    public function GetLoginUserDefaultCntrlAndAction()
    {
        $LoggedUserRoleDefaultCntrlAndAction = 0;
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserRoleDefaultCntrlAndAction')) {
                $LoggedUserRoleDefaultCntrlAndAction = $session->get('LoggedUserRoleDefaultCntrlAndAction');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserRoleDefaultCntrlAndAction'))) {
                $LoggedUserRoleDefaultCntrlAndAction = self::CookieElementDecrypt($cookies->getValue('LoggedUserRoleDefaultCntrlAndAction'));
            }
        }

        return $LoggedUserRoleDefaultCntrlAndAction;
    }

    /**
     * @return mixed|string
     */
    public function GetLoginUserId()
    {
        $LoggedInUserId = '';
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserId')) {
                $LoggedInUserId = $session->get('LoggedUserId');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserId'))) {
                $LoggedInUserId = self::CookieElementDecrypt($cookies->getValue('LoggedUserId'));
            }
        }

        return $LoggedInUserId;
    }

    /**
     * @return mixed|string
     */
    public function GetLoginUserFirstName()
    {
        $LoggedUserFirstName = '';
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserFirstName')) {
                $LoggedUserFirstName = $session->get('LoggedUserFirstName');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserFirstName'))) {
                $LoggedUserFirstName = self::CookieElementDecrypt($cookies->getValue('LoggedUserFirstName'));
            }
        }

        return $LoggedUserFirstName;
    }

    /**
     * @return mixed|string
     */
    public function GetLoginUserLastName()
    {
        $LoggedUserLastName = '';
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserLastName')) {
                $LoggedUserLastName = $session->get('LoggedUserLastName');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserLastName'))) {
                $LoggedUserLastName = self::CookieElementDecrypt($cookies->getValue('LoggedUserLastName'));
            }
        }

        return $LoggedUserLastName;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function GetLoggedinUserRoles_Headings_Pages()
    {
        //get DB Connection
        $dbConnectionHd = CommonMethods::connectDb(DB_HD);

        $user_id = CommonMethods::GetLoginUserId();
        $res_RoleHeadingsPages = array();
        $fetchQuery = "SELECT role_id FROM " . TBL_HD_USER_ROLES . " WHERE user_id = :user_id";
        $role_id = $dbConnectionHd
            ->createCommand($fetchQuery)
            ->bindValue(':user_id', $user_id)
            ->queryScalar();
        if (!empty($role_id)) {
            $res_RoleHeadingsPages = UserModule::fetchRoleHeadingsPages($role_id);
        }
        return $res_RoleHeadingsPages;
    }


    /**
     * @return int|mixed|string
     */
    public function GetLoginUserRoleId()
    {
        $LoggedUserRoleId = 0;
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserRoleId')) {
                $LoggedUserRoleId = $session->get('LoggedUserRoleId');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {
            $cookies = Yii::$app->request->cookies;
            if (!empty($cookies->has('LoggedUserRoleId'))) {
                $LoggedUserRoleId = self::CookieElementDecrypt($cookies->getValue('LoggedUserRoleId'));
            }
        }

        return $LoggedUserRoleId;
    }

    /**
     * @param $strContent
     * @return array|string|string[]|null
     */
    public function sanitizeUrlQueryString($strContent)
    {
        if (is_array($strContent)) {
            $resultString = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $strContent);
            $resultString = array_map('html_entity_decode', $resultString);
            $resultString = preg_replace('/\s+/', ' ', $resultString);
            $resultString = array_map('trim', $resultString);
            $resultString = array_map('strip_tags', $resultString);
        } else {

            $arrString = array();
            $arrString[0] = $strContent;
            $resultString = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $arrString);

            $resultString = preg_replace('/\s+/', ' ', $resultString[0]);
            $resultString = html_entity_decode($resultString);
            $resultString = trim($resultString);
            $resultString = strip_tags($resultString);
        }
        return $resultString;

    }

    /**
     * @return array
     */
    public function GetAllControllerNames()
    {
        $controllerlist = [];
        if ($handle = opendir('../controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = str_replace('controller.php', '', strtolower($file));
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        return $controllerlist;
    }

    /**
     * @param $strContent
     * @return string
     */
    public function displayVariableContent($strContent)
    {
        if(!is_array($strContent)) {
            if ($strContent != strip_tags($strContent)) {
                return trim(\yii\helpers\HtmlPurifier::process($strContent));
            } else {
                return trim(\yii\helpers\Html::encode(html_entity_decode($strContent,ENT_QUOTES)));
            }
        }else{
            return 'Invalid argument!';
        }
    }

    /**
     * @param $ControllerName
     * @return array|mixed
     */
    public function GetControllerActions($ControllerName)
    {
        $fulllist = [];
        $controller = ucfirst($ControllerName) . '.php';
        $handle = fopen('../controllers/' . $controller, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/public function action(.*?)\(/', $line, $display)):
                    if (strlen($display[1]) > 2):
                        $fulllist[substr($controller, 0, -4)][] = strtolower(preg_replace('/\B([A-Z])/', '-$1',
                            $display[1]));
                    endif;
                endif;
            }
        }
        fclose($handle);
        $fulllist = $fulllist[$ControllerName];
        asort($fulllist);
        return $fulllist;
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function GetLoginUserPageControlesAndActions()
    {

        //From Session Or Cookie
        $LoggedUserPageControlesAndActions = 0;
        if (MANAGE_LOGIN_ACCOUNT_DETAILS == 'session') {
            $session = Yii::$app->session;
            if ($session->has('LoggedUserPageControlesAndActions')) {
                $LoggedUserPageControlesAndActions = $session->get('LoggedUserPageControlesAndActions');
            }
        } elseif (MANAGE_LOGIN_ACCOUNT_DETAILS == 'cookie') {

            //Page Controles And Actions Start :: INSTANT
            $dbConnectionHd = CommonMethods::connectDb(DB_HD);
            $userRoleId = CommonMethods::GetLoginUserRoleId();

            $stQryRoleDefaultPages = "
			  SELECT 
			  		pm.controller_name,pm.action_name 
			  FROM 
			  		" . DB_HD . "." . TBL_HD_PAGE_MASTER . " pm 
			  INNER JOIN 
			  		" . DB_HD . "." . TBL_HD_ROLE_PAGES . " rp
			  ON 
			  		pm.page_id = rp.page_id && 
					rp.role_id = :role_id
			  ";
            $UserRoleDefaultPages = $dbConnectionHd
                ->createCommand(trim($stQryRoleDefaultPages))
                ->bindValue(':role_id', $userRoleId)
                ->queryAll();

            $arrUserRolePageControlesAndActions = array();
            foreach ($UserRoleDefaultPages as $arrPageCntrlAndAction) {
                $arrUserRolePageControlesAndActions[] = $arrPageCntrlAndAction['controller_name'] . '###' . $arrPageCntrlAndAction['action_name'];
            }

            $LoggedUserPageControlesAndActions = json_encode($arrUserRolePageControlesAndActions);
            //Page Controles And Actions End :: INSTANT

        }

        return json_decode($LoggedUserPageControlesAndActions);
    }

    /**
     * @param $controllerName
     * @param $actionName
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getUserVisitedPageId($controllerName, $actionName)
    {

        $dbConnectionHd = CommonMethods::connectDb(DB_HD);
        $strQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ page_id FROM " . DB_HD . "." . TBL_HD_PAGE_MASTER . " WHERE controller_name=:controller_name && action_name=:action_name;";
        $VisitedpagId = $dbConnectionHd
            ->createCommand($strQuery)
            ->bindValue(':controller_name', $controllerName)
            ->bindValue(':action_name', $actionName)
            ->queryScalar();

        return $VisitedpagId;

    }

    /**
     * @param string $fileName
     * @return string|string[]|null
     */
    public function fnGetFileNameWithoutSpecialChars($fileName = '')
    {
        if (empty($fileName)) {
            return '';
        }
        if (!empty($fileName)) {
            $_trSpec = array(
                'Ç' => 'C',
                'Ğ' => 'G',
                'İ' => 'I',
                'Ö' => 'O',
                'Ş' => 'S',
                'Ü' => 'U',
                'ç' => 'c',
                'ğ' => 'g',
                'ı' => 'i',
                'i' => 'i',
                'ö' => 'o',
                'ş' => 's',
                'ü' => 'u',
            );
            $enChars = array_values($_trSpec);
            $trChars = array_keys($_trSpec);
            $theValue = str_replace($trChars, $enChars, $fileName);
            $theValue = preg_replace("@[^A-Za-z0-9\-_.\/]+@i", "-", $theValue);
            return $theValue;
        }
    }

    /**
     * @param $FileName
     * @param $SourceFilePath
     * @param $BucketName
     * @param string $AccessControlType
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function UploadFileToS3($FileName, $SourceFilePath, $BucketName, $AccessControlType = 'authenticated-read')
    {

        $s3 = Yii::$app->get('s3');
        $FileName = trim($FileName);
        $ObjUploadResult = $s3->commands()->upload($FileName,$SourceFilePath . $FileName)->inBucket($BucketName)->execute();

        $arrUploadResult = (array)$ObjUploadResult;

        foreach (array_keys($arrUploadResult) as $IndexKeyName) {
            $arrAwsResult = $arrUploadResult[$IndexKeyName];
        }

        if (!empty($arrAwsResult['ObjectURL'])) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $SubFolderNameInS3
     * @param $FileName
     * @param $SourceFilePath
     * @param $BucketName
     * @param string $AccessControlType
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function UploadFileToS3WithSubFolder($SubFolderNameInS3,$FileName, $SourceFilePath, $BucketName, $AccessControlType = 'authenticated-read')
    {

        $s3 = Yii::$app->get('s3');
        $FileName = trim($FileName);
        $ObjUploadResult = $s3->commands()->upload($SubFolderNameInS3.$FileName,$SourceFilePath . $FileName)->inBucket($BucketName)->execute();

        $arrUploadResult = (array)$ObjUploadResult;

        foreach (array_keys($arrUploadResult) as $IndexKeyName) {
            $arrAwsResult = $arrUploadResult[$IndexKeyName];
        }

        if (!empty($arrAwsResult['ObjectURL'])) {
            return true;
        } else {
            return false;
        }

    }
    /**
     * @param $FileName
     * @param $BucketName
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function GetAuthenticatedFilePath($FileName, $BucketName)
    {

        //Expiry Time :: Min:1 and Max:604800 (In Seconds)
        $FileName = trim($FileName);
        $s3 = Yii::$app->get('s3');
        $boolExistBefore = $s3->commands()->exist($FileName)->inBucket($BucketName)->execute();
        $ReturnData = '';

        if ($boolExistBefore) {
            $FileWithPath = $s3->commands()->getPresignedUrl($FileName,
                '+600 seconds')->inBucket($BucketName)->execute();//'+1 days'
            if (!empty($FileWithPath)) {
                $ReturnData = $FileWithPath;
            }
        }

        return $ReturnData;

    }

    /**
     * @param $FileName
     * @param $BucketName
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function DeleteFileFromS3Bucket($FileName, $BucketName)
    {

        $FileName = trim($FileName);
        $s3 = Yii::$app->get('s3');
        $boolExistBefore = $s3->commands()->exist($FileName)->inBucket($BucketName)->execute();

        if ($boolExistBefore) {
            $ObjDeleteResult = $s3->commands()->delete($FileName)->inBucket($BucketName)->execute();
        }

        $boolExistAfter = $s3->commands()->exist($FileName)->inBucket($BucketName)->execute();

        if (!$boolExistBefore) {
            return true;
        } else {
            return false;
        }

    }


    /**
     * @param $FileName
     * @param $BucketName
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function DisplayPdfFromS3($FileName, $BucketName)
    {
        $FileName = trim($FileName);
        $s3 = Yii::$app->get('s3');
        $boolExistBefore = $s3->commands()->exist($FileName)->inBucket($BucketName)->execute();

        if ($boolExistBefore) {
            $FileWithPath = $s3->commands()->getPresignedUrl($FileName, '+1 days')->inBucket($BucketName)->execute();
            return $FileWithPath;
        } else {
            return 'File not found.';
        }
    }

    /**
     * @param $FileName
     * @param $BucketName
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function GetS3ObjectFileSizeAndFileType($FileName, $BucketName)
    {
        $s3 = Yii::$app->get('s3');
        $boolExistBefore = $s3->commands()->get($FileName)->inBucket($BucketName)->execute();
        $arrDetails['ContentLength'] = $boolExistBefore['ContentLength'];
        $arrDetails['ContentType'] = $boolExistBefore['ContentType'];

        return $arrDetails;
    }

    /**
     * @param $FileName
     * @param $BucketName
     * @param string $OptionalCustomFileName
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function DownloadS3Object($FileName, $BucketName, $OptionalCustomFileName = '')
    {
        if (!empty(CommonMethods::GetLoginUserId())) {
            $FileWithPath = CommonMethods::GetAuthenticatedFilePath($FileName, $BucketName);
            $arrFileHeaders = CommonMethods::GetS3ObjectFileSizeAndFileType($FileName, $BucketName);
            $FileSize = $arrFileHeaders['ContentLength'];
            $ContentType = $arrFileHeaders['ContentType'];
            if (empty($OptionalCustomFileName)) {
                $DownloadFileNameAs = $FileName;
            } else {
                $DownloadFileNameAs = $OptionalCustomFileName;
            }
            if (!empty($FileWithPath)) {
                ob_start();
                header("Content-Type: $ContentType");
                header('Content-Disposition: attachment; filename="' . $DownloadFileNameAs . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                header("Content-Length: $FileSize");
                @readfile($FileWithPath);
                ob_end_flush();
            } else {
                return 'File not found!';
            }
        } else {
            return 'Access Denied!';
        }
    }

    /**
     * @param $FileName
     * @param $BucketName
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function FileExistInS3Bucket($FileName, $BucketName)
    {

        $FileName = trim($FileName);
        $s3 = Yii::$app->get('s3');
        $boolExistBefore = $s3->commands()->exist($FileName)->inBucket($BucketName)->execute();

        if ($boolExistBefore) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $FileName
     * @param $BucketName
     * @param $WebServerPath
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function CopyS3FileToCurrentWebServer($FileName, $BucketName, $WebServerPath)
    {

        $FileName = trim($FileName);
        $s3 = Yii::$app->get('s3');

        $arrResult = $s3->commands()->get($FileName)->inBucket($BucketName)->saveAs($WebServerPath . $FileName)->execute();

        if (file_exists($WebServerPath . $FileName)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $Subject
     * @param $Message
     * @param $ToEmailIds
     * @param string $attachments
     * @return bool
     */
    public function SendMail($Subject, $Message, $ToEmailIds, $attachments = "")
    {

        $url		 = 'https://api.sendgrid.com/';
        $apiKey		 = '';//getenv('SENDGRID_API_KEY');
        $ToEmailIds	 = preg_replace('/\s+/', '', $ToEmailIds);
        $arrEmailIds = explode(",", $ToEmailIds);
        $json_string = array('to' => $arrEmailIds, 'category' => PORTAL_NAME);
        $params = array(
            'x-smtpapi' => json_encode($json_string),
            'subject' => $Subject,
            'html' => $Message,
            'to' => 'example3@sendgrid.com',//This will not receive an email,as per Sendgrid rules It should be dummy
            'text' => strip_tags($Message),
            'fromname' => FROM_EMAIL_DOMAIN_NAME,
            'from' => FROM_EMAIL
        );

        if(!empty($attachments))
        {
            if(!is_array($attachments)) // Means given only one file
                $attachmentsReStore[] = $attachments;
            else
                $attachmentsReStore = $attachments;

            if (!empty($attachmentsReStore)) {
                $fileName = "";
                for ($i = 0; $i < count($attachmentsReStore); $i++) {
                    if(file_exists($attachmentsReStore[$i]))
                    {
                        $x = $i + 1;
                        $ext = pathinfo($attachmentsReStore[$i], PATHINFO_EXTENSION);
                        $fileName = 'attachment' . $x . '.' . $ext;
                        $params['files[' . $fileName . ']'] = file_get_contents($attachmentsReStore[$i]);
                    }
                }
            }
        }

        $request = $url . 'api/mail.send.json';
        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $apiKey));
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        // obtain response
        $response = curl_exec($session);
        curl_close($session);
        $Result = json_decode($response, true);
        if ($Result['message'] == 'success') {
            return true;
        } else {
            return false;
        }
    }
 
}