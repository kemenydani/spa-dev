<?php

namespace core;

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;

class Mail extends PHPMailer
{
	public function __construct( $exceptions = false )
	{
		parent::__construct($exceptions);
		
		$this->isHTML(true);
		$this->SMTPDebug = false;

        $this->isSMTP();  // Set mailer to use SMTP
        $this->Host       = getConfig('noreply.smtp.hosts');  // Specify main and backup SMTP servers
        $this->SMTPAuth   = (bool)getConfig('noreply.smtp.auth', false); // Enable SMTP authentication
        $this->Username   = getConfig('noreply.smtp.username'); // SMTP username
        $this->Password   = getConfig('noreply.smtp.password');  // SMTP password
        $this->SMTPSecure = getConfig('noreply.smtp.secure'); // Enable TLS encryption, `ssl` also accepted
        $this->Port       = getConfig('noreply.smtp.port'); // TCP port to connect to

		/*
		try
		{
			if(getConfig('smtp.enabled'))
			{
				$this->isSMTP();  // Set mailer to use SMTP
				$this->Host       = getConfig('smtp.hosts');  // Specify main and backup SMTP servers
				$this->SMTPAuth   = getConfig('smtp.auth_enabled'); // Enable SMTP authentication
				$this->Username   = getConfig('smtp.username'); // SMTP username
				$this->Password   = getConfig('smtp.password');  // SMTP password
				$this->SMTPSecure = getConfig('smtp.secure_enabled'); // Enable TLS encryption, `ssl` also accepted
				$this->Port       = getConfig('smtp.port'); // TCP port to connect to
			}
			
			$this->setFrom(
				getConfig('email.default.email', getConfig('organisation.email')),
				getConfig('email.default.from', getConfig('organisation.name'))
			);
			
			$this->isHTML(true);
		
		}
		catch (\Exception $e)
		{
			return false;
		}
		*/
		return $this;
	}
	
	public static function renderTemplateContactDetails()
	{
		$name  = getConfig('organisation.name', __HOST__);
		$phone = getConfig('organisation.phone');
		$email = getConfig('organisation.email');
		
		$string = '';
		$string .= strlen($name)  ? $name . '<br>' : '';
		$string .= strlen($phone) ? $phone . '<br>' : '';
		$string .= strlen($email) ? $email . '<br>' : '';
		
		return $string;
	}
	
}
