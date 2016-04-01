<?php
require_once "includes/header.php";
require_once "classes/manage_hotels.class.php";

$objHotel = new ManageHotels();

$hotel_name     = $objHotel->getHotelName();
$prices         = $objHotel->editPrices();
$add_price_form = $objHotel->showAddPriceForm();

//check for archived list
$archived_list  = $objHotel->editArchivedPrices();
$hotel_id = $_GET["hid"];
?>
<link href="css/jquery.datetimepicker.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/hotel_sidebar.php'; ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Preise <small>(<?=$hotel_name?>)</small>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#priceModal" >
                    Preise hinzufügen
                </button>
            </h1>
            <?=$messages?>
            <div class="row" id="prcContainer">
                <div class="col-md-6">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?= $prices?>
                    </div>
                </div>
                <?php if($archived_list!=""){  ?>
                <div class="col-md-6">
                    <?= $archived_list?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!--modal- addCourse-->
<div class="modal fade" id="priceModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Preise hinzufügen</h4>
            </div>
            <div class="modal-body">
                <?=$add_price_form?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="button" id="addPrcBtn" class="btn btn-primary">Neuen Eintrag speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php include "includes/scripts.php"; ?>
<script src="js/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    //bind datepicker
    $('.prcDate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'de',
        format:'d.m.Y',
        timepicker:false,
    });

    //addPrices to Hotel
    $('#addPrcBtn').click( function() {
        if($('#addPriceFrm').length) {
            if ($('#alert_div').length) {
                $('#alert_div').remove();
            }
            if ($('#date_begin').val() == '' || $('#date_end').val() == '') {
                var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '<span id="alert_msg"><strong>Fehler: </strong>Datum ist ein Pflichtfeld!</span>' +
                    '</div>';
                $(htmlstr).insertBefore('#addPriceFrm');
                return false;
            }
            var params = $('#addPriceFrm').serialize();
            $.post("ajax/hotel_price.ajax.php?mode=C", params, function (data) {
                if (data.success > 0) {
                    $('#addPriceFrm')[0].reset();
                    var htmlstr = '<div class="alert alert-success" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Preise wurden hinzugefügt. Bitte warten...&hellip;</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#addPriceFrm');
                    $("#alert_div").fadeTo(2500, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                        $("#priceModal").modal('hide');
                        location.reload();
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Fehler: Preis wurde nicht gespeichert</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#addPriceFrm');
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");
        }
    });

    //on closing modal clear form
    $('#priceModal').on('hidden.bs.modal', function (e) {
        if($('#addPriceFrm').length) {
            $('#addPriceFrm')[0].reset();
        }
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
    });

    //when no price - hide modal buttons
    if(!$('#addPriceFrm').length) {
        $('#priceModal .modal-footer').hide();
    }

    //navigate to settings - hide modal
    if($('#settingNavA').length) {
        $('#settingNavA').click( function() {
            $("#priceModal").modal('hide');
        });
    }

    //Update hotel prices
    if($('.prcUpdate').length) {
        $("#prcContainer").on('click', '.prcUpdate', function (e) {
            if($('#alert_div').length){
                $('#alert_div').remove();
            }
            var id = $(this).attr("id").split("_")[1];
            var params = $('#pricesFrm_'+id).serialize();
            $.post("ajax/hotel_price.ajax.php", params , function (data) {
                if(data.success>0) {
                    if(data.heading!='')
                        $('#panelHead'+id).text(data.heading);
                    var htmlstr = '<div class="alert alert-success fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Erfolgreich aktualisiert. Bitte warten...&hellip;</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#pricesBtn_'+id);
                    $("#alert_div").fadeTo(2500, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                        location.reload();
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Fehler: Konnte nicht gespeichert werden!</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#pricesBtn_'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");
        });
    }
    $('#navPrcLst').addClass('active');
</script>
<?php include "includes/footer.php";?>
