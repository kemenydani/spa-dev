<?php
/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 2018. 05. 02.
 * Time: 20:08
 */

namespace core;

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;

class Mail extends PHPMailer
{
	public function __construct( ?bool $exceptions = null )
	{
		parent::__construct($exceptions);
		
		try
		{
			if(getConfig('smtp.enabled') === true)
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
				getConfig('email.default.address', getConfig('organisation.email')),
				getConfig('email.default.from', getConfig('organisation.name'))
			);
			
			$this->isHTML(true);
		}
		catch (\Exception $e)
		{
			return false;
		}
		
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
	
	public static function renderTemplatePayPalCompletedOrder($details)
	{
	
		return '<b>Dear '.$details['recipient'].'<b><br><br>' .
			   'Your payment is completed. <br><br>' .
			   '<b>You ordered <a href="#">' . $details['quantity'] . '×' . $details['item_name'] . '</a></b><br>' .
			   '<b>Total: ' . $details['gross'] . '' . $details['currency'] . '</b><br><br>' .
		       'Please give us some time to process your order. <br>' .
		       'If you have any questions, feel free to contact us! <br><br>' .
		       'Regards,<br>' .
		       self::renderTemplateContactDetails();
	}
	
}
