<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Shared/Environment');
	}
	
	public function Index()
	{
		$this->load->Render();
	}

	public function ContactUs()
	{
		$this->load->Render();
	}

	public function SendEmail()
	{
		$this->load->helper(array('file', 'url'));
		$this->load->library('email');

		$config = LoadJsonFileWithEncrypt('./assets/app_data/mail.server.json', TRUE);
		$this->email->initialize($config);
		
		$data_post_name = $this->input->post('YourName', TRUE);
		$data_post_mail = $this->input->post('YourEmail', TRUE);
		$data_post_subject = $this->input->post('YourSubject', TRUE);
		$data_post_message = $this->input->post('YourMessage', TRUE);

		$this->email->from($data_post_mail, $data_post_name);
		$this->email->to('lovero32000@gmail.com');
		$this->email->cc('l7960261@gmail.com');
		$this->email->subject($data_post_subject);
		$this->email->message($data_post_message);

		if ($this->email->send())
		{
			redirect('Lab/Success');
		}
		else
		{
			show_error($this->email->print_debugger());
		}
	}

	public function Success()
	{
		$this->load->Render();
	}

	public function MailServerSetting()
	{
		$this->load->Render();
	}

	public function SetMailServer()
	{
		$this->load->helper(array('file', 'url'));

		$account = $this->input->post('account');
		$password = $this->input->post('password');
		
		if($this->Environment->IsDevelopment())
		{
			$config = array(
				'protocol' => 'smtp' ,
				'smtp_host'=> 'ssl://smtp.googlemail.com',
				'smtp_port'=> '465',
				'smtp_user'=> $account,
				'smtp_pass'=> $password,
				'charset'  => 'utf-8',
				'wordwrap' => TRUE
			);
		}
		else
		{
			$config = array(
				'protocol' => 'smtp' ,
				'smtp_host'=> 'mail.ienglishtutors.com',
				'smtp_port'=> '26',
				'smtp_user'=> $account,
				'smtp_pass'=> $password,
				'charset'  => 'utf-8',
				'wordwrap' => TRUE
			);
		}

		WriteJsonFileWithEncrypt('./assets/app_data/mail.server.json', $config);
		redirect('Lab/Success');
	}

	public function LoadView()
	{
		// 第三個 可選的 參數，它返回讀取那個頁面的整個 HTML
		$string = $this->load->view('Home/Lobby', '', TRUE);
		echo $string;
	}

	public function SetSessionData()
	{
		$this->load->library('session');

		$newdata = array(
		        'Username'  => 'johndoe',
		        'Email'     => 'johndoe@some-site.com',
		        'Logged_in' => TRUE
		);

		$this->session->set_userdata($newdata);
	}

	public function GetSessionData()
	{
		$this->load->library('session');
		echo $this->session->userdata('Username');
		echo $this->session->userdata('Email');
		echo $this->session->userdata('Logged_in');
	}

	public function UnsetSessionData()
	{
		$this->load->library('session');
		$this->session->unset_userdata('Username');

		$array_items = array('Email', 'Logged_in');
		$this->session->unset_userdata($array_items);
	}

	public function DestroySession()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
	}
	
	public function SetTempData()
	{
		$this->load->library('session');
		$this->session->set_tempdata(array(
			'Username'  => 'wilson',
			'Email'     => 'wilson@hotmail.com',
			'Logged' => TRUE
		), NULL, 60*60*2);
	}
	
	public function GetTempData()
	{
		$this->load->library('session');
		print_r($this->session->tempdata());
	}
	
	public function SetLanguages()
	{
		$this->User->SetPreference();
	}
	
	public function CheckLogin()
	{
		echo $this->User->IsLogin() ? '已登入' : '尚未登入';
	}

	public function SetCookie()
	{
		$this->load->helper('cookie');

		$config = json_encode(array(
			'Languages' => 'zh-TW'));

		set_cookie('performance', $config, 60*60*24*5);
	}

	public function GetCookie()
	{
		$this->load->helper('cookie');
		echo get_cookie('performance');

		$config = json_decode(get_cookie('performance'));
		echo $config->Languages;

		$config1 = json_decode(get_cookie('performance'), TRUE);
		echo $config1['Languages'];
	}

	public function DeleteCookie()
	{
		$this->load->helper('cookie');
		delete_cookie('performance');
		echo 'cookie is delete!';
	}
	
	public function Upload_Form()
	{	
		$this->load->helper(array('form','url'));
		$this->load->Render('', array('error' => ' ' ));
	}

	public function DoUpload()
	{
		$this->load->helper(array('form','url'));

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|ppt|pptx|doc|docx|txt|pdf|zip|7zip|rar';
		$config['max_size']	= '10000';//php最大到10MB
		$config['max_width']  = '0';// 0 = 不限制寬度
		$config['max_height']  = '0';
		$this->load->library('upload', $config);
		
		$input_title = ($_POST["TM_title"]);
		$input_time = ($_POST["TM_time"]);
		
		$already=FALSE;
		foreach($_FILES as $key => $value)
		{
			if ( ! $this->upload->do_upload($key))
			{
				$error = array('error' => $this->upload->display_errors());
				echo $key.'上傳失敗'."<br>";
			}
			else
			{
				$file = $this->upload->data();//回傳上傳檔案的詳細訊息
				echo $file['orig_name'].'上傳成功'."<br>";
				
				if ($already == FALSE)
				{
					$already = TRUE;
					$course = array(
										'Title' => $input_title,
										'Time' => $input_time
					);
					$this->db->insert('course', $course);
					$C_Id = $this->db->insert_id();
				}

				$course_files =array(
											'C_Id' => $C_Id,
											'Path' =>$file['full_path'],
											'Name' =>$file['orig_name'],
											'Type' =>$file['file_type']
				);
				$this->db->insert('course_files', $course_files);
			}
		}
	}

	public function Download_Form()
	{	
		$this->db->select('*');
		$this->db->from('course');
		$query = $this->db->get()->result_array();
		
		foreach ($query as $key => $value)
		{
			$course_files[$value['Id']] = array();
			$course_files[$value['Id']]['Title'] = $value['Title'];
			$course_files[$value['Id']]['Time'] = $value['Time'];
			$course_files[$value['Id']]['Files'] = array();
		}

		//print_r($course_files);	
		
		$this->db->select('C_Id,Name');
		$this->db->from('course_files');
		$query = $this->db->get()->result_array();
			
		foreach ($query as $key => $value) {
			array_push($course_files[$value['C_Id']]['Files'], $value);
		}
		
		$data['course_files'] = $course_files;
		
		$this->load->Render('',$data);
	}

	public function DoDownload()
	{

	}

	public function Download_urlPhoto()//下載網路圖片
	{	
		$data_post_URL = $this->input->post('TargetURL', TRUE);
		$headers = get_headers($data_post_URL);//剖析網址
		$pattern = $headers[0]; 
		
		if (preg_match("/200/i", $pattern))//判斷圖片是否存在
		{
			$contentType = strrchr($data_post_URL, '.');//判斷圖片的副檔名
				switch ($contentType){
					case ".jpg":
						$extension= ".jpg";
					break;
					case ".png":
						$extension = ".png";
					break;
					default:
						echo "not match"; exit;
					break;
				}
		}
		 
		$tmpFile="uploads/".md5($data_post_URL).$extension; 

		if (file_put_contents($tmpFile, file_get_contents($data_post_URL)))
		{
			echo '下載成功';
		}
		else
		{
			echo '下載失敗';
		}
	}
}

/* End of file Lab.php */
/* Location: ./application/controllers/Lab.php */


			// if ($value['Title'] != $previous ) 
			// {
			// 	if ($key==0) {$count = 0 ;}
			// 	else {$count++;}
				
			// 	$temp = array('Title' =>$value['Title'], 'Time' => $value['Time']);
			// 	array_push($teamArray[$count], $temp);
				
			// }
			// else
			// {
			// 	$temp = array('Title' =>$value['Title'], 'Time' => $value['Time']);
			// 	array_push($teamArray[$count], $temp);
			// }
			// $previous = $value['Title'];