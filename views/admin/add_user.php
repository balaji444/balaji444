<?php

	use app\models\CommonMethods;

$select2MinCss = "@web/assets/libs/select2/css/select2.min.css";
$this->registerCssFile($select2MinCss);

	$actionName = '/sathsang/web/admin/add-user';
	$editUid = 0;
	$pg_controllerName = Yii::$app->controller->id;
	$pg_actionName = Yii::$app->controller->action->id;
	
	if (empty($showDataDtlsArr['user_id'])) {
		$this->title = "Create User";
		$pgName = "Add User";
	}
	if (!empty($showDataDtlsArr['user_id'])) {
		$this->title = "Edit User";
		$actionName = '/sathsang/web/admin/edit-user';
		$editUid = base64_encode($showDataDtlsArr['user_id']);
		$pgName = "Edit User";
	}


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

                            <h2 class="mb-0 mt-0"><?= CommonMethods::displayVariableContent($pgName); ?></h2>

                            <hr/>
                            <?php if ($session->hasFlash('ErrorMessage')) { ?>
                                <div style="color: red;"><?= $session->getFlash('ErrorMessage'); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>
                                </div>
                            <?php } ?>

                            <div class="col-lg-6">


                    <form class="form-horizontal" action="<?= CommonMethods::displayVariableContent($actionName); ?>" method="post"
                          onsubmit="return validateUser()">
                        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                               value="<?= Yii::$app->request->csrfToken ?>"/>
                        <input type="hidden" name="hdn_uid" id="hdn_uid" value="<?= CommonMethods::displayVariableContent($editUid); ?>"/>


                        <div class="row mb-3">
                            <label for="user_role" class="col-4 col-xl-3 col-form-label">Roles</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-control" data-toggle="select2" data-width="100%" name="user_role"
                                        id="user_role">
                                    <option value="">Select</option>
                                    <?php if (!empty($arrAllRoles)) {
                                        foreach ($arrAllRoles as $roleArr) {
                                            $roleSelected = '';
                                            if (!empty($showDataDtlsArr['user_role']) && $showDataDtlsArr['user_role'] == $roleArr['role_id']) {
                                                $roleSelected = " selected='selected' ";
                                            } ?>
                                            <option <?= $roleSelected; ?> value="<?= CommonMethods::displayVariableContent($roleArr['role_id']); ?>"><?= CommonMethods::displayVariableContent($roleArr['role_name']); ?></option>

                                        <?php	}
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="first_name" class="col-4 col-xl-3 col-form-label">First Name</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" <?php if (!empty($showDataDtlsArr['first_name'])) { ?> value="<?= CommonMethods::displayVariableContent($showDataDtlsArr['first_name']); ?>" <?php } ?>>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-4 col-xl-3 col-form-label">Last Name</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" <?php if (!empty($showDataDtlsArr['last_name'])) { ?> value="<?= CommonMethods::displayVariableContent($showDataDtlsArr['last_name']); ?>" <?php } ?>>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_email" class="col-4 col-xl-3 col-form-label">Email</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="user_email" id="user_email" placeholder="" <?php if (!empty($showDataDtlsArr['user_email'])) { ?>  value="<?= CommonMethods::displayVariableContent($showDataDtlsArr['user_email']); ?>" <?php } ?>>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="" class="col-4 col-xl-3 col-form-label">Status</label>
                            <div class="col-8 col-xl-9">
                                <input type="radio" name="user_status" id="radio_3" value="Y" <?php if (!empty($showDataDtlsArr['user_status']) && $showDataDtlsArr['user_status'] == 'Y') { ?> checked="checked" <?php } ?>>
                                <label for="radio_3">Active</label>

                                <input type="radio" name="user_status" id="radio_4" value="Y" <?php if (!empty($showDataDtlsArr['user_status']) && $showDataDtlsArr['user_status'] == 'N') { ?> checked="checked" <?php } ?>>
                                <label for="radio_4">Inactive</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_phone_no" class="col-4 col-xl-3 col-form-label">Phone</label>
                            <div class="col-8 col-xl-9">
                                <input pattern="[0-9]{3}[0-9]{3}[0-9]{4}" type="text" class="form-control" name="user_phone_no" id="user_phone_no" placeholder="" value="<?php if (!empty($showDataDtlsArr['user_phone_number'])) { echo CommonMethods::displayVariableContent($showDataDtlsArr['user_phone_number']); }?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_note" class="col-4 col-xl-3 col-form-label">Note</label>
                            <div class="col-8 col-xl-9">
                                <textarea class="form-control" rows="5" name="user_note" id="user_note"><?php if (!empty($showDataDtlsArr['user_note'])) {
                                        echo CommonMethods::displayVariableContent($showDataDtlsArr['user_note']);
                                    } ?></textarea>
                            </div>
                        </div>

                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" class="btn btn-secondary waves-effect waves-light">Submit</button>
                                &nbsp;
                                <button type="button" class="btn btn-light waves-effect" onclick="window.location.href='<?= '/admin/view-users/'; ?>'">Cancel</button>
                                <div id="validation_errorMsg" style="display:none;color: red;"></div>
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
?>
<script language="javascript" type="application/javascript">

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }


    function redirectToUsers() {
        window.location.href = "/sathsang/web/<?= CommonMethods::displayVariableContent($pg_controllerName); ?>/view-users";
    }


    function validateUser() {
        ss = 0;

        $("#validation_errorMsg").html("");

        var sEmail = $.trim($("#user_email").val());
        if ($.trim(sEmail) != '') {
            if (isEmail(sEmail)) {
                ss = 0;
            } else {
                $("#validation_errorMsg").show();
                $("#validation_errorMsg").html("<b>Invalid Email</b>");
                return false;
            }
        }
        ss = 0;
        var userRole = $.trim($("#user_role").val());
        if (userRole == "") {

            $("#validation_errorMsg").show();
            $("#validation_errorMsg").html("<b>Select Role.</b>");
            return false;
        }

        if (!$("input[name='user_status']:checked").val()) {
            $("#validation_errorMsg").show();
            $("#validation_errorMsg").html("<b>Please check status.</b>");
            return false;
        }

        var userPhone = $.trim($("#user_phone_no").val());
        if (userPhone == "") {
            $("#validation_errorMsg").show();
            $("#validation_errorMsg").html("<b>Please enter phone number.</b>");
            return false;
        }

        $("#validation_errorMsg").hide("");
    }
</script>