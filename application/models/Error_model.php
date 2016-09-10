<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Error_model extends CI_Model {

        public $errors = array(
                        "auth" => "Authorization failed!",
                        "" => "",
                );

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function show($error)
        {
                //Check if app has appropriate keys
                if(array_key_exists($error, $this->errors))
                        return $this->errors[$error];
                else
                        return "";
        }
}