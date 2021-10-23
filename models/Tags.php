<?php

namespace app\models;

use yii\base\Model;


class Tags extends Model
{

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function fnGetTags()
    {

        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                   *              
                FROM
                  " . DB_AW . "." . TBL_HD_TAGS . "
                
                GROUP BY
                    tag_id DESC";

        $result = $dbConnectionAW
            ->createCommand($sql)
            ->queryAll();

        return $result;
    }

    /**
     * @param int $tagId
     * @return array|false|string
     * @throws \yii\db\Exception
     */
    public function fnGetTagDetailsById($tagId = 0)
    {

        if(empty($tagId)) {
            return '';
        }
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        $sql = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                   *              
                FROM
                  " . DB_AW . "." . TBL_DB_HD_TAGS . "
                WHERE
                    tag_id = :tag_id";

        $result = $dbConnectionAW
            ->createCommand($sql)
            ->bindValue(':tag_id', (int)$tagId)
            ->queryOne();

        return $result;
    }

    /**
     * @param int $tagId
     * @param string $tagName
     * @return false|int|string|null
     * @throws \yii\db\Exception
     */
    public function fnIsTagNameExists($tagId = 0, $tagName = '')
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        $fetchQuery = "SELECT /* " . __FILE__ . " Line No: " . __LINE__ . "  */
                                tag_name
							FROM 
								  " . DB_AW . "." . TBL_HD_TAGS . "
						    WHERE
						        tag_id <> :tag_id
						        && tag_name = :tag_name";

        $tag = $dbConnectionAW
            ->createCommand($fetchQuery)
            ->bindValue(':tag_id', (int)$tagId)
            ->bindValue(':tag_name', $tagName)
            ->queryScalar();

        return $tag;

    }

    /**
     * @param int $tagId
     * @param $tagArray
     * @param int $loggedInUid
     * @return string
     * @throws \yii\db\Exception
     */
    public function fnSaveTagDetails($tagId = 0,$tagArray , $loggedInUid = 0)
    {

        if(empty($tagId)) {
            $tagId = Tags::fnInsertTagDetails($tagArray,$loggedInUid);
        } else {
            Tags::fnLogInsetTagDetails($tagId);
            Tags::fnUpdateTagDetails($tagId,$tagArray[0],$loggedInUid);
        }
        return '1';
    }

    /**
     * @param $tagArray
     * @param int $loggedInUid
     * @return string
     * @throws \yii\db\Exception
     */
    public function fnInsertTagDetails($tagArray, $loggedInUid = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);


        $insSql = "INSERT INTO   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_TAGS . " 
		  SET 
			  tag_name = :tag_name,			  
			  created_by_user_id = :created_by_user_id,
			  created_at = now()
		  ";
        $tagId = '';
        for($i=0;$i<count($tagArray);$i++)
        {
            $isTagNameExists = Tags::fnIsTagNameExists($tagId,$tagArray[$i]);
            if(!empty($isTagNameExists)) {

            }
            else
            {
                //Insert into the Database
                $dbConnectionAW
                    ->createCommand($insSql)
                    ->bindValue(':tag_name', $tagArray[$i])
                    ->bindValue(':created_by_user_id', (int)$loggedInUid)
                    ->execute();
            }

        }

        return $tagRecId = $dbConnectionAW->getLastInsertID();
    }

    /**
     * @param int $tagId
     * @param string $tagName
     * @param int $loggedInUid
     * @throws \yii\db\Exception
     */
    public function fnUpdateTagDetails($tagId = 0, $tagName = '', $loggedInUid = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        $updateSql = "UPDATE   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_TAGS . " 
		  SET 
			  tag_name = :tag_name,			  
			  updated_by_user_id = :updated_by_user_id,
			  updated_at = now()
		  WHERE
		    tag_id = :tag_id
		  ";

        //Update
        $dbConnectionAW
            ->createCommand($updateSql)
            ->bindValue(':tag_id', (int)$tagId)
            ->bindValue(':tag_name', $tagName)
            ->bindValue(':updated_by_user_id', (int)$loggedInUid)
            ->execute();
    }

    /**
     * @param int $tagId
     * @throws \yii\db\Exception
     */
    public function fnLogInsetTagDetails($tagId = 0)
    {
        //get DB Connection
        $commonMethodsClassObj      =   new CommonMethods();
        $dbConnectionAW             =   $commonMethodsClassObj->connectDb(DB_HD);

        $insSql = "INSERT INTO   /* " . __FILE__ . " Line No: " . __LINE__ . "  */
			  " . DB_AW . "." . TBL_HD_TAGS_HISTORY . " 
			  (tag_id,tag_name,created_by_user_id,created_at,updated_by_user_id,updated_at)
		      SELECT 
		        tag_id,tag_name,created_by_user_id,created_at,updated_by_user_id,updated_at 
		      FROM 
		        " . DB_AW . "." . TBL_HD_TAGS . "
		      WHERE
		        tag_id = :tag_id
		  ";

        //Insert into the Database
        $dbConnectionAW
            ->createCommand($insSql)
            ->bindValue(':tag_id', (int)$tagId)
            ->execute();
    }

}
