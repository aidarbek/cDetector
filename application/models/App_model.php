<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class App_model extends Crud {
        /*
                "App" table has following columns:
                ----------------------------------
                id - unique app identifier
                secret - secret key of app, by which it gets access to the functional
                title - title name of the application
                description - short description of app
                thumb - image that is associated with app
                owner - user, who owns this application 
        */
        public $table = "apps";

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function valid($appId, $appSecret)
        {
                //Check if app has appropriate keys
                return $this->check_if_equal(array("secret"=>$appSecret), array("id"=>$appId));
        }
}