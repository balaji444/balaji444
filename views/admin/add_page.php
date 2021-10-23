<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommonMethods;

$this->title = "Add Page";

$select2MinCss = "@web/assets/libs/select2/css/select2.min.css";
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

                            <h2 class="mb-0 mt-0">Add Page</h2>

                            <hr/>
                            <?php if ($session->hasFlash('ErrorMessage')) { ?>
                                <div class="alert alert-danger"><?= $session->getFlash('ErrorMessage'); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>
                                </div>
                            <?php } ?>

                            <div class="col-lg-6">

                                <form class="form-horizontal" name="EntryForm" action="/sathsang/web/admin/add-page" method="post">
                                    <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                           value="<?= Yii::$app->request->csrfToken ?>"/>

                                    <div class="row mb-3">
                                        <label for="controller_name" class="col-4 col-xl-3 col-form-label">Controller Name</label>
                                        <div class="col-8 col-xl-9">
                                            <select class="form-control" data-toggle="select2" data-width="100%" required name="controller_name"
                                                    id="controller_name" onchange="getActionOfController()">
                                                <option value="">Select</option>
                                                <?php foreach ($arrAllControllerNames as $res) { ?>
                                                    <option value="<?= CommonMethods::displayVariableContent($res) ?>"><?= CommonMethods::displayVariableContent($res) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <label for="action_name" class="col-4 col-xl-3 col-form-label">Action Name</label>
                                        <div class="col-8 col-xl-9">
                                            <select class="form-control" data-toggle="select2" data-width="100%" required name="action_name" id="action_name">
                                                <option value="">Select</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="page_name" class="col-4 col-xl-3 col-form-label">Page Name</label>
                                        <div class="col-8 col-xl-9">
                                            <input type="text" class="form-control" required id="page_name" name="page_name">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="page_display_icon_css_class_name" class="col-4 col-xl-3 col-form-label">Page Icon</label>
                                        <div class="col-8 col-xl-9">
                                            <input type="text" class="form-control" name="page_display_icon_css_class_name" id="page_display_icon_css_class_name" placeholder="">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="page_description" class="col-4 col-xl-3 col-form-label">Page Description</label>
                                        <div class="col-8 col-xl-9">
                                            <textarea class="form-control" rows="5" name="page_description" id="page_description"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="is_page_active" class="col-4 col-xl-3 col-form-label">Action Name</label>
                                        <div class="col-8 col-xl-9">
                                            <select class="form-control" data-toggle="select2" data-width="100%" name="is_page_active" id="is_page_active">
                                                <option value="Y">Active</option>
                                                <option value="N">Inactive</option>
                                            </select>
                                        </div>
                                    </div>




                                    <div class="justify-content-end row">
                                        <div class="col-8 col-xl-9">
                                            <button type="submit" class="btn btn-secondary waves-effect waves-light">Submit</button>
                                            &nbsp;
                                            <button type="button" class="btn btn-light waves-effect" onclick="window.location.href='<?php echo '/admin/view-pages/'; ?>'">Cancel</button>
                                        </div>
                                    </div>
                                </form>





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


$auto_suggest = "@web/js/auto_suggest.js";
$this->registerJsFile($auto_suggest,[ 'depends' => [ \yii\web\JqueryAsset::className() ]]);

?>   
