<?php
session_name("Private");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    die();
}
?>

<!DOCTYPE html>
<html style="min-height: 100%;">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans|Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote-bs4.min.css" rel="stylesheet">

    <style>
        body {
            
            background-color: #d8e5ed;
            background-size: cover;
        }

        ._card_box {
            padding: 20px;
        }

        ._card-tp {
            background-color: rgba(255, 255, 255, 1);
            /*min-height: 100%;*/
        }

        ._card-tp-body {
            min-height: 100%;
        }

        a {
            color: #aa8e03;
        }

        a:hover {
            color: #8a6a00;
        }

        .btn-collapse-inline-block-60 {
            display: inline-block !important;
            margin: 0 !important;
            width: 50% !important;
            color: #FFFFFF;
            box-sizing: border-box;
            border-radius: 0 !important;
        }

        .btn-collapse-inline-block-20 {
            display: inline-block !important;
            margin: 0 !important;
            width: 25% !important;
            border-radius: 0 !important;
            color: #FFFFFF;
            box-sizing: border-box;
        }

        .tooltip {
            z-index: 100000000;
        }
    </style>
</head>

<body style="min-height: 100%">
<header class="navbar navbar-expand-lg text-white navbar-dark bg-dark sticky-top">
    <div class="navbar-brand nav-item">
        Yield CMS
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" style="color: #8a440d !important; float: right;">
                <a class="nav-link" href="logout.php" target="_blank"><i class="fas fa-sign-out-alt"
                                                                                  style="font-size: 30px; vertical-align: middle;margin-right:8px;"></i>Deconectare</a>
            </li>

        </ul>
    </div>

</header>

<div class="container-fluid"
     style="min-height: 100%; width: 100%;position: relative; box-sizing: border-box; display: block;">
    <div class="row" style="min-height: 100%;width: 100%;margin:0;" id="quick">
        <!-- Meniu -->
        <div class="col-sm-12 col-md-4 _card_box">
            <div class="card _card-tp">
                <div class="card-body _card-tp-body">
                    <h3 class="card-title" style="display: inline-block">Meniu</h3>
                    <button class="btn btn-outline-info" style="float: right" onclick="sendUpdatedMenu()">
                        Publică meniu
                        <i class="fas fa-save" style="margin-left: 5px;"></i>
                    </button>
                    <div style="width: 100%">
                        <ul class="list-group sortMenu sortMenuBase" style="width: 100%" id="menuList">

                        </ul>

                        <button class="btn btn-dark" style="margin-top: 30px;margin-right: 5px;" id="btn-menu-new-category">
                            <i class="fas fa-plus"></i>
                            Categorie nouă
                        </button>
                        <button class="btn btn-dark" style="margin-top: 30px;" id="btn-menu-add-page">
                            <i class="fas fa-plus"></i>
                            Adaugă pagină
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagini -->
        <div class="col-sm-12 col-md-4 _card_box">
            <div class="card _card-tp">
                <div class="card-body _card-tp-body">
                    <h3 class="card-title">Pagini</h3>
                    <div style="width: 100%">
                        <ul class="list-group" style="width: 100%" id="pageList">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Adauga Continut -->
        <div class="col-sm-12 col-md-4 _card_box">
            <div class="card _card-tp">
                <div class="card-body _card-tp-body">
                    <h3 class="card-title">Conținut</h3>
                    <button type="button" class="btn btn-outline-info" id="btn-upload-gdrive" onclick="createPage('document')"
                            style="width:100%;height:60px;margin-bottom: 25px;">Adaugă document
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="createPage('html')"
                            style="width:100%;height:60px;margin-bottom: 25px;">Adaugă pagină scrisă
                    </button>



                    <!-- Page select dialog -->
                    <div class="modal fade" id="bd-page-select-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content" style="min-height: 200px;">
                                <h3 class="card-title" style="margin: 25px">Selectează o pagină</h3>
                                <div class="display list-group-item" style="margin-bottom: 100px;">
                                    <form action="" id="page-select-choice">
                                    </form>
                                </div>
                                <p style="right: 15px; bottom: 5px; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-info" id="btn-page-select-modal-cancel">Cancel</button>
                                    <button class="btn btn-danger" id="btn-page-select-modal-ok">OK</button>
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Field edit dialog-->
                    <div class="modal fade" id="bd-field-edit-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content" style="min-height: 200px;">
                                <h3 class="card-title" id="field-edit-modal-title" style="margin: 25px"></h3>
                                <div class="display list-group-item" style="margin-bottom: 100px;">
                                    <label for="tbx-field-edit-modal-input" id="label-field-edit-modal-input"></label>
                                    <input type="text" id="tbx-field-edit-modal-input">
                                </div>
                                <p style="right: 15px; bottom: 5px; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-info" id="btn-field-edit-modal-cancel">Cancel</button>
                                    <button class="btn btn-danger" id="btn-field-edit-modal-ok">OK</button>
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Page edit html -->
                    <div class="modal fade" id="bd-html-edit-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="min-height: 500px;">
                                <div class="card-title">
                                    <h3 class="" id="html-edit-modal-title" style="margin: 25px; display: inline-block">Editează pagina</h3>
                                    <span style="float:right; display: inline-block;margin-top: 25px;margin-right: 25px;">
                                        <button class="btn btn-info" id="btn-html-edit-modal-cancel">Cancel</button>
                                        <button class="btn btn-danger" id="btn-html-edit-modal-ok">OK</button>
                                    </span>
                                </div>
                                <div type="text" id="summernote-html-edit"
                                     style="height: 500px;margin-bottom: 150px;"></div>
                                <!--<p style="right: 15px; bottom: 5px; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-info" id="btn-html-edit-modal-cancel">Cancel</button>
                                    <button class="btn btn-danger" id="btn-html-edit-modal-ok">OK</button>
                                </p>-->

                            </div>
                        </div>
                    </div>

                    <!-- Upload Dialog -->
                    <div class="modal fade" id="bd-upload-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content" style="">
                                <h3 class="card-title" id="upload-modal-title" style="margin: 25px">Încarcă un
                                    document</h3>
                                <p style="margin-left: 25px;"><input type="file" id="file-input-upload"></p>
                                <p style="width: 100%; text-align: center;">
                                    <svg version="1.1" id="upload-loading" style="width: 100px;display: none;" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                        <circle fill="#000" stroke="none" cx="6" cy="50" r="6">
                                            <animateTransform
                                                    attributeName="transform"
                                                    dur="1s"
                                                    type="translate"
                                                    values="0 15 ; 0 -15; 0 15"
                                                    repeatCount="indefinite"
                                                    begin="0.1"/>
                                        </circle>
                                        <circle fill="#000" stroke="none" cx="30" cy="50" r="6">
                                            <animateTransform
                                                    attributeName="transform"
                                                    dur="1s"
                                                    type="translate"
                                                    values="0 10 ; 0 -10; 0 10"
                                                    repeatCount="indefinite"
                                                    begin="0.2"/>
                                        </circle>
                                        <circle fill="#000" stroke="none" cx="54" cy="50" r="6">
                                            <animateTransform
                                                    attributeName="transform"
                                                    dur="1s"
                                                    type="translate"
                                                    values="0 5 ; 0 -5; 0 5"
                                                    repeatCount="indefinite"
                                                    begin="0.3"/>
                                        </circle>
                                    </svg>
                                    <img id="upload-success" src="assets/success.svg" style="width: 100px;display: none;" alt="Success">
                                </p>
                                <p style="right: 15px; bottom: 5px; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-info" id="btn-upload-modal-cancel">Cancel</button>
                                    <button class="btn btn-danger" id="btn-upload-modal-ok">OK</button>
                                </p>
                                <p style="height: 60px;"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Display Dialog -->
                    <div class="modal fade" id="bd-display-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" style="border: 0; padding: 0;">
                            <div class="modal-content" style="min-height: 200px;">
                                <div id="display-modal-html-area" class="d-block" style="width: 100%;background-color: #333333;"></div>
                                <p style="right: 15px; bottom: 0; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-secondary" id="btn-display-modal-link">Link</button>
                                    <button class="btn btn-info" id="btn-display-modal-cancel">Cancel</button>

                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Confirmation dialog -->
                    <div class="modal fade" id="bd-confirm-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content" style="height: 200px;">
                                <h3 class="card-title" id="title-confirm-dialog" style="margin: 25px">Confirmă</h3>
                                <p class="display" style="margin-left: 25px;"></p>
                                <p style="right: 15px; bottom: 5px; display: block; position: absolute;z-index:999;">
                                    <button class="btn btn-info" id="btn-confirm-modal-cancel">Cancel</button>
                                    <button class="btn btn-danger" id="btn-confirm-modal-ok">OK</button>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="assets/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote-bs4.min.js"></script>
<script src="assets/toast.min.js"></script>
<script src="assets/excel/xlsx.core.min.js"></script>
<script src="cmsExtensions.js"></script>
<script src="cmsController.js"></script>

</body>
</html>


<?php
session_write_close();
?>
