<?php

namespace Thingsee;

use Config;

class ThingseeAPI {

	/**
	 * \GuzzleHttp\Client
	 * @var Client
	 */
	protected $client;

	/**
	 * Auth token
	 * @var string
	 */
	protected $accountAuthToken;

	/**
	 * Initializes new GuzzleHttp Client
	 * Logs in the user and recieves auth token
	 */
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

		// Obsolete, 401 and 500 will be caught in the calling function
		$statusCode = $response->getStatusCode();

		$data = json_decode($response->getBody(), true);
		$this->accountAuthToken = $data['accountAuthToken'];
	}

	/**
	 * @param  string device
	 * @param  string query
	 * @return json events
	 */
	public function getEvents($device, $query) {
		$response = $this->client->request('GET', 'http://api.thingsee.com/v2/events/' . Config::get('thingsee.' . $device . '') . $query, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->accountAuthToken
			]
		]);
		
		return json_decode($response->getBody(), true);
	}
}