<?php
require_once "includes/header.php";
require_once "classes/manage_school_offers.class.php";


$objShlOffer = new ManageSchoolOffers();

$offers       = $objShlOffer->listOffers();
$course_opts  = $objShlOffer->getCourseOptions();
$status_opts  = $objShlOffer->getStatusOptions();
$date_opts    = $objShlOffer->getTypeDateOptions();
$typ_opts     = $objShlOffer->getTypes();
$school_name  = $objShlOffer->getSchoolName();
$school_id    = $_GET["sid"];
?>
<link href="css/jquery.datetimepicker.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/school_sidebar.php'; ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Angebote <small>(<?=$school_name?>)</small>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#offerModal">
                    Angebot hinzufügen
                </button>
            </h1>
            <div class="row" id="ofrContainer">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?=$offers?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--modal- addOffer-->
<div class="modal fade" id="offerModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Angebote hinzufügen</h4>
            </div>
            <div class="modal-body">
                <form id="addOfferFrm" onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Verfügbar ab</label>
                            <input type="text" class="form-control offrDate" placeholder="Date begin" id="date_begin" name="date_begin" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Verfügbar bis</label>
                            <input type="text" class="form-control offrDate" placeholder="Date end" id="date_end" name="date_end" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Titel</label>
                            <input type="text" class="form-control" placeholder="Title" id="titletxt" name="title" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Untertitel</label>
                            <input type="text" class="form-control" placeholder="Sub title" name="subtitle" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Kopfbereich</label>
                            <textarea name="header" id="headerTxt" class="form-control textarea mceEditor" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Fußbereich</label>
                            <textarea name="footer" id="footerTxt" class="form-control textarea mceEditor" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Leistungen </label>
                            <textarea name="services" class="form-control textarea" rows="7"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Beschreibung </label>
                            <textarea name="description" class="form-control textarea" rows="7"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Alias </label>
                            <input type="text" class="form-control" placeholder="Alias" name="alias" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Typ </label>
                            <select name="type" class="form-control"><?=$typ_opts?></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Course</label>
                            <select name="course" class="form-control"><?=$course_opts?></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Status</label>
                            <select name="status" class="form-control"><?=$status_opts?></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Datum Typ</label>
                            <select name="type_date" class="form-control"><?=$date_opts?></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Rabatt <small>(EUR)</small></label>
                            <input type="text" class="form-control" placeholder="Rabatt" name="discount" value="">
                        </div>
                    </div>
                    <input type="hidden" name="school_id" value="<?=$school_id?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="button" id="addOfrBtn" class="btn btn-primary">Speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php include "includes/scripts.php"; ?>
<script src="js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode : "specific_textareas",
        editor_selector : "mceEditor",



        theme : "advanced",
        plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

        // Theme options
        theme_advanced_buttons1 : "undo,redo,|,bold,italic,underline,|,styleselect",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_resizing : true,

        content_css : "style.css",
        width:"100%",

        // Style formats
        style_formats : [
            {title : 'Überschriften'},
            {title : 'Überschrift 4 in Rot', block : 'h4', classes : 'text-red'},
            {title : 'Überschrift 5 in Rot', block : 'h5', classes : 'text-red'},
            {title : 'Überschrift 6 in Rot', block : 'h6', classes : 'text-red'},
            {title : 'Überschrift 4 in Grün', block : 'h4', classes : 'text-green'},
            {title : 'Überschrift 5 in Grün', block : 'h5', classes : 'text-green'},
            {title : 'Überschrift 6 in Grün', block : 'h6', classes : 'text-green'},
            {title : 'Normaler Text'},
            {title : 'Schrift in Rot', inline : 'span', classes : 'text-red'},
            {title : 'Schrift in Grün', inline : 'span', classes : 'text-green'},
        ],

    });

    if($('.btnUpdate').length) {
        $("#ofrContainer").on('click', '.btnUpdate', function (e) {
            if($('#alert_div').length){
                $('#alert_div').remove();
            }
            var id = $(this).attr("id").split("-")[1];
            if($('#title_'+id).val()=='')
            {
                var htmlstr = '<div class="alert alert-danger fade in text-left" id="alert_div" role="alert">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '<span id="alert_msg"><strong>Fehler </strong>Titel ist ein Pflichtfeld!</span>' +
                    '</div>';
                $(htmlstr).insertBefore('#updateBtn-'+id);
                return false;
            }

            $('#header_'+id).html(tinymce.get('header_'+id).getContent());
            $('#footer_'+id).html(tinymce.get('footer_'+id).getContent());
            var params = $('#offerFrm_'+id).serialize();
            $.post("ajax/school_offers.ajax.php", params , function (data) {
                if(data.success>0) {
                    $('#headtxt_'+id).html(data.heading);
                    var htmlstr = '<div class="alert alert-success fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Erfolgreich gespeichert!</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn-'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Fehler beim Speichern!</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn-'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");
        });
    }

    //add course modal - save
    $('#addOfrBtn').click( function() {
        if($('#alert_div').length){
            $('#alert_div').remove();
        }

        if ($('#date_begin').val() == '' || $('#date_end').val() == '') {
            var htmlstr = '<div class="alert alert-danger fade in text-left" id="alert_div" role="alert">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<span id="alert_msg"><strong>Fehler: </strong>Datum ist ein Pflichtfeld!</span>' +
                '</div>';
            $('#addOfrBtn').parent().prepend(htmlstr);
            return false;
        }
        if($('#titletxt').val()=='')
        {
            var htmlstr = '<div class="alert alert-danger fade in text-left" id="alert_div" role="alert">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<span id="alert_msg"><strong>Fehler: </strong>Titel ist ein Pflichtfeld!</span>' +
                '</div>';
            $('#addOfrBtn').parent().prepend(htmlstr);
            return false;
        }
        $('#headerTxt').html(tinymce.get('headerTxt').getContent());
        $('#footerTxt').html(tinymce.get('footerTxt').getContent());
        var params = $('#addOfferFrm').serialize();
        $.post("ajax/school_offers.ajax.php?mode=C", params , function (data) {
            if(data.success>0) {
                $('#addOfferFrm')[0].reset();
                var htmlstr = '<div class="alert alert-success text-left" id="alert_div" role="alert">' +
                    '<span id="alert_msg">Erfolgreich gespeichert! Bitte warten...&hellip;</span>' +
                    '</div>';
                $('#addOfrBtn').parent().prepend(htmlstr);
                $("#alert_div").fadeTo(2500, 500).slideUp(500, function () {
                    $("#alert_div").alert('close');
                    $("#offerModal").modal('hide');
                    location.reload();
                });
            }
            else {
                var htmlstr = '<div class="alert alert-danger fade in text-left" id="alert_div" role="alert">' +
                    '<span id="alert_msg">Fehler beim Speichern!</span>' +
                    '</div>';
                $('#addOfrBtn').parent().prepend(htmlstr);
                $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                    $("#alert_div").alert('close');
                });
            }
        }, "json");
    });

    //on closing modal clear form
    $('#offerModal').on('hidden.bs.modal', function (e) {
        $('#addOfferFrm')[0].reset();
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
    });

    //bind datepicker
    $('.offrDate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'de',
        format:'d.m.Y',
        timepicker:false,
    });

$('#navShlOfr').addClass('active');
</script>
<?php include "includes/footer.php"; ?>
