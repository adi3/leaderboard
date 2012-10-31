<!DOCTYPE html>

<html>
    <head>
        <title>Leaderboard | CS Video Contest | Adi</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <?= link_tag("css/style.css") ?>
        <?= link_tag("css/960.css") ?>
        <?= link_tag("css/reset.css") ?>
        <?= link_tag("resources/icon.ico", "shortcut icon", "image/ico") ?>
        
        <meta name="robots" content="no-cache" />
        <meta name="description" content="Web App for tracking ranks of CouchSurfing video contest entries" />
        <meta name="keywords" content="YouTube, CouchSurfing, video contest, ranks, sort" />
    </head>
    
    <body>
       <br />
       <div id="page" class="container_12">
           <div id="spacer" class="grid_1"></div>       
           
           <?php include_once('contest_results.php') ?>
           <?php include_once('contest_sidebar.php') ?>

       </div><!-- End page -->
       
       <!-- Begin footer info -->
       <div id="footer_line" class="container_12"><hr /></div>
       <div id="footer" class="container_12">Copyright 2012 | Adi | Page loaded in {elapsed_time} sec</div>
       <!-- End footer info --> 
    
    </body>
</html>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        var flag = false;
        
        /** 
         * Fetches and displays repeated video entries
         * through AJAX. If data already fetched, then
         * toggles the displaying div. 
         */
        $("#repeats").click(function(){
            if(flag) {
                $("#repeat_links").slideToggle('slow');
            } else {
                $.ajax({
                    url: "<?= site_url('contest/show_repeated') ?>",
                    type: "POST",
                    data: null,
                    cache: false,
                    success: function(msg){
                       $("#repeat_links").html(msg);
                       flag = true;                       
                       $("#repeat_links").slideDown('slow');
                    }
                });
            }
        });
    });
</script>