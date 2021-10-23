<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommonMethods;

$this->title = "Edit Page";

$select2MinCss = "@web/assets/libs/select2/css/select2.min.css";
$this->registerCssFile($select2MinCss);

$select2MinCss = "@web/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);

$select2MinCss = "@web/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css";
$this->registerCssFile($select2MinCss);


?>
<style>
    .selectize-input{ height:auto;}
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div  class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <h2 class="mb-0 mt-0">Edit Page</h2>

                            <hr/>
                    <?php if ($session->hasFlash('ErrorMessage')) { ?>
                        <div class="alert alert-danger"><?= $session->getFlash('ErrorMessage'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                        </div>
                    <?php } ?>
                            <div class="col-lg-6">
                    <form class="form-horizontal" name="EntryForm" action="/sathsang/web/admin/edit-page" method="post">
                        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                               value="<?= Yii::$app->request->csrfToken ?>"/>

                        <div class="row mb-3">
                            <label for="controller_name" class="col-4 col-xl-3 col-form-label">Controller Name</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-control" data-toggle="select2" data-width="100%" required name="controller_name"
                                        id="controller_name" onchange="getActionOfController()">
                                    <option value="">Select</option>
                                    <?php foreach ($arrAllControllerNames as $res) { ?>
                                        <option <?php if ($res == $tblDatawithID['controller_name']) { ?>
                                                selected="selected" <?php } ?>value="<?= CommonMethods::displayVariableContent($res) ?>"><?= CommonMethods::displayVariableContent($res) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="action_name" class="col-4 col-xl-3 col-form-label">Action Name</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-control" data-toggle="select2" data-width="100%" required name="action_name" id="action_name">
                                    <option value="">Select</option>
                                    <?php foreach ($arrControllerActions as $res) { ?>
                                        <option <?php if ($res == $tblDatawithID['action_name']) { ?>  selected="selected" <?php } ?>
                                                value="<?= CommonMethods::displayVariableContent($res); ?>"><?= CommonMethods::displayVariableContent($res); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="page_name" class="col-4 col-xl-3 col-form-label">Page Name</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" required id="page_name" name="page_name" value="<?= CommonMethods::displayVariableContent($tblDatawithID['page_name']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="page_display_icon_css_class_name" class="col-4 col-xl-3 col-form-label">Page Icon</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="page_display_icon_css_class_name" id="page_display_icon_css_class_name" placeholder="" value="<?= CommonMethods::displayVariableContent($tblDatawithID['page_display_icon_css_class_name']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="page_description" class="col-4 col-xl-3 col-form-label">Page Description</label>
                            <div class="col-8 col-xl-9">
                                <textarea class="form-control" rows="5" name="page_description" id="page_description"><?= CommonMethods::displayVariableContent($tblDatawithID['page_description']); ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="is_page_active" class="col-4 col-xl-3 col-form-label">Action Name</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-control" data-toggle="select2" data-width="100%" name="is_page_active" id="is_page_active">
                                    <option value="Y" <?php if($tblDatawithID['is_page_active']=='Y') { ?> selected="selected"<?php } ?> >Active</option>
                                    <option value="N" <?php if($tblDatawithID['is_page_active']=='N') { ?> selected="selected"<?php } ?>>InActive</option>
                                </select>
                            </div>
                        </div>


                        <div class="justify-content-end row">
                            <input type="hidden" id="page_id" name="page_id" value="<?= CommonMethods::displayVariableContent($tblDatawithID['page_id']); ?>"/>
                            <div class="col-8 col-xl-9">
                                <button type="submit" class="btn btn-secondary waves-effect waves-light">Submit</button>
                                &nbsp;
                                <button type="button" class="btn btn-light waves-effect" onclick="window.location.href='<?php echo '/admin/view-pages/'; ?>'">Cancel</button>
                            </div>
                        </div>
                    </form>
                            </div>

                        <hr/>
            <?php if (!empty($arrDataWIthIDHistory)) { ?>

                        

                              <div><h4 class="float-left mt-2">Change Log</h4></div>
                <div class="table-responsive">
                            <table class="table dt-responsive nowrap w-100 table-hover" id="pageChangeLog">
                                <thead class="">
                                <tr>
                                    <th>ID</th>
                                    <th>Controller Name&nbsp;&nbsp;</th>
                                    <th>Action Name&nbsp;&nbsp;</th>
                                    <th>Page Name&nbsp;&nbsp;</th>
                                    <th>Page Description</th>
                                    <th>Page Status</th>
                                    <th>Page Icon</th>
                                    <th>Updated By</th>
                                    <th>Updated On&nbsp;&nbsp;</th>

                                </tr>
                                </thead>
                                <?php foreach ($arrDataWIthIDHistory as $res) { ?>
                                    <tr>
                                        <td><?= CommonMethods::displayVariableContent($res['page_id']); ?></td>
                                        <td><?= CommonMethods::displayVariableContent($res['controller_name']); ?></td>
                                        <td><?= CommonMethods::displayVariableContent($res['action_name']); ?></td>
                                        <td><?= CommonMethods::displayVariableContent($res['page_name']); ?></td>
                                        <td><?= CommonMethods::displayVariableContent($res['page_description']); ?></td>
                                        <td>
											<?php
                                                if($res['is_page_active']=='Y') { echo "Active"; }
                                                if($res['is_page_active']=='N') { echo "InActive"; }													
                                            ?>
                                        </td>
                                        <td><?= CommonMethods::displayVariableContent($res['page_display_icon_css_class_name']); ?></td>
                                        <td><?= CommonMethods::displayVariableContent($res['added_by_name']); ?></td>
                                        <td><?= date('m/d/Y', strtotime(CommonMethods::displayVariableContent($res['page_added_on']))); ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                </div>
            <?php } ?>


                        </div>

                    </div>
                </div>
            </div>



        </div>
        <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->
<?php

$select = "@web/assets/libs/select2/js/select2.min.js";
$this->registerJsFile($select,
    ['depends' => [\yii\web\JqueryAsset::className()]]);


$selectFormAdvanced = "@web/assets/js/pages/form-advanced.init.js";
$this->registerJsFile($selectFormAdvanced,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$auto_suggest = "/js/auto_suggest.js";
$this->registerJsFile($auto_suggest/*,
    ['depends' => [\yii\web\JqueryAsset::className()]]*/);

$jsNoConflict = "
$(function() {
        jQuery.noConflict();             
    });
";
$this->registerJs($jsNoConflict, static::POS_END);
$jsSearch = "
$(function() {
    $('#pageChangeLog').DataTable({
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
?>   
