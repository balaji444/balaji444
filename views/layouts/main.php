<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\CommonMethods;

$arrLoginUserDetails = CommonMethods::GetLoginUserFullDetails();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="http://localhost/sathsang/web/assets/images/favicon.ico">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<body class="loading" data-layout-mode="horizontal">
<?php
if(!empty($arrLoginUserDetails->LoggedUserRoleId)) {
    require_once("header.php");
} else {
    require_once("headerLogin.php");
}
 ?>
        <?= $content ?>
<?php require_once("footer.php"); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
