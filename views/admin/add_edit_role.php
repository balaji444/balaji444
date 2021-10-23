<?php
$this->title = 'Assign Pages to Role';

use app\models\Users;
use app\models\CommonMethods;
use app\models\Roles;
use app\models\UserModule;

$usrsDtls = Users::GetAllUsers();
$rolesDtls = Roles::GetAllRoles();

$pg_controllerName = Yii::$app->controller->id;
$pg_actionName = Yii::$app->controller->action->id;

$hdn_edit_Role_pageIds_ss = '';
$hdn_edit_Role_pageNames_ss = '';
$hdn_edit_pageNamesString = '';
$hdn_edit_pageNamesString_not_to_show_in_LeftBar = '';

$pageName = 'Add Role';
$headingEditArr = array();

$existingHeadingNames = '';
$existingHeadingIds = '';

$existing_options_PageNames = '';


$hdn_edit_Role_page_ns_Ids_ss = "";
$hdn_edit_Role_pageNames_ns_ss = '';

if (!empty($editData['roleId'])) {
    $this->title = 'Edit Role';
    $pageName = 'Edit Role';
    $dataArr = Roles::GetPagesBasedOnRole($editData['roleId']);
    if (!empty($dataArr)) {
        $i = 1;
        foreach ($dataArr as $d_k => $d_val) {
            $hdn_edit_Role_pageIds_ss .= $d_k . "#";
            $hdn_edit_Role_pageNames_ss .= $d_val . "#";
            $hdn_edit_pageNamesString .= "<span title='Page_" . $d_k . "' class='badge bg-primary rounded-pill' id='pcp_div_" . $i . "' data-npi=" . $d_k . ">" . $d_val . "&nbsp;<a class=' text-white' href='javascript:void(0);' onclick = 'removePageName(" . $i . ")'>x</a></span>&nbsp;";

            $pageSelected = '';
            if ($editData['role_Default_Page_Id'] == $d_k) {
                $pageSelected = " selected='selected'; ";
            }
            $existing_options_PageNames .= "<option value='" . $d_k . "' " . $pageSelected . ">" . $d_val . "</option>";
            $i++;
        }
        $hdn_edit_Role_pageIds_ss = rtrim($hdn_edit_Role_pageIds_ss, "#");
        $hdn_edit_Role_pageNames_ss = rtrim($hdn_edit_Role_pageNames_ss, "#");
    }
    //Getting Headings

    $headingEditArr = UserModule::fetchRoleHeadingsPages($editData['roleId']);
    if (!empty($headingEditArr)) {
        foreach ($headingEditArr as $t_headingId => $t_pagesArr) {
            $existingHeadingIds .= $t_headingId . ",";
            if (!empty($t_headingId)) {
                $t_headingName = UserModule::getHeadingName($t_headingId);
                $existingHeadingNames .= "<span class='badge bg-primary rounded-pill' id='heading_close_div_" . $t_headingId . "'>" . $t_headingName . "&nbsp;";
                $existingHeadingNames .= "<a class='text-white' href='javascript:void(0);'	onclick = 'removeHeading(" . $t_headingId . ")'>x</a></span>&nbsp;";
            }
        }
        $existingHeadingIds = rtrim($existingHeadingIds, ",");
    }

    //Get Pages Not Shown in LeftBar
    $data_ns_Arr = Roles::GetPages_Not_Shown_in_LeftBar_BasedOnRole($editData['roleId']);
    if (!empty($data_ns_Arr)) {
        foreach ($data_ns_Arr as $d_k => $d_val) {
            $hdn_edit_Role_page_ns_Ids_ss .= $d_k . ",";
            $hdn_edit_Role_pageNames_ns_ss .= $d_val . "#";
            $hdn_edit_pageNamesString_not_to_show_in_LeftBar .= "<span title='Page_" . $d_k . "' class='badge bg-primary rounded-pill' id='pg_wo_leftbar_close_div_" . $d_k . "' data-npi=" . $d_k . ">" . $d_val . "&nbsp;<a class=' text-white' href='javascript:void(0);' onclick = 'removePg_wo_leftbar(" . $d_k . ")'>x</a></span>&nbsp;";
        }
        $hdn_edit_Role_page_ns_Ids_ss = rtrim($hdn_edit_Role_page_ns_Ids_ss, ",");
    }
} else {
    $this->title = 'Add Role';
}


$roleName = "";
if (!empty($editData['roleName'])) {
    $roleName = $editData['roleName'];
    $roleName = CommonMethods::displayVariableContent($roleName);
}

$roleDesc = "";
if (!empty($editData['roleDesc'])) {
    $roleDesc = $editData['roleDesc'];
    $roleDesc = CommonMethods::displayVariableContent($roleDesc);
}


$jUiCss = "@web/assets/css/jquery-ui.css";
$this->registerCssFile($jUiCss);

?>
    <style>
        .selectize-input {
            height: auto;
        }

      .ULheadingSort {
          /* border:1px solid Black;*/
          width: auto;
          height: auto;
          display: inline-block;
          vertical-align: top;
          padding: 11px;
      }

        .LiheadingSort.selected {
            background-color: #e2e1e1;
        }

        /* Heading sorting */
        .ui-draggable, .ui-droppable {
            background-position: top;
        }

        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
            border: 1px solid #c5c5c5;
            background: #f6f6f6;
            font-weight: normal;
            color: #454545;
        }

        .ui-sortable-handle {
            -ms-touch-action: none;
            touch-action: none;
        }

        #sortableBobILI {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }

        #sortableBobILI li {
            margin: 10px 3px 3px;
            padding: 8px;
            font-size: 13px;
            height: 35px;
        }

        #sortableBobILI li span {
            position: absolute;
            margin-left: -1.3em;
        }

        /* Heading sorting */
        /* Grid Sort */
        .placeholder {
            border: 1px solid green;
            background-color: white;
            -webkit-box-shadow: 0px 0px 10px #888;
            -moz-box-shadow: 0px 0px 10px #888;
            box-shadow: 0px 0px 10px #888;
        }

        .tile {
            height: auto;
        }

        .grid {
            margin-top: 1em;
        }

        /* Grid Sort */
        .label {
            margin: 3px !important;
        }

        .well, pre {
            box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%) !important;
            margin: 10px !important;
            border-radius: 10px !important;
            padding: 10px 0 0 15px;
        }
    </style>
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <h2 class="mb-0 mt-0"><?= CommonMethods::displayVariableContent($pageName); ?></h2>

                                <hr/>

                                <div class="col-lg-8">
                                    <form class="form-horizontal">
                                        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                               value="<?= Yii::$app->request->csrfToken ?>"/>
                                        <input type="hidden" name="hdn_page_names" id="hdn_page_names"
                                               value="<?= $hdn_edit_Role_pageNames_ss; ?>"/>
                                        <input type="hidden" name="hdn_pages_list_Ids" id="hdn_pages_list_Ids"
                                               value="<?= $hdn_edit_Role_pageIds_ss; ?>"/>
                                        <input type="hidden" name="hdn_old_pages_list_Ids" id="hdn_old_pages_list_Ids"
                                               value="<?= $hdn_edit_Role_pageIds_ss; ?>"/>


                                        <input type="hidden" name="hdn_heading_list_Ids" id="hdn_heading_list_Ids"
                                               value="<?= $existingHeadingIds; ?>"/>
                                        <input type="hidden" name="hdn_old_heading_list_Ids"
                                               id="hdn_old_heading_list_Ids"
                                               value="<?= $existingHeadingIds; ?>"/>

                                        <input type="hidden" name="hdn_roleId" id="hdn_roleId"
                                               value="<?php if (!empty($editData['roleId'])) { ?>
                                 <?= CommonMethods::displayVariableContent(base64_encode($editData['roleId'])); ?>
                                  <?php } ?>"/>

                                        <div class="row mb-3">
                                            <label for="role_name" class="col-4 col-xl-3 col-form-label">Role
                                                Name</label>
                                            <div class="col-8 col-xl-9">
                                                <input type="text" class="form-control" id="role_name" name="role_name"
                                                       placeholder="Role Name" value="<?= $roleName ?>">
                                                <div id="RoleNameErrMsg" class="text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="role_description" class="col-4 col-xl-3 col-form-label">Role
                                                Description</label>
                                            <div class="col-8 col-xl-9">
                                                <textarea class="form-control" rows="5" name="role_description"
                                                          id="role_description"
                                                          placeholder="Description"><?= $roleDesc; ?></textarea>
                                                <div id="RoleDescErrMsg"></div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <input type="hidden" name="hdn_not_to_show_pages_list_Ids"
                                                   id="hdn_not_to_show_pages_list_Ids"
                                                   value="<?= $hdn_edit_Role_page_ns_Ids_ss; ?>"/>
                                            <input type="hidden" name="hdn_old_not_to_show_pages_list_Ids"
                                                   id="hdn_old_not_to_show_pages_list_Ids"
                                                   value="<?= $hdn_edit_Role_page_ns_Ids_ss; ?>"/>
                                            <label for="inputPage_not_to_show" class="col-4 col-xl-3 col-form-label">Pages
                                                not to show in leftbar</label>
                                            <div class="col-8 col-xl-9">
                                                <input type="text" class="form-control mb-1" value=""
                                                       name="inputPage_not_to_show" id="inputPage_not_to_show"
                                                       placeholder="Start Type To Select Page"
                                                       onkeyup="fnAutocomplete('inputPage_not_to_show')">
                                                <div id="PageErr_Not_to_Show_in_LeftBar_Msg"></div>
                                                <div id="page_names_not_to_show_in_LeftBar_div"><?= $hdn_edit_pageNamesString_not_to_show_in_LeftBar; ?>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputHeading" class="col-4 col-xl-3 col-form-label">Select
                                                Leftbar Headings</label>
                                            <div class="col-8 col-xl-9">
                                                <input type="text" class="form-control mb-1" value=""
                                                       name="inputHeading" id="inputHeading"
                                                       placeholder="Start Type To Select Page"
                                                       onkeyup="fnAutocomplete('inputHeading')">
                                                <div id="HeadingErrMsg"></div>
                                                <div class="clearfix m-t-10"
                                                     id="heading_names_div"><?= $existingHeadingNames; ?></div>
                                                <!--<span class="badge bg-primary rounded-pill">Ajax Action Names of Controller  &nbsp;<a class="text-white" href="#">x</a></span>-->

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputPage" class="col-4 col-xl-3 col-form-label">Pages to show
                                                in leftbar</label>
                                            <div class="col-8 col-xl-9">
                                                <input type="text" class="form-control mb-1" value="" name="inputPage"
                                                       id="inputPage" placeholder="Start Type To Select Page"
                                                       onkeyup="fnAutocomplete('inputPage')">
                                                <div id="PageErrMsg"></div>
                                                <div class="clearfix m-t-10"
                                                     id="page_names_div"><?= $hdn_edit_pageNamesString; ?></div>
                                                <!--<span class="badge bg-primary rounded-pill">Ajax Action Names of Controller  &nbsp;<a class="text-white" href="#">x</a></span>-->

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="sel_role_default_page" class="col-4 col-xl-3 col-form-label">Select
                                                Role Default Page</label>
                                            <div class="col-8 col-xl-9">
                                                <select class="form-control" id="sel_role_default_page"
                                                        name="sel_role_default_page" data-toggle="select2"
                                                        data-width="100%">
                                                    <option value="">Select Page</option>
                                                    <?= $existing_options_PageNames; ?>
                                                </select>
                                                <div id="DefaultPageErrMsg"></div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputPassword5" class="col-4 col-xl-3 col-form-label">Map
                                                Headings to Pages</label>
                                            <div class="col-8 col-xl-9">
                                                <div class="row grid" id="HeadingBobILI">
                                                    <?php
                                                    $pages_wo_headingExistsFlag = false;
                                                    if (!empty($headingEditArr)) {
                                                        foreach ($headingEditArr as $k_hId => $headingPagesArr) {
                                                            if ($k_hId == 0) {
                                                                ?>
                                                                <div class="col-md-6 mb-2">
                                                                <div class="list-group">
                                                                <div class="list-group-item list-group-item-secondary headingClass"
                                                                     id="div_heading_0">
                                                                    Pages without heading
                                                                </div>
                                                                <ul class="ULheadingSort" id="UL_Heading_0">
                                                                <?php if (!empty($headingPagesArr)) foreach ($headingPagesArr as $head_pageId) {
                                                                    $pageName = UserModule::getPageName($head_pageId);

                                                                    $head_pageId = CommonMethods::displayVariableContent($head_pageId);

                                                                    ?>
                                                                    <li class="LiheadingSort lsort1"
                                                                         data-liId="<?= $head_pageId; ?>"
                                                                         id="heading_page_li_<?= $head_pageId; ?>"><?= $pageName; ?></li>

                                                                    <?php
                                                                } ?>
                                                                </ul>
                                                                </div>
                                                                </div>

                                                                <?php    $pages_wo_headingExistsFlag = true;
                                                                } else {
                                                                    $edit_headingName = UserModule::getHeadingName($k_hId);
                                                                    $k_hId = CommonMethods::displayVariableContent($k_hId);
                                                                    ?>

                                                                    <div class="col-md-6 mb-2 headingClass"
                                                                         id="div_heading_<?= $k_hId; ?>"
                                                                         data-heading-id="<?= $k_hId; ?>">
                                                                        <div class="list-group">
                                                                            <div class="list-group-item list-group-item-secondary">
                                                                                <?= $edit_headingName; ?>
                                                                            </div>
                                                                            <ul class="ULheadingSort" id="UL_Heading_<?= $k_hId; ?>">
                                                                                <?php if (!empty($headingPagesArr)) foreach ($headingPagesArr as $head_pageId) {
                                                                                    $pageName = UserModule::getPageName($head_pageId);
                                                                                    ?>
                                                                                    <li class="LiheadingSort lsort1"
                                                                                         data-liId="<?= $head_pageId; ?>"
                                                                                         id="heading_page_li_<?= $head_pageId; ?>"><?= $pageName; ?></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }

                                                        }
                                                    }
                                                    ?>
                                                    <?php if (empty($headingEditArr) || $pages_wo_headingExistsFlag == false) { ?>
                                                        <div id="div_heading_0"
                                                             class="list-group-item list-group-item-secondary headingClass"
                                                             data-heading-id="0">Pages without heading
                                                        </div>
                                                        <div class="list-group-item ULheadingSort" id="UL_Heading_0"></div>
                                                    <?php }
                                                    ?>
                                                    <?php
                                                    $hdn_total_HeadingIds = '';
                                                    $firstHeadingId = '';
                                                    $ss = 0;

                                                    ?>
                                                </div>
                                            </div>
                                            <span id="spn_hdn_headingIds"
                                                  style="display:none;"><?= $hdn_total_HeadingIds; ?></span>
                                        </div>


                                                <div class="justify-content-end row">
                                        <div class="col-8 col-xl-9">
                                            <span id="btnAction_role">
                                            <button type="button" class="btn btn-secondary waves-effect waves-light"
                                                    onclick="AddEditRole()">Submit</button>
                                            &nbsp;
                                            <button type="button" class="btn btn-light waves-effect"
                                                    onclick="window.location.href='<?php echo '/admin/view-roles/'; ?>'">Cancel</button>
                                        </span>
                                        </div>

                                                    <span id="loading_action_Row"
                                                          style="display:none;">Assigning...</span>
                                                    <span id="post_action_Row" class="text-success"></span>
                                                </div>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

    <script language="javascript" type="text/javascript">
        rdURL_role = "<?='/sathsang/web/admin/view-roles/'; ?>";
        pstURL_Add_Edit_Role = "<?='/sathsang/web/admin/savemapingofpagestouser'; ?>";
        firstHeadingId = "<?=$firstHeadingId; ?>";
    </script>
    <?php
    if (!empty($hdn_edit_Role_pageIds_ss)) {
        ?>
        <script language="javascript">
            document.getElementById("hdn_page_names").value = "<?=$hdn_edit_Role_pageNames_ss; ?>";
            document.getElementById("hdn_pages_list_Ids").value = "<?=$hdn_edit_Role_pageIds_ss; ?>";
            document.getElementById("hdn_old_pages_list_Ids").value = "<?=$hdn_edit_Role_pageIds_ss; ?>";
        </script>
        <?php
    } else {
        ?>
        <script language="javascript">
            document.getElementById("hdn_page_names").value = "";
            document.getElementById("hdn_pages_list_Ids").value = "";
            document.getElementById("hdn_old_pages_list_Ids").value = "";

            document.getElementById("hdn_heading_list_Ids").value = "";
            document.getElementById("hdn_old_heading_list_Ids").value = "";
        </script>
        <?php
    }
    ?>
<script language="javascript" type="application/javascript">
    function fnAutocomplete(suggestFldName) {

        if (suggestFldName == 'inputPage_not_to_show') {
            urlVal = '/sathsang/web/admin/show-pages-suggest';
        }
        if (suggestFldName == 'inputHeading') {
            urlVal = '/sathsang/web/admin/show-heading-suggest';
        }
        if (suggestFldName == 'inputPage') {
            urlVal = '/sathsang/web/admin/show-pages-suggest';
        }
        $("#" + suggestFldName).autocomplete({
            source: function (request, response) {
                // Fetch data
                minLength: 3
                $.ajax({
                    url: urlVal,
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        cmsYear: "",
                        _csrf: csrfTokenPage
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                if (suggestFldName == 'inputPage_not_to_show') {
                    getPageDetails_not_to_show_in_leftbar(event, ui.item.value);
                }
                if (suggestFldName == 'inputHeading') {
                    AddHeadingDetails(event, ui.item.value);
                }
                if (suggestFldName == 'inputPage') {
                    getPageDetails(event, ui.item.value)
                }
            }
        });
    }

</script>
    <?php

$validation_js = "@web/js/add_edit_role.js";
$this->registerJsFile($validation_js,[ 'depends' => [ \yii\web\JqueryAsset::className() ]]);

    $jsNoConflict = "
$(function() {
        jQuery.noConflict();             
    });
";
    $this->registerJs($jsNoConflict, static::POS_END);
    $jUi = "@web/js/jquery-ui.js";
    $this->registerJsFile($jUi,[ 'depends' => [ \yii\web\JqueryAsset::className() ]]);

    $dragJs = "@web/js/multi_drag.js";
    $this->registerJsFile($dragJs, ['depends' => [\yii\web\JqueryAsset::className()]]);
    ?>
