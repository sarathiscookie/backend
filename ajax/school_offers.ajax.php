<?php
require_once "../classes/manage_school_offers.class.php";

$objShlOffer = new ManageSchoolOffers();

if(isset($_GET['mode']) && $_GET['mode']=='C') {
    $objShlOffer -> createOffer();
}
elseif(isset($_GET['mode']) && $_GET['mode']=='UPrc') {
    $objShlOffer -> updateOfferDetails();
}
elseif(isset($_GET['mode']) && $_GET['mode']=='APrc') {
    $objShlOffer -> createOfferDetails();
}
else {
    $objShlOffer->updateOffer();
}
