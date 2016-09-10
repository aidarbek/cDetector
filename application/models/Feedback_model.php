<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Feedback_model extends Crud {
        /*
                "Feedbacks" table has following columns:
                ----------------------------------
                id - unique feedback identifier
                lat - latitude of position
                lng - longitude of position
                safer - (1 or 0) if position is safer than predicted
                id - ip address of voted person
        */
        public $table = "feedbacks";

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
        public function voted($square)
        {
                $square["ip"] = $this->input->ip_address();
                $res = $this->count_where($square);
                if($res)
                        return 1;
                else
                        return 0;
        }

}