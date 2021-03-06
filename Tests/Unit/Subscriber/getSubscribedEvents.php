<?php

namespace WPMedia\Cloudflare\Tests\Unit\Subscriber;

use WPMedia\Cloudflare\Subscriber;

/**
 * @covers WPMedia\Cloudflare\Subscriber::get_subscribed_events
 * @group  Cloudflare
 * @group  CloudflareSubscriber
 */
class Test_GetSubscribedEvents extends TestCase {

	public function testShouldReturnSubscribedEventsArray() {
		$events = [
			'rocket_varnish_ip'                         => 'set_varnish_localhost',
			'rocket_varnish_purge_request_host'         => 'set_varnish_purge_request_host',
			'rocket_cron_deactivate_cloudflare_devmode' => 'deactivate_devmode',
			'after_rocket_clean_domain'                 => 'auto_purge',
			'after_rocket_clean_post'                   => [ 'auto_purge_by_url', 10, 3 ],
			'admin_post_rocket_purge_cloudflare'        => 'purge_cache',
			'init'                                      => [ 'set_real_ip', 1 ],
			'update_option_wp_rocket_settings'          => [ 'save_cloudflare_options', 10, 2 ],
			'pre_update_option_wp_rocket_settings'      => [ 'save_cloudflare_old_settings', 10, 2 ],
			'admin_notices'                             => [
				[ 'maybe_display_purge_notice' ],
				[ 'maybe_print_update_settings_notice' ],
			],
		];

		$this->assertSame( $events, Subscriber::get_subscribed_events() );
	}
}
