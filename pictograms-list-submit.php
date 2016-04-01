<?php
require_once "config/debug.php";
require_once "classes/pictograms-list-class.php";
$service                   = new pictogramsService();
if(isset($_POST['editpictogram'])){
    $pictogram_id              = $_POST['id'];
    $pictogram_alt             = $_POST['firstname'];
    $pictograms_caption        = $_POST['secondname'];
    $pictograms_type           = $_POST['thirdname'];
    $service->updatePictogram($pictogram_alt, $pictograms_caption, $pictograms_type, $pictogram_id);
}
if(isset($_POST['add_pictogram'])){
    $alt            = $_POST['alt'];
    $caption        = $_POST['caption'];
    $type           = $_POST['type'];
    $service->addPictogram($alt, $caption, $type);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Piktogramm erfolgreich hinzugefügt!
            </div>';
}
