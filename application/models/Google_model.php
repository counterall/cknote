<?php
include('functions.php');

class Google_model extends CI_Model {

//<---fetch all the documents from database--->
   public function get_results()
	 {
			// establish connection to database "google" defined in database.php file under "application/config" folder
      $content_db = $this->load->database('google', TRUE);
      $query = $content_db->get('search_results');
      return $query->result_array();
		}

//<---fetch google search result and save them into database--->
		public function set_results()
		{
		    $content_db = $this->load->database('google', TRUE);

        $keywords = $this->input->post('keywords');
        $pages = $this->input->post('pages');
        //array to save all urls waiting to be curled
        $search_rows = count($keywords);
        //use multi-threaded curl if receiving more than one url requests
        if ($search_rows > 1) {
          $search_urls = [];
          for ($i=0; $i < $search_rows ; $i++) {
            // combine the keywords as a whole for goolge search purpose
            $keyword = str_replace(' ', '+', $keywords[$i]);
            // page number times 10 items per page to decide how many items to fetch from google search
            $items = $pages[$i] * 10;
            $search_urls[$i] = "https://www.google.fi/search?hl=en&q=$keyword&num=$items";
          }

          // array to place all threads of curl
          $curl_array = array();
          // the resourse usage status before implementing multi-threaded curl, used for tracking CPU time
          $rustart = getrusage();
          // a timestamp to mark the start of the code in real time in microsecs
          $tstart = microtime(true)*1000000;

          // Initialize a cURL multi handler
          $curl_multi_handle = curl_multi_init();

          // extract each url from $search_urls and set options for each curl thread
          for($j = 0; $j < count($search_urls); $j++)
          {
          $url = $search_urls[$j];

          //Initialize a single cURL thread
          $curl_array[$j] = curl_init();

          $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
           );

          //Setting the option for a cURL thread
          curl_setopt_array($curl_array[$j], $options);

          //Incrementally add each curl thread into the curl multi handler
          curl_multi_add_handle($curl_multi_handle, $curl_array[$j]);
          }

          //Make sure the multi-curl execution has finished.
          do {
            curl_multi_exec($curl_multi_handle, $running);
            curl_multi_select($curl_multi_handle);
          } while($running > 0);

          // Get all returned html content and save them into $html
          $html = "";
          for($k = 0; $k < count($curl_array); $k++)
          {
            // alert when curl failed to fetch anything and add the alert to logs as well
            if (empty(curl_multi_getcontent($curl_array[$k]))) {
              echo "<h3 style='color: red'>Sorry, an error occured when trying to fetch web content against \"{$keywords[$k]}\" from Google!<br>";
              log_message('error', "Curl failed against keywords \"{$keywords[$k]}\"!");
              curl_close($curl_array[$k]);
            }else{
              $html .= curl_multi_getcontent($curl_array[$k]);
              curl_close($curl_array[$k]);
            }
          }
          curl_multi_close($curl_multi_handle);

          // if multi-curl returns nothing, then stop running the remaining script
          if (empty($html)) {
            echo "</h3><p><a href='./create'>Back to search again</a></p>";
            die();
          }
        }

        //use normal single-thread curl if only receiving one url request
        else if($search_rows === 1){
          // the resourse usage status before implementing multi-threaded curl, used for tracking CPU time
          $rustart = getrusage();
          // a timestamp to mark the start of the code in real time in microsecs
          $tstart = microtime(true)*1000000;

          $curl_handler = curl_init();
          $keyword = str_replace(' ', '+', $keywords[0]);
          $items = $pages[0] * 10;
          $url = "https://www.google.fi/search?hl=en&q=$keyword&num=$items";

          //Setting the option for a cURL thread
          $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
           );
          curl_setopt_array($curl_handler, $options);

          // alert when curl failed to fetch anything and add the alert to logs as well
          if (empty($html = curl_exec($curl_handler))) {
            echo "<h3 style='color: red'>Sorry, an error occured when trying to fetch web content against \"{$keywords[0]}\" from Google!</h3><p><a href='./create'>Back to search again</a></p>";
            log_message('error', "Curl failed against keywords \"{$keywords[0]}\"!");
            curl_close($curl_handler);
            die();
          }
          curl_close($curl_handler);
        }

        // calculate the time consumption of this block of codes in real time in microsecs
        $real_time = round(microtime(true)*1000000 - $tstart);
        // track the resource usage again when launching of multi-curl end
        $ruend = getrusage();
        // rutime is a function defined in function.php file, "utime" is used to get user cpu time, "stime" is used to get system cpu time
        $cpu_time = array(
            'user_time' => rutime($ruend, $rustart, "utime"),
            'sys_time' => rutime($ruend, $rustart, "stime"),
        );

        // Launching DOMDocument instance
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        // Launching DOMXPath instance
        $xpath = new DOMXPath($dom);

        //list the search result items including title and content
        foreach ($xpath->query("//li[@class = 'g']") as $item) {
          //fetch the title of each result item
            $title = $item->firstChild->firstChild;
            $heading = $title->nodeValue;

          //fetch the url of each results item
            $link = $title->getAttribute('href');
            //get rid of the letters ahead of "http"
            $link2 = substr($link, 7);

            // get rid of the characters after the actual URL of each link
            //"&" actually occupies 5 letters "&amp;" as html entity, but we used DOMDocument class above, so here still use & to search instead of "&amp;"
            $pos = strpos($link2, "&sa=");
            $link3 = substr($link2, 0, $pos);

          //fetch the description of each results item
            $content1 = $item->lastChild->firstChild->nextSibling;
            if (is_object($content1)) {
              $content2 = $content1->nodeValue;
            }else{
              $content2 = null;
            }

          // save each search result into database as an new column
    		    $data = array(
    		        'title' => $heading,
                'link' => $link3,
    		        'text' => $content2,
    		    );
    		    $content_db->insert('search_results', $data);
		    }
        return $rtime = array($real_time, $cpu_time );
      }

//<---conduct sphinx search--->
		public function search_index()
		{
        // connect to SphinxQL server
        $index_db = $this->load->database('sphinxql', TRUE);
        // sanitize the keywords to search, use the function defined in functions.php file
        $query_words = sanitizeSearch($this->input->get('query'));
        // escape the sanitized string for SQL query
        $query_words = $index_db->escape_str($query_words);
        // trim the Sphinx query words received from user, which is used for meta message
        $trim_query = trim($this->input->get('query'));

        // do the Sphinx index search
        $query = "SELECT * FROM google_index WHERE MATCH('$query_words') OPTION ranker = PROXIMITY_BM25";
        // use a custom error message instead of the one generated by systems to hide detail of query, to make this message valid, the "db_debug" option needs to be turned off in database.php configuration file. You do not need to do so in testing or staging environment, only do so in production.
        if (!$index_db->query($query)) {
          echo "<h3 style='color: red'>Sorry, an error occured when trying to do the Sphinx search!</h3><p><a href='./'>Back to search again</a></p>";
          log_message('error', "Sphinx index search failed against keywords \"{$query_words}\"!");
          die();
        }
        $index_result = $index_db->query($query);

        if ($index_result->num_rows() > 0) {
            	// collect the ids of matched columns
	            $ids = [];
				      foreach ($index_result->result() as $row) {
					    $ids[] = $row->id;
				      }

				// get the metadata of the Sphinx index search result
				$meta_search = $index_db->query('SHOW META');
				$meta_result = [];
				foreach ($meta_search->result_array() as $row) {
					$meta_result[$row['Variable_name']] = $row['Value'];
				}

				$index_meta = 'The query for keywords ' . "\"<span style='color: red'>{$trim_query}</span>\"" . ' has ' . $meta_result['total_found'] . ' match(es) in ' . $meta_result['time'] . 'sec.';

				// create an array to save output data which will be then used to query database content
				$final_output = [];
				$final_output['ids'] = $ids;
				$final_output['meta'] = $index_meta;
				return $final_output;
        }
        else
        {
            $no_result = "<p style='color:red'>Sorry, we cannot find any matches for the keyword '{$trim_query}', please try other keywords.</p>";
            return $no_result;
        }
		}

//<---fetch full content of each column based on the Sphinx index result---->
		public function search_content($index_result)
		{
			$content_db = $this->load->database('google', TRUE);
			$ids = $index_result['ids'];

			// fetch the content of matched columns from orginal database
			$matched_id = implode(', ', $ids);
			$query_words = "SELECT title, link, text FROM search_results WHERE id IN ($matched_id)";
      //the if-condition will be valid after turning-off db_debug option in /config/database.php, recommended to do so in production env to conceal detail of mysql command
      if (!$content_db->query($query_words)) {
        echo "<h3 style='color: red'>Sorry, an error occured when trying to fetch content from database</h3><p><a href='./'>Back to search again</a></p>";
        die();
      }
			return $content_db->query($query_words);
		}

//<---function to allow user to erase the database, mainly for testing purpose--->
    public function truncate_table()
    {
      $content_db = $this->load->database('google', TRUE);
      $content_db->query('TRUNCATE search_results');
    }
}
