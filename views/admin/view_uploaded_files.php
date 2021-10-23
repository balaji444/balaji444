<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Users;
use app\models\Roles;
use app\models\CommonMethods;

$this->title = "View Uploaded Files";

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
                                        <h2 class="mb-0 mt-0">View Uploaded Files</h2>
                                    </div><!-- end col-->

                                    <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                        <a href="/sathsang/web/admin/upload-files" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ Upload Files</a>
                                    </div>
                                </div>

                                <hr/>

                                <table id="viewPageDatatable" class="table dt-responsive nowrap w-100 table-hover">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Title</th>
                                        <th>File (URL)</th>
                                        <th>Description</th>
                                        <th>Add / Updated by & on</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($tableValuesResult)) {
                                        $i=1;
                                        foreach ($tableValuesResult as $res) { ?>
                                            <tr>
                                                <td><?= CommonMethods::displayVariableContent($i); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['content_title']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['content_path']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['content_description']); ?></td>
                                                <td><?= CommonMethods::displayVariableContent($res['added_by_name']); ?><br /><?= date('m/d/Y', strtotime(CommonMethods::displayVariableContent($res['uploaded_on']))); ?></td>
                                            </tr>
                                        <?php
                                        $i++;
                                        }
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
        }		
    });
});";

$this->registerJs($jsSearch, static::POS_END);

$dataTables = "@web/assets/libs/datatables.net/js/jquery.dataTables.min.js";
$this->registerJsFile($dataTables,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$dataTablesResp = "@web/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js";
$this->registerJsFile($dataTablesResp,
    ['depends' => [\yii\web\JqueryAsset::className()]]);



