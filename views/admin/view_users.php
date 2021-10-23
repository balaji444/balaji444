<?php


use app\models\Users;
use app\models\Roles;
use app\models\CommonMethods;

$this->title = "View Users";

$select2MinCss = "@web/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);

$select2MinCss = "@web/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);

$pg_controllerName = Yii::$app->controller->id;
$pg_actionName = Yii::$app->controller->action->id;


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
                                    <h2 class="mb-0 mt-0">View Users</h2>
                                </div><!-- end col-->

                                <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                    <a href="/sathsang/web/admin/add-user" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ Add User</a>
                                </div>
                            </div>

                            <hr/>

                            <table id="viewPageDatatable" class="table dt-responsive nowrap w-100 table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Role</th>
                                    <th>Created&nbsp;by&nbsp;&&nbsp;on</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($showAllUsers)) {
                                    foreach ($showAllUsers as $s_u_Arr) {
                                        $CreatedByuser_name = '';
                                        if (!empty($s_u_Arr['created_by_user_id'])) {
                                            $CreatedByuser_name = "";
                                            $CreatedByUser_info = Users::GetAllUsers($s_u_Arr['created_by_user_id']);
                                            if(!empty($CreatedByUser_info)) {
                                                $CreatedByuser_name = $CreatedByUser_info[0]['first_name'] . " " . $CreatedByUser_info[0]['last_name'];
                                            }
                                        }
                                        $roleName = Roles::GetRoleNameOfUser($s_u_Arr['user_id']);
                                        ?>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1"><?= CommonMethods::displayVariableContent($s_u_Arr['user_id']); ?></td>
                                            <td><?= CommonMethods::displayVariableContent($s_u_Arr['first_name']); ?>&nbsp;<?= CommonMethods::displayVariableContent($s_u_Arr['last_name']); ?></td>
                                            <td><?= CommonMethods::displayVariableContent($s_u_Arr['user_email']); ?></td>
                                            <td><?=($s_u_Arr['is_active'] == 'Y' ? 'Active' : "Inactive"); ?></td>
                                            <td><?= CommonMethods::displayVariableContent($roleName); ?></td>
                                            <td><?= CommonMethods::displayVariableContent($CreatedByuser_name); ?><br /><?= date("m/d/Y", strtotime(CommonMethods::displayVariableContent($s_u_Arr['created_on']))); ?></td>
                                            <td><?= wordwrap(CommonMethods::displayVariableContent($s_u_Arr['user_note']), 50, "<br>\n"); ?></td>
                                            <td><a class="text-primary" href="/sathsang/web/admin/edit-user?req_uid=<?= base64_encode(CommonMethods::displayVariableContent($s_u_Arr['user_id'])); ?>"><i title="Edit" class="fa fa-edit"></i></a></td>
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

    <?php

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