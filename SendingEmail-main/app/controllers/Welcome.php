<?php


defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'app/vendor/autoload.php';

class Welcome extends Controller {


	public function __construct(){
		parent::__construct();
		$this->call->helper('url');
		$this->call->library('session');
		$this->call->library('form_validation');
		$this->call->model('User_model');
		$this->call->database();

	}


	public function index() {
		$this->call->view('welcome_page');
	}
	public function register(){
		$this->call->view('register');
	}
	public function login(){
		$this->call->view('login');
	}
	public function dashboard(){
		$this->call->view('email_form');
	}
	public function account(){
		$this->call->view('account_verify');
	}

	public function viewEmail(){
		$this->call->view('email_form');
	}

	public function register_val()
{
    // Validate form input
    $this->form_validation
        ->name('name')
        ->required()
        ->min_length(3)
        ->max_length(20)
        ->name('password')
        ->required()
        ->min_length(8)
        ->name('confpassword')
        ->matches('password')
        ->required()
        ->min_length(8)
        ->name('email')
        ->valid_email();

    // Check if validation fails
    if ($this->form_validation->run() == FALSE) {
        $this->call->view('register');
    } else {
        // Check if email already exists
        $existingEmail = $this->User_model->get_user_by_email($this->io->post('email'));

        if ($existingEmail) {
            // Email already registered, display an error
            $this->call->view('register', ['error_message' => 'Email already registered']);
        } else {
            // Generate verification code
            $verificationCode = substr(md5(rand()), 0, 8);
            $is_verify = FALSE;
            $email = $this->io->post('email');

            // Insert new user
            $this->User_model->insert(
                $this->io->post('name'),
                $this->io->post('password'),
                $email,
                $verificationCode,
                $is_verify
            );

            // Set data for the view
            $data['email'] = $email;

            // Load the account_verify view
            $this->call->view('account_verify', $data);

            // Set registered email in session
            $this->session->set_userdata('registered_email', $email);

            // Send verification email
            $this->sendVerificationEmail($email, $verificationCode);
        }
    }
}


	// public function register_val(){
	// 	$this->form_validation
	// 		->name('name')
	// 			->required()
	// 			->min_length(3)
	// 			->max_length(20)
	// 		->name('password')
	// 			->required()
	// 			->min_length(8)
	// 		->name('confpassword')
	// 			->matches('password')
	// 			->required()
	// 			->min_length(8)
	// 		->name('email')
	// 			->valid_email();
	// 			if ($this->form_validation->run() == FALSE)
	// 			{
	// 				$this->call->view('register');
				
	// 			}
	// 			else
	// 			{
	// 				$verificationCode = substr(md5(rand()), 0, 8);
	// 				$is_verify = FALSE;
	// 				$email =$this->io->post('email');
	// 				$this->User_model->insert(
	// 					$this->io->post('name'),
	// 					$this->io->post('password'),
	// 					$email,
	// 					$verificationCode,
	// 					$is_verify 
	// 				);
		
	// 				$data['email'] = $this->io->post('email');
	// 				$this->call->view('account_verify',$data);

	// 				$verify = $this->getRegisteredEmail();
	// 				$this->session->set_userdata('registered_email', $this->io->post('email'));
				
	// 				$this->sendVerificationEmail($email, $verificationCode);
				
	// 			}
    // }

	public function getRegisteredEmail() {
		return $this->session->userdata('registered_email');
	}

	public function login_val(){

		$this->form_validation
		->name('password')
			->required()
			->min_length(8)
		->name('email')
			->valid_email();

			if ($this->form_validation->run() == FALSE)
			{
				$this->call->view('login');
				
			}
			else
			{
				$email = $this->io->post('email'); 
				$password = $this->io->post('password');
	
				$user = $this->User_model->get_user_by_email($email);

				if ($user) {
					if ($password === $user['password']) {
						if ($user['is_verified']) {
							// User is verified, proceed to the dashboard
							$_SESSION['email'] = $this->io->post('email');
							$this->call->view('email_form');

						} else {
							$data['email'] = $this->io->post('email');
							$data['error_message'] = 'Veriy Your Email'; 
							$this->call->view('account_verify', $data);
						}
					} else {
						$data['error_message'] = 'Invalid password!'; 
						$this->call->view('login', $data);
					}
				} else {
					$data['error_message'] = 'Email Not Found!'; 
					$this->call->view('login', $data);
				}		
			}
	}

	public function email(){
		$mail = new PHPMailer(true);

			$to = $_POST["to"];
			$from = $this->session->userdata('email');
				   
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'liquiganrustom15@gmail.com';  
        $mail->Password = 'gqdc cuij zfya oimb'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587;  

        $mail->setFrom($from); 
        $mail->addAddress($_POST['to'], $to);  

      
        $mail->isHTML(true);
        $mail->Subject = $_POST['subject'];
        $mail->Body = $_POST['message'];  

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
        }

        try {
            $mail->send();
		
			$data['success_message'] = 'Email has been sent succesfully'; 
			$this->call->view('email_form', $data);
        } catch (Exception $e) {
	
			$data['error_message'] = 'Email Not Found!'; 
			$this->call->view('email_form', $data);
        }
	}


	public function sendVerificationEmail($to, $verificationCode) {
		$mail = new PHPMailer(true);
	
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com'; 
		$mail->SMTPAuth = true;
		$mail->Username = 'liquiganrustom15@gmail.com';  
		$mail->Password = 'gqdc cuij zfya oimb';  
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
		$mail->Port = 587;  
	

		$from = 'liquiganrustom15@gmail.com'; 
		$mail->setFrom($from, 'Ark'); 
		$mail->addAddress( $to);  
		// var_dump($email);
		$mail->isHTML(true);
		$mail->Subject = 'Account Verification Code';
		$mail->Body = 'Your verification code is: ' . $verificationCode;


		try {
			$mail->send();
			
			$this->User_model->updateVerificationCode($to, $verificationCode);
			$this->call->view('account_verify');
		} catch (Exception $e) {
			
			echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
		}
	}


public function check() {
	$email = $this->io->post('email');
	$verificationCode = $this->io->post('verify');

	$isVerified = $this->User_model->verifyUser($email, $verificationCode);

	if ($isVerified) {
		$data['email'] = $this->io->post('email');
		$data['success_message'] = 'Email successfully verified!';
		$this->call->view('login', $data);
	} else {
		$data['email'] = $this->io->post('email');
		$data['error_message'] = 'Invalid verification code.';
		$this->call->view('account_verify', $data);
	}
}

}
?>