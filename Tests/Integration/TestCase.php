<?php

namespace WPMedia\Cloudflare\Tests\Integration;

use Brain\Monkey;
use WPMedia\PHPUnit\Integration\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	protected static $api;
	protected static $api_key;
	protected static $email;
	protected static $zone_id;
	protected static $site_url;

	/**
	 * Name of the API credentials config file, if applicable. Set in the test or new TestCase.
	 *
	 * For example: rocketcdn.php or cloudflare.php.
	 *
	 * @var string
	 */
	protected static $api_credentials_config_file;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		self::$email    = Factory::$email;
		self::$api_key  = Factory::$api_key;
		self::$zone_id  = Factory::$zone_id;
		self::$site_url = Factory::$site_url;
		self::$api      = getFactory()->getContainer( 'cloudflare_api' );
	}

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();
		Monkey\setUp();

		getFactory()->restoreState();
	}

	/**
	 * Gets the credential's value from either an environment variable (stored locally on the machine or CI) or from a
	 * local constant defined in `tests/env/local/cloudflare.php`.
	 *
	 * @param string $name Name of the environment variable or constant to find.
	 *
	 * @return string returns the value if available; else an empty string.
	 */
	protected static function getApiCredential( $name ) {
		return Factory::getApiCredential( $name );
	}

	protected function getNewCacheLevel( $value ) {
		$valid_cache_level         = $this->getCacheLevelValidValues();
		$without_given_cache_level = array_values( array_diff( $valid_cache_level, [ $value ] ) );

		return $without_given_cache_level[ rand( 0, count( $without_given_cache_level ) - 1 ) ];
	}

	protected function getNewTTL( $value ) {
		$valid_ttls        = $this->getTTLValidValues();
		$without_given_ttl = array_values( array_diff( $valid_ttls, [ $value ] ) );

		return $without_given_ttl[ rand( 0, count( $without_given_ttl ) - 1 ) ];
	}

	protected function getCacheLevelValidValues() {
		return [
			'aggressive',
			'basic',
			'simplified',
		];
	}

	protected function getTTLValidValues() {
		return [
			0,
			30,
			60,
			300,
			1200,
			1800,
			3600,
			7200,
			10800,
			14400,
			18000,
			28800,
			43200,
			57600,
			72000,
			86400,
			172800,
			259200,
			345600,
			432000,
			691200,
			1382400,
			2073600,
			2678400,
			5356800,
			16070400,
			31536000,
		];
	}

	protected function getSetting( $setting ) {
		$method   = $this->get_reflective_method( 'get', self::$api );
		$response = $method->invoke( self::$api, 'zones/' . self::$zone_id . "/settings/{$setting}" );

		if ( $response->success ) {
			return $response->result->value;
		}
	}
}
