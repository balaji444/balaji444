<?php

namespace app\models;

use Yii;
use yii\db\Connection;
use yii\web\Response;
use yii\web\JsExpression;
use yii\base\Model;
use yii\web\Session;
use yii\web\Cookie;
use app\models\UserModule;
use app\models\Pcps;
use app\models\CmsHcc;
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
class Admin extends Model
{

    /**
     * @param $tableName
     * @param $postValues
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function checkAlreadyExsists($tableName, $postValues)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $DbConnectionHD             =   $commonMethodsClassObj->connectDb(DB_HD);

        $queryResult = '';
        $page_id = '';
        if (empty($postValues['page_id'])) {
            //Build the insert query
            $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									  COUNT(1) 
								  FROM 
									  " . $tableName . " 
								  WHERE 
									  action_name =  :action_name 
									  && controller_name = :controller_name";

            //Extract the post array
            $queryResult = $DbConnectionHD
                ->createCommand($fetchQuery)
                ->bindValue(':action_name', $postValues['action_name'])
                ->bindValue(':controller_name', $postValues['controller_name'])
                ->queryScalar();
        }
        if (!empty($postValues['page_id'])) {
            $page_id = $postValues['page_id'];
            $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									COUNT(1) 
								FROM 
									" . $tableName . " 
								WHERE 
									action_name =  :action_name 
									&& controller_name = :controller_name
									&& page_id!=:page_id";
            //Extract the post array
            $queryResult = $DbConnectionHD
                ->createCommand($fetchQuery)
                ->bindValue(':action_name', $postValues['action_name'])
                ->bindValue(':controller_name', $postValues['controller_name'])
                ->bindValue(':page_id', $page_id)
                ->queryScalar();
        }
        return $queryResult;
    }

    /**
     * @param $tableName
     * @param $postValues
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveValueInDB($tableName, $postValues)
    {

        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $DbConnectionHD             =   $commonMethodsClassObj->connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //defined variables
        $tblfieldNames = "";
        $insertValues = "";
        $columnNames = "";

        //consturct the variables with values
        foreach ($postValues as $key => $value) {

            if ($key != '_csrf') {
                $columnNames .= $key . ", ";
                $tblfieldNames .= ":" . $key . ", ";
                $insertValues .= "'" . $value . "', ";
            }

        }

        //remove the last Comma from the variables
        $tblfieldNames = substr($tblfieldNames, 0, -2);
        $insertValues = substr($insertValues, 0, -2);
        $columnNames = substr($columnNames, 0, -2);

        //Build the insert query
        $insertQuery = "INSERT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ INTO " . DB_HD . "." . $tableName . " (" . $columnNames . ", page_added_by_user_id) VALUES (" . $tblfieldNames . ", :page_added_by_user_id)";

        //Insert into the Database
        $memberDetails = $DbConnectionHD
            ->createCommand($insertQuery)
            ->bindValue(':page_name', $postValues['page_name'])
            ->bindValue(':action_name', $postValues['action_name'])
            ->bindValue(':controller_name', $postValues['controller_name'])
            ->bindValue(':page_description', $postValues['page_description'])
            ->bindValue(':page_display_icon_css_class_name', $postValues['page_display_icon_css_class_name'])
            ->bindValue(':page_added_by_user_id', $user_id)
            ->bindValue(':is_page_active', $postValues['is_page_active'])
            ->execute();

        return 1;

    }

    /**
     * @param $tableName
     * @param string $userIdColumnName
     * @return array
     * @throws \yii\db\Exception
     */
    public function fetchTableValues($tableName, $userIdColumnName = '')
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);
        

        //Build the insert query
        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									*, (SELECT CONCAT(last_name,', ', first_name) FROM " . DB_HD . "." . TBL_HD_USERS . " as u WHERE u.user_id = t." . $userIdColumnName . ") as added_by_name
								FROM 
									" . DB_HD . "." . $tableName . " t ";
        //fetch values from Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->queryAll();

        return $queryResult;

    }

    /**
     * @param $tableName
     * @param int $id
     * @param string $field_name
     * @return array
     * @throws \yii\db\Exception
     */
    public function fetchDataWIthIDHistory($tableName, $id = 0, $field_name = '')
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //Build the insert query
        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									*, (SELECT CONCAT(last_name,', ', first_name) FROM " . DB_HD . "." . TBL_HD_USERS . " as u WHERE u.user_id = t.page_added_by_user_id) as added_by_name 
								FROM 
									" . DB_HD . "." . $tableName . "  as t
								WHERE 
									" . $field_name . " =  :" . $field_name . " ";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':' . $field_name, $id)
            ->queryAll();

        return $queryResult;

    }

    /**
     * @param $history_tbl_name
     * @param $tbl_name
     * @param $field_name
     * @param $postValues
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveHistoryofPagesandUpdate($history_tbl_name, $tbl_name, $field_name, $postValues)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        $insertQuery = "INSERT  /* " . __FILE__ . " Line No: " . __LINE__ . "  */ INTO " . DB_HD . "." . $history_tbl_name . " (page_id, page_name, action_name, controller_name, page_description, page_display_icon_css_class_name,is_page_active,page_added_by_user_id, page_added_on) SELECT page_id, page_name, action_name, controller_name, page_description, page_display_icon_css_class_name,is_page_active,page_added_by_user_id, page_added_on FROM  " . $tbl_name . " WHERE " . $field_name . " = :page_id";

        $queryResult = $DbConnectionHD->createCommand($insertQuery)
            ->bindValue(':page_id', $postValues['page_id'])
            ->execute();


        //update to main table
        $updatePageData = "UPDATE 
			" . DB_HD . "." . $tbl_name . " 
		SET 
			page_name = :page_name,
			action_name = :action_name,
			controller_name = :controller_name,
			page_description = :page_description,
			page_display_icon_css_class_name=:page_display_icon_css_class_name,
			is_page_active=:is_page_active,
			page_added_by_user_id = :page_added_by_user_id,
			page_added_on = now()
		WHERE
			page_id = :page_id";

        //Insert into the Database
        $updatePageData = $DbConnectionHD
            ->createCommand($updatePageData)
            ->bindValue(':page_name', $postValues['page_name'])
            ->bindValue(':action_name', $postValues['action_name'])
            ->bindValue(':controller_name', $postValues['controller_name'])
            ->bindValue(':page_description', $postValues['page_description'])
            ->bindValue(':page_display_icon_css_class_name', $postValues['page_display_icon_css_class_name'])
            ->bindValue(':is_page_active', $postValues['is_page_active'])
            ->bindValue(':page_added_by_user_id', $user_id)
            ->bindValue(':page_id', $postValues['page_id'])
            ->execute();
        return 1;
    }

    /**
     * @param $tableName
     * @param int $id
     * @param string $field_name
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function fetchDataWIthID($tableName, $id = 0, $field_name = '')
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //Build the insert query
        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									* 
								FROM 
									" . $tableName . " 
								WHERE 
									" . $field_name . " =  :" . $field_name . " ";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':' . $field_name, $id)
            ->queryOne();

        return $queryResult;
    }

    /**
     * @param $tableName
     * @return array
     * @throws \yii\db\Exception
     */
    public function fetchRoleValues($tableName)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //Build the insert query
        $fetchQuery = "	SELECT  
									rm.role_id, rm.role_name, rm.role_description, rm.default_page_id,
									COUNT(DISTINCT rp.page_id) as pageCnt, 
									COUNT(DISTINCT IF(rp.is_pages_shown_in_leftbar = 'Y', CONCAT(rp.page_id), NULL)) as pagesCnt_show_in_LeftBar,  
									COUNT(DISTINCT IF(rp.is_pages_shown_in_leftbar = 'N', CONCAT(rp.page_id), NULL)) as pagesCnt_not_shown_in_LeftBar,  									
									
									(SELECT CONCAT(last_name,', ', first_name) FROM " . DB_HD . "." . TBL_HD_USERS . " as u WHERE u.user_id = rm.role_added_by_user_id) as added_by_name, rm.role_added_on
								FROM
									" . DB_HD . "." . TBL_HD_ROLE_MASTER . " as rm
								INNER JOIN
									" . DB_HD . "." . TBL_HD_ROLE_PAGES . " as rp
								ON
									rm.role_id = rp.role_id
								GROUP BY
									rm.role_id";
        //fetch values from Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->queryAll();

        return $queryResult;
    }

    /**
     * @param $role_id
     * @param $displayType
     * @return array
     * @throws \yii\db\Exception
     */
    public function fetchPagesAssignedToRoles($role_id, $displayType)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //Build the insert query
        $fetchQuery = "SELECT
									rp.role_id, pm.page_id, pm.page_name
								FROM
									" . DB_HD . "." . TBL_HD_ROLE_PAGES . " as rp
								INNER JOIN
									" . DB_HD . "." . TBL_HD_PAGE_MASTER . " as pm
								ON
									pm.page_id = rp.page_id
								
								WHERE
									rp.role_id = :role_id && 
									rp.is_pages_shown_in_leftbar=:is_pages_shown_in_leftbar";
        //fetch values from Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':role_id', $role_id)
            ->bindValue(':is_pages_shown_in_leftbar', $displayType)
            ->queryAll();

        return $queryResult;

    }

    /**
     * @param $title
     * @param $description
     * @param $uploadType
     * @param $filename
     * @return int
     * @throws \yii\db\Exception
     */
    public function fnInsertFileUploadData($title, $description, $uploadType, $filename)
    {

        //get DB Connection
        $DbConnectionHd = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $userId = CommonMethods::GetLoginUserId();

        if(empty($userId))
            $userId = 0;

        if($uploadType !='youtube_url') {
            $binaryHash = hash_file('sha512', UPLOAD_FILES_SERVER_PATH . '/' . $filename);
        } else {
            $binaryHash = hash_file('sha512', $filename);
        }

        //Build the insert query
        $insertQuery = "INSERT INTO 
								" . DB_HD . "." . TBL_HD_CONTENT . " (
										content_title, 
										content_type,
										content_description,
										content_path,
										file_content_unique_binary_hash, 
										uploaded_by_user_id, 
										uploaded_on) 
								VALUES 
									(	:content_title, 
										:content_type,
										:content_description,
										:content_path, 
										:file_content_unique_binary_hash,
										:uploaded_by_user_id, 
										NOW())";

        //Insert into the Database
        $insertExecute = $DbConnectionHd
            ->createCommand($insertQuery)
            ->bindValue(':content_title', $title)
            ->bindValue(':content_type', $uploadType)
            ->bindValue(':content_description', $description)
            ->bindValue(':content_path', $filename)
            ->bindValue(':file_content_unique_binary_hash', $binaryHash)
            ->bindValue(':uploaded_by_user_id', $userId)
            ->execute();

        return $insertExecute;

    }

    public function fnCheckFileAlreadyExists($filename = '')
    {
        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        $binaryHash = hash_file('sha512', UPLOAD_FILES_SERVER_PATH . '/' . $filename);
        //Build the insert query
        $fetchQuery = "SELECT
								*	
								FROM
									" . DB_HD . "." . TBL_HD_CONTENT . "								
								WHERE
									file_content_unique_binary_hash = :file_content_unique_binary_hash";
        //fetch values from Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':file_content_unique_binary_hash', $binaryHash)
            ->queryAll();

        return $queryResult;
    }
}