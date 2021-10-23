<?php

use yii\helpers\Html;
use yii\web\Response;
use yii\jui\AutoComplete;
use app\models\CommonMethods;
use app\models\UserModule;

$UserFullName = CommonMethods::GetLoginUserFirstName() . ' ' . CommonMethods::GetLoginUserLastName();

$heder_loggedInUid = CommonMethods::GetLoginUserId();

$h_user_role_heading_pages_h = array();
if (!empty($heder_loggedInUid)) {
    $h_user_role_heading_pages_h = CommonMethods::GetLoggedinUserRoles_Headings_Pages();
}

//Profile Picture
//$ProfilePicture = USER_PROFILE_PIC.CommonMethods::GetLoginUserId() . '.jpg';

$LoggedUserRoleId = CommonMethods::GetLoginUserRoleId();
?>
<div id="wrapper">
<div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-end mb-0">

                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?=SITE_URL;?>/assets/images/users/user.png" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ms-1">
My Name <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome</h6>
                                </div>
                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a href="/sathsang/web/admin/logout" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>

                            </div>
                        </li>
                    </ul>

                    <!-- LOGO -->
                    <div class="logo-box">

                        <a href="#" class="logo logo-light text-center">
                            <span class="logo-sm">
                                <span class="fw-bold logomain" title="Hindu App"><i class="fas fa-om"></i> </span>
                            </span>
                            <span class="logo-lg">
                            <span class="fw-bold logomain" title="Hindu App"><i class="fas fa-om"></i> Hindu App</span>
                            </span>
                        </a>
                    </div>

                    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                        <li>
                            <button class="button-menu-mobile waves-effect waves-light">
                                <i class="fe-menu"></i>
                            </button>
                        </li>

                        <li>
                            <!-- Mobile menu toggle (Horizontal Layout)-->
                            <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>

                        <!--Mega Menu Start-->
                        <li class="dropdown dropdown-mega d-none d-xl-block">
                            <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                Menu
                                <i class="mdi mdi-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu dropdown-megamenu">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="row">

                                            <?php
                                            if (!empty($h_user_role_heading_pages_h)) {
                                                foreach ($h_user_role_heading_pages_h as $k_h_hId => $h_headingPagesArr) {
                                                    if ($k_h_hId == 0) continue;
                                                    $h_headingName = UserModule::getHeadingName($k_h_hId);

                                                    ?>
                                                    <div class="col-md-3 mb-2">
                                                    <h5 class="text-dark mt-0"><?php echo $h_headingName; ?></h5>
                                                    <ul class="list-unstyled megamenu-list">
                                                        <?php
                                                        if (!empty($h_headingPagesArr)) {
                                                            foreach ($h_headingPagesArr as $h_head_pageId) {
                                                                $h_pageName = UserModule::getPageName($h_head_pageId);
                                                                $h_pageLink = UserModule::getPageLink($h_head_pageId);
                                                                ?>
                                                                <li>
                                                                    <a href="<?php echo $h_pageLink; ?>"> <?php echo $h_pageName; ?></a>
                                                                </li>
                                                            <?php }
                                                        } ?>
                                                    </ul>
                                                <?php } ?>
                                                </div>
                                            <?php } ?>

                                            <?php
                                            if (!empty($h_user_role_heading_pages_h)) {
                                                foreach ($h_user_role_heading_pages_h as $k_h_hId => $h_headingPagesArr) {
                                                    if ($k_h_hId != 0) continue;


                                                    ?>
                                                    <div class="col-md-3 mb-2">
                                                    <h5 class="text-dark mt-0">Other</h5>
                                                    <ul class="list-unstyled megamenu-list">
                                                        <?php
                                                        if (!empty($h_headingPagesArr)) {
                                                            foreach ($h_headingPagesArr as $h_head_pageId) {
                                                                $h_pageName = UserModule::getPageName($h_head_pageId);
                                                                $h_pageLink = UserModule::getPageLink($h_head_pageId);
                                                                ?>
                                                                <li>
                                                                    <a href="<?php echo $h_pageLink; ?>"> <?php echo $h_pageName; ?></a>
                                                                </li>
                                                            <?php }
                                                        } ?>
                                                    </ul>
                                                <?php } ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!--Mega Menu End-->
                    </ul>
                    <div class="clearfix"></div>
                </div>
</div>
            <!-- end Topbar -->



            <!-- Start topnav-->
				<div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fe-airplay me-1"></i> Home
                                    </a>
                                </li>

                            </ul> <!-- end navbar-->
                        </div> <!-- end .collapsed-->
                    </nav>
                </div> <!-- end container-fluid -->
                    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
            </div>