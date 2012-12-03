
<div id="border_container" class="grid_7">
   <div id="container" class="grid_7">
   <h1>CS Video Contest Leaderboard</h1>
   <hr />
   
   <!-- Loop through all video data and display results -->
   <?php for($i = 0; $i < count($results); $i++): ?>
       <?php $link = $results[$i]['link']; ?>
  
       <!-- Entire entry block is hyperlinked with the video's youtube link -->
       <a href="http://youtube.com/watch/?v=<?= $link ?>" target="_blank" >
       <div id="entry">
           <!-- Thumbnail of the video -->
           <div id="thumb" class="grid_2">
               <?= img(Array('src' => 'http://img.youtube.com/vi/'.$link.'/2.jpg', 'width' => '120', 'height' => '90')); ?>
           </div>
       
           <div id="entry_data" class="grid_4">
               <!-- Rank as determined by sort order and property -->
               <div id="rank"><b>Rank: <?= ($i+1) ?></b></div>
               <hr />
               
               <!-- Title, views, likes, and duration of the video -->
               <h3><div id="title"><?= $results[$i]['title']; ?></div></h3>
               <div class="grid_1" style="width:0"></div>
               <div id="views">Views: <?= $results[$i]['views']; ?></div>
               <div class="grid_1" style="width:0"></div>
               <div id="likes">Likes: <?= $results[$i]['likes']; ?></div>
               <div class="grid_1" style="width:0"></div>
               <div>Duration: <?= $results[$i]['duration']; ?></div>
           </div>
       </div> <!-- End entry -->
       </a>
   <?php endfor; ?>
</div> <!-- End container -->
</div> <!-- End border_container -->
