<?php

class Contest extends CI_Controller {
    
    /* Instance variable storing the array that is
     * passed to the view for display.
     */
    var $results;
    
    /**
     * Loads the corresponding model class and initializes
     * the results instance variable.
     */
    function __construct(){
        parent:: __construct();
        $this->load->model('contest_model');
        $this->results = Array();
    }
    
    /**
     * Displays video entries sorted by property and order. Property
     * options are 'title', 'views', 'likes' and 'duration'. Default
     * is 'likes'. Order options are 1 for ascending and 2 for
     * descending. The default order value of 0 prompts page reload 
     * to get latest data from YouTube.
     * 
     * @access  public
     * @param   string      the property to be ranked
     * @param   integer     the sorting order
     * @return  void
     */
    function index($option = 'likes', $order = 0){
        if($order){
            $this->results = $this->contest_model->sort_entries($order, $option);
        } else {
            // returns results sorted by likes in descending order.
            $this->results = $this->contest_model->get_results();
        }
        
        $data['results'] = $this->results;
        $this->load->view('contest_view', $data);
    }
    
    /**
     * Retrieves content from a given CouchSurfing contest page
     * (deteremined by parameter) and displays it for reading the 
     * links from page source through javascript.
     * 
     * @access  public
     * @param   integer     CouchSurfing contest page number
     * @return  void
     */
    function store($index = 1){
        $data['source'] = $this->contest_model->get_source($index);
        $this->load->view('contest_record', $data);
    }
    
    /**
     * Writes all data received through post to the given file.
     * Prior to writing, the data is formatted to ensure it can 
     * be easily read into an array (for later use).
     * 
     * @access  public
     * @return  string      confirmation message
     */
    function record_entries(){
        $links = $this->input->post();
        $this->contest_model->write_links($links);
        echo "Copied links to /extras/entries.txt";
    }
    
    /**
     * Fetches all repeated video entries from the given input 
     * and returns a single concatenated html-formatted string.
     * 
     * @access  public
     * @return  string      html-formatted string of repeated videos
     */
    function show_repeated(){
        echo $this->contest_model->get_repeated();
    }

}