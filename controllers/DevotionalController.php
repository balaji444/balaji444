<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class DevotionalController extends Controller
{
     /**
     * Displays about page.
     *
     * @return string
     */
    public function actionListings()
    {

        return $this->render('listings');
    }
    public function actionDescription()
    {

        return $this->render('descriptionPage');
    }
    public function actionImages()
    {

        return $this->render('imagePage');
    }
    public function actionAudios()
    {

        return $this->render('audioPage');
    }
}
