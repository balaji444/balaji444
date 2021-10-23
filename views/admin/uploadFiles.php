<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Uploads';
$this->params['breadcrumbs'][] = $this->title;

$select2MinCss = "@web/assets/libs/select2/css/select2.min.css";
$this->registerCssFile($select2MinCss);

$quillCoreCss = "@web/assets/libs/quill/quill.core.css";
$this->registerCssFile($quillCoreCss);

$quillBubbleCss = "@web/assets/libs/quill/quill.bubble.css";
$this->registerCssFile($quillBubbleCss);

$quillSnowCss = "@web/assets/libs/quill/quill.snow.css";
$this->registerCssFile($quillSnowCss);

$selectizeCss = "@web/assets/libs/selectize/css/selectize.bootstrap3.css";
$this->registerCssFile($selectizeCss);


?>

<div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">


                        <div class="row">
                            <div  class="col-lg-12">
                            	<div class="card">
                                <div class="card-body">
                                
                                <h2 class="mb-0 mt-0">Upload</h2>
                                
                                <hr/>
                                
                                <div class="col-lg-6">
                                
                                <form id="uploadFiles" class="form-horizontal" action="javascript:void(0)"  method="post" enctype="multipart/form-data">
                                    <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                           value="<?= Yii::$app->request->csrfToken ?>"/>
                                    <input type="hidden" id="description_text" name="description_text" value="">
                                            <div class="row mb-3">
                                                <label for="title" class="col-4 col-xl-3 col-form-label">Tittle</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control" id="title" name="title" placeholder="Tittle">
                                                </div>
                                            </div>
                                            <!--<div class="row mb-3">
                                                <label for="inputPassword3" class="col-4 col-xl-3 col-form-label">Description </label>
                                                <div class="col-8 col-xl-9">

                                                    <div id="snow-editor" style="height: 200px;">
                                            		<p id="description">Description</p>
                                      				  </div>

                                                </div>
                                            </div>-->
                                    <div class="row mb-3">
                                        <label for="inputPassword3" class="col-4 col-xl-3 col-form-label">Description </label>
                                        <div class="col-8 col-xl-9">
                                            <textarea class="form-control" rows="5" name="description"
                                                      id="description"
                                                      placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                            <!--<div class="row mb-3">
                                                <label for="tags" class="col-4 col-xl-3 col-form-label">Tags</label>
                                                <div class="col-8 col-xl-9">
                                                     <input type="text" class="selectize-close-btn" id="tags" name="tags" value="awesome,neat" >
                                                </div>
                                            </div>-->
                                            <div class="row mb-3">
                                                <label for="upload_type" class="col-4 col-xl-3 col-form-label">Upload</label>
                                                <div class="col-8 col-xl-9">
                                                     <select class="form-select" id="upload_type" name="upload_type" onchange="fnShowBrowseUploads()">
                                                            <option value="">Select</option>
                                                            <option value="youtube_url">Youtube URL</option>
                                                            <option value="image">Image</option>
                                                            <option value="audio">Audio</option>
                                                        </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3" id="uploadBrowseDiv" style="display: none">
                                                <label for="upload_files" class="col-4 col-xl-3 col-form-label">&nbsp;</label>
                                                <div class="col-8 col-xl-9">
                                                    <div class="input-group">
                                                        <input class="form-control" type="file" id="upload_files" name="upload_files">
                                                    </div>
                                                    <span class="small mt-1">Maximum File Size: 50KB</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3" id="youtubeUrlDiv" style="display: none">
                                                <label for="youtube_url" class="col-4 col-xl-3 col-form-label">&nbsp;</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control" id="youtube_url" name="youtube_url" placeholder="Youtube URL">
                                                </div>
                                            </div>
                                            
                                            

                                            <div class="justify-content-end row">
                                                <div class="col-8 col-xl-9">
                                                    <button type="button" class="btn btn-secondary waves-effect waves-light" onclick="fnValidateUploadFile()">Submit</button>
                                                    <span id="error_msg" style="display: none;color: red;"></span>
                                                    <span id="success_msg" style="display: none"></span>
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
    <script type="text/javascript">
        function fnShowBrowseUploads() {
            var uploadType = $("#upload_type").val();
            if(uploadType == '') {
                $("#youtubeUrlDiv").hide();
                $("#uploadBrowseDiv").hide();
            } else if(uploadType != 'youtube_url') {
                $("#youtubeUrlDiv").hide();
                $("#uploadBrowseDiv").show();
            } else {
                $("#uploadBrowseDiv").hide();
                $("#youtubeUrlDiv").show();
            }
        }
        function fnValidateUploadFile() {

            $("#error_msg").text("");
            $("#error_msg").hide();

            var title = $("#title").val();
            if($.trim(title) == '') {
                $("#error_msg").show('');
                $("#error_msg").text("Please Enter Title");
                return false;
            }
            var description = $("#description").val();
            if($.trim(description) == '') {
                $("#error_msg").show('');
                $("#error_msg").text("Please Enter Description");
                return false;
            }

            var youtubeUrl = $("#youtube_url").val();
            var uploadType = $("#upload_type").val();
            if($.trim(uploadType) == '') {
                $("#error_msg").show('');
                $("#error_msg").text("Please Select Upload");
                return false;
            }
            if($.trim(uploadType) != 'youtube_url') {
                $("#error_msg").show('');

                const fi = document.getElementById('upload_files');
                // Check if any file is selected.
                if (fi.files.length > 0) {
                    const fileSize = fi.files[0].size / 1024 / 1024;

                        // The size of the file.
                        if (fileSize >= 25) {
                            $("#error_msg").text("File too Big, please select a file less than 25mb");
                            return false;
                        }

                    var filePath = fi.value;
                    // Allowing file type
                    var allowedExtensions =
                        /(\.jpg|\.jpeg|\.png|\.gif|\.pdf|\.mp4|\.mp3)$/i;

                    if (!allowedExtensions.exec(filePath)) {
                        $("#error_msg").text("Please Upload Valid File");
                        fi.value = '';
                        return false;
                    }

                } else {
                    $("#error_msg").text("Please Select File ");
                    return false;
                }
            } else {
                if($.trim(youtubeUrl) == '') {
                    $("#error_msg").show('');
                    $("#error_msg").text("Please Enter YouTune URL ");
                    return false;
                }
            }

            var url 			= "/sathsang/web/admin/upload-files-ajax";
            var form 			= $("#uploadFiles");
            if($.trim(uploadType) != 'youtube_url') {
                var formData = false;
                if (window.FormData) {
                    formData = new FormData(form[0]);
                }
            } else {
                formData = new FormData(form[0]);
            }


            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function (e) {
                    if(e == "Success")
                    {
                        $("#success_msg").css("color","green").css("display","").text("File Successfully Uploaded.");

                        setTimeout('window.location.href="/sathsang/web/admin/view-uploaded-files"',"2000");
                    }else if(e == "File Exists"){
                        $("#error_msg").show('');
                        $("#error_msg").text("File Already Exists, Please choose another file...!");
                    }else{
                        $("#error_msg").show('');
                        $("#error_msg").text("Something went wrong, try again...!");
                    }
                },
                error: function (e) {
                }
            });
        }
    </script>
<?php
$select = "@web/assets/libs/select2/js/select2.min.js";
$this->registerJsFile($select,
['depends' => [\yii\web\JqueryAsset::className()]]);

$quillMinJs = "@web/assets/libs/quill/quill.min.js";
$this->registerJsFile($quillMinJs,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$quillInitJs = "@web/assets/js/pages/form-quilljs.init.js";
$this->registerJsFile($quillInitJs,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$selectizeJs = "@web/assets/libs/selectize/js/standalone/selectize.min.js";
$this->registerJsFile($selectizeJs,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$multiSelectJs = "@web/assets/libs/multiselect/js/jquery.multi-select.js";
$this->registerJsFile($multiSelectJs,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$maxSelectJs = "@web/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js";
$this->registerJsFile($maxSelectJs,
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$selectFormAdvanced = "@web/assets/js/pages/form-advanced.init.js";
$this->registerJsFile($selectFormAdvanced,
['depends' => [\yii\web\JqueryAsset::className()]]);