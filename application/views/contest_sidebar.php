<!-- Begin sidebar -->

   <!-- Section for update button -->
   <div id="border_container" class="grid_3">
   <div id="cpanel" style="padding-bottom:10px" class="grid_3">
       <!-- URL segments stored to track current sort order and property -->
       <?php $order = $this->uri->segment(4, '2') ?>
       <?php $sort = $this->uri->segment(3, 'likes') ?>

       <div id="update" class="grid_3">    
           <a href="<?= site_url('') ?>"><?= img("resources/update.png"); ?></a>
       </div>
       <div id="update_title" class="grid_3">* Scroll to the bottom. If app fails to display all data, press this button.</div>
   </div><!-- End cpanel -->
   </div> <!-- End border_container -->
   
   <!-- Section for ascending and descending order button -->
   <div id="border_container" class="grid_3">
   <div id="cpanel" class="grid_3">
       <div id="asc_sort" class="grid_1">
           <a href="<?= site_url('contest/index/'.$sort.'/1') ?>"><?= img("resources/asc.png"); ?></a>
       </div>
       <div id="des_sort" class="grid_1">                   
           <a href="<?= site_url('contest/index/'.$sort.'/2') ?>"><?= img("resources/des.png"); ?></a>
       </div>
   </div><!-- End cpanel -->
   </div> <!-- End border_container -->
       
   <!-- Section for sort by title, views, likes and duration buttons -->
   <div id="border_container" class="grid_3">
   <div id="cpanel" class="grid_3">
       <div id="ch_order" class="grid_1">
           <a href="<?= site_url('contest/index/title/'.$order) ?>"><?= img("resources/title.png"); ?></a>
       </div>
       <div id="ch_order" class="grid_1">
           <a href="<?= site_url('contest/index/views/'.$order) ?>"><?= img("resources/views.png"); ?></a>
       </div>
       <div id="ch_order" class="grid_1">
           <a href="<?= site_url('contest/index/likes/'.$order) ?>"><?= img("resources/likes.png"); ?></a>
       </div>
       <div id="ch_order" class="grid_1">
           <a href="<?= site_url('contest/index/duration/'.$order) ?>"><?= img("resources/duration.png"); ?></a>
       </div>
   </div><!-- End cpanel -->
   </div> <!-- End border_container -->
       
   <!-- Section displaying repeated entries -->       
   <div id="border_container" class="grid_3">
   <div id="cpanel" class="grid_3">
       <div id="repeats" class="grid_1"><?= img("resources/repeat.png"); ?></div>
       <div id="repeat_links" class="grid_3"></div>
   </div><!-- End cpanel -->
   </div> <!-- End border_container -->
   
   <!-- Section linked to github repo -->       
   <div id="border_container" class="grid_3">
   <div id="cpanel" class="grid_3">
       <div id="github" class="grid_3">
       		<a href="https://github.com/adi3/leaderboard" target="_blank"><?= img("resources/github.png"); ?></a>
       </div>
   </div><!-- End cpanel -->
   </div> <!-- End border_container -->
   
   <!-- Begin disclaimer -->
   <div id="border_container" class="grid_3">
   <div id="disclaimer" class="grid_3">
       This leaderboard is a covenience tool developed for personal use. I do not hold any rights to the CouchSurfing 
       brand name, nor am I associated in any manner, except membership, with the same.  Code has been made open-source. --- Compliments 
       accepted at <i>adisin@stanford.edu</i>.
   </div><!-- End disclaimer -->
   </div> <!-- End border_container -->
   
<!-- End sidebar -->