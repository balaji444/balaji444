<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Users extends Model
{

    /**
     * @param $Email
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function checkUserLoginDetails($Email)
    {

        $memberDetails = array();
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);

        $strQuery = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ user_id,user_email,first_name,last_name,profile_pic_path as profile_pic_filename,is_active FROM " . TBL_HD_USERS . " WHERE user_phone_number = :user_phone_number LIMIT 1";
        $memberDetails = $dbConnectionHD
            ->createCommand($strQuery)
            ->bindValue(':user_phone_number', $Email)
            ->queryOne();

        if (!empty($memberDetails)) {
            return $memberDetails;
        } else {
            return $memberDetails;
        }

    }

    /**
     * @param int $uid
     * @param string $isActive
     * @return array
     * @throws \yii\db\Exception
     */
    public function GetAllUsers($uid = 0, $isActive = "")
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $memberDetailsArr = array();
        $userCondition = '';
        if (!empty($uid)) {
            $userCondition = ' WHERE user_id =:user_id';
        }

        if (!empty($isActive)) {
            if (!empty($uid)) {
                $userCondition .= ' && is_active =:is_active';
            } else {
                $userCondition = ' WHERE is_active =:is_active';
            }
        }

        $strQuery = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ * FROM " . DB_HD . "." . TBL_HD_USERS . " $userCondition ORDER BY user_id DESC";
        $memberDetails = $dbConnectionHD->createCommand($strQuery);

        if (!empty($uid)) {
            $memberDetails->bindValue(':user_id', $uid);
        }

        if (!empty($isActive)) {
            $memberDetails->bindValue(':is_active', $isActive);
        }
        $memberDetailsArr = $memberDetails->queryAll();

        return $memberDetailsArr;
    }

    /**
     * @param $postValues
     * @param int $existingUid
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function checkUserAlreadyExists_or_not($postValues, $existingUid = 0)
    {

        $memberDetails = array();
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        if (empty($existingUid)) {
            $strQuery = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ user_id FROM " . DB_HD . "." . TBL_HD_USERS . " WHERE user_phone_number = :user_phone_number  LIMIT 1";
            $memberDetails = $dbConnectionHD
                ->createCommand($strQuery)
                ->bindValue(':user_phone_number', $postValues['user_phone_no'])
                ->queryOne();
        }
        if (!empty($existingUid)) {
            $strQuery = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ user_id FROM " . DB_HD . "." . TBL_HD_USERS . " WHERE user_phone_number = :user_phone_number && user_id!=:user_id  LIMIT 1";
            $memberDetails = $dbConnectionHD
                ->createCommand($strQuery)
                ->bindValue(':user_phone_number', $postValues['user_phone_no'])
                ->bindValue(':user_id', $existingUid)
                ->queryOne();

        }

        if (!empty($memberDetails)) {
            return $memberDetails;
        } else {
            return $memberDetails;
        }
    }

    /**
     * @param $postValues
     * @param $loggedInUid
     * @return int
     * @throws \yii\db\Exception
     */
    public function CreateUser($postValues, $loggedInUid)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $insertQuery = "INSERT INTO /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USERS . " (role_id,user_email,first_name,last_name,is_active,user_note,created_by_user_id,user_phone_number,created_on) VALUES (:role_id,:user_email,:first_name,
		:last_name,:is_active,:user_note,:created_by_user_id,:user_phone_number,NOW())";

        $user_email = $postValues['user_email'];
        $user_status = $postValues['user_status'];
        $user_note = $postValues['user_note'];
        $user_role = $postValues['user_role'];
		$phoneNo = $postValues['user_phone_no'];


        $user_status = CommonMethods::sanitizeUrlQueryString($user_status);
        $user_note = CommonMethods::sanitizeUrlQueryString($user_note);
        $first_name = !empty($postValues['first_name']) ? $postValues['first_name'] : "";
        $last_name = !empty($postValues['last_name']) ? $postValues['last_name'] : "";

        //Insert into the Database
        $memberDetails = $dbConnectionHD
            ->createCommand($insertQuery)
            ->bindValue(':role_id', $user_role)
            ->bindValue(':user_email', $user_email)
            ->bindValue(':first_name', $first_name)
            ->bindValue(':last_name', $last_name)
            ->bindValue(':is_active', $user_status)
            ->bindValue(':user_note', $user_note)
            ->bindValue(':created_by_user_id', $loggedInUid)
			->bindValue(':user_phone_number', $phoneNo)
            ->execute();
        $lastInsertedUID = $dbConnectionHD->getLastInsertID();
        //Delete From table TBL_HD_ROLE_PAGES
        $model = $dbConnectionHD->createCommand("DELETE FROM /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USER_ROLES . " WHERE user_id=:user_id ");
        $model->bindParam(':user_id', $lastInsertedUID);
        $model->execute();


        //Move to History
        $insertRoleMasterHistoryQry = "INSERT INTO /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USER_ROLES . " (user_id,role_id,role_assigned_by_user_id)  VALUES (:user_id,:role_id,:role_assigned_by_user_id)";
        $historyDetails = $dbConnectionHD
            ->createCommand($insertRoleMasterHistoryQry)
            ->bindValue(':role_id', $user_role)
            ->bindValue(':user_id', $lastInsertedUID)
            ->bindValue(':role_assigned_by_user_id', $loggedInUid)
            ->execute();

        return 1;
    }

    /**
     * @param $uid
     * @param $loggedInUid
     * @throws \yii\db\Exception
     */
    public function CopyUserDetails_into_history($uid, $loggedInUid)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        //Get the User Details
        $roleId = 0;
        $roleAssignedBy = 0;
        $roleAddedOn = '';

        $pcp_npi = 0;
        $sql = "SELECT * /* " . __FILE__ . " " . __LINE__ . "  */ FROM " . TBL_HD_USERS . " WHERE user_id=:user_id";
        $rs = $dbConnectionHD
            ->createCommand($sql)
            ->bindValue(':user_id', $uid)
            ->queryAll();
        if (!empty($rs)) {
            //Get the User's Role details
            $rolesql = "SELECT * /* " . __FILE__ . " " . __LINE__ . "  */ FROM " . TBL_HD_USER_ROLES . " WHERE user_id=:user_id";
            $roleRs = $dbConnectionHD
                ->createCommand($rolesql)
                ->bindValue(':user_id', $uid)
                ->queryAll();
            if (!empty($roleRs)) {
                $roleId = $roleRs[0]['role_id'];
                $roleAssignedBy = $roleRs[0]['role_assigned_by_user_id'];
                $roleAddedOn = $roleRs[0]['role_assigned_on'];
            }


            //Insert into HIstory
            $insHistorySql = "
		  				  INSERT INTO  /* " . __FILE__ . " " . __LINE__ . "  */
		  							 " . DB_HD . ".".TBL_HD_USERS_HISTORY." 
		  				  SET 
		  							 user_id=:uid,
									 user_email=:user_email,									
									 first_name=:first_name,
									 last_name=:last_name,
									 profile_pic_path=:profile_pic_path,
									 is_active=:is_active,
									 user_note=:user_note,									
									 created_by_user_id=:created_by_user_id,
									 created_on=:created_on,									 									
									 role_id=:roleId,
									 user_phone_number=:user_phone_number,									 
									 updated_by_user_id=:loggedInUid
						
		   ";
            $historyDetails = $dbConnectionHD
                ->createCommand($insHistorySql)
                ->bindValue(':uid', $uid)
                ->bindValue(':user_email', $rs[0]['user_email'])
                ->bindValue(':first_name', $rs[0]['first_name'])
                ->bindValue(':last_name', $rs[0]['last_name'])
                ->bindValue(':profile_pic_path', $rs[0]['profile_pic_path'])
                ->bindValue(':is_active', $rs[0]['is_active'])
                ->bindValue(':user_note', $rs[0]['user_note'])
                ->bindValue(':created_by_user_id', $rs[0]['created_by_user_id'])
                ->bindValue(':created_on', $rs[0]['created_on'])
                ->bindValue(':roleId', $roleId)
				->bindValue(':user_phone_number', $rs[0]['user_phone_number'])
                ->bindValue(':loggedInUid', $loggedInUid)
                ->execute();
        } else {
            exit;
        }
    }

    /**
     * @param $postValues
     * @param $loggedInUid
     * @return int|string
     * @throws \yii\db\Exception
     */
    public function UpdateUser($postValues, $loggedInUid)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);

        $hdn_uid = $postValues['hdn_uid'];
        $user_email = $postValues['user_email'];
        $user_status = $postValues['user_status'];
        $user_note = $postValues['user_note'];
        $user_role = $postValues['user_role'];
        $first_name = !empty($postValues['first_name'])?$postValues['first_name']:"";
        $last_name = !empty($postValues['last_name'])?$postValues['last_name']:"";
		$phoneNo = $postValues['user_phone_no'];

        $user_status = CommonMethods::sanitizeUrlQueryString($user_status);

        $user_note = CommonMethods::sanitizeUrlQueryString($user_note);

        $isValidationErrorExists = false;

        if (!empty($hdn_uid)) {
            $uid = base64_decode(trim($hdn_uid));

            $uid = CommonMethods::sanitizeUrlQueryString($uid);

            if (empty($uid)) {
                $isValidationErrorExists = true;
            }
        } else {
            $isValidationErrorExists = true;
        }


        if ($isValidationErrorExists == true) {
            return "validation-error";
        }

        Users::CopyUserDetails_into_history($uid, $loggedInUid);

        $updateDataQuery = "UPDATE  /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USERS . " 
                            SET 
                                user_email=:user_email,
                                first_name=:first_name,
                                last_name=:last_name,
                                is_active=:is_active,
                                user_note=:user_note,                               
								user_phone_number=:user_phone_number,
								updated_by_user_id=:updated_by_user_id,
								updated_on = NOW() 
                            WHERE 
                                user_id=:user_id
                            ";



        //Insert into the Database
        $memberDetails = $dbConnectionHD
            ->createCommand($updateDataQuery)
            ->bindValue(':user_email', $user_email)
            ->bindValue(':first_name', $first_name)
            ->bindValue(':last_name', $last_name)
            ->bindValue(':is_active', $user_status)
            ->bindValue(':user_note', $user_note)
			->bindValue(':user_phone_number', $phoneNo)
            ->bindValue(':updated_by_user_id', (int)$loggedInUid)
            ->bindValue(':user_id', (int)$uid)
            ->execute();
        //Delete From table TBL_HD_ROLE_PAGES
        $model = $dbConnectionHD->createCommand('DELETE FROM /* " . __FILE__ . " " . __LINE__ . "  */ ' . DB_HD . '.' . TBL_HD_USER_ROLES . ' WHERE user_id=:user_id');
        $model->bindParam(':user_id', $uid);
        $model->execute();


        //Move to History
        $insertRoleMasterHistoryQry = "INSERT INTO /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USER_ROLES . " (user_id,role_id,role_assigned_by_user_id)  VALUES (:user_id,:role_id,:role_assigned_by_user_id)";
        $historyDetails = $dbConnectionHD
            ->createCommand($insertRoleMasterHistoryQry)
            ->bindValue(':role_id', $user_role)
            ->bindValue(':user_id', $uid)
            ->bindValue(':role_assigned_by_user_id', $loggedInUid)
            ->execute();

        return 1;
    }

    /**
     * @param $LogoutUserId
     * @param $LoggedUserRoleId
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveLogoutUserDetails($LogoutUserId, $LoggedUserRoleId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $UserIp = $_SERVER['REMOTE_ADDR'];

        $Year = date("Y");
        $Month = date("m");
        $Day = date("d");
        $Time = date("H:i:s");
        $reAuto = CommonMethods::sanitizeUrlQueryString(Yii::$app->request->get('auto', ''));
        if (!empty($reAuto)) {
            $isAuto = 'Y';
        } else {
            $isAuto = 'N';
        }

        $InsertLogoutDetails = "INSERT /* " . __FILE__ . " " . __LINE__ . "  */ 
                                INTO " . DB_HD . "." . TBL_HD_USER_LOGOUT_LOG . " 
                                    (user_logout_log_id,user_id,is_auto_logout,user_logged_out_role_id,user_ip,logout_year,logout_month,logout_day,logout_time)  
                                VALUES 
                                    (NULL,:LogoutUserId,:isAuto,:LoggedUserRoleId,:UserIp,:Year,:Month,:Day,:Time)";

        $ExecuteQuery = $dbConnectionHD
            ->createCommand($InsertLogoutDetails)
            ->bindValue(':LogoutUserId', $LogoutUserId)
            ->bindValue(':isAuto', $isAuto)
            ->bindValue(':LoggedUserRoleId', $LoggedUserRoleId)
            ->bindValue(':UserIp', $UserIp)
            ->bindValue(':Year', $Year)
            ->bindValue(':Month', $Month)
            ->bindValue(':Day', $Day)
            ->bindValue(':Time', $Time)
            ->execute();

        $selLoginDetails = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ user_login_log_id FROM " . DB_HD . "." . TBL_HD_USER_LOGIN_LOG . " WHERE user_id = :user_id ORDER BY user_login_log_id DESC LIMIT 1";
        $loginLogId = $updateExecuteQuery = $dbConnectionHD
            ->createCommand($selLoginDetails)
            ->bindValue(':user_id', $LogoutUserId)
            ->queryScalar();

        $loginLogId = empty($loginLogId) ? 0 : $loginLogId;
        $updateLoginDetails = "UPDATE /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USER_LOGIN_LOG . " SET logout_at = NOW() WHERE user_login_log_id = :user_login_log_id";
        $updateExecuteQuery = $dbConnectionHD
            ->createCommand($updateLoginDetails)
            ->bindValue(':user_login_log_id', $loginLogId)
            ->execute();
        return $ExecuteQuery;
    }

    /**
     * @param $LoginUserId
     * @param $LoggedUserRoleId
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveLoginUserDetails($LoginUserId, $LoggedUserRoleId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $UserIp = $_SERVER['REMOTE_ADDR'];

        $Year = date("Y");
        $Month = date("m");
        $Day = date("d");
        $Time = date("H:i:s");

        $loginDate = date('Y-m-d');

        $InsertLogoutDetails = "INSERT INTO /* " . __FILE__ . " " . __LINE__ . "  */
                                    " . DB_HD . "." . TBL_HD_USER_LOGIN_LOG . " 
                                    (user_login_log_id,user_id,user_logged_role_id,user_logged_ip,login_year,login_month,login_day,login_date,login_time)  
                                VALUES 
                                    (NULL,:user_id,:user_logged_role_id,:user_logged_ip,:login_year,:login_month,:login_day,:login_date,:login_time)";

        $ExecuteQuery = $dbConnectionHD
            ->createCommand($InsertLogoutDetails)
            ->bindValue(':user_id', $LoginUserId)
            ->bindValue(':user_logged_role_id', $LoggedUserRoleId)
            ->bindValue(':user_logged_ip', $UserIp)
            ->bindValue(':login_year', $Year)
            ->bindValue(':login_month', $Month)
            ->bindValue(':login_day', $Day)
            ->bindValue(':login_date', $loginDate)
            ->bindValue(':login_time', $Time)
            ->execute();

        return $ExecuteQuery;
    }

    /**
     * @param $arrVisitedPageDetails
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveUserPageVisit($arrVisitedPageDetails)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);

        $InsertVisitedPageDetails = "INSERT INTO /* " . __FILE__ . " " . __LINE__ . "  */
                                        " . DB_HD . "." . TBL_HD_USER_VISITS . " 
                                        (user_page_visit_id,visit_user_id,visited_user_role_id,visit_user_ip,visit_page_id,visit_year,visit_month,visit_day,visit_ts,visit_date)  
                                    VALUES 
                                        (NULL,:visit_user_id,:visited_user_role_id,:visit_user_ip,:visit_page_id,:visit_year,:visit_month,:visit_day,:visit_ts,CURRENT_DATE())";

        $ExecuteQuery = $dbConnectionHD
            ->createCommand($InsertVisitedPageDetails)
            ->bindValue(':visit_user_id', $arrVisitedPageDetails['0'])
            ->bindValue(':visited_user_role_id', $arrVisitedPageDetails['1'])
            ->bindValue(':visit_user_ip', $arrVisitedPageDetails['2'])
            ->bindValue(':visit_page_id', $arrVisitedPageDetails['3'])
            ->bindValue(':visit_year', $arrVisitedPageDetails['4'])
            ->bindValue(':visit_month', $arrVisitedPageDetails['5'])
            ->bindValue(':visit_day', $arrVisitedPageDetails['6'])
            ->bindValue(':visit_ts', $arrVisitedPageDetails['7'])
            ->execute();

        return $ExecuteQuery;
    }


    /**
     * @param $user_id
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getRoleIdFromUserId($user_id)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $UserRoleId = "";
        $stQryUserRole = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ role_id FROM " . TBL_HD_USER_ROLES . " WHERE user_id = :user_id";
        $UserRoleId = $dbConnectionHD
            ->createCommand($stQryUserRole)
            ->bindValue(':user_id', $user_id)
            ->queryScalar();
        return $UserRoleId;
    }

    /**
     * @param $UserRoleId
     * @return array|string
     * @throws \yii\db\Exception
     */
    public function getPageIdFromRoleId($UserRoleId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $stQryUserPages = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ page_id FROM " . TBL_HD_ROLE_PAGES . " WHERE role_id = :userRoleId";
        $rsUserRolePages = "";
        $rsUserRolePages = $dbConnectionHD
            ->createCommand($stQryUserPages)
            ->bindValue(':userRoleId', $UserRoleId)
            ->queryAll();
        return $rsUserRolePages;
    }

    /**
     * @param $UserRoleId
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getDefaultPageIdFromRoleId($UserRoleId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $stQryUserDefaultRolePageId = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ default_page_id FROM " . TBL_HD_ROLE_MASTER . " WHERE role_id = :userRoleId";
        $UserDefaultRolePageId = "";
        $UserDefaultRolePageId = $dbConnectionHD
            ->createCommand($stQryUserDefaultRolePageId)
            ->bindValue(':userRoleId', $UserRoleId)
            ->queryScalar();
        return $UserDefaultRolePageId;
    }

    /**
     * @param $UserDefaultRolePageId
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getRoleDefaultPageWithRoleId($UserDefaultRolePageId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $stQryRoleDefaultPage = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ CONCAT('/',controller_name,'/',action_name) FROM " . TBL_HD_PAGE_MASTER . " WHERE page_id = :userDefaultRolePageId && is_page_active='Y'";
        $UserRoleDefaultPage = "";
        $UserRoleDefaultPage = $dbConnectionHD
            ->createCommand($stQryRoleDefaultPage)
            ->bindValue(':userDefaultRolePageId', $UserDefaultRolePageId)
            ->queryScalar();
        return $UserRoleDefaultPage;
    }

    /**
     * @param $UserRoleId
     * @return array|string
     * @throws \yii\db\Exception
     */
    public function getRoleDefaultPageWithPageIdAndRoleId($UserRoleId)
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);
        $UserRoleDefaultPages = "";
        $stQryRoleDefaultPages = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ pm.controller_name,pm.action_name FROM " . TBL_HD_PAGE_MASTER . " pm INNER JOIN " . TBL_HD_ROLE_PAGES . " rp ON pm.page_id = rp.page_id && rp.role_id = :userRoleId WHERE pm.is_page_active='Y'";
        $UserRoleDefaultPages = $dbConnectionHD
            ->createCommand($stQryRoleDefaultPages)
            ->bindValue(':userRoleId', $UserRoleId)
            ->queryAll();
        return $UserRoleDefaultPages;
    }

    /**
     * @param int $minutes
     * @return string
     */
    public function fnGetHrsAndMtsFromMts($minutes = 0)
    {

        if(empty($minutes)) {
            return '';
        }
        $hourMinute = '';
        $hour = floor($minutes / 60);
        if(!empty($hour)) {
            $hourMinute = $hour.' hrs ';
        }
        $minutes = ($minutes -   floor($minutes / 60) * 60);
        if(!empty($minutes)) {
            $hourMinute .= $minutes.' mts';
        }
        return $hourMinute;
    }

    /**
     * @param $mobile
     * @param $message
     * @return bool|string
     */
    public function fnSendOTP($mobile,$message)

    {
        $request=$mobile.'/'.$message.'';
        $ch = curl_init('https://2factor.in/API/V1/'.OTP_SECRETE_KEY.'/SMS/'.$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $resuponce=curl_exec($ch);
        curl_close($ch);
        return $resuponce;

    }

    /**
     * @param string $mobileNo
     * @return int
     * @throws \yii\db\Exception
     */
    public function fnUpdateUserAuthentication($mobileNo = '')
    {
        $dbConnectionHD = CommonMethods::connectDb(DB_HD);

        $updateDataQuery = "UPDATE  /* " . __FILE__ . " " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_USERS . " 
                            SET 
                                is_moblie_number_verified=:yesVal,                                
								mobile_number_auth_code_generated_date_time = NOW() 
                            WHERE 
                                user_phone_number=:user_phone_number
                                && is_moblie_number_verified = :noVal
                            ";

        //Insert into the Database
        $memberDetails = $dbConnectionHD
            ->createCommand($updateDataQuery)
            ->bindValue(':user_phone_number', $mobileNo)
            ->bindValue(':noVal', 'N')
            ->bindValue(':yesVal', 'Y')
            ->execute();

        return 1;
    }

}
