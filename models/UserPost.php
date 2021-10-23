<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\Response;
use yii\base\Model;
use app\models\Clinicalcrosswalk;
use app\models\TitanRulesDashboard;
use app\models\Clinicalmapping;
use app\models\Msr;
use app\models\Mphi;
use app\models\MphiReplica;

class UserPost extends Model
{

    /**
     * @param int $userId
     * @return array
     * @throws \yii\db\Exception
     */
    public function fnGetUserPosts($userId = 0, $postId = 0)
    {
        $dbConnectionAW = CommonMethods::connectDb(DB_HD);

        $whereCondition = [];
        if(!empty($userId)) {
            $whereCondition[] = 'post_by_user_id = :post_by_user_id';
        }

        if(!empty($postId)) {
            $whereCondition[] = 'a.post_id = :post_id';
        }

        $condition = '';
        if(!empty($whereCondition)) {
            $condition = 'WHERE' . implode('&&', $whereCondition);
        }


        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                         a.*,
                         b.post_description
                    FROM
                          " . DB_AW . "." . TBL_HD_POSTS . " as a
                    INNER JOIN
                        " . DB_AW . "." . TBL_HD_POSTS_DESCRIPTION . " as b
                    ON
                        (a.post_id = b.post_id)
                    $condition";

        $rsState = $dbConnectionAW
            ->createCommand($sql);
        if(!empty($userId)) {
            $rsState->bindValue(':post_by_user_id', (int)$userId);
        }
        if(!empty($postId)) {
            $rsState->bindValue(':post_id', (int)$postId);
        }
        $rs = $rsState->queryAll();
        return $rs;
    }

    /**
     * @param int $postId
     * @return array|string
     * @throws \yii\db\Exception
     */
    public function fnGetPostTags($postId = 0)
    {
        $dbConnectionAW = CommonMethods::connectDb(DB_HD);

        if(empty($postId)) {
            return '';
        }

        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                         b.tag_title
                    FROM
                          " . DB_AW . "." . TBL_HD_POST_TAGS . " as a
                    INNER JOIN
                        " . DB_AW . "." . TBL_HD_TAGS . " as b
                    ON
                        (a.post_id = b.post_id)
                    WHERE 
                        a.post_id = :post_id";

        $rsState = $dbConnectionAW
            ->createCommand($sql);
        if(!empty($userId)) {
            $rsState->bindValue(':post_id', (int)$postId);
        }
        $rs = $rsState->queryAll();
        return $rs;
    }

    /**
     * @param int $postId
     * @param string $postTitle
     * @param string $postDescription
     * @param array $postTags
     * @param int $loggedInUid
     * @return string
     * @throws \yii\db\Exception
     */
    public function fnSaveUserPostDetails($postId = 0,$postTitle = '',$postDescription = '',$postTags = [],$loggedInUid = 0)
    {

        UserPost::fnInsertUserPostDetails($postId,$postTitle,$postDescription,$postTags,$loggedInUid);

        return '1';
    }

    /**
     * @param int $postId
     * @param string $postTitle
     * @param string $postDescription
     * @param array $postTags
     * @param int $loggedInUid
     * @return int|string
     * @throws \yii\db\Exception
     */
    public function fnInsertUserPostDetails($postId = 0,$postTitle = '',$postDescription = '',$postTags = [],$loggedInUid = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        if(empty($postId)) {
            $insSql = "INSERT INTO   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POSTS . " 
		  SET 
			  post_title = :post_title,			  
			  post_by_user_id = :post_by_user_id,
			  created_at = now()
		  ";

            //Insert into the Database
            $dbConnectionAW
                ->createCommand($insSql)
                ->bindValue(':post_title', $postTitle)
                ->bindValue(':post_by_user_id', (int)$loggedInUid)
                ->execute();

            $postRecId = $dbConnectionAW->getLastInsertID();
        } else {
            $insSql = "UPDATE TABLE   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POSTS . " 
		  SET 
			  post_title = :post_title,			  
			  post_by_user_id = :post_by_user_id,
			  created_at = now()
		  WHERE 
		    post_id = :post_id  
		  ";

            //Insert into the Database
            $dbConnectionAW
                ->createCommand($insSql)
                ->bindValue(':post_id', $postId)
                ->bindValue(':post_title', $postTitle)
                ->bindValue(':post_by_user_id', (int)$loggedInUid)
                ->execute();

            $postRecId = $postId;
        }
        UserPost::fnInsertUserPostDescription($postRecId,$postDescription);
        UserPost::fnInsertUserPostTags($postRecId,$postTags);
        return $postRecId;
    }

    /**
     * @param int $postId
     * @param string $postDescription
     * @param int $loggedInUid
     * @throws \yii\db\Exception
     */
    public function fnInsertUserPostDescription($postId = 0,$postDescription = '',$loggedInUid = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        UserPost::fnDeleteUserPostDescription($postId);
        $insSql = "INSERT INTO   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POST_DESCRIPTION . " 
		  SET 						  
			  post_id = :post_id,
			  post_description = :post_description
		  ";

        //Insert into the Database
        $dbConnectionAW
            ->createCommand($insSql)
            ->bindValue(':post_id', (int)$postId)
            ->bindValue(':post_description', $postDescription)
            ->execute();
    }

    /**
     * @param int $postId
     * @param string $postTags
     * @throws \yii\db\Exception
     */
    public function fnInsertUserPostTags($postId = 0,$postTags = '')
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);
        UserPost::fnDeleteUserPostTags($postId);
        if(!empty($postTags)) {
        $insSql = "INSERT INTO   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POST_TAGS . " 
		  SET 						  
			  post_id = :post_id,
			  tag_id = :tag_id
		  ";

        foreach($postTags as $res) {

        //Insert into the Database
        $dbConnectionAW
            ->createCommand($insSql)
            ->bindValue(':post_id', (int)$postId)
            ->bindValue(':tag_id', $res)
            ->execute();
            }
        }
    }

    /**
     * @param int $postId
     * @throws \yii\db\Exception
     */
    public function fnDeleteUserPostDescription($postId = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);


        $insSql = "DELETE FROM   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POST_DESCRIPTION . " 
		  WHERE 						  
			  post_id = :post_id
		  ";

        //Insert into the Database
        $dbConnectionAW
            ->createCommand($insSql)
            ->bindValue(':post_id', (int)$postId)
            ->execute();
    }

    /**
     * @param int $postId
     * @throws \yii\db\Exception
     */
    public function fnDeleteUserPostTags($postId = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);


        $insSql = "DELETE FROM   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_USER_POST_Tags . " 
		  WHERE 						  
			  post_id = :post_id
		  ";

        //Insert into the Database
        $dbConnectionAW
            ->createCommand($insSql)
            ->bindValue(':post_id', (int)$postId)
            ->execute();
    }
}
