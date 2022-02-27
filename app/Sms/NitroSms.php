<?php

namespace App\Sms;

use Illuminate\Support\Facades\Http;

class NitroSms
{

	private $user;
	private $pass;

	public function __construct()
	{
		$this->user = "081_glsdelivery1";
		$this->pass = "123456789";
		$this->sender = "Gls_Delivery";
	}

	public function activation_msg($to, $msg)
	{
		$msg = strval($msg);
		try {
			Http::get("http://nitrosms.cm/api_v1?sub_account=" . $this->user . "&sub_account_pass=" . $this->pass . "&action=send_sms&sender_id=" . $this->sender . "&message=" . $msg . "&recipients=" . $to);

			return "http://nitrosms.cm/api_v1?sub_account=" . $this->user . "&sub_account_pass=" . $this->pass . "&action=send_sms&sender_id=" . $this->sender . "&message=" . $msg . "&recipients=" . $to;
		} catch (\Throwable $th) {
			return $th;
		}
	}
}
