<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 *	API controller
	 *----------------	 
	 *	Responsible for queries processing
	 *	
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("crime_model");
		$this->load->model("app_model");
		$this->load->model("error_model");
		$this->load->model("feedback_model");
	}
	private function authorized($method = "")
	{
		$appId = $this->input->get("id");
		$appSecret = $this->input->get("secret");

		if($method == "addFeedback" && !in_array($appId, array(1, 2)))
		{
			// Only certain apps can add a feedback
			return 0;
		}
		if($this->app_model->valid($appId, $appSecret))
			return 1;
		else 
			return 0;
	}
	public function getByPosition()
	{
		$data = array();
		if($this->authorized())
		{
			$lat = $this->input->get("lat");
			$lng = $this->input->get("lng");
			
			if($lat && $lng)
			{
				$data = $this->crime_model->get_rate_by_position($lat, $lng);
			}
		}
		else
		{
			$data["error"] = $this->error_model->show("auth");
		}
		echo json_encode($data);
	}
	public function getByAddress()
	{
		$data = array();
		if($this->authorized())
		{
			$address = $this->input->get("address");
			
			if($address)
			{
				$data = $this->crime_model->get_rate_by_address($address);
			}
		}
		else
		{
			$data["error"] = $this->error_model->show("auth");
		}
		echo json_encode($data);
	}
	public function addFeedback()
	{
		$data = array();
		if($this->authorized("addFeedback"))
		{
			$ip = $this->input->ip_address();
			$lat = $this->input->get("lat");
			$lng = $this->input->get("lng");
			$safer = $this->input->get("safer");

			if($ip && $lat && $lng && $safer != NULL)
			{
				$where_square = array(
                                "lat >= " => ((double)$lat - 0.0025),
                                "lat <= " =>((double)$lat + 0.0025),
                                "lng >=" => ((double)$lng - 0.0025),
                                "lng <=" => ((double)$lng + 0.0025)
                        );
				if(!$this->feedback_model->voted($where_square))
				{
					$this->feedback_model->add(array(
						"ip" => $ip,
						"lat" => $lat,
						"lng" => $lng, 
						"safer" =>$safer
					));
					$data["success"] = "You added feedback!";
				}
				else
				{
					$data["error"] = "You have already voted!";
				}
			}
		}
		else
		{
			$data["error"] = $this->error_model->show("auth");
		}
		echo json_encode($data);
	}
}
