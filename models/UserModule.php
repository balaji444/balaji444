<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\ConfigureDailyTarget;
use app\models\Pcps;
use app\models\Users;

class UserModule extends Model
{
    public $page_name;
    public $controller_name;
    public $view_name;

    public function rules()
    {
        return [
            [['page_name', 'controller_name', 'view_name'], 'required'],
            //['email', 'email'],
        ];
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

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

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
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function fetchDataWIthID($tableName, $id = 0, $field_name = '')
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

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
     * @param $pageName
     * @param $tbl_name
     * @return false|string
     * @throws \yii\db\Exception
     */
    public function getPageDetails($pageName, $tbl_name)
    {

        $resultArr = array();
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        $fetchQry = "SELECT  /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                            page_id,
							 page_name,
							 page_description
					FROM 
							" . DB_HD . "." . $tbl_name . "
					WHERE
						  	(page_name like :pageName || page_name LIKE :pageName) && 
							is_page_active='Y'";
        /*		$memberDetails  = $DbConnectionAllPayers
                                  ->createCommand($strQuery)
                                   ->queryAll();
                                  */
        $result = $DbConnectionHD->createCommand($fetchQry)
            ->bindValue(':pageName', '%' . $pageName . '%')
            ->queryAll();
        if (!empty($result)) {
            foreach ($result as $results) {
                $pageDesc = $results['page_description'];
                $resultArr[] = $results['page_name'] . " (" . $results['page_id'] . ")@#|" . $pageDesc;
                //$resultArr[] = $results['page_name'];
            }
        } else {
            $resultArr[] = " No Result";
        }
        return json_encode($resultArr);
    }

    /**
     * @param $postValues
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveMappingRolesandPages($postValues)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $loggedInUid = CommonMethods::GetLoginUserId();


        //Build the insert query
        $insertQuery = "INSERT INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_MASTER . " (role_name, role_description,  default_page_id, role_added_by_user_id, role_added_on) VALUES (:role_name, :role_description, :default_page_id, :role_added_by_user_id, now())";

        if (empty($postValues['role_name']) || empty($postValues['role_description']) || empty($postValues['pstDefaultPageId'])) {
            return 2;
        }

        $arrPagesList = explode("#", $postValues['pagesList']);
        if (empty($arrPagesList)) {
            return 2;
        }

        $arrPages_not_show_List = explode(",", $postValues['pstNotShownPageIds']);
        //For Create Role
        if (empty($postValues['pstEditRoleId'])) {
            //Insert into the Database
            $memberDetails = $DbConnectionHD
                ->createCommand($insertQuery)
                ->bindValue(':role_name', CommonMethods::sanitizeUrlQueryString($postValues['role_name']))
                ->bindValue(':role_description', CommonMethods::sanitizeUrlQueryString($postValues['role_description']))
                ->bindValue(':default_page_id', CommonMethods::sanitizeUrlQueryString($postValues['pstDefaultPageId']))
                ->bindValue(':role_added_by_user_id', $loggedInUid)
                ->execute();


            $lastInsertID = $DbConnectionHD->getLastInsertID();

            //Pages shown in Leftbar
            foreach ($arrPagesList as $res_pgId) {
                $insertRolesPages = "INSERT IGNORE INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES . " (role_id, page_id,  page_assigned_by_user_id, page_assigned_on)  VALUES (:role_id, :page_id, :page_assigned_by_user_id, now()) ";

                //Insert into the Database
                $pgDetails = $DbConnectionHD
                    ->createCommand($insertRolesPages)
                    ->bindValue(':role_id', $lastInsertID)
                    ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($res_pgId))
                    ->bindValue(':page_assigned_by_user_id', $loggedInUid)
                    ->execute();
            }

            //Pages not shown in Leftbar
            foreach ($arrPages_not_show_List as $res_ns_pgId) {
                if (empty($res_ns_pgId)) {
                    continue;
                }
                $insertRolesPages = "INSERT IGNORE INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES . " (role_id, page_id,  page_assigned_by_user_id, page_assigned_on,is_pages_shown_in_leftbar)  VALUES (:role_id, :page_id, :page_assigned_by_user_id, NOW(),'N') ";

                //Insert into the Database
                $pgs_not_shown_Details = $DbConnectionHD
                    ->createCommand($insertRolesPages)
                    ->bindValue(':role_id', $lastInsertID)
                    ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($res_ns_pgId))
                    ->bindValue(':page_assigned_by_user_id', $loggedInUid)
                    ->execute();
            }
            UserModule::SaveLeftbarforRole($postValues['pstHeadingPageIds'], $lastInsertID, 'Add');
            //UserModule::fnSendNotificationEmailForRole($lastInsertID, $loggedInUid, "create");
			
            return 1;
        }
        //For Edit Role
        $remainIngPagesArr = array();
        if (!empty($postValues['pstEditRoleId'])) {
            $pstEditRoleId = base64_decode($postValues['pstEditRoleId']);
            $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ role_name,role_description, default_page_id FROM " . TBL_HD_ROLE_MASTER . " WHERE role_id=:role_id";
            $result = $DbConnectionHD
                ->createCommand($sql)
                ->bindValue(':role_id', $pstEditRoleId)
                ->queryAll();
            if (!empty($result)) {
                $oldRoleName = CommonMethods::sanitizeUrlQueryString($result[0]['role_name']);
                $oldRoleDescription = CommonMethods::sanitizeUrlQueryString($result[0]['role_description']);
                $old_default_page_id = CommonMethods::sanitizeUrlQueryString($result[0]['default_page_id']);

                //Move to History
                $insertRoleMasterHistoryQry = "INSERT INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_MASTER_HISTORY . " (role_id, role_name,  role_description, default_page_id ,role_update_by_user_id,
						 role_updated_on)  VALUES (:role_id, :role_name,:role_description, :default_page_id, :role_update_by_user_id,NOW()) ";

                $historyDetails = $DbConnectionHD
                    ->createCommand($insertRoleMasterHistoryQry)
                    ->bindValue(':role_id', $pstEditRoleId)
                    ->bindValue(':role_name', $oldRoleName)
                    ->bindValue(':role_description', $oldRoleDescription)
                    ->bindValue(':default_page_id', $old_default_page_id)
                    ->bindValue(':role_update_by_user_id', $loggedInUid)
                    ->execute();

                $updateQuery = "UPDATE /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_MASTER . "  SET role_name = :role_name, role_description = :role_description, default_page_id = :default_page_id WHERE role_id = :role_id";

                //Insert into the Database
                $memberDetails = $DbConnectionHD
                    ->createCommand($updateQuery)
                    ->bindValue(':role_name', CommonMethods::sanitizeUrlQueryString($postValues['role_name']))
                    ->bindValue(':role_description', CommonMethods::sanitizeUrlQueryString($postValues['role_description']))
                    ->bindValue(':default_page_id', CommonMethods::sanitizeUrlQueryString($postValues['pstDefaultPageId']))
                    ->bindValue(':role_id', $pstEditRoleId)
                    ->execute();


                if (!empty($postValues['pstOldpgsLst'])) {
                    $oldPgsLstArr = explode("#", $postValues['pstOldpgsLst']);
                    if (!empty($oldPgsLstArr)) {
                        foreach ($oldPgsLstArr as $old_pageId) {
                            if (in_array($old_pageId, $arrPagesList)) {
                                $ss = 0;
                            } else {
                                $remainIngPagesArr[] = $old_pageId;
                            }
                        }
                    }
                }
                if (!empty($remainIngPagesArr)) {
                    foreach ($remainIngPagesArr as $r_pgId) {
                        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ page_assigned_by_user_id,page_assigned_on FROM " . TBL_HD_ROLE_PAGES . " WHERE role_id=:role_id && page_id=:page_id";
                        //$result=$DbConnectionHD->createCommand($sql)->queryAll();
                        $result = $DbConnectionHD
                            ->createCommand($sql)
                            ->bindValue(':role_id', $pstEditRoleId)
                            ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($r_pgId))
                            ->queryAll();
                        if (!empty($result)) {
                            $addedBy = $result[0]['page_assigned_by_user_id'];
                            $addedOn = $result[0]['page_assigned_on'];

                            //Move to History
                            $insertMappingHistoryQry = "INSERT INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES_HISTORY . " (role_id, page_id,  page_updated_by_user_id,
						 page_updated_on)  VALUES (:role_id, :page_id, :page_updated_by_user_id,NOW()) ";

                            $historyDetails = $DbConnectionHD
                                ->createCommand($insertMappingHistoryQry)
                                ->bindValue(':role_id', $pstEditRoleId)
                                ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($r_pgId))
                                ->bindValue(':page_updated_by_user_id', $loggedInUid)
                                ->execute();

                            //Delete From table TBL_HD_ROLE_PAGES
                            $model = $DbConnectionHD->createCommand('DELETE /* " . __FILE__ . " Line No: " . __LINE__ . "  */ FROM ' . DB_HD . "." . TBL_HD_ROLE_PAGES . ' WHERE role_id=:role_id && page_id=:page_id');
                            $model->bindParam(':role_id', $pstEditRoleId);
                            $model->bindParam(':page_id', $r_pgId);
                            $model->execute();
                        }
                    }
                }
                //Pages shown in Leftbar
                foreach ($arrPagesList as $pageIdVal) {
                    if (1 == 1) {
                        $insertRolesPages = "INSERT IGNORE INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES . " (role_id, page_id,  page_assigned_by_user_id, page_assigned_on,is_pages_shown_in_leftbar)  VALUES (:role_id, :page_id, :page_assigned_by_user_id, NOW(),'Y') ";

                        //Insert into the Database
                        $memberDetails = $DbConnectionHD
                            ->createCommand($insertRolesPages)
                            ->bindValue(':role_id', $pstEditRoleId)
                            ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($pageIdVal))
                            ->bindValue(':page_assigned_by_user_id', $loggedInUid)
                            ->execute();

                    }
                }
                //Pages not shown in Leftbar

                if (!empty($postValues['pstNotShownOldPageIds'])) {
                    $old_NS_PgsLstArr = explode(",", $postValues['pstNotShownOldPageIds']);
                    if (!empty($old_NS_PgsLstArr)) {
                        foreach ($old_NS_PgsLstArr as $old_pageId) {
                            if (empty($old_pageId)) {
                                continue;
                            }
                            if (in_array($old_pageId, $arrPages_not_show_List)) {
                                $ss = 0;
                            } else {
                                $remainIngPages_NS_Arr[] = $old_pageId;
                            }
                        }
                    }
                }
                if (!empty($remainIngPages_NS_Arr)) {
                    foreach ($remainIngPages_NS_Arr as $r_pgId) {
                        if (empty($r_pgId)) {
                            continue;
                        }
                        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ page_assigned_by_user_id,page_assigned_on FROM " . TBL_HD_ROLE_PAGES . " WHERE role_id=:role_id && page_id=:page_id";
                        //$result=$DbConnectionHD->createCommand($sql)->queryAll();
                        $result = $DbConnectionHD
                            ->createCommand($sql)
                            ->bindValue(':role_id', $pstEditRoleId)
                            ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($r_pgId))
                            ->queryAll();
                        if (!empty($result)) {
                            $addedBy = $result[0]['page_assigned_by_user_id'];
                            $addedOn = $result[0]['page_assigned_on'];

                            //Move to History
                            $insertMappingHistoryQry = "INSERT INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES_HISTORY . " (role_id, page_id,  page_updated_by_user_id,
					   page_updated_on)  VALUES (:role_id, :page_id, :page_updated_by_user_id,NOW()) ";

                            $historyDetails = $DbConnectionHD
                                ->createCommand($insertMappingHistoryQry)
                                ->bindValue(':role_id', $pstEditRoleId)
                                ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($r_pgId))
                                ->bindValue(':page_updated_by_user_id', $loggedInUid)
                                ->execute();

                            //Delete From table TBL_HD_ROLE_PAGES_NOT_SHOWN_IN_LEFTBAR
                            $model_v2 = $DbConnectionHD->createCommand("DELETE FROM /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES . " WHERE role_id=:role_id && page_id=:page_id");
                            $model_v2->bindParam(':role_id', $pstEditRoleId);
                            $model_v2->bindParam(':page_id', $r_pgId);
                            $model_v2->execute();
                        }
                    }
                }


                foreach ($arrPages_not_show_List as $res_ns_pgId) {
                    if (empty($res_ns_pgId)) {
                        continue;
                    }
                    $insertRolesPages = "INSERT IGNORE INTO /* " . __FILE__ . " Line No: " . __LINE__ . "  */ " . DB_HD . "." . TBL_HD_ROLE_PAGES . " (role_id, page_id,  page_assigned_by_user_id, page_assigned_on,is_pages_shown_in_leftbar)  VALUES (:role_id, :page_id, :page_assigned_by_user_id, NOW(),'N') ";

                    //Insert into the Database
                    $pgs_not_shown_Details = $DbConnectionHD
                        ->createCommand($insertRolesPages)
                        ->bindValue(':role_id', $pstEditRoleId)
                        ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($res_ns_pgId))
                        ->bindValue(':page_assigned_by_user_id', $loggedInUid)
                        ->execute();
                }

                UserModule::SaveLeftbarforRole($postValues['pstHeadingPageIds'], $pstEditRoleId, 'Edit');

                //UserModule::fnSendNotificationEmailForRole($pstEditRoleId, $loggedInUid, "update");
				
                return 1;
            }
        }

    }


    /**
     * @param $tableName
     * @param $postValues
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function HeadingNameExsists($tableName, $postValues)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //Build the insert query
        $fetchQuery = "SELECT 
									COUNT(1) 
								FROM 
									" . $tableName . " 
								WHERE 
									heading_name =  :heading_name";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':heading_name', $postValues['heading_name'])
            ->queryScalar();

        return $queryResult;

    }

    /**
     * @param $tableName
     * @param $postValues
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveLefbarHeading($tableName, $postValues)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //Build the insert query
        $insertQuery = "INSERT INTO " . $tableName . " (heading_name, heading_added_by_user_id, heading_added_on) VALUES (:heading_name, :page_added_by_user_id, now())";

        //Insert into the Database
        $memberDetails = $DbConnectionHD
            ->createCommand($insertQuery)
            ->bindValue(':heading_name', $postValues['heading_name'])
            ->bindValue(':page_added_by_user_id', $user_id)
            ->execute();

        return 1;

    }


    /**
     * @param $history_tbl_name
     * @param $tbl_name
     * @param $field_name
     * @param $postValues
     * @return int
     * @throws \yii\db\Exception
     */
    public function EditLefbarHeading($history_tbl_name, $tbl_name, $field_name, $postValues)
    {
        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID

        $user_id = CommonMethods::GetLoginUserId();
        if(empty($user_id)) {
            $user_id = '1';
        }
        $insertQuery = "INSERT  INTO " . $history_tbl_name . " (heading_id, heading_name, heading_edited_by_user_id, heading_edited_on) SELECT heading_id, heading_name, " . $user_id . ", now() FROM  " . $tbl_name . " WHERE " . $field_name . " = :heading_id";

        $queryResult = $DbConnectionHD->createCommand($insertQuery)
            ->bindValue(':heading_id', $postValues['heading_id'])
            ->execute();


        //update to main table
        $updatePageData = "UPDATE /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			" . $tbl_name . " 
		SET 
			heading_name = :heading_name
		WHERE
			heading_id = :heading_id";

        //Insert into the Database
        $updatePageData = $DbConnectionHD
            ->createCommand($updatePageData)
            ->bindValue(':heading_name',  $postValues['heading_name'])
            ->bindValue(':heading_id',  $postValues['heading_id'])
            ->execute();
        return 1;
    }

    /**
     * @param $pageName
     * @param $tbl_name
     * @return false|string
     * @throws \yii\db\Exception
     */
    public function getHeadingDetails($pageName, $tbl_name)
    {

        $resultArr = array();
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        $fetchQry = "SELECT  /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                                heading_id
								   ,heading_name
						  	FROM " . $tbl_name . "
						  WHERE
						  	(heading_name like :pageName || heading_name LIKE :pageName)";
        /*		$memberDetails  = $DbConnectionAllPayers
                                  ->createCommand($strQuery)
                                   ->queryAll();
                                  */
        $result = $DbConnectionHD->createCommand($fetchQry)
            ->bindValue(':pageName', '%' . $pageName . '%')
            ->queryAll();
        if (!empty($result)) {
            foreach ($result as $results) {
                $resultArr[] = $results['heading_name'] . " (" . $results['heading_id'] . ")";
                //$resultArr[] = $results['page_name'];
            }
        } else {
            $resultArr[] = " No Result";
        }
        return json_encode($resultArr);
    }

    /**
     * @param $string
     * @param $role_id
     * @param $type
     * @return int
     * @throws \yii\db\Exception
     */
    public function SaveLeftbarforRole($string, $role_id, $type)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();
        if(empty($user_id))
            $user_id = 0;

        $stringEx = explode("|", $string);

        if ($type == 'Edit') {
            $insertQuery = "INSERT  /* " . __FILE__ . " Line No: " . __LINE__ . "  */ INTO " . TBL_HD_ROLE_LEFTBAR_HISTORY . " (role_leftbar_rec_id, role_id, heading_id, page_id, heading_display_order_id, page_display_order_id, role_leftbar_edited_by_user_id, role_leftbar_edited_on) SELECT rec_id, role_id, heading_id, page_id, heading_display_order_id, page_display_order_id, " . $user_id . ", now() FROM  " . TBL_HD_ROLE_LEFTBAR . " WHERE role_id = :role_id";

            $queryResult = $DbConnectionHD->createCommand($insertQuery)
                ->bindValue(':role_id', $role_id)
                ->execute();

            $deleteQuery = "DELETE /* " . __FILE__ . " Line No: " . __LINE__ . "  */ FROM " . TBL_HD_ROLE_LEFTBAR . " WHERE role_id = :role_id";
            $deleteResult = $DbConnectionHD->createCommand($deleteQuery)
                ->bindValue(':role_id', $role_id)
                ->execute();

        }
        $headingOrder = 1;

        foreach ($stringEx as $res) {
            $headingsPageIds = explode("#", $res);
            $heading_id = array_shift($headingsPageIds);
            foreach ($headingsPageIds as $pageIdsWithComa) {
                $arrPageIds = explode(",", $pageIdsWithComa);
                $pageOrder = 1;
                if (!empty($arrPageIds)) {
                    foreach ($arrPageIds as $pageIds) {
                        if (empty($pageIds)) {
                            continue;
                        }
                        //Build the insert query
                        $insertQuery = "INSERT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ INTO " . TBL_HD_ROLE_LEFTBAR . " (role_id, heading_id, page_id, heading_display_order_id, page_display_order_id,role_leftbar_added_by_user_id, role_leftbar_added_on) VALUES (:role_id, :heading_id, :page_id, :heading_display_order_id, :page_display_order_id,:role_leftbar_added_by_user_id, now())";

                        //Insert into the Database
                        $memberDetails = $DbConnectionHD
                            ->createCommand($insertQuery)
                            ->bindValue(':role_id', $role_id)
                            ->bindValue(':heading_id', CommonMethods::sanitizeUrlQueryString($heading_id))
                            ->bindValue(':page_id', CommonMethods::sanitizeUrlQueryString($pageIds))
                            ->bindValue(':heading_display_order_id', $headingOrder)
                            ->bindValue(':page_display_order_id', $pageOrder)
                            ->bindValue(':role_leftbar_added_by_user_id', $user_id)
                            ->execute();
                        $pageOrder++;
                    }
                }
            }
            $headingOrder++;
        }

        return 1;

    }

    /**
     * @param $role_id
     * @return array
     * @throws \yii\db\Exception
     */
    public function fetchRoleHeadingsPages($role_id)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //Build the insert query
        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									*
								FROM 
									" . TBL_HD_ROLE_LEFTBAR . " 
								WHERE 
									role_id =  :role_id
								ORDER BY 
									heading_display_order_id, 
									page_display_order_id";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':role_id', $role_id)
            ->queryAll();

        $rolesHeadingPages = array();
        foreach ($queryResult as $res) {
            $rolesHeadingPages[$res['heading_id']][] = $res['page_id'];
        }
        return $rolesHeadingPages;
    }

    /**
     * @param $heading_id
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getHeadingName($heading_id)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //Build the insert query
        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
									heading_name
								FROM 
									" . TBL_HD_LEFTBAR_HEADING_MASTER . " 
								WHERE 
									heading_id =  :heading_id";

        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':heading_id', $heading_id)
            ->queryScalar();

        return $queryResult;

    }

    /**
     * @param $page_id
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function getPageName($page_id)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        //get Logged User ID
        $user_id = CommonMethods::GetLoginUserId();

        //Build the insert query
        $fetchQuery = "SELECT  /* " . __FILE__ . " LINE NO: " . __LINE__ . " */
									page_name
								FROM 
									" . DB_HD . "." . TBL_HD_PAGE_MASTER . " 
								WHERE 
									page_id =  :page_id &&
								    is_page_active = 'Y'";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':page_id', $page_id)
            ->queryScalar();

        return $queryResult;

    }

    /**
     * @param $page_id
     * @return string
     * @throws \yii\db\Exception
     */
    public function getPageLink($page_id)
    {

        //get DB Connection
        $DbConnectionHD = CommonMethods::connectDb(DB_HD);

        $pageLink = "#";

        $fetchQuery = "SELECT  /* " . __FILE__ . " LINE NO: " . __LINE__ . " */
									action_name,
									controller_name
								FROM 
									" . DB_HD . "." . TBL_HD_PAGE_MASTER . " 
								WHERE 
									page_id =  :page_id";

        //Insert into the Database
        $queryResult = $DbConnectionHD
            ->createCommand($fetchQuery)
            ->bindValue(':page_id', $page_id)
            ->queryOne();

        if (!empty($queryResult)) {
            $pageLink = "/sathsang/web/" . strtolower($queryResult['controller_name']) . "/" . $queryResult['action_name'];
        }
        return $pageLink;
    }

    /**
     * @param int $roleId
     * @return array|string
     * @throws \yii\db\Exception
     */
	public function fnGetLastUpdatedByUpdatedOnDetails($roleId=0) {
		$data = "";
		if(!empty($roleId)) {
			//get DB Connection
    	    $commonMethodsClassObj = new CommonMethods();
        	$DbConnectionHD = $commonMethodsClassObj->connectDb(DB_HD);
			
			$roleSql = "SELECT /* " . __FILE__ . " " . __LINE__ . "  */ * 
						FROM " . DB_HD . "." . TBL_HD_ROLE_PAGES . " WHERE 
						role_id=:role_id ORDER BY 
						page_assigned_on DESC LIMIT 1";
        	$data = $DbConnectionHD
            ->createCommand($roleSql)
            ->bindValue(':role_id', $roleId)
            ->queryAll();
		}
		return $data;
	}
	
}

?>