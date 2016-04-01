<?php
require_once "includes/header.php";
require_once "classes/manage_hotels.class.php";

$objHotel = new ManageHotels();

if(isset($_POST) && $_POST['title']!='')
{
    $objHotel -> createHotel();
}
$messages = $objHotel->showMessage();
?>
<link href="css/select2.min.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/sidebar.php'; ?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Neues Hotel anlegen</h1>
<?=$messages?>
            <form method="POST" action="create_hotel.php">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Hotelname</label>
                        <input type="text" class="form-control" id="title" name="title" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Alias (z.b. hotel-wutzschleife / Keine Umlaute, Nur Kleinschreibung)</label>
                        <input type="text" class="form-control" id="alias" name="alias" value="hotel-">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="subtitle">Untertitel</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subtitle">Gruppierung</label>
                        <select name="group_id" class="form-control">
                            <?= $objHotel->getGroup() ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="subtitle">Golfschule</label>
                        <select name="school_id" id="schoolID" class="form-control">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="stars">Sterne</label>
                        <input type="text" class="form-control" name="stars" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="golfdistance">Distanz zum Golfplatz</label>
                        <input type="text" class="form-control" id="golfdistance" name="golfdistance" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="golfdistance">Adresse für Google Maps</label>
                        <input type="text" class="form-control controls" id="address" name="address" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="coords">Geo Koordinaten</label>
                        <input type="text" class="form-control" id="coords" name="coords" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="city">Stadt</label>
                        <input type="text" class="form-control" name="city" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="location">Bundesland / Location</label>
                        <input type="text" class="form-control" name="location" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="country">Land</label>
                        <input type="text" class="form-control" name="country" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="country_code">Ländercode</label>
                        <input type="text" class="form-control" name="country_short" value="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="additional_nights">Zusatznächte</label>
                        <br /><input class="" type="radio" name="additional_nights" value="yes" > Ja &nbsp;
                        <input class="" type="radio" name="additional_nights" value="no" > Nein
                    </div>
                    <div class="form-group col-md-3">
                        <label for="new">Neu</label>
                        <br /><input class="" type="radio" name="new" value="yes" > Ja &nbsp;
                        <input class="" type="radio" name="new" value="no" checked="checked" > Nein
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Beschreibung</label>
                    <textarea name="description" class="form-control textarea mceEditor" rows="15"></textarea>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Informationen</label>
                    <textarea name="terms" style="width:50%;" class="form-control" rows="15"></textarea>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Leistungen</label>
                    <textarea name="services" class="form-control" rows="10"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Erstellen</button>
                </div>
            </form>
            <br><br><br>
        </div>
    </div>
</div>
<?php include "includes/scripts.php"; ?>
<script src="js/select2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
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


    //bind select2 with category input
    if($('#schoolID').length) {
        $('#schoolID').select2({
            ajax: {
                url: "ajax/school_list.ajax.php",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 3,
        });
    }
    $("#alert_div").fadeTo(5000, 500).slideUp(500, function () {
        $("#alert_div").alert('close');
    });
    $('#navAddHtl').addClass('active');

    /* Geometry Location */
    function initialize() {
        var address = (document.getElementById('address'));
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            /*********************************************************************/
            /* var address contain your autocomplete address *********************/
            /* place.geometry.location.lat() && place.geometry.location.lat() ****/
            /* will be used for current address latitude and longitude************/
            /*********************************************************************/
            document.getElementById('coords').value = place.geometry.location.lat() +','+ place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php include "includes/footer.php";?>