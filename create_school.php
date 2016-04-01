<?php
require_once "includes/header.php";
require_once "classes/manage_schools.class.php";

$objSchool = new ManageSchools();

if(isset($_POST) && $_POST['title']!='')
{
    $objSchool -> createSchool();
}
$messages = $objSchool->showMessage();
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/sidebar.php'; ?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Neue Golfschule anlegen</h1>
<?=$messages?>
            <form method="POST" action="create_school.php">
                <div class="row">
                    <div class="form-group col-md-6">
                    <label for="name">Schul-Name</label>
                    <input type="text" class="form-control" id="title" name="title" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Alias</label>
                        <input type="text" class="form-control" id="alias" name="alias" value="">
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
                            <?= $objSchool->getGroup() ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="golfdistance">Adresse für Google Maps</label>
                        <input type="text" class="form-control controls" id="address" name="address">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="coords">Geo-Koordinaten</label>
                        <input type="text" class="form-control" id="coords" name="coords" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="city">Stadt</label>
                        <input type="text" class="form-control" name="city" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="location">Bundesland / Location</label>
                        <input type="text" class="form-control" name="location" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="country">Land</label>
                        <input type="text" class="form-control" name="country" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="holes">Löcher</label>
                        <input type="number" class="form-control" name="holes" value="" min="0">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="training">Ausbildung</label>
                        <input type="number" class="form-control" name="training" value="" min="0">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="duration">Dauer</label>
                        <input type="text" class="form-control" name="duration" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="flex_booking">Flexible Buchung</label>
                        &nbsp;<input class="" type="radio" name="flex_booking" value="yes" > Ja &nbsp;
                        <input class="" type="radio" name="flex_booking" value="no" checked="checked"> Nein
                    </div>
                    <div class="form-group col-md-6">
                        <label for="new">Neu</label>
                        &nbsp;<input class="" type="radio" name="new" value="yes" > Ja &nbsp;
                        <input class="" type="radio" name="new" value="no" checked="checked"> Nein
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Schul-Beschreibung</label>
                    <textarea name="description" class="form-control textarea mceEditor" rows="15"></textarea>
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

    $("#alert_div").fadeTo(5000, 500).slideUp(500, function () {
        $("#alert_div").alert('close');
    });
    $('#navAddShl').addClass('active');

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