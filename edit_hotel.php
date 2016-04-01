<?php
require_once "includes/header.php";
require_once "classes/manage_hotels.class.php";

$objHotel = new ManageHotels();

if(isset($_POST) && $_POST['title']!='')
{
    $objHotel -> updateHotel();
}
$fetch    = $objHotel -> viewHotel();

$option ='';
if($fetch['school_id']>0) {
    $text     = $objHotel->getSelectedSchool($fetch['school_id']);
    $option   = '<option selected="selected" value="'.$fetch['school_id'].'">'.$text.'</option>';
}
$messages = $objHotel->showMessage('U');
$hotel_id = $fetch["id"];
?>
<link href="css/select2.min.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/hotel_sidebar.php'; ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header"><?=$fetch["title"]?> bearbeiten</h1>
            <?=$messages?>
            <?php
            if ($fetch["id"]>0) {
            ?>
            <form method="POST" action="edit_hotel.php?hid=<?=$fetch["id"]?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Hotel-Name</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?=$fetch["title"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Alias</label>
                        <input type="text" class="form-control" id="alias" name="alias" value="<?=$fetch["alias"]?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="subtitle">Untertitel</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?=$fetch["subtitle"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subtitle">Status</label>
                        <select name="status" class="form-control">
                            <?= $objHotel->getStatus($fetch["status"]);  ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="subtitle">Gruppierung</label>
                        <select name="group_id" class="form-control">
                            <?= $objHotel->getGroup($fetch["group_id"]) ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subtitle">Golfschule</label>
                        <select name="school_id" id="schoolID" class="form-control">
                           <?= $option?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="stars">Sterne</label>
                        <input type="text" class="form-control" name="stars" value="<?=$fetch["stars"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="golfdistance">Distanz zum Golfplatz</label>
                        <input type="text" class="form-control" id="golfdistance" name="golfdistance" value="<?=$fetch["golfdistance"]?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="golfdistance">Adresse für Google Maps</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?=$fetch["address"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="coords">Geo Koordinaten</label>
                        <input type="text" class="form-control" id="coords" name="coords" value="<?=$fetch["coords"]?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="city">Stadt</label>
                        <input type="text" class="form-control" name="city" value="<?=$fetch["city"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="location">Bundesland / Location</label>
                        <input type="text" class="form-control" name="location" value="<?=$fetch["location"]?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="country">Land</label>
                        <input type="text" class="form-control" name="country" value="<?=$fetch["country"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="country_code">Ländercode</label>
                        <input type="text" class="form-control" name="country_short" value="<?=$fetch["country_short"]?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="additional_nights">Zusatznächte</label>
                        &nbsp;<input class="" type="radio" name="additional_nights" value="yes" <?php echo ($fetch["additional_nights"]=='yes')? "checked":'' ?> > Ja &nbsp;
                        <input class="" type="radio" name="additional_nights" value="no" <?php echo ($fetch["additional_nights"]=='no')? "checked":'' ?> > Nein
                    </div>
                    <div class="form-group col-md-6">
                        <label for="new">Neu</label>
                        &nbsp;<input class="" type="radio" name="new" value="yes" <?php echo ($fetch["new"]=='yes')? "checked":'' ?> > Ja &nbsp;
                        <input class="" type="radio" name="new" value="no" <?php echo ($fetch["new"]=='no')? "checked":'' ?> > Nein
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Beschreibung</label>
                    <textarea name="description" class="form-control textarea mceEditor" rows="15"><?=$fetch["description"]?></textarea>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Informationen</label>
                    <textarea name="terms" style="width:50%;" class="form-control" rows="15"><?=$fetch["terms"]?></textarea>
                </div>
                <div class="form-group">
                    <label for="name">Hotel-Leistungen</label>
                    <textarea name="services" class="form-control" rows="10"><?=$fetch["services"]?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Aktualisieren</button>
                </div>
            </form>
            <?php }?>
            <br><br><br>
        </div>
    </div>
</div>
<?php include "includes/scripts.php"; ?>
<script src="js/select2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    $('#navEdtHtl').addClass('active');
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