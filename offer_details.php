<?php
require_once "includes/header.php";
require_once "classes/manage_offers.class.php";

$objOffer = new ManageOffers();

$info     = $objOffer ->getPageDetails();
$options  = $objOffer ->listOfferDetails();
$hotel_id = $info["hid"];
?>
<link href="css/jquery.datetimepicker.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/hotel_sidebar.php'; ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header"> Preise bearbeiten von <?=$info["title"]?>
                <span class="pull-right">
                    <a class="btn btn-primary" href="offers.php?hid=<?=$info["hid"]?>" role="button"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>Angebote</a>&nbsp;
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pricesModal">
                        Option hinzufügen
                    </button>
                </span>
            </h1>
            <div class="row" id="optnContainer">
                <?=$options?>
            </div>
        </div>
    </div>
</div>
<!--modal- addOfferPrices-->
<div class="modal fade" id="pricesModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Angebotsoption hinzufügen</h4>
            </div>
            <div class="modal-body">
                <form id="addDetailsFrm" onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Von: </label>
                            <input type="text" class="form-control priceDate" placeholder="Date begin" id="date_begin" name="date_begin" value="" readOnly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bis: </label>
                            <input type="text" class="form-control priceDate" placeholder="Date end" id="date_end" name="date_end" value="" readOnly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Nächste: </label>
                            <input type="number" class="form-control" placeholder="Nächte" name="nights" value="" min="1" max="15">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ankunft: </label>
                            <select class="form-control" name="arrival"><?=$info["arrival_opt"]?></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Typ: </label>
                            <input type="text" class="form-control" placeholder="Typ" name="type" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Status: </label>
                            <select class="form-control" name="status"><?=$info["status_opt"]?></select>
                        </div>
                    </div>
                    <div id="pricesBody"><?=$info["room_inputs"]?></div>
                    <input type="hidden" name="offer_id" value="<?=$_GET["ofrid"]?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="button" id="addDtlBtn" class="btn btn-primary">Speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php include "includes/scripts.php"; ?>
<script src="js/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    //bind datepicker
    $('.priceDate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'de',
        format:'d.m.Y',
        timepicker:false,
    });

    //Update offer dates & prices
    if($('.btnUpdate').length) {
        $('.btnUpdate').attr('disabled',false).removeClass('disabled');
        $("#optnContainer").on('click', '.btnUpdate', function (e) {
            if($('#alert_div').length){
                $('#alert_div').remove();
            }
            var id = $(this).attr("id").split("_")[1];
            var params = $('#ofrDateFrm_'+id).serialize();
            $.post("ajax/offers.ajax.php?mode=UPrc", params , function (data) {
                if(data.success>0) {
                    $('#updateBtn_'+id).attr('disabled',true).addClass('disabled');
                    if(data.heading!='')
                        $('#panelHead'+id).find('h3').html(data.heading);
                    var htmlstr = '<div class="alert alert-success fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Erfolgreich gespeichert! Bitte warten...&hellip;</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn_'+id);
                    $("#alert_div").fadeTo(2500, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                        location.reload();
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Fehler beim speichern!</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn_'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");
        });
    }

    //add offer details modal save
    $('#addDtlBtn').click( function() {
            if ($('#alert_div').length) {
                $('#alert_div').remove();
            }

            var params = $('#addDetailsFrm').serialize();
            $.post("ajax/offers.ajax.php?mode=APrc", params, function (data) {
                if (data.success > 0) {
                    $('#addDetailsFrm')[0].reset();
                    var htmlstr = '<div class="alert alert-success" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Daten wurden erfolgreich gespeichert. Bitte warten...&hellip;</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#addDetailsFrm');
                    $("#alert_div").fadeTo(2500, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                        $("#pricesModal").modal('hide');
                        location.reload();
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Fehler beim Speichern!</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#addDetailsFrm');
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");

    });

    //on closing modal clear form
    $('#pricesModal').on('hidden.bs.modal', function (e) {
        $('#addDetailsFrm')[0].reset();
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
    });

    $('#navOfrLst').addClass('active');
</script>
<?php include "includes/footer.php"; ?>

