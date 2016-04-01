<?php
require_once "config/debug.php";
require_once "classes/text-blocks-class.php";
$service                   = new textBlocksService();
if(isset($_POST['upd_text_block'])){
    $upd_title              = $_POST['upd_title'];
    $upd_description        = $_POST['upd_description'];
    $upd_id                 = $_POST['upd_id'];
    $service->updTextBlocks($upd_title, $upd_description, $upd_id);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Erfolgreich gespeichert!
            </div>';
}
if(isset($_POST['add_text_block'])){
    $title              = $_POST['title'];
    $description        = $_POST['description'];
    $add_text_blocks    = $_POST['add_text_blocks'];
    $service->addTextBlocks($title, $description);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Erfolgreich gespeichert!
            </div>';
}
