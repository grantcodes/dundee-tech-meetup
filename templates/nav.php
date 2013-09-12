<nav class="main-nav" role="navigation">
    <?php wp_nav_menu( array( 
    	'sort_column' => 'menu_order',
    	'menu_class' => 'nav',
    	'container' => false, 
    	'theme_location' => 'primary-menu' 
    ) ); ?>
</nav>