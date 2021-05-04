<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>

		<!-- #page -->
		<div id="page">

			<!-- header -->
			<header class="header clear">

                <nav class="navbar navbar-expand-md fixed-top navbar-light bg-light">

					<div id="navbarHeader">

						<!-- Your site title as branding in the menu -->
						<?php if ( ! has_custom_logo() ) { ?>

							<?php if ( is_front_page() && is_home() ) : ?>

								<h1 class="navbar-brand mx-auto d-block"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

							<?php else : ?>

								<a class="navbar-brand mx-auto d-block" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a>

							<?php endif; ?>


						<?php } else {
							the_custom_logo();
							echo get_codigo_custom_logo();
						} ?><!-- end custom logo -->

						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<!-- The WordPress Menu goes here -->
					<div id="navbarNavDropdown" class="navbar-collapse collapse">
						<?php wp_nav_menu(
							array(
								'theme_location'  => 'header-menu',
								'container'    	  => false,
								//'container_class' => 'collapse navbar-collapse',
								//'container_id'    => 'navbarNavDropdown',
								'menu_class'      => 'navbar-nav ml-auto',
								'fallback_cb'     => '',
								'menu_id'         => 'main-menu',
								'depth'           => 2,
								'walker'          => new Codigo_WP_Bootstrap_Navwalker(),
							)
						); ?>

						<form class="search form-inline p-0 d-md-none" method="get" action="<?php echo home_url(); ?>">
							<input class="search-input" type="search" name="s" placeholder="<?php _e( 'Search', 'codigo' ); ?>">
						</form>
					</div>
					<div class="navbar-right d-none d-md-block">
						<ul class="navbar-nav ml-auto">
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"  class="menu-item nav-item nav-item-search ">
								<a title="Search" href="#" class="nav-link search-link" ></a>
							</li>
						</ul>
					</div>

                    <!-- /.container -->
                </nav>

				<form id="desktopSearch" class="search form-inline d-none d-md-block" method="get" action="<?php echo home_url(); ?>">
					<div class="d-flex">
						<input class="p-0 search-input" type="search" name="s" placeholder="<?php _e( 'Enter search term', 'codigo' ); ?>">
						<!--<button class="p-0 search-submit btn btn-secondary" type="submit" disabled="disabled"><?php _e( 'Search', 'codigo' ); ?></button> -->
						<button class="p-0 search-close btn btn-secondary" type="button"></button>
					</div>
				</form>

			</header>
			<!-- /header -->
