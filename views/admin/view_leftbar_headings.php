<?php


use app\models\CommonMethods;

$this->title = "View Leftbar Headings";

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
                                    <h2 class="mb-0 mt-0">View Leftbar Headings</h2>
                                </div><!-- end col-->

                                <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                    <a href="/sathsang/web/admin/add-leftbar-heading" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ Add Leftbar Headings</a>
                                </div>
                            </div>

                            <hr/>

                            <table id="viewPageDatatable" class="table dt-responsive nowrap w-100 table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Heading Name</th>
                                    <th>Added By</th>
                                    <th>Added On&nbsp;&nbsp;</th>
                                    <th>Action&nbsp;&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tableValuesResult as $res) { ?>
                                    <tr>
                                        <td><?=CommonMethods::displayVariableContent($res['heading_id']); ?></td>
                                        <td><?=CommonMethods::displayVariableContent($res['heading_name']); ?></td>
                                        <td><?=CommonMethods::displayVariableContent($res['added_by_name']); ?></td>
                                        <td><?=CommonMethods::displayVariableContent(date('m/d/Y', strtotime($res['heading_added_on']))); ?></td>
                                        <td>
                                            <a class="text-primary" href="/sathsang/web/<?= CommonMethods::displayVariableContent($pg_controllerName); ?>/edit-leftbar-heading?heading_id=<?= CommonMethods::displayVariableContent(base64_encode($res['heading_id'])); ?>"
                                               target="_blank"><i class="fa fa-edit" title="Edit"></i></a></td>
                                    </tr>
                                <?php } ?>
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
            'targets': [4], /* column index */
            'orderable': false, /* true or false */

        }]		
    });
});";

$this->registerJs($jsSearch, static::POS_END);

$dataTables = "@web/assets/libs/datatables.net/js/jquery.dataTables.min.js";
$this->registerJsFile($dataTables,
    ['depends' => [\yii\web\JqueryAsset::className()]]);
