<?php
class Google extends CI_Controller {

//<---globally load Google_model.php for all the functions beneath this controller--->
        public function __construct()
        {
                parent::__construct();
                $this->load->model('note_model');
        }

//<---define how to display the index page of "./google/"--->
        public function index()
        {
                $data['query'] = $this->input->get('query');
                //alternative pages to show dependent on whether query words are given or not
                if (!isset($data['query'])) {
                        $data['fetch_results'] = $this->google_model->get_results();
                        if (empty($data['fetch_results'])) {
                          $data['empty_set'] = "Sorry, no Google search results saved in database now.";
                        } else {
                          $data['empty_set'] = null;
                        }
                        $data['title'] = 'Fetched Google Search Results';

                        $this->load->view('templates/header', $data);
                        $this->load->view('google/index', $data);
                        $this->load->view('templates/footer');

                }else{
                        $data['title'] = 'Query Results';
                        $data['query'] = trim($data['query']);
                        $index_result = $this->google_model->search_index();
                        if (is_array($index_result)) {
                                $data['content_result'] = $this->google_model->search_content($index_result);
                                $data['search_meta'] = $index_result['meta'];
                        }else{
                                $data['search_error'] = $index_result;
                        }

                        $this->load->view('templates/header', $data);
                        $this->load->view('google/search', $data);
                        $this->load->view('templates/footer');
                }
        }

//<---define the page "./google/create" used to do the google search and database writing--->
        public function create()
        {
                $this->load->helper('form');
                $this->load->library('form_validation');

                $data['title'] = 'Fetch search results from Google and save them into local database';
                $data['asset_url'] = asset_url();
                $data['js_to_load'] = "js/search.js";
                $data['css_to_load'] = "css/search.css";

                //validate if the required fields have been given
                $this->form_validation->set_rules('keywords[]', 'Search Words', 'required');
                $this->form_validation->set_rules('pages[]', 'Pages of Search Results', 'required');
                //if validation passes, then do the google search and database writing
                if ($this->form_validation->run() === FALSE)
                {
                        $this->load->view('templates/create_header', $data);
                        $this->load->view('google/create');
                        $this->load->view('templates/footer');

                }
                else
                {
                        $rtime = $this->google_model->set_results();
                        $data['real_time'] = $rtime[0];
                        $data['user_time'] = $rtime[1]['user_time'];
                        $data['sys_time'] = $rtime[1]['sys_time'];
                        $this->load->view('google/success', $data);
                }
        }

//<---do the database erase when click the link "./google/truncate"--->
        public function truncate()
        {
          $this->google_model->truncate_table();
          $this->load->view('google/truncate');
        }
}
