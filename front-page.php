<?php 
wp_enqueue_style( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css' );
wp_enqueue_script( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js', false, false, false ); // TODO: put in footer
?>
<?php get_header(); ?>

    <main class="main-content" role="main">
        <div class="nextevent">
        	<header class="nextevent--header">
        	    <h1>Tech Meetup <?php echo get_event_month(); ?></h1>
        	</header>
        	<div class="nextevent--description">
                <?php echo get_event_description(); ?>
        	</div>
        	<div class="nextevent--map" id="map"></div>
        </div>
        <?php if ($link = get_event_link()): ?>
        	<a href="<?php echo $link; ?>" class="getticket">Get Your Ticket</a>
        <?php endif; ?>
        	 						
    </main>

    <?php echo get_event_leaflet_script(); ?>

<?php get_footer(); ?>