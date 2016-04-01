<?php
require_once "../classes/manage_offers.class.php";

$objOffer = new ManageOffers();

if(isset($_GET['mode']) && $_GET['mode']=='C') {
    $objOffer -> createOffer();
}
elseif(isset($_GET['mode']) && $_GET['mode']=='UPrc') {
    $objOffer -> updateOfferDetails();
}
elseif(isset($_GET['mode']) && $_GET['mode']=='APrc') {
    $objOffer -> createOfferDetails();
}
else {
    $objOffer->updateOffer();
}
