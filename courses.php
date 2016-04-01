<?php
require_once "includes/header.php";
require_once "classes/manage_courses.class.php";

$objCourse = new ManageCourses();
$courses       = $objCourse ->listCourses();
$course_type   = $objCourse ->getCourseTypes();
$duration      = $objCourse ->getCourseDurations();
$school_name   = $objCourse->getSchoolName();
$school_id     = $_GET['sid'];

?>
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/school_sidebar.php'; ?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Kurse <small>(<?=$school_name?>)</small>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#courseModal">
                    Kurs hinzufügen
                </button>
            </h1>
            <div class="row" id="crsContainer">
                <?=$courses?>
            </div>
        </div>
    </div>
</div>
<!--modal- addCourse-->
<div class="modal fade" id="courseModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Kurs hinzufügen</h4>
            </div>
            <div class="modal-body">
                <form id="addCourseFrm">
                    <div class="form-group">
                        <label>Kursart </label>
                        <select id="crsType" name="course_type_id" class="form-control" required><?=$course_type?></select>
                    </div>
                    <div class="form-group">
                        <label>Von Monat</label>
                        <input type="number" class="form-control" placeholder="Monat Start" name="month_begin" value="" min="1" max="12">
                    </div>
                    <div class="form-group">
                        <label>Bis Monat</label>
                        <input type="number" class="form-control" placeholder="Monat Ende" name="month_end" value="" min="1" max="12">
                    </div>
                    <div class="form-group">
                        <label>Kurs-Leistungen</label>
                        <textarea name="services" class="form-control textarea" rows="10"></textarea>
                    </div>
                    <input type="hidden" name="school_id" value="<?=$_GET['sid']?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="button" id="addCrsBtn" class="btn btn-primary">Speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--modal- addOptions for course-->
<div class="modal fade" id="optionsModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Option hinzufügen</h4>
            </div>
            <div class="modal-body">
                <form id="addOptionFrm">
                    <div class="form-group">
                        <label>Kursdauer</label>
                        <select id="crsDuration" name="course_duration_id" class="form-control" required><?=$duration?></select>
                    </div>
                    <div class="form-group">
                        <label>Preis</label>
                        <input type="text" placeholder="Preis" name="price" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>Pseudo Preis </label>
                        <input type="text" placeholder="Pseudo Preis" name="pseudo_price" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>Preis EZ Person</label>
                        <input type="text" placeholder="Preis EZ Person (optional)" name="price_solo" class="form-control"value="">
                    </div>
                    <div class="form-group">
                        <label>Pseudo Preis EZ Person</label>
                        <input type="text" placeholder="Pseudo Preis EZ Person (optional)" name="pseudo_price_solo" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>Rabatt in EUR</label>
                        <input type="text" placeholder="Rabatt" name="discount" class="form-control" value="">
                    </div>
                    <input type="hidden" name="optcourse_id" id="optcourse_id">
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="button" id="addOptBtn" class="btn btn-primary">Speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php include "includes/scripts.php"; ?>
<script type="text/javascript">
    if($('.btnUpdate').length) {
        $("#crsContainer").on('click', '.btnUpdate', function (e) {
        //$('.btnUpdate').click( function() {
            if($('#alert_div').length){
                $('#alert_div').remove();
            }
            var id = $(this).attr("id").split("-")[1];
            var params = $('#courseFrm_'+id).serialize();
            $.post("ajax/save_course.ajax.php", params , function (data) {
                if(data.success>0) {
                    var htmlstr = '<div class="alert alert-success fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Erfolgreich aktualisiert</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn-'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
                else {
                    var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                        '<span id="alert_msg">Es ist ein Fehler aufgetreten.</span>' +
                        '</div>';
                    $(htmlstr).insertBefore('#updateBtn-'+id);
                    $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                        $("#alert_div").alert('close');
                    });
                }
            }, "json");
        });
    }

    //add course modal - save
    $('#addCrsBtn').click( function() {
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
        if($('#crsType').val()=='')
        {
            var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<span id="alert_msg"><strong>Fehler: </strong>Kurstyp ist ein Pflichtfeld!</span>' +
                '</div>';
            $(htmlstr).insertBefore('#addCourseFrm');
            return false;
        }
        var params = $('#addCourseFrm').serialize();
        $.post("ajax/save_course.ajax.php?mode=C", params , function (data) {
            if(data.success>0) {
                $('#crsContainer').prepend(data.row);
                $("#courseModal").modal('hide');
            }
            else {
                var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                    '<span id="alert_msg">Fehler: Kurs nicht hinzugefügt!</span>' +
                    '</div>';
                $(htmlstr).insertBefore('#addCourseFrm');
                $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                    $("#alert_div").alert('close');
                });
            }
        }, "json");
    });

    //on closing modal clear form
    $('#courseModal').on('hidden.bs.modal', function (e) {
        $('#addCourseFrm')[0].reset();
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
    });

    //open options model for particular course
    $("#crsContainer").on('click', '.optmodalBtn', function (e) {
        var optcourse = $(this).attr('id').split("_")[1];
        $("#optcourse_id"). val(optcourse);
        $("#optionsModal").modal();
    });

    //add option button click
    $('#addOptBtn').click( function() {
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
        if($('#crsDuration').val()=='')
        {
            var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '<span id="alert_msg"><strong>Fehler: </strong>Kursdauer ist ein Pflichtfeld!</span>' +
                '</div>';
            $(htmlstr).insertBefore('#addOptionFrm');
            return false;
        }
        var params = $('#addOptionFrm').serialize();
        $.post("ajax/save_course.ajax.php?mode=Opt", params , function (data) {
            if(data.success>0) {
                $('#optionContainer_'+data.parent).append(data.options);
                $("#optionsModal").modal('hide');
            }
            else {
                var htmlstr = '<div class="alert alert-danger fade in" id="alert_div" role="alert">' +
                    '<span id="alert_msg">Fehler: Option wurde nicht hinzugefügt!</span>' +
                    '</div>';
                $(htmlstr).insertBefore('#addOptionFrm');
                $("#alert_div").fadeTo(3000, 500).slideUp(500, function () {
                    $("#alert_div").alert('close');
                });
            }
        }, "json");
    });

    //on closing options modal clear form
    $('#optionsModal').on('hidden.bs.modal', function (e) {
        $('#addOptionFrm')[0].reset();
        if($('#alert_div').length){
            $('#alert_div').remove();
        }
    });


    $('#navCrsLst').addClass('active');
</script>
<?php include "includes/footer.php"; ?>
