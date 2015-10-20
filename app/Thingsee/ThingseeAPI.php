<?php

namespace Thingsee;

use Config;

class ThingseeAPI {

	protected $client;
	protected $accountAuthToken;

	public function __construct() {
		$client = new \GuzzleHttp\Client;
		$this->client = $client;

		$response = $this->client->request('POST', 'http://api.thingsee.com/v2/accounts/login', 
			['json' => 
				[
					'email' => Config::get('thingsee.email'), 
					'password' => Config::get('thingsee.password')
				]
			]);

		$statusCode = $response->getStatusCode();

		$data = json_decode($response->getBody(), true);
		$this->accountAuthToken = $data['accountAuthToken'];
	}

	public function getEvents($device, $query) {
		$response = $this->client->request('GET', 'http://api.thingsee.com/v2/events/' . Config::get('thingsee.' . $device . '') . $query, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->accountAuthToken
			]
		]);
		
		return json_decode($response->getBody(), true);
	}
}