<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\Response;
use yii\base\Model;

class Roles extends Model
{

    /**
     * @param int $roleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function GetAllRoles($roleId = 0)
    {
        $DbConnectionAW = CommonMethods::connectDb(DB_HD);

        if (!empty($roleId)) {
            $strQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ * FROM  " . DB_HD . "." . TBL_HD_ROLE_MASTER . " WHERE role_id=:role_id";
            $RoleDetails = $DbConnectionAW
                ->createCommand($strQuery)
                ->bindValue(':role_id', $roleId)
                ->queryAll();
        } else {
            $strQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */ * FROM  " . DB_HD . "." . TBL_HD_ROLE_MASTER . "";
            $RoleDetails = $DbConnectionAW
                ->createCommand($strQuery)
                ->queryAll();
        }

        if (!empty($RoleDetails)) {
            return $RoleDetails;
        } else {
            return $RoleDetails;
        }
    }

    /**
     * @param $roleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function GetPagesBasedOnRole($roleId)
    {
        $DbConnectionAW = CommonMethods::connectDb(DB_HD);
        $data = array();
        $strQuery = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ * FROM  " . DB_HD . "." . TBL_HD_ROLE_PAGES . " WHERE role_id=:role_id && is_pages_shown_in_leftbar=:is_pages_shown_in_leftbar";
        $RolePageDetailsRs = $DbConnectionAW
            ->createCommand($strQuery)
            ->bindValue(':role_id', (int)$roleId)
            ->bindValue(':is_pages_shown_in_leftbar', 'Y')
            ->queryAll();
        if (!empty($RolePageDetailsRs)) {
            foreach ($RolePageDetailsRs as $rs) {
                $pgId = $rs['page_id'];
                $pageDtlsSql = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ page_name FROM  " . DB_HD . "." . TBL_HD_PAGE_MASTER . " WHERE page_id=:page_id && is_page_active=:is_page_active";
                $pageDtlsRs = $DbConnectionAW
                    ->createCommand($pageDtlsSql)
                    ->bindValue(':page_id', (int)$pgId)
                    ->bindValue(':is_page_active', 'Y')
                    ->queryAll();
                if (!empty($pageDtlsRs)) {
                    $data[$pgId] = $pageDtlsRs[0]['page_name'];
                }
            }
        }
        return $data;
    }

    /**
     * @param $roleId
     * @return array
     * @throws \yii\db\Exception
     */
    public function GetPages_Not_Shown_in_LeftBar_BasedOnRole($roleId)
    {
        $DbConnectionAW = CommonMethods::connectDb(DB_HD);
        $data = array();
        $strQuery = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ * FROM  " . DB_HD . "." . TBL_HD_ROLE_PAGES . " WHERE role_id=:role_id && is_pages_shown_in_leftbar='N'";

        $RolePageDetailsRs = $DbConnectionAW
            ->createCommand($strQuery)
            ->bindValue(':role_id', $roleId)
            ->queryAll();
        if (!empty($RolePageDetailsRs)) {
            foreach ($RolePageDetailsRs as $rs) {
                $pgId = $rs['page_id'];
                $pageDtlsSql = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ page_name FROM  " . DB_HD . "." . TBL_HD_PAGE_MASTER . " WHERE page_id=:page_id && is_page_active='Y'";
                $pageDtlsRs = $DbConnectionAW
                    ->createCommand($pageDtlsSql)
                    ->bindValue(':page_id', $pgId)
                    ->queryAll();
                if (!empty($pageDtlsRs)) {
                    $data[$pgId] = $pageDtlsRs[0]['page_name'];
                }
            }
        }
        return $data;
    }

    /**
     * @param $userId
     * @return mixed|string
     * @throws \yii\db\Exception
     */
    public function GetRoleIdOfUser($userId)
    {
        $DbConnectionAW = CommonMethods::connectDb(DB_HD);
        $roleId = '';
        $strQuery = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ * FROM " . DB_HD . "." . TBL_HD_USER_ROLES . " WHERE user_id = (:user_id)";
        $userDetailsRs = $DbConnectionAW
            ->createCommand($strQuery)
            ->bindValue(':user_id', $userId)
            ->queryAll();
        if (!empty($userDetailsRs)) {
            foreach ($userDetailsRs as $u_arr) {
                $roleId = $u_arr['role_id'];
            }
        }
        return $roleId;
    }

    /**
     * @param $userId
     * @return mixed|string
     * @throws \yii\db\Exception
     */
    public function GetRoleNameOfUser($userId)
    {
        $DbConnectionAW = CommonMethods::connectDb(DB_HD);
        $roleName = '';
        $strQuery = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ * FROM " . DB_HD . "." . TBL_HD_USER_ROLES . " WHERE user_id = (:user_id)";
        $userDetailsRs = $DbConnectionAW
            ->createCommand($strQuery)
            ->bindValue(':user_id', $userId)
            ->queryAll();
        if (!empty($userDetailsRs)) {
            foreach ($userDetailsRs as $u_arr) {
                $roleId = $u_arr['role_id'];
                $roleSql = "SELECT /* " . __FILE__ . " LINE NO: " . __LINE__ . " */ * FROM " . DB_HD . "." . TBL_HD_ROLE_MASTER . " WHERE role_id = (:role_id)";
                $roleDetailsRs = $DbConnectionAW
                    ->createCommand($roleSql)
                    ->bindValue(':role_id', $roleId)
                    ->queryAll();
                if (!empty($roleDetailsRs)) {
                    $roleName = $roleDetailsRs[0]['role_name'];
                }
            }
        }
        return $roleName;
    }
}
