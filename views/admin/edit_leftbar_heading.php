<?php

use app\models\CommonMethods;

$this->title = "Edit Leftbar Headings";



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
                                    <h2 class="mb-0 mt-0">Edit Leftbar Headings</h2>
                                </div><!-- end col-->

                                <div class="col-lg-4 col-md-6 col-sm-6 text-end">
                                    <a href="/sathsang/web/admin/view-leftbar-heading" class="btn btn-soft-primary btn-sm waves-effect waves-light">+ View Leftbar Headings</a>
                                </div>
                            </div>


                            <hr/>
                            <?php if ($session->hasFlash('ErrorMessage')) { ?>
                                <div class="alert alert-danger"><?= $session->getFlash('ErrorMessage'); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>
                                </div>
                            <?php } ?>
                            <div class="col-lg-6">
                                <form class="form-horizontal" name="EntryForm" action="/sathsang/web/admin/edit-leftbar-heading?heading_id=<?= CommonMethods::displayVariableContent(base64_encode($tblDatawithID['heading_id'])); ?>" method="post">
                                    <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                           value="<?= Yii::$app->request->csrfToken ?>"/>

                                    <div class="row mb-3">
                                        <label for="page_name" class="col-4 col-xl-3 col-form-label">Letfbar Headings</label>
                                        <div class="col-8 col-xl-9">
                                            <input type="text" class="form-control" required id="heading_name" name="heading_name" value="<?= CommonMethods::displayVariableContent($tblDatawithID['heading_name']) ?>">
                                        </div>
                                    </div>

                                    <div class="justify-content-end row">
                                        <div class="col-8 col-xl-9">
                                            <button type="submit" class="btn btn-secondary waves-effect waves-light">Submit</button>
                                            &nbsp;
                                            <button type="button" class="btn btn-light waves-effect" onclick="window.location.href='<?= CommonMethods::displayVariableContent('/admin/view-leftbar-headings/'); ?>'">Cancel</button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="heading_id" name="heading_id"
                                           value="<?= $tblDatawithID['heading_id'] ?>"/>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->