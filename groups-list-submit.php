<?php
require_once "config/debug.php";
require_once "classes/groups-list-class.php";
$service                   = new groupService();
if(isset($_POST['editgroup'])){
    $group_id              = $_POST['id'];
    $group_title           = $_POST['firstname'];
    $group_headline        = htmlspecialchars_decode($_POST['secondname']);
    $anchor                = $_POST['thirdname'];
    $service->updateGroup($group_title, $group_headline, $anchor, $group_id);
}
if(isset($_POST['add_group'])){
    $title            = $_POST['title'];
    $headline         = htmlspecialchars_decode($_POST['headline']);
    $anchor           = $_POST['anchor'];
    $service->addGroup($title, $headline, $anchor);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            <strong>Well done!</strong> Group added successfully.
            </div>';
}
