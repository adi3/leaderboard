<?php

class Contest_model extends CI_Model {
    
    /* Array instance variable storing all repeated elements
     * found in the entries database/file.
     */
    var $repeated = Array();
    
    var $entries_path;
    var $records_path;
    var $repeat_path;
    
    /**
     * Initializes paths for files holding contest entries ('entries.txt'),
     * recorded YouTube info of all videos ('records.txt'), and info of
     * all repeated entires ('repeated.txt').
     */
    function __construct(){
        parent:: __construct();
        $this->entries_path = "extras" . DIRECTORY_SEPARATOR . "entries.txt";
        $this->records_path = "extras" . DIRECTORY_SEPARATOR . "records.txt"; 
        $this->repeat_path = "extras" . DIRECTORY_SEPARATOR . "repeated.txt";      
    }
    
    /**
     * Returns the page source from the given CouchSurfing 
     * contest page, as determined by the parameter.
     * 
     * @access  public
     * @param   integer      CouchSurfing contest page number
     * @return  string       html-formatted page source code
     */
    function get_source($index){
        return file_get_contents("http://www.couchsurfing.com/contest/?tubepress_page=".$index, 0);
    }  
    
    /**
     * Receives an array with video links (here, obtained from page
     * source by jQuery) and extracts a substring of their YouTube 
     * IDs. These substrings are concatenated and finally written
     * to the given storage file in one go.
     * 
     * @access  public
     * @param   array        array with YouTube video links
     * @return  void
     */  
    function write_links($links){
        $entries = '';
        foreach($links as $link){
             $index = strpos($link, "vi/") + 3;
             $entries = $entries . substr($link, $index, 11) . "\n";
        }
        write_file($this->entries_path, $entries, 'a');
    }
    
    /**
     * Reads all stored YouTube IDs, gets their statistics from
     * YouTube and returns a sorted array filled with all required 
     * information. The array has a form of:
     * 
     *          [This][YouTube ID][Property] 
     * 
     * By default, the array is sorted by 'likes' in descending order.
     * Also, repeated entries are tracked and recorded separately.
     * 
     * @access  public
     * @return  array        sorted array with all video information
     */     
    function get_results(){
        $links = $this->_read_links();
        $arr = Array();
        
        foreach($links as $link){
            if($link != '') $this->_get_info(trim($link), $arr);
        }
        
        rsort($arr);
        $this->_record_stats($arr);
        $this->_set_repeated();
        
        return $arr;
    }
    
    /**
     * Reads the storage file and returns its contents after 
     * exploding them into an array.
     * 
     * @access  private
     * @return  array        array with YouTube video IDs
     */     
    function _read_links(){
        $contents = explode("\n", read_file($this->entries_path));
        return $contents;
    }
 
    /**
     * Retrieves information corresponding to the video ID from
     * YouTube and adds it to the referenced array. If ID is
     * repeated, it is stored in a separate array for later use.
     * 
     * @access  private
     * @param   string       YouTube ID of a video
     * @param   array        referenced array with info for all videos
     * @return  void
     */  
    function _get_info($id, &$table){
        if($table) if(array_key_exists($id, $table)) {
            $arr = array('link' => $id, 'title' => $table[$id]['title']);
            array_push($this->repeated, $arr);        
            return;
        }
        $results = json_decode(
                     file_get_contents(
                        "http://gdata.youtube.com/feeds/api/videos/".$id."?v=2&alt=json"), true);
        
        $this->_set_values($id, $table, $results);
    }

    /**
     * Writes all video information for all keys of the referenced
     * array into the given storage file. Information for a single
     * video is separated by a "`" character, and the videos are
     * separated by a "\n" character.
     * 
     * @access  private
     * @param   array        referenced array with info for all videos
     * @return  void
     */   
    function _record_stats(&$arr){
        $temp = '';
        $size = count($arr);
        for($i = 0; $i < $size; $i++){
            foreach($arr[$i] as $entry){
                $temp = $temp.$entry."`";
            }
            if($i != $size - 1) $temp = $temp."\n";
        }
        write_file($this->records_path, $temp);
    }

    /**
     * Assigns video information from the referenced 'results' array
     * (holding data for the single video at hand) to the referenced
     * 'table' array (holding data for all videos). Keys in the 'table'
     * array are of the format:
     * 
     *          [This][YouTube ID][Property] 
     * 
     * The properties stored, in order, are: 
     * 
     *          likes, title, views, duration, and link (or ID)
     * 
     * @access  private
     * @param   string      YouTube ID of a video
     * @param   array       array holding data for all videos
     * @param   array       array holding data for a single video
     * @return  void
     */
    function _set_values($id, &$table, &$results){
        $likes;
        if(isset($results['entry']['yt$rating']['numLikes'])){
            $table[$id]['likes'] = $results['entry']['yt$rating']['numLikes'];
        } else {
            $table[$id]['likes'] = 0;
        }
        
        $table[$id]['title'] = $results['entry']['title']['$t'];
        $table[$id]['views'] = $results['entry']['yt$statistics']['viewCount'];
        $table[$id]['duration'] = $results['entry']['media$group']['yt$duration']['seconds'];
        
        $table[$id]['link'] = $id;
    }

    /**
     * Reads a file storing info for all videos and returns an
     * array sorted according to the received order and property.
     * Order options are 1 for ascending and 2 for descending.
     * Property options are 'title', 'views', 'likes' and 'duration'.
     * 
     * @access  public
     * @param   integer     the sorting order
     * @param   string      the property to be ranked
     * @return  array       sorted array with all video information
     */
    function sort_entries($order, $property){
        $contents = explode("\n", read_file($this->records_path));
        $arr = Array();
        
        for($i = 0; $i < count($contents); $i++){
            $store = explode("`", $contents[$i]);            
            $this->_fix_entry_indices($store);
            $this->_array_reorder($store, $property);
            $arr[$i] = $store;
        }
        
        $this->_sort_results_order($arr, $order, $property);
        $this->_format_durations($arr);
        return $arr;
    }
    
    /**
     * Unsets keys 0 to 5 of the referenced array andvreplaces 
     * them with the keys 'likes', 'title', 'views',v'duration',
     * and 'link' respectively.
     * 
     * @access  private
     * @param   array     array whose keys need to be reset
     * @return  void
     */
    function _fix_entry_indices(&$temp){
        $temp['likes'] = $temp[0]; 
        $temp['title'] = $temp[1];
        $temp['views'] = $temp[2];
        $temp['duration'] = $temp[3];
        $temp['link'] = $temp[4];
        for($i = 0; $i <= 5; $i++){
            unset($temp[$i]);
        }
    }
    
    /**
     * Checks the first key of the referenced array and, if
     * it does not match the property parameter, sets it as
     * the last key of the array while shifting all other keys
     * by one. As long as the first key of the array does not
     * match the property parameter, the function is called
     * recursively. 
     * The end result is that the first key of the referenced 
     * array is the property parameter.
     * 
     * @access  private
     * @param   array     array whose first key is to be changed 
     * @param   string    property to be sorted by
     * @return  void     
     */    
    function _array_reorder(&$temp, $property){
      list($k) = array_keys($temp);
      if($k == $property) return;
      $r  = array($k=>$temp[$k]);
      unset($temp[$k]);
        
      $temp = $temp + $r;
      $this->_array_reorder($temp, $property);
    }
    
    /**
     * Sorts the referenced array in ascending ('order' = 1) 
     * or descending ('order' = 2) order. If sorting property
     * is 'likes', then the default stored array is returned
     * as is for descensing and reversed for ascending.
     * 
     * @access  private
     * @param   array     array to be reordered
     * @param   integer   order to be sorted by
     * @param   string    property to be sorted by
     * @return  void     
     */
    function _sort_results_order(&$arr, $order, $property){
        if($order == 1) {
            if($property != 'likes') sort($arr);
            else $arr = array_reverse($arr);
        } else {
            if($property != 'likes') rsort($arr);
        }
    }

    /**
     * Iterates thrugh the referenced array and for each the 
     * duration entries, converts the string of time in seconds 
     * to a string of the format mm:ss for easy readability.
     * NOTE: The time string is not stored preformatted because
     * it hinders subsequent sorting.
     * 
     * @access  private
     * @param   string     time in seconds
     * @return  string     time in mm:ss format     
     */
    function _format_durations(&$arr){
        for($i=0; $i < count($arr); $i++){
            $duration = $arr[$i]['duration']; 
            $minutes = intval($duration / 60);
            $seconds = intval($duration % 60);
            if (strlen($seconds) == 1) $seconds = "0" . $seconds;
            $arr[$i]['duration'] = $minutes.":".$seconds;
      }
    }
    
    /**
     * Writes all data in the 'repeated' array instance variable 
     * to a storage file for later use. The video id and title
     * properties are stored.
     * 
     * @access  private
     * @return  void
     */     
    function _set_repeated(){
        $links = '';
        
        foreach($this->repeated as $link){
             $links = $link['link']."`".$link['title']."\n".$links;
        }
        write_file($this->repeat_path, $links);
    }
    
    /**
     * Reads the storage file containing repeated entries and
     * returns its contents after exploding them into an array
     * and html-formatting them into a string. This string is
     * then inserted directly into the page through AJAX.
     * 
     * @access  public
     * @return  string      contains info for all repeated videos
     */       
    function get_repeated(){
        $repeated = Array();
        $arr = explode("\n", read_file($this->repeat_path));
        
        foreach($arr as $element){
            $vid = explode("`", $element);
            array_push($repeated, $vid);
        }
        return $this->format_repeated($repeated);
    }
    
    /**
     * Formats all entries in the referenced array with HTML
     * tags and returns a single concatenated string.
     * 
     * @access  public
     * @param   array     array holding video information 
     * @return  string    contains info for all repeated videos
     */  
    function format_repeated(&$arr){
        $result = '<br /><hr /><br />';
        for($i = 0; $i < count($arr)-1; $i++){
            $result = $result.'<a href="http://youtube.com/watch?v='.$arr[$i][0].'" target="_blank">';
            $result = $result.'<div id="repeat_vid">'.img("http://img.youtube.com/vi/".$arr[$i][0]."/2.jpg");
            $result = $result.'<br /><div id="repeat_title">'.$arr[$i][1].'</div></div></a>';
            if($i != count($arr)-2) $result = $result."<br />";
        }
        return $result;
    }
    
}
