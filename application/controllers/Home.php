<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function Index()
	{
		$this->Lobby();
	}

	public function Lobby()
	{
		$this->load->Render();
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */