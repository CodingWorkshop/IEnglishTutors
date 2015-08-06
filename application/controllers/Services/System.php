<?php
require_once '/application/models/dbo/Language.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CI_Controller {
	
	public function JsonOutput() {
		$response = array('status' => 'OK');

		$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		        ->_display();
		exit;
	}

	public function LanguageWithResult() {
		$this->load->database();
		$query = $this->db->get('language');

		foreach ($query->result() as $row)
		{
		        echo $row->Id;
		        echo '|';
		        echo $row->Name;
		        echo '<br>';
		}

		exit;
	}

	public function LanguageWithMaterialization() {
		$this->load->database();
		$query = $this->db->get('language');

		foreach ($query->result('Language') as $language)
		{
        	echo $language->Id;
        	echo '|';
        	echo $language->Name;
        	echo '<br>';
        	//echo $language->reverse_name(); // or methods defined on the 'User' class
		}
	}
}