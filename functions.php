<?php 

	// function __autoload($class_name) {
	// 	if (file_exists('lib/' . $class_name . '.php')) {
	// 		include 'lib/' . $class_name . '.php';
	// 	} else {
	// 		return false;
	// 	}
	// }
	// if (!class_exists(CustomThemeBase)) {
	// 	new CustomThemeBase();
	// }
	
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_filter('widget_text', 'do_shortcode');
	// register_nav_menu( 'Main Menu' );
	add_editor_style( );
	
	if (!is_admin()) {
		wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css' );
		wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=Lato:400,900|Exo:100,200,400' );
	}
	
	add_shortcode( 'event', 'eventbrite_event_info' );

	function eventbrite_event_info() {
		if ( $event = json_decode( get_event_info() )->event ) {
			$latlon = $event -> venue -> {'Lat-Long'};
			$venue_name = $event -> venue -> name;
			$latlon = explode( ' / ', $latlon );
			$lat = $latlon[0];
			$lon = $latlon[1];
			ob_start(); ?>
					<div class="event-details">
						<h1><?php echo $event -> title; ?></h1>
						<p><?php echo $event -> description; ?></p>
						<?php if ( isset( $event -> tickets -> ticket -> quantity_sold ) ): ?>
							<span class="attendees"><?php echo $event -> tickets -> ticket -> quantity_sold; ?></span>
						<?php endif; ?>
						<time><?php echo $event -> start_date; ?></time><a href="<?php echo $event -> url; ?>">Get Your Ticket</a>
					</div>
				<?php
			return ob_get_clean();
		} else {
			return '<p>There should be event information here...</p><p>Maybe you can find info<a href="http://dundeewebmeetup.eventbrite.com/">here</a></p>';
		}
	}

	function get_event_info() {
		$api_key = 'T5LWQA5GJXBYFDCDA6';
		$event_id = 2966318893;
		$transient = 'event-info';
		$cache_time = 60 * 60 * 1;
		// $url = 'https://www.eventbrite.com/json/event_get?app_key=' . $api_key . '&id=' . $event_id;
		$url = 'https://www.eventbrite.com/json/organizer_list_events?app_key=' . $api_key . '&id=' . $event_id . '&only_display=id,title,description,start_date,url,venue,status';
		if ( !$event = get_transient( $transient ) ) {
			if ( $results_json = file_get_contents( $url ) ) {
				$data = json_decode($results_json);
				$live_event = false;
				foreach ($data->events as $event) {
					if (strtolower($event->event->status) == 'live') {
						$live_event = $event->event;
					} 
				}
				set_transient( $transient, $live_event, $cache_time );
			} else {
				return false;
			}
		}

		if ( isset( $live_event ) && !empty( $live_event ) ) {
			return $live_event;
		} else {
			return false;
		}
	}	

	function get_event_title () {
		$event = get_event_info();
		$title = $event->title;
		return $title;
	}

	function get_event_link () {
		if ($event = get_event_info()) {
			$url = $event->url;
			return $url;
		}
		return false;
	}

	function get_event_month () {
		if ($event = get_event_info()){
			$date = strtotime( $event->start_date );
			$month = date('F', $date);
			return $month;
		}
		return '';
	}

	function get_event_leaflet_script () {
		if ($event = get_event_info()) {
			$venue = $event->venue;
			$lat = $venue->latitude;
			$lon = $venue->longitude;
			$name = $venue->name;
			$googlemapslink = 'http://maps.google.com/?q=' . $lat . ',' . $lon;

			$script = "<script>
		        var map = L.map('map').setView([$lat, $lon], 13);
		        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		            maxZoom: 18
		        }).addTo(map);
				L.marker([$lat, $lon]).addTo(map).bindPopup('<a href=\"$googlemapslink\">Get Directions</a>');
		    </script>";

			return $script;
		}

		$script = "<script>
	        var map = L.map('map').setView([56.45795, -2.98214], 13);
	        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	            maxZoom: 18
	        }).addTo(map);
	    </script>";

		return $script;
	}

	function get_event_description () {
		if ($event = get_event_info()) {
			$description = $event->description;
			// Remove eventbrite junk markup
			$description = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $description);
			$description = str_replace('<p>&nbsp;</p>', '', $description);
			$description = str_replace('<p> </p>', '', $description);

			return $description;
		}

		return '<p>Looks like we don\'t have anything lined up right now.</p><p>Please check back later.</p>';
	}
?>
