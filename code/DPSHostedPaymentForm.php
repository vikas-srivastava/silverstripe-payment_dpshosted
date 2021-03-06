<?php
/**
 * @package payment_dpshosted
 */
class DPSHostedPaymentForm extends Form{
	
	/**
	 * @var string $payment_class Subclass of DPSHostedPayment for custom processing
	 */
	static $payment_class = 'DPSHostedPayment';
	
	function __construct($controller, $name){
		$fields = new FieldSet(
			$donationAmount = new CurrencyField("Amount", "Amount"),
			new TextField("FirstName", "First Name"),
			new TextField("Surname", "Surname"),
			$email = new EmailField("Email", "Email")
		);

		$actions = new FieldSet(
			new FormAction("doPay", "Pay")
		);
		
		$validator = new RequiredFields(array(
			"Amount",
			"FirstName",
			"Surname",
			"Email",
		));
		
		parent::__construct($controller, $name, $fields, $actions, $validator);
		
	}
	
	function doPay($data, $form){
		$paymentClass = self::$payment_class;
		$payment = new $paymentClass();
		
		// ensures that we just write data that was submitted through the form
		$form->saveInto($payment);
		
		$payment->setClientIP();
		$payment->write();
		$payment->processPayment($data, $form);
	}
}
?>