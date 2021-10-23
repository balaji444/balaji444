<?php

use app\models\CommonMethods;

$this->title = "View Pages";

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
                                        <h2 class="mb-0 mt-0">View Pages</h2>
                                    </div><!-- end col-->

                                    <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                        <a href="/sathsang/web/admin/add-page" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ Add Page</a>
                                    </div>
                                </div>

                                <hr/>

                                <table id="viewPageDatatable" class="table dt-responsive nowrap w-100 table-hover">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Page Name</th>
                                        <th>Action Name</th>
                                        <th>Controller Name</th>
                                        <th>Page Description</th>
                                        <th>Page Status</th>
                                        <th>Add / Updated by & on</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($tableValuesResult)) {
                                        foreach ($tableValuesResult as $res) { ?>
                                            <tr>
                                                <td><?= CommonMethods::displayVariableContent($res['page_id']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['page_name']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['action_name']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['controller_name']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['page_description']); ?></td>
                                                <td>
                                                    <?php
                                                    if($res['is_page_active']=='Y') { echo "Active"; }
                                                    if($res['is_page_active']=='N') { echo "InActive"; }
                                                    ?>
                                                </td>
                                                <td><?= CommonMethods::displayVariableContent($res['added_by_name']); ?><br /><?= date('m/d/Y', strtotime(CommonMethods::displayVariableContent($res['page_added_on']))); ?></td>
                                                <td>
                                                    <a class="text-primary" href="<?= SITE_URL; ?>/<?= $pg_controllerName ?>/edit-page?page_id=<?= base64_encode($res['page_id']); ?>"
                                                       target="_blank"><i class="fa fa-edit" title="Edit"></i></a></td>
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



