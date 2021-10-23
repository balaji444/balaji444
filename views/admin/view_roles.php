<?php

use app\models\Users;
use app\models\UserModule;
use app\models\CommonMethods;

$this->title = "View Roles";

$select2MinCss = "@web/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);

$select2MinCss = "@web/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);

$pg_controllerName = Yii::$app->controller->id;
$pg_actionName = Yii::$app->controller->action->id;

echo $encrypt = CommonMethods::CookieElementEncrypt('qwewq');
echo CommonMethods::CookieElementDecrypt($encrypt);exit;
?>
    <div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">


            <div class="row">
                <div  class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-lg-8 col-md-6 col-sm-6">
                                    <h2 class="mb-0 mt-0">View Roles</h2>
                                </div><!-- end col-->

                                <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                    <a href="/sathsang/web/admin/add-role" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ Add Role</a>
                                </div>
                            </div>

                            <hr/>

                            <table id="viewPageDatatable" class="table dt-responsive nowrap w-100 table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role&nbsp;Name</th>
                                    <th>Role&nbsp;Description</th>
                                    <th>Pages</th>
                                    <th>Default&nbsp;Page</th>
                                    <th>Created&nbsp;by&nbsp;&&nbsp;on</th>
                                    <th>Last&nbsp;Updated&nbsp;by&nbsp;&&nbsp;on</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($tableValuesResult)) {
                                    foreach ($tableValuesResult as $res) {
                                        $defaultPageName = '';
                                        if (!empty($res['default_page_id'])) {
                                            $defaultPageName = UserModule::getPageName($res['default_page_id']);
                                        }

                                        $roleId = $res['role_id'];

                                        $latestUpdatedData = UserModule::fnGetLastUpdatedByUpdatedOnDetails($roleId);
                                        $latestUpdatedBy = "";
                                        $latestUpdatedOn = "";
                                        if(!empty($latestUpdatedData)) {
                                            $latestUpdatedOn=$latestUpdatedData[0]['page_assigned_on'];
                                            $latestUpdatedOn=date('m/d/Y', strtotime($latestUpdatedOn));

                                            $latestUpdatedByUid = $latestUpdatedData[0]['page_assigned_by_user_id'];
                                            $getOtherUserData = Users::GetAllUsers($latestUpdatedByUid);
                                            if (!empty($getOtherUserData)) {
                                                $latestUpdatedBy = $getOtherUserData[0]['first_name'] . " " . $getOtherUserData[0]['last_name'];
                                            }

                                        }
                                        ?>
                                        <tr>
                                            <td><?= CommonMethods::displayVariableContent($res['role_id']); ?></td>
                                            <td>
                                                <?= CommonMethods::displayVariableContent($res['role_name']); ?></td>
                                            <td>
                                                <?= CommonMethods::displayVariableContent($res['role_description']); ?></td>
                                            <td>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#standard-modal" class="text-primary" title="Pages Shown in LeftBar" onclick="getPagesAssignedtoRole('<?= CommonMethods::displayVariableContent($res['role_id']); ?>','Y')">
                                                <?= CommonMethods::displayVariableContent($res['pagesCnt_show_in_LeftBar']); ?></a>
                                                |
                                                <a class="text-primary" href="#" data-bs-toggle="modal"
                                                   onclick="getPagesAssignedtoRole('<?= CommonMethods::displayVariableContent($res['role_id']); ?>','N')"
                                                   data-bs-target="#standard-modal"
                                                   title="Pages Not Shown in LeftBar"><?= CommonMethods::displayVariableContent($res['pagesCnt_not_shown_in_LeftBar']); ?></a>
                                            </td>
                                            <td><?php echo $defaultPageName; ?></td>
                                            <td><?= $res['added_by_name']; ?> <br /><?= date('m/d/Y', strtotime(CommonMethods::displayVariableContent($res['role_added_on']))); ?></td>


                                            <td><?= $latestUpdatedBy; ?><br /><?= $latestUpdatedOn; ?></td>
                                            <td>
                                                <a class="text-primary" href="/sathsang/web/admin/edit-role?req_roleid=<?= base64_encode(CommonMethods::displayVariableContent($res['role_id'])); ?>"><i
                                                            class="fa fa-edit" title="Edit"></i></a>&nbsp;&nbsp;&nbsp;<a
                                                        href="/sathsang/web/admin/edit-role?req_roleid=<?= base64_encode(CommonMethods::displayVariableContent($res['role_id'])); ?>"><i
                                                            class="mdi mdi-settings" title="Configure Leftbar"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->

    <!-- Standard modal content -->
    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Pages</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body" id="pages_assigned_to_roles">
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php
$view_role = "@web/js/view_role.js";
$this->registerJsFile($view_role,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$jsNoConflict = "
$(function() {
        jQuery.noConflict();             
    });
";
$this->registerJs($jsNoConflict, static::POS_END);
$jsSearch = "
$(function() {
    $('#viewPageDatatable').DataTable({
        pageLength: 6,
        lengthChange: false,
        bFilter: true,
        autoWidth: true,
        paging:false,
        info:false,
        'oLanguage': {
            'sEmptyTable': 'No Data'
        },
        'columnDefs': [ {
            'targets': [7], /* column index */
            'orderable': false, /* true or false */

        }]		
    });
});";

$this->registerJs($jsSearch, static::POS_END);

$dataTables = "@web/assets/libs/datatables.net/js/jquery.dataTables.min.js";
$this->registerJsFile($dataTables,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$dataTablesResp = "@web/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js";
$this->registerJsFile($dataTablesResp,
    ['depends' => [\yii\web\JqueryAsset::className()]]);
