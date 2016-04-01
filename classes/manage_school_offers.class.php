<?php
require_once "dbconnection-class.php";

class ManageSchoolOffers
{
    /**
     * list all offers of - particular School
     * @return string
     */
    public function listOffers()
    {
        global $mysqli, $_GET;
        $offer_list = '';
        $typ_opt =["offer-discount"=>"Rabatt auf reguläres Angebot", "offer"=>"Eigenständiges Angebot", "offer-additionaldays"=>"Zusatztage auf reguläres Angebot"];
        $school_id   = $_GET['sid'];
        $offers = "SELECT OFR.* FROM `offers` OFR  WHERE OFR.school_id=".$school_id." AND OFR.status<>'deleted' ORDER BY OFR.id DESC ";
        $rows   = $mysqli->query($offers);
        if ($rows->num_rows > 0) {
            $i=1;
            while ($offer = $rows->fetch_object()) {
                $offer_id    = $offer->id;
                $course_opts = $this->getCourseOptions($offer->course);
                $status_opts = $this->getStatusOptions($offer->status);
                $date_opts   = $this->getTypeDateOptions($offer->type_date);
                $typ_opts    = $this->getTypes($offer->type);
                $header      = $offer->header;
                $footer      = $offer->footer;

                $offer_list .= '<div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading'.$i.'"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="false" aria-controls="collapse'.$i.'"><h4 id="headtxt_'.$offer_id.'">'.$offer->title.'<small> Typ: '.$typ_opt[$offer->type].'</small>&nbsp; '.date("d.m.Y",strtotime($offer->date_begin)).' - '.date("d.m.Y",strtotime($offer->date_end)).'</h4></a>
                                 <a class="btn btn-primary pull-right" href="school_offer_details.php?ofrid='.$offer_id.'" role="button">Optionen anzeigen</a>
                                 <div class="clearfix"></div>
                                </div>
                                <div id="collapse'.$i.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$i.'">
                                <div class="panel-body">
                                <form id="offerFrm_'.$offer_id.'" onsubmit="return false">
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Verfügbar ab </label> <input type="text" class="form-control offrDate" placeholder="Verfügbar ab" id="date_begin_'.$offer_id.'" name="date_begin" value="'.date("d.m.Y",strtotime($offer->date_begin)).'" readonly></div>
                                    <div class="form-group col-md-6"><label>Verfügbar bis </label> <input type="text" class="form-control offrDate" placeholder="Verfügbar bis" id="date_end_'.$offer_id.'" name="date_end" value="'.date("d.m.Y",strtotime($offer->date_end)).'" readonly></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Title </label> <input type="text" class="form-control" placeholder="Title" id="title_'.$offer_id.'" name="title" value="'.$offer->title.'"></div>
                                    <div class="form-group col-md-6"><label>Untertitel </label> <input type="text" class="form-control" placeholder="Untertitel" name="subtitle" value="'.$offer->subtitle.'"></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Kopfzeile </label> <textarea name="header" id="header_'.$offer_id.'" class="form-control textarea mceEditor" rows="10">'.$header.'</textarea></div>
                                    <div class="form-group col-md-6"><label>Fußzeile </label> <textarea name="footer" id="footer_'.$offer_id.'" class="form-control textarea mceEditor" rows="10">'.$footer.'</textarea></div>
                                </div>
                                <div class="row">
                                <div class="form-group col-md-6"><label>Leistungen </label> <textarea name="services" class="form-control textarea" rows="10">'.$offer->services.'</textarea></div>
                                <div class="form-group col-md-6"><label>Beschreibung </label> <textarea name="description" class="form-control textarea" rows="10">'.$offer->description.'</textarea></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Alias </label> <input type="text" class="form-control" placeholder="Alias" name="alias" value="'.$offer->alias.'"></div>
                                    <div class="form-group col-md-6"><label>Typ </label><select name="type" class="form-control">'.$typ_opts.'</select></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Kurs</label><select name="course" class="form-control">'.$course_opts.'</select></div>
                                    <div class="form-group col-md-6"><label>Status</label><select name="status" class="form-control">'.$status_opts.'</select></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6"><label>Datum Typ</label><select name="type_date" class="form-control">'.$date_opts.'</select></div>
                                    <div class="form-group col-md-6"><label>Rabatt <small>(EUR)</small></label><input type="text" class="form-control" placeholder="Discount" name="discount" value="'.$offer->discount.'"></div>
                                </div>
                                <input type="hidden" name="offer_id" value="'.$offer_id.'">
                                <div class="form-group"><button class="btn btn-primary btn-lg btn-block btnUpdate" id="updateBtn-'.$offer_id.'" type="button">Aktualisieren</button></div>
                                </form>
                                </div>
                                </div>
                                </div>';
                $i++;
            }
        }
        else {
            $offer_list ='<small><i>Keine Angebote verfügbar</i></small>';
        }
        return $offer_list;
    }

    /**
     * get course options list
     * @param string $selected
     * @return string
     */
    public function getCourseOptions($selected="no")
    {
        $course_opt =["yes"=>"Ja","no"=>"Nein","optional"=>"Optional"];
        $opt ='';
        foreach ($course_opt as $key => $value)
        {
            $checked = ($key==$selected)?'selected':'';
            $opt .= '<option value="'.$key.'" '.$checked.'>'.$value.'</option>';
        }
        return $opt;
    }

    /**
     * status options list of offers
     * @param string $selected
     * @return string
     */
    public function getStatusOptions($selected="offline")
    {
        $status_opt =["offline"=>"Offline","online"=>"Online","hidden"=>"Versteckt","deleted"=>"Gelöscht"];
        $opt ='';
        foreach ($status_opt as $key => $value)
        {
            $checked = ($key==$selected)?'selected':'';
            $opt .= '<option value="'.$key.'" '.$checked.'>'.$value.'</option>';
        }
        return $opt;
    }

    /**
     * update an offer - Ajax
     */

    public function updateOffer()
    {
        global $mysqli, $_POST;
        $offer_id       = $_POST['offer_id'];
        $date_begin     =  date("Y-m-d", strtotime($_POST["date_begin"]));
        $date_end       =  date("Y-m-d", strtotime($_POST["date_end"]));
        $title          =  $mysqli->real_escape_string($_POST["title"]);
        $subtitle       =  $mysqli->real_escape_string($_POST["subtitle"]);
        $header         =  $mysqli->real_escape_string($_POST["header"]);
        $footer         =  $mysqli->real_escape_string($_POST["footer"]);
        $services       =  $mysqli->real_escape_string($_POST["services"]);
        $description    =  $mysqli->real_escape_string($_POST["description"]);
        $alias          =  $mysqli->real_escape_string($_POST["alias"]);
        $type           =  $mysqli->real_escape_string($_POST["type"]);
        $course         =  $mysqli->real_escape_string($_POST["course"]);
        $status         =  $mysqli->real_escape_string($_POST["status"]);
        $type_date      =  $mysqli->real_escape_string($_POST["type_date"]);
        $discount       =  $mysqli->real_escape_string($_POST["discount"]);
        $update_qry     = "UPDATE `offers` SET
                            `date_begin`='".$date_begin."',
                            `date_end`='".$date_end."',
                            `title`='".$title."',
                            `subtitle`='".$subtitle."',
                            `header`='".$header."',
                            `footer`='".$footer."',
                            `services`='".$services."',
                            `description`='".$description."',
                            `alias`='".$alias."',
                            `type`='".$type."',
                            `course`='".$course."',
                            `status`='".$status."',
                            `type_date`='".$type_date."',
                            `discount`='".$discount."',
                             `updated_at` =NOW() WHERE id=".$offer_id;
        $mysqli->query($update_qry);
        $mes = ($mysqli->affected_rows>=0)?1:0;
        $heading = $title.'<small> Angebot - '.$type.'</small>';

        print json_encode(['success'=>$mes,"heading" =>$heading]);
    }

    /**
     * create an offer - Ajax
     */
    public function createOffer()
    {
        global $mysqli, $_POST;
        $school_id      = $_POST['school_id'];
        $date_begin     =  date("Y-m-d", strtotime($_POST["date_begin"]));
        $date_end       =  date("Y-m-d", strtotime($_POST["date_end"]));
        $title          =  $mysqli->real_escape_string($_POST["title"]);
        $subtitle       =  $mysqli->real_escape_string($_POST["subtitle"]);
        $header         =  $mysqli->real_escape_string($_POST["header"]);
        $footer         =  $mysqli->real_escape_string($_POST["footer"]);
        $services       =  $mysqli->real_escape_string($_POST["services"]);
        $description    =  $mysqli->real_escape_string($_POST["description"]);
        $alias          =  $mysqli->real_escape_string($_POST["alias"]);
        $type           =  $mysqli->real_escape_string($_POST["type"]);
        $course         =  $mysqli->real_escape_string($_POST["course"]);
        $status         =  $mysqli->real_escape_string($_POST["status"]);
        $type_date      =  $mysqli->real_escape_string($_POST["type_date"]);
        $discount       =  $mysqli->real_escape_string($_POST["discount"]);

        $insert_offer ="INSERT INTO offers(`school_id`,`title`,`subtitle`,`header`,`footer`,`services`,`description`,`alias`,`type`,`type_date`,`course`,`discount`,`date_begin`,`date_end`,`status`,`created_at`)
                        VALUES('".$school_id."','".$title."','".$subtitle."','".$header."','".$footer."','".$services."','".$description."','".$alias."','".$type."','".$type_date."','".$course."','".$discount."','".$date_begin."','".$date_end."','".$status."',NOW())";
        $mysqli->query($insert_offer);
        $id = $mysqli->insert_id;
        if($id>0) {
            print json_encode(['success'=>1,'row'=>'']);
        }
        else {
            print json_encode(['success'=>0,'row'=>'']);
        }

    }

    /**
     * List all offer dates and details
     * @return string
     */
    public function listOfferDetails()
    {
        global $mysqli, $_GET;

        $options  = '';
        $offer_id = $_GET["ofrid"];
        $select_dates = "SELECT OD.*,OFR.school_id FROM `offer_dates` OD INNER JOIN offers OFR ON OFR.id=OD.offer_id WHERE OD.`status`<>'deleted' AND  OD.`offer_id` ='".$offer_id."' ORDER BY  OD.date_end DESC, OD.id DESC";
        $dates        = $mysqli->query($select_dates);
        if ($dates->num_rows > 0) {
            while ($date = $dates->fetch_object()) {
                $status_opt   = $this->getDateStatusOptions($date->status);
                $offer_prices = $this->getOfferPrices($date->id);

                $options .= '<div class="col-md-6">
                                <div class="panel panel-default">
                                <div class="panel-heading" id="panelHead'.$date->id.'"><h3 class="panel-title">'.date("d.m.Y", strtotime($date->date_begin)).' bis '.date("d.m.Y", strtotime($date->date_end)).'</h3></div>
                                    <div class="panel-body">
                                    <form method="POST" id="ofrDateFrm_'.$date->id.'" onsubmit="return false">
                                         <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Beginn: </label>
                                                <input type="text" class="form-control priceDate" placeholder="Beginn" name="date_begin" value="'.date("d.m.Y", strtotime($date->date_begin)).'" readOnly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Ende: </label>
                                                <input type="text" class="form-control priceDate" placeholder="Ende" name="date_end" value="'.date("d.m.Y", strtotime($date->date_end)).'" readOnly>
                                            </div>
                                         </div>
                                         <div class="row">
                                             <div class="form-group col-md-6" style="display: none;">
                                                <label>Typ: </label>
                                                <input type="text" class="form-control" placeholder="Typ" name="type" value="'.$date->type.'">
                                             </div>
                                             <div class="form-group col-md-6">
                                                <label>Status: </label>
                                                <select class="form-control" name="status">'.$status_opt.'</select>
                                             </div>
                                         </div>
                                         <div id="pricesBody_'.$date->id.'">'.$offer_prices.'</div>
                                         <input type="hidden" name="date_id" value="'.$date->id.'">
                                         <button class="btn btn-primary btn-lg btn-block btnUpdate" id="updateBtn_'.$date->id.'" type="button">Aktualisieren</button>
                                     </form>
                                    </div>
                                </div>
                            </div>';

            }
        }
        else {
            $options ='<small><i>Keine Angebot-Details verfügbar!</i></small>';
        }
        return $options;
    }




    /**
     * show status options for offer_dates
     * @param string $selected
     * @return string
     */
    public function getDateStatusOptions($selected="online")
    {
        $status_opt =["offline"=>"Offline","online"=>"Online","soldout"=>"Ausgebucht","lastchance"=>"Letzte Chance","deleted"=>"Gelöscht"];
        $opt ='';
        foreach ($status_opt as $key => $value)
        {
            $checked = ($key==$selected)?'selected':'';
            $opt .= '<option value="'.$key.'" '.$checked.'>'.$value.'</option>';
        }
        return $opt;
    }

    /**
     * get offer prices of offer dates - edit mode
     * @param $id
     * @return string
     */
    public function getOfferPrices($id)
    {
        global $mysqli;

        $price_id  = '';
        $priceval  = '';
        $pseudoval = '';
        $prefix    = 'new_';
        $offer_prices = "SELECT OD.id as priceid, OD.price, OD.pseudo_price FROM  offer_details OD  WHERE OD.offer_date_id='".$id."'";
        $prices       = $mysqli->query($offer_prices);
        if ($prices->num_rows > 0) {
            $price = $prices->fetch_array();
            $price_id  = $price["priceid"];
            $priceval  = $price["price"];
            $pseudoval = $price["pseudo_price"];
            $prefix    = '';
        }
        $price_input ='<div class="well">
                            <div class="row">
                            <div class="form-group col-md-6"><input  class="form-control" placeholder="Preis" name="'.$prefix.'price['.$price_id.']" value="'.$priceval.'"></div>
                            <div class="form-group col-md-6"><input  class="form-control" placeholder="Pseudo Preis" name="'.$prefix.'pseudo_price['.$price_id.']" value="'.$pseudoval.'"></div>
                            </div>
                        </div>';
        return $price_input;
    }

    /**
     * get all info when offer_details page opens
     * @return array
     */
    public function getPageDetails()
    {
        global $mysqli,$_GET;

        $offer_query = $mysqli->query("SELECT OFR.title, OFR.type, OFR.school_id, SHL.title AS schoolname FROM offers OFR INNER JOIN schools SHL ON SHL.id=OFR.school_id WHERE OFR.id=".$_GET["ofrid"]);
        $result = $offer_query->fetch_object();

        $type  = ($result->type!='')? ' <small>(Typ: '.$result->type.')</small>':'';
        $title = $result->title. $type . ' <small>in '.$result->schoolname.'</small>';

        //inputs to add modal on page load
        $statuses    = $this->getDateStatusOptions();

        return ["title"=>$title,"sid"=>$result->school_id,"status_opt"=>$statuses];
    }

    /**
     * update offer date & prices - Ajax
     */
    public function updateOfferDetails()
    {
        global $mysqli, $_POST;

        $heading ='';
        $date_begin    = date("Y-m-d", strtotime($_POST['date_begin']));
        $date_end      = date("Y-m-d", strtotime($_POST['date_end']));
        $type          = $mysqli->real_escape_string($_POST['type']);
        $status        = $_POST['status'];
        $offer_date_id = $_POST['date_id'];

        $update_dates = "UPDATE offer_dates SET date_begin ='".$date_begin."', date_end ='".$date_end."', `type` ='".$type."', `status` ='".$status."', updated_at=NOW() WHERE id='".$offer_date_id."'";
        $mysqli->query($update_dates);
        $mes = ($mysqli->affected_rows>=0)?1:0;

        if(isset($_POST["price"])) {
            foreach ($_POST["price"] AS $key => $price) {
                if ($price != "" AND $price != 0) {
                    $mysqli->query("UPDATE offer_details SET price = '" . $price . "', pseudo_price = '" . $_POST["pseudo_price"][$key] . "', updated_at=NOW()  WHERE id = '" . $key . "'");
                }
            }
        }
        if(isset($_POST["new_price"])) {
            foreach ($_POST["new_price"] AS $index => $new_price) {
                if ($new_price != "" AND $new_price != 0) {
                    $mysqli->query("INSERT INTO offer_details (offer_date_id,price,pseudo_price,created_at)
                                    VALUES('" . $offer_date_id . "', '" . $new_price . "','" . $_POST["new_pseudo_price"][$index] . "',NOW())");
                }
            }
        }
        if($_POST["date_begin"]!='' && $_POST["date_end"]!='')
            $heading = $_POST["date_begin"] . " bis " . $_POST["date_end"];

        print json_encode(['success'=>$mes,'heading' =>$heading]);

    }

    /**
     * create new offer date & prices - Ajax
     */
    public function createOfferDetails()
    {
        global $mysqli, $_POST;

        $offer_id      = $_POST['offer_id'];
        $date_begin    = date("Y-m-d", strtotime($_POST['date_begin']));
        $date_end      = date("Y-m-d", strtotime($_POST['date_end']));
        $type          = $mysqli->real_escape_string($_POST['type']);
        $status        = $_POST['status'];

        $insert_date  = "INSERT INTO offer_dates (`offer_id`, `date_begin`, `date_end`, `type`, `status`, `created_at`)
                              VALUES('".$offer_id."', '".$date_begin."', '".$date_end."', '".$type."', '".$status."', NOW())";
        $mysqli->query($insert_date);
        $offer_date_id  = $mysqli->insert_id;

        if($offer_date_id>0) {
            if (isset($_POST["add_price"])) {
                if ($_POST["add_price"] != "" AND $_POST["add_price"] != 0) {
                    $mysqli->query("INSERT INTO offer_details (offer_date_id,price,pseudo_price,created_at)
                                    VALUES('" . $offer_date_id . "', '" . $_POST["add_price"] . "','" . $_POST["add_pseudo_price"] . "',NOW())");

                }
            }
            print json_encode(['success'=>1]);
        }
        else{
            print json_encode(['success'=>0]);
        }
    }

    /**
     * get the selected school name
     * @return mixed
     */
    public function getSchoolName()
    {
        global $mysqli;
        $school = $mysqli->query("SELECT title FROM schools WHERE id=".$_GET["sid"]);
        $row    = $school->fetch_array();
        return $row["title"];
    }

    /**
     * get type_date field options - enum
     * @param string $selected
     * @return string
     */
    public function getTypeDateOptions($selected="")
    {
        $status_opt =["timerange"=>"Zeitspannen (Saison)", "validrange"=>"Zeitspanne (Veröffentlichungsdatum)", "fixdates"=>"Feste Termine"];
        $opt ='<option value="">- Datum-Typ auswählen -</option>';
        foreach ($status_opt as $key => $value)
        {
            $checked = ($key==$selected)?'selected':'';
            $opt .= '<option value="'.$key.'" '.$checked.'>'.$value.'</option>';
        }
        return $opt;
    }

    /**
     * Get Typ options
     * @param string $selected
     * @return string
     */
    public function getTypes($selected="")
    {
        $typ_opt =["offer-discount"=>"Rabatt auf reguläres Angebot", "offer"=>"Eigenständiges Angebot", "offer-additionaldays"=>"Zusatztage auf reguläres Angebot"];
        $opt ='<option value="">- Angebotstyp auswählen -</option>';
        foreach ($typ_opt as $key => $value)
        {
            $checked = ($key==$selected)?'selected':'';
            $opt .= '<option value="'.$key.'" '.$checked.'>'.$value.'</option>';
        }
        return $opt;
    }
}