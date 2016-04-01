<?php
include "includes/header.php";
require_once "classes/text-blocks-class.php";
$service                              = new textBlocksService();
?>
    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/sidebar.php';
            ?>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <h2 class="sub-header">Textblöcke</h2>

                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Textblock hinzufügen</button>
                </div>

                <div class="panel panel-primary">
                    <!-- Default panel contents -->
                    <div class="panel-heading">Textblöcke</div>

                    <div class="table-responsive">
                        <!-- Table -->
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titel</th>
                                <th>Beschreibung</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // How many adjacent pages should be shown on each side?
                            $adjacents                         = 3;
                            /*
                                 First get total number of rows in data table.
                                 If you have a WHERE clause in your query, make sure you mirror it here.
                            */
                            $countTextBlocks                   = $service->countTextBlocks();
                            if($countTextBlocks){
                                $countTextBlocks_result        = $mysqli->use_result();
                                $total_items                   = $countTextBlocks_result->fetch_array();
                            }
                            $countTextBlocks_result->free();/* free result set */

                            /* Setup vars for query. */
                            $targetpage                           = "text-blocks-list.php";   //your file name
                            $limit                                = 15;                 //how many items to show per page
                            if(isset($_GET['page'])) {
                                $page   = $_GET['page'];
                                $start  = ($page - 1) * $limit;      //first item to display on this page
                            } else {
                                $page  = 0;
                                $start = 0;               //if no page var is given, set start to 0
                            }
                            /* Setup page vars for display. */
                            if ($page == 0) $page = 1;      //if no page var is given, default to 1.
                            $prev = $page - 1;              //previous page is page - 1
                            $next = $page + 1;              //next page is page + 1
                            $lastpage = ceil($total_items[0]/$limit);//lastpage is = total pages / items per page, rounded up.
                            $lpm1 = $lastpage - 1;            //last page minus 1
                            /*
                               Now we apply our rules and draw the pagination object.
                               We're actually saving the code to a variable in case we want to draw it more than once.
                             */
                            $pagination = "";
                            if($lastpage > 1)
                            {
                                $pagination .= "<nav><ul class=\"pagination\">";
                                //previous button
                                if ($page > 1)
                                    $pagination.= "<li><a href=\"$targetpage?page=$prev\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
                                else
                                    $pagination.= "<li class=\"disabled\"><a href=\"#\">Vorherige</a></li>";
                                //pages
                                if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
                                {
                                    for ($counter = 1; $counter <= $lastpage; $counter++)
                                    {
                                        if ($counter == $page)
                                            $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                        else
                                            $pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";
                                    }
                                }
                                elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
                                {
                                    //close to beginning; only hide later pages
                                    if($page < 1 + ($adjacents * 2))
                                    {
                                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                        $pagination.= "<a href=\"#\">...</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                    }
                                    //in middle; hide some front and some back
                                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                    {
                                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                                        $pagination.= "<a href=\"#\">...</a>";
                                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                        $pagination.= "<a href=\"#\">...</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                    }
                                    //close to end; only hide early pages
                                    else
                                    {
                                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                                        $pagination.= "<a href=\"#\">...</a>";
                                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                    }
                                }
                                //next button
                                if ($page < $counter - 1)
                                    $pagination.= "<li><a href=\"$targetpage?page=$next\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                                else
                                    $pagination.= "<li class=\"disabled\"><a href=\"#\">Nächste</a></li>";
                                $pagination.= "</ul></nav>\n";
                            }
                            $viewTextBlocksDetails     = $service->listTextBlocks($start, $limit);
                            if($viewTextBlocksDetails)
                            {
                                $view_text_blocks_result  = $mysqli->use_result();
                                while($view_text_blocks_result_array = $view_text_blocks_result->fetch_array())
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $view_text_blocks_result_array['id'];?></td>
                                        <td><?php echo $view_text_blocks_result_array['title'];?></td>
                                        <td><?php echo $view_text_blocks_result_array['description'];?></td>
                                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalEdit<?php echo $view_text_blocks_result_array['id'];?>">Bearbeiten</button></td>
                                        <!-- Modal Div Update Text Blocks Open-->
                                        <div class="modal fade" id="myModalEdit<?php echo $view_text_blocks_result_array['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close closebutton" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel">Textblock aktualisieren</h4>
                                                    </div>
                                                    <!-- Alert Success Div Open-->
                                                    <div id="alert_update"></div>
                                                    <!-- Alert Success Div Close-->
                                                    <div class="modal-body">
                                                        <form name="upd_form" id="updForm_<?php echo $view_text_blocks_result_array['id'];?>">
                                                            <div class="form-group">
                                                                <label for="title">Titel</label>
                                                                <input type="text" class="form-control" id="upd_title_<?php echo $view_text_blocks_result_array['id'];?>" name="upd_title" maxlength="100" value="<?php echo $view_text_blocks_result_array['title'];?>">
                                                            </div><!-- /form-group -->

                                                            <div class="form-group">
                                                                <label for="persons">Beschreibung</label>
                                                                <textarea name="upd_description" class="form-control textarea mceEditor" rows="15" id="upd_description_<?php echo $view_text_blocks_result_array['id'];?>"><?php echo $view_text_blocks_result_array['description'];?></textarea>
                                                            </div><!-- /form-group -->

                                                            <input type="hidden" name="upd_text_block" id="upd_text_block">
                                                            <input type="hidden" name="upd_id" id="upd_id" value="<?php echo $view_text_blocks_result_array['id'];?>">

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default closebutton" data-dismiss="modal">Schließen</button>
                                                                <button type="button" class="btn btn-primary upd_text_blocks" id="<?php echo $view_text_blocks_result_array['id'];?>">Aktualisieren</button>
                                                                <span id="message_ta"></span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Div Update Text Blocks Close-->
                                    </tr>
                                    <?php
                                }
                                $view_text_blocks_result->free();
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center"><?php echo $pagination; ?></div>

                <!-- Modal Div Add Text Blocks Open-->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close closebutton" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Textblock hinzufügen</h4>
                            </div>
                            <!-- Alert Success Div Open-->
                            <div id="alert_success"></div>
                            <!-- Alert Success Div Close-->
                            <div class="modal-body">
                                <form name="form" id="addForm">
                                    <div class="form-group">
                                        <label for="title">Titel</label>
                                        <input type="text" class="form-control" id="title" name="title" maxlength="100">
                                    </div><!-- /form-group -->

                                    <div class="form-group">
                                        <label for="persons">Beschreibung</label>
                                        <textarea name="description" class="form-control textarea mceEditor" rows="15" id="description"></textarea>
                                    </div><!-- /form-group -->

                                    <input type="hidden" name="add_text_block" id="add_text_block">

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default closebutton" data-dismiss="modal">Schließen</button>
                                        <button type="button" class="btn btn-primary" id="add_text_blocks">Speichern</button>
                                        <span id="message_ta"></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Div Add Text Blocks Close-->

            </div>
        </div>
    </div>
<?php include "includes/scripts.php"; ?>
    <!-- Check All -->
    <script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
    <script>
        $(function() {
            /*TinyMCE*/
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
                width:"100%",

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

            /* Tooltip */
            $('[data-toggle="tooltip"]').tooltip();

            /* Add Text Blocks */
            $("#add_text_blocks").click(function(){
                $('#description').html(tinymce.get('description').getContent());
                var params = $('#addForm').serialize();
                $.post('text-blocks-submit.php', params,
                    function(data){
                        $("#alert_success").html(data);
                        setInterval('location.reload(true)', 1000);
                });
            });

            /* Edit Text Blocks */
            $(".upd_text_blocks").click(function(){
                var ID                  = $(this).attr('id');
                $("#upd_description_"+ID).html(tinymce.get("upd_description_"+ID).getContent());
                var params = $('#updForm_'+ID).serialize();
                $.post('text-blocks-submit.php', params,
                    function(data){
                        $("#alert_update").html(data);
                        setInterval('location.reload(true)', 1000);
                    });
            });
        });
    </script>

<?php include "includes/footer.php"; ?>