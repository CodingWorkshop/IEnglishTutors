<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Model {
	
	protected $expiration;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('encrypt');
	}
	
	public function Create($username, $password, $role)
	{
		if(!$this->UsernameAvailable($username))
		{
			return FALSE;
		}
		
		$query = $this->db->get_where('role', array('Role' => $role));
		$Id = $query->first_row()->Id;
		
		$member = array(
				'Username' => $username,
				'Password' => $this->encrypt->encode($password),
				'R_Id' => $Id
		);
		
		$result = $this->db->insert('member', $member);
		
		if($result)
		{
			$this->Login($username, $password);
		}
		
		return $result;
	}
	
	public function Login($username, $password, $remember = FALSE)
	{
		$this->db->select('member.Id, Username, Password, Role');
		$this->db->from('member');
		$this->db->join('role', 'member.R_Id = role.Id');
		$this->db->where('member.UserName', $username);
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$member = $query->first_row();
			if(strcmp($password, $this->encrypt->decode($member->Password)) === 0)
			{
				$this->db->where('Id', $member->Id)
							->update('member', array('LastLogin'=>date('Y-m-d H:i:s')));
				
				$this->expiration = $remember ? 60*60*24 : 3600;
				$this->Save($member);
				return TRUE;
			}
		}

		return FALSE;
	}
	
	public function Logout()
	{
		$this->User->Destroy();
		
		return TRUE;
	}
	
	public function UsernameAvailable($username)
	{
		$query = $this->db->get_where('member', array('Username' => $username));
		$existed = $query->num_rows() > 0;
		
		return !$existed;
	}
	
	public function All($role = NULL)
	{
		$this->db->select('Id, Username, R_Id, Picture, DisplayName, Description, GitHub, Facebook, LastLogin');
		$this->db->from('member');
		
		if ($role !== NULL)
		{
			$this->db->where('R_Id', $role);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function Update($mem)
	{
		return $this->db->where('Id', $mem->Id)
					->update('member', array('DisplayName' => $mem->DisplayName,
								'Description' => $mem->Description,
								'GitHub' 	   => $mem->GitHub,
								'Facebook'    => $mem->Facebook,
								'LastLogin'   => date('Y-m-d H:i:s')));
	}
	
	public function UpdatePicture($id, $picture)
	{
		return $this->db->where('Id', $id)
					->update('member', array('Picture' 	=> $picture,
								'LastLogin'   => date('Y-m-d H:i:s')));
	}
	
	private function Save($member)
	{
		$this->User->Set(array(
			'Id'			  => $member->Id,
			'Username' 	  => $member->Username,
			'Email'    	  => $member->Username,
			'Logged'  	  => TRUE,
			'Role'    	  => $member->Role
		), $this->expiration);
	}
}

/* End of file Member.php */
/* Location: ./application/models/WebPortal/Member.php */