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
		
		try
		{
			$this->db->insert('member', $member);
		}
		catch (Exception $e)
		{
			return FALSE;
		}
		
		$this->Login($username, $password);
		return TRUE;
	}
	
	public function Login($username, $password, $remember = FALSE)
	{
		$this->db->select('member.Id, Username, Password, Picture, Role');
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
	
	private function Save($member)
	{
		$this->User->Set(array(
			'Username'  => $member->Username,
			'Email'     => $member->Username,
			'Logged'    => TRUE,
			'Role'      => $member->Role,
			'Picture'   => $member->Picture ? $member->Picture : ''
		), $this->expiration);
	}
}

/* End of file Member.php */
/* Location: ./application/models/WebPortal/Member.php */