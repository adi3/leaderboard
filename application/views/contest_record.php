<?php

// Displays the received content; in this case, the entire remote CS page.
echo $source;

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        
        /**
         * Copies entry links on the currently loaded page after user
         * confirmation. AJAX is used. 
         */
        var proceed = confirm("Proceed copying links data?");
        if (proceed){
            
            var links = {};
            var i = 0;
            $('.tubepress_thumb img').each(function() {
                var link = $(this).attr('src');
                links[i] = link;
                i++;
            });
    
            $.ajax({
                url: "<?= site_url('contest/record_entries'); ?>",
                type: 'POST',
                data: links,
                cache: false,
                success: function(msg){
                    alert(msg);
                }
             });
        }
    });
</script>