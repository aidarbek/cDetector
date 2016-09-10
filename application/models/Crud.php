<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Crud extends CI_Model {

        public $table = "";
        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function check_if_equal($eq, $where)
        {
                //Check if app has appropriate keys
                $query = $this->db->get_where($this->table, $where);
                $result = $query->first_row("array");
                
                if(!is_array($result))
                        return 0;

                foreach ($eq as $key => $value) 
                {
                        if(array_key_exists($key, $result))
                        {
                                if($value != $result[$key])
                                        return 0;
                        }
                }
                return 1;
        }
        public function count_where($where)
        {
                $this->db->where($where);
                return $this->db->count_all_results($this->table);
        }
        public function add($add)
        {
                $this->db->insert($this->table, $add);
        }
}