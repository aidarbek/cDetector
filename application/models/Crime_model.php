<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Crime_model extends Crud {

        public $table = "crime";

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get_rate_by_position($lat, $lng)
        {
                $this->load->model("feedback_model");
                
                $str = "SELECT COUNT(*) FROM crime WHERE lat >= ".((double)$lat - 0.0025); 
                $str .= "AND lat <= ".($lat + 0.0025);
                $str .="AND lng >=".($lng - 0.0025)." AND lng <= ".((double)$lng + 0.0025);
                $query = $this->db->query($str);
                $result = $query->result_array();
                $result = $result[0]["COUNT(*)"];

                $where_square = array(
                                "lat >= " => ((double)$lat - 0.0025),
                                "lat <= " =>((double)$lat + 0.0025),
                                "lng >=" => ((double)$lng - 0.0025),
                                "lng <=" => ((double)$lng + 0.0025)
                        );
                $result = $this->count_where($where_square);

                $where_square["safer"] = 0;
                $plus = $this->feedback_model->count_where($where_square);

                $where_square["safer"] = 1;
                $minus = $this->feedback_model->count_where($where_square);

                
                $data = array("count"=>(int)$result + (int)$plus - (int)$minus,"percent"=>(int)($result / 1.5));
                
                $data["voted"] = $this->feedback_model->voted($where_square);
                
                $data["lat"] = $lat;
                $data["lng"] = $lng;
                if($data["percent"] > 100) 
                        $data["percent"] = 100;
                return $data;
        }
        public function get_rate_by_address($address)
        {
                $lang = "ru";
                $key = "AIzaSyBdZIapyQ5Rpy4YTVsYiO2b-iYNpqExT50";
                $url = "http://maps.googleapis.com/maps/api/geocode/json?language=".$lang."&address=".$address;
                $url = str_replace(" ", "+", $url);
               
                //$address_data = $this->curl->simple_get('http://example.com/');
                //var_dump($address_data);
                $lat = -100;
                $lng = -100;
                $data = "";
                if( $curl = curl_init() ) 
                {
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        $out = curl_exec($curl);
                        $data = json_decode($out, true);
                        curl_close($curl);
                        if($data["status"] != "ZERO_RESULTS")
                        {
                                $lat = floatval($data["results"][0]["geometry"]["location"]["lat"]);
                                $lng = floatval($data["results"][0]["geometry"]["location"]["lng"]);
                        }
                }
                return $this->get_rate_by_position($lat, $lng);
        }
}