<?php

namespace app\controllers;

use app\models\UserPost;
use Yii;
use yii\web\Controller;
use app\models\Tags;


class UserpostController extends Controller
{

    public $enableCsrfValidation = true;

    public function actionLts()
    {

        //Get The All tags
        $tags = Tags::fnGetTags();

        return $this->render('tagsList',
            [
                'tags'  => $tags
            ]
        );
    }

    public function actionTau()
    {

        $request = Yii::$app->request;

        $tagId = base64_decode(CommonMethods::sanitizeUrlQueryString($request->get('tId')));
        $tagDetails = [];
        if(!empty($tagId)) {
            $tagDetails = Tags::fnGetTagDetailsById($tagId);
            if(empty($tagDetails)) {
                return $this->redirect(CommonMethods::GetLoginUserDefaultCntrlAndAction());
            }
        }

        return $this->render('tagAddEdit',
            [
                'tagDetails'   => $tagDetails,
                'tagId' => $tagId
            ]
        );
    }


    public function actionSaveTagNameAjax()
    {

        $request = Yii::$app->request;
        if ($request->isAjax && $request->isPost) {
            $loggedInUid = CommonMethods::GetLoginUserId();

            $formPostValues = $request->post();

            $tagNamesEmpty = 0;

            $tagNameFinalArr = array();


            $tagId = CommonMethods::sanitizeUrlQueryString($request->post('tagId'));

            for($i=1;$i<=count($formPostValues['postData']);$i++)
            {
                $indexVal = $i-1;
                $phraseName = CommonMethods::sanitizeUrlQueryString($formPostValues['postData'][$indexVal]['value']);
                if(!empty($phraseName))
                {
                    $isPhraseNameExists = Tags::fnIsTagNameExists($tagId, $phraseName);
                    if(!empty($isPhraseNameExists)) {
                        return $i;
                    }
                    $tagNameFinalArr[] = $phraseName;
                    $tagNamesEmpty ++;
                }
            }

            if ($tagNamesEmpty == 0) {
                return 'emptytags';
            }
            $returnVal =  Tags::fnSaveTagDetails($tagId,$tagNameFinalArr,$loggedInUid);
            if($returnVal)
                return 'success';
            else
                return 'fail';
        }
    }

    public function actionListPosts()
    {

        //Get The All tags
        $userPosts = UserPost::fnGetUserPosts();

        return $this->render('userPostList',
            [
                'userPosts'  => $userPosts
            ]
        );
    }

    public function actionPostsAE()
    {

        $request = Yii::$app->request;

        $postId = base64_decode(CommonMethods::sanitizeUrlQueryString($request->get('postId')));
        $postDetails = [];
        if(!empty($postId)) {
            $postDetails = UserPost::fnGetUserPosts($postId);
            if(empty($postDetails)) {
                return $this->redirect(CommonMethods::GetLoginUserDefaultCntrlAndAction());
            }
        }

        return $this->render('postsAddEdit',
            [
                'postDetails'   => $postDetails,
                'postId' => $postId
            ]
        );
    }


    public function actionSaveUserPostAjax()
    {

        $request = Yii::$app->request;
        if ($request->isAjax && $request->isPost) {
            $loggedInUid = CommonMethods::GetLoginUserId();

            $formPostValues = $request->post();


            $postId = CommonMethods::sanitizeUrlQueryString($request->post('postId'));
            $postTitle = CommonMethods::sanitizeUrlQueryString($request->post('postTitle'));
            $postDescription = CommonMethods::sanitizeUrlQueryString($request->post('postDescription'));
            $postTags = CommonMethods::sanitizeUrlQueryString($request->post('postTags'));

            if (empty($postTitle) || empty($postDescription) || empty($postTags)) {
                return '2';
            }
            $returnVal =  UserPost::fnSaveUserPostDetails($postId,$postTitle,$postDescription,$postTags,$loggedInUid);
            if($returnVal)
                return 'success';
            else
                return 'fail';
        }
    }

}