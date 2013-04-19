<?php
	
class CCUser extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		$this->views->SetTitle('User Index');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array (
			'is_auth' => $this->user->IsAuthenticated(),
			'user' => $this->user,
		));
	}
	
	public function Profile() {
		$form = new CFormUserProfile($this, $this->user);
		$form->Check();
		
		$this->views->SetTitle('User Profile');
		$this->views->AddInclude(__DIR__ .'/profile.tpl.php', array(
			'is_auth' => $this->user['isAuthenticated'],
			'user' => $this->user,
			'profile_form' => $form->GetHTML(),
		));
	}
	
	public function DoChangePassword($form) {
		if($form['password']['value'] != $form['password1']['value'] ||
		 empty($form['password']['value']) || empty($form['password1']['value'])) {
			
			$this->session->AddMessage('error', 'Password does not match or is empty');
			} else {
				$ret = $this->user->ChangePassword($form['password']['value']);
				$this->AddMessage($ret, 'Saved new password', 'Failed to update password');
			}
			$this->RedirectTo('user/profile');
	}
	
	public function DoProfileSave($form) {
		$this->user['name'] = $form['name']['value'];
		$this->user['email'] = $form['email']['value'];
		$ret = $this->user->Save();
		$this->AddMessage($ret, 'Saved profile', 'Failed');
		$this->RedirectTo('user/profile');
	}
	
  public function Login() {
    $form = new CFormUserLogin($this);
    //$form->CheckIfSubmitted();
	if($form->Check() === false) {
		$this->session->AddMessage('notice', 'Something wrong');
		$this->RedirectTo('user/login');
	}
    $this->views->SetTitle('Login');
    $this->views->AddInclude(__DIR__ . '/login.tpl.php', array(
														'login_form'=>$form,
														'allow_create_user' => CJrnek::Instance()->config['create_new_users'],
														'create_user_url' => $this->request->CreateUrl('user/create')
														));     
  }
	
	public function DoLogin($form) {
		if($this->user->Login($form['acronym']['value'], $form['password']['value']))	{
			//$this->RedirectTo($this->request->CreateUrl($this->request->controller . '/profile'));
			$this->RedirectTo('user/profile');
		} else {
			//$this->RedirectTo($this->request->CreateUrl($this->request->controller));
			$this->RedirectTo('user/login');
		}
	}
	
	public function Create() {
		$form = new CFormUserCreate($this);
		if($form->Check() === false) {
			$this->session->AddMessage('notice', 'Fill all values');
			$this->RedirectTo('user/create');
		}
		$this->views->SetTitle('Create user');
		$this->views->AddInclude(__DIR__ . '/create.tpl.php', array('form' => $form->GetHTML()));
	}
	
	public function DoCreate($form) {    
		if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
			$this->session->AddMessage('error', 'Password does not match or is empty.');
			$this->RedirectTo('user/create');
		} else if($this->user->Create($form['acronym']['value'], $form['password']['value'], $form['name']['value'],
										$form['email']['value'])) {
			$this->session->AddMessage('success', "Welcome {$this->user['name']}. Your have successfully created a new account.");
			$this->user->Login($form['acronym']['value'], $form['password']['value']);
			$this->RedirectTo('user/profile');
		} else {
			$this->session->AddMessage('notice', "Failed to create an account.");
			$this->RedirectTo('user/create');
		}
	}
	
	public function Logout() {
		$this->user->Logout();
		$this->RedirectTo($this->request->CreateUrl($this->request->controller));
	}
	
	public function Init() {
		$this->user->Init();
		$this->RedirectTo($this->request->CreateUrl($this->request->controller));
	}
	
	public function AddMessage($bool, $trueMsg, $falseMsg) {
		if($bool) {
			$this->session->AddMessage('info', $trueMsg);
		} else {
			$this->session->AddMessage('error', $falseMsg);
		}
	}
}