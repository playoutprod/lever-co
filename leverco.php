<?php
/**
 * @package LEVER_CO
 */
/*
Plugin Name: Lever-co
Plugin URI: https://wwW.playoutprod.com/plugins/lever_co
Description: Lever integration in wordpress
Version: 0.1.0
Author: Playout Production
Author URI: https://wwW.playoutprod.com
License: GPLv2 or later
Text Domain: LEVER_CO

Lever-co is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Lever-co is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Lever-co. If not, see https://www.fsf.org/.


*/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lever_co' ) ) {
    /**
     * Class Lever_co
     */
    class Lever_co {

        protected $slug = 'lever_co';
				protected $version = '0.1.0';
				public $company = 'leverdemo';
				public $list = array();
				public $original_list = array();
				public $job = array();
				public $filters;
				public $url = '';


        /*  constructor */
        public function __construct() {
            add_action( 'init', array( $this, 'init' ) );
						$this->company = 'joyn';
        }

        /** initialize */
        public function init() {
					wp_register_style($this->slug.'_style',plugin_dir_url( __FILE__ ) . 'css/style.css',null,$this->version);
					wp_register_script($this->slug.'_functions',plugin_dir_url( __FILE__ ) . 'js/functions.js',array('jquery'),$this->version);
					add_shortcode($this->slug,array($this,'render_list'));
					add_shortcode($this->slug.'_list',array($this,'render_list'));
					add_shortcode($this->slug.'_job',array($this,'render_job'));
					add_shortcode($this->slug.'_search',array($this,'render_search'));
        }

				/** get html output
       	* @param array $template
       	* @param array $data
       	* @return string
       	*/

				private function get_output($template = 'list'){
					wp_enqueue_style($this->slug.'_style');
					wp_enqueue_script($this->slug.'_functions');

					$output =  '<div class="lever_co '.$template.'">';
					ob_start();
					if($template == 'list' || $template == 'teams'){
						if($this->filters != NULL){
							include 'outputs/filters.php';
						}
					}
					include 'outputs/'.$template.'.php';
					$output .= ob_get_clean();
					$output .= '</div>';
					return($output);
				}

				/** set filters from list
       	* @return array
       	*/

				private function get_filters(){
					$filters = new stdClass;

					//For each job
					foreach ($this->original_list as $key => $job) {

						//For each job category
						foreach ($job->categories as $filter => $value) {

							//For each categories, if the filter dosen't exists, create it (ex : commitment, departement, location, team)
							if(!isset($filters->$filter)){
								$filters->$filter = new stdClass;
							}

							//If filter has no values then create an array for it
							if(!isset($filters->$filter->values)){
								$filters->$filter->values = array();
							}

							if($filter == 'team'){

								//Get departement
								$dep = $this->original_list[$key]->categories->department;

								//Look for departemnt in departement filters and get index.
								$index = array_search($dep,$filters->department->values);

								//If departement has no team, create an array
								if(!isset($filters->department->teams)){
									$filters->department->teams = array();
								}

								//Create an array corresponding on departement index (for matching)
								if(!isset($filters->department->teams[$index])){
									$filters->department->teams[$index] = array();
								}

								$exist = false;
								$exist = array_search($value,$filters->department->teams[$index]);

								if($exist === false){

									//Add value to departement filter
									array_push($filters->department->teams[$index],$value);

									if(array_search($value,$filters->$filter->values) === false){
										array_push($filters->$filter->values,$value);
										//Add a count object on filter to count how many values are in it.
										if(!isset($filters->$filter->counts)){
											$filters->$filter->counts = new stdClass;
										}
										$filters->$filter->counts->$value = 0;
									}
									//Add a count object on filter to count how many values are in it.
									if(!isset($filters->$filter->counts)){
										$filters->$filter->counts = new stdClass;
									}
									$filters->$filter->counts->$value = 0;
								}
							}else{
								//If value dosent' exists, insert it
								if(array_search($value,$filters->$filter->values) === false){
									//Add value to filter
									array_push($filters->$filter->values,$value);
									//Add a count object on filter to count how many values are in it.
									if(!isset($filters->$filter->counts)){
										$filters->$filter->counts = new stdClass;
									}
									$filters->$filter->counts->$value = 0;
								}
							}
						}
					}


					return($filters);
				}

        /** job list render shortcode
       	* @param array $atts
       	* @param null $content
       	* @return string
       	*/

        public function render_list( $atts) {

					if(isset($_GET['job_id'])){
						//Single
						return($this->render_job(array('id'=>$_GET['job_id'])));
					}else{
						$atts = shortcode_atts(array(
                'skip'       => '',
                'limit'      => '',
                'location'   => '',
                'commitment' => '',
                'team'       => '',
                'department' => '',
                'level'      => '',
                'group'      => '',
            ), $atts, $this->slug );

						//Get unfiltered list
            $this->original_list = $this->get_jobs($atts);

						if(! empty($this->original_list)){

							usort($this->original_list,function($a,$b){
								if ($a->createdAt == $b->createdAt) {
					        return 0;
					    	}
					    	return ($a->createdAt > $b->createdAt) ? -1 : 1;
							});

							//Create filters
							$this->filters = $this->get_filters();

							//Filter original list
							foreach ($this->original_list as $j => $job) {
								$remove= false;

								//Filter from url
								foreach ($this->filters as $filter => $filter_obj) {
									if(isset($_GET[$filter])){
										if($job->categories->$filter != $_GET[$filter]){
											$remove = true;
										}
									}
								}

								if($remove == false){
									$dep = $job->categories->department;
									$team = $job->categories->team;

									$this->filters->department->counts->$dep++;
									$this->filters->team->counts->$team++;

									array_push($this->list,$job);
								}
							}
							return($this->get_output('teams'));
						}else{
							return($this->get_output('empty'));
						}
					}
        }

				/** job render shortcode
       	* @param array $atts
       	* @param null $content
       	* @return string
       	*/

        public function render_job($atts) {
            $atts = shortcode_atts(array(
                'id'       => '',
            ), $atts, $this->slug );
						if($atts['id'] != ''){
							$this->job = $this->get_job($atts['id']);
							if(isset($this->job->error)){
								return($this->get_output('error'));
							}else{
								return($this->get_output('job'));
							}
						}else{
							return($this->get_output('error'));
						}
        }

				public function render_search($atts){

					if(isset($atts['url'])){
						$this->url = $atts['url'];
					}
					$atts = shortcode_atts(array(
							'skip'       => '',
							'limit'      => '',
							'location'   => '',
							'commitment' => '',
							'team'       => '',
							'department' => '',
							'level'      => '',
							'group'      => '',
					), $atts, $this->slug );

					$this->original_list = $this->get_jobs($atts);
					if(! empty($this->original_list)){
						$this->filters = $this->get_filters();
						return($this->get_output('filters'));
					}else{
						return('');
					}
				}


				/**
       	* convert atts in query
       	* @param array $atts
       	* @return string
       	*/

				private function build_query($atts){

					$query = '';
					foreach ($atts as $key => $value) {
						if($value != '' && $key != 'site'){
							$query .= $key.'='.$value.'&';
						}
					}
					if($query != ''){
						$query = substr($query, 0, -1);
					}
					return($query);
				}

      	/**
       	* fetch jobs
       	* @param string $site
       	* @param array $params
       	* @return array
       	*/

        public function get_jobs($atts) {
            $query = $this->build_query( $atts);
            $url = 'https://api.lever.co/v0/postings/'.$this->company.'?'. $query;
            $response = wp_remote_get( $url, array('timeout' => 200,'headers' => array('Accept' => 'application/json')));
            $json = wp_remote_retrieve_body( $response );
            return json_decode( $json );
        }
				/**
       	* fetch job details
       	* @param string $site
       	* @param string $job_id
       	* @return array
       	*/

        public function get_job($job_id) {
            $url = 'https://api.lever.co/v0/postings/'.$this->company.'/'. $job_id;
            $response = wp_remote_get( $url, array('timeout' => 200,'headers' => array('Accept' => 'application/json')));
            $json = wp_remote_retrieve_body( $response );
            return json_decode( $json );
        }
    }

    new Lever_co();
}
