<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="card shadow-none">
                            <div class="card-body p-3">
                                <?php if(empty($mobileNumber)) { ?>
                                <form class="form-horizontal " id="loginform" action="/sathsang/web/site/login-for-otp" method="post">
                                    <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Your Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><img src="<?=SITE_URL;?>/assets/images/flags/india.jpg" height="16" alt=""></span>
                                            <input type="tel" id="phone" name="phone" class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-center d-grid">
                                        <button class="btn btn-secondary waves-effect waves-light" type="submit"> Login / Sign up </button>
                                        <span style="color: red;"><?=!empty($otpInvalid)? $otpInvalid : '';?></span>
                                    </div>
                                </form>

                               <?php } if(!empty($mobileNumber)) { ?>
                                <form class="form-horizontal" id="loginOtpForm" action="/sathsang/web/site/login-submit" method="post">
                                    <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>
                                    <input type="hidden" id="phone" name="phone" value="<?=$mobileNumber;?>">
                                    <div class="mb-3">
                                        <h3>Enter the 4-digit code sent to</h3>
                                        <h3 class="mb-3"><?=$mobileNumber[0];?>*******<?=$mobileNumber[8];?><?=$mobileNumber[9];?> &nbsp;<a href="/sathsang/web/site/login" class="small text-muted fw-normal"><ins>Change mobile number?</ins></a></h3>
                                        <div id="divOuter">
                                            <div id="divInner">
                                                <input id="partitioned" name="userOtp" type="text" maxlength="4" />
                                            </div>
                                        </div>
                                        <div class="mt-2" id="resendCode" style="display: none"><a href="javascript:void(0);" class="small" onclick="fnOtpResend('<?=$mobileNumber?>')">Resend code</a></div>
                                        <div class="mt-2 optCodeDis">Code expires in <span class="countdown"></span></div>
                                    </div>
                                    <div class="text-center d-grid">
                                        <button class="btn btn-secondary waves-effect waves-light" type="submit" id="logContinue"> Continue </button>
                                        <span style="color: red;"><?=!empty($otpInvalid)? $otpInvalid : '';?></span>
                                    </div>
                                </form>
<?php } ?>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div> <!-- container -->
        </div> <!-- content -->
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->
<script type="text/javascript">
    function fnCountdownShow() {
        $(".optCodeDis").show();
        $("#resendCode").hide();
        var timer2 = "02:00";
        var interval = setInterval(function () {


            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            minutes = (minutes < 10) ?  minutes : minutes;
            $('.countdown').html(minutes + ':' + seconds);
            timer2 = minutes + ':' + seconds;


            if (minutes == '00' && seconds == '00') {
                $("#resendCode").show();
                $(".optCodeDis").hide();
                $("#logContinue").hide();
            }
        }, 1000);
    }

    function fnOtpResend(mobileNumber) {
        $("#logContinue").show();
        var formURL = '/sathsang/web/site/otp-resend-ajax';
        var csrfTokenPage = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "POST",
            url: formURL,
            data: {
                phone: mobileNumber,
                _csrf: csrfTokenPage
            },
            success: function (data) {
                fnCountdownShow();
            }
        });
    }
</script>
<?php
$JsInro = '
$(document).ready(function() {
    fnCountdownShow();
});
';
$this->registerJs($JsInro, static::POS_END);