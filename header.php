<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<?php if ( ! has_site_icon()) : ?>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" >
		    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png">
			<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-60x60.png">
			<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-76x76.png">
			<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-114x114.png">
			<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-120x120.png">
			<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-144x144.png">
			<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-icon-180x180.png">
			<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri(); ?>/img/icons/android-icon-192x192.png">
			<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon-96x96.png">
			<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon-16x16.png">
			<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/icons/manifest.json">
			<meta name="msapplication-TileColor" content="#ffffff">
			<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/icons/ms-icon-144x144.png">
			<meta name="theme-color" content="#ffffff">
		<?php endif; ?>
		
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>
	<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>

		<!-- #page -->
		<div id="page">

			<!-- header -->
			<header class="header clear">

                <nav class="navbar fixed-top">

					<div id="navbarHeader" class="container-fluid">

						<div class="col-lg-2 offset-lg-5 p-lg-0 col-logo text-center">
							<!-- Your site title as branding in the menu -->
							<?php if ( ! has_custom_logo() ) {
									$logo_extra_class = '';
									if(is_front_page() && (get_field('home_big_logo') || get_field('home_hide_logo_on_first_section')) ) {
										$logo_extra_class = 'hide_logo_effect';
									}
								?>

								<a id="siteLogo" class="navbar-brand mx-auto d-block <?=$logo_extra_class?>" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a>

							<?php } else {
								the_custom_logo();
							} ?><!-- end custom logo -->
						</div>

						<div class="col-lg-5 p-lg-0 text-right">

							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>

						</div>
					</div>
					<!-- The WordPress Menu goes here -->
					<div id="navbarNavDropdown" class="navbar-collapse collapse">
						<?php wp_nav_menu(
							array(
								'theme_location'  => 'header-menu',
								'container'    	  => false,
								//'container_class' => 'collapse navbar-collapse',
								//'container_id'    => 'navbarNavDropdown',
								'menu_class'      => 'navbar-nav d-flex flex-column justify-content-center',
								'fallback_cb'     => '',
								'menu_id'         => 'main-menu',
								'depth'           => 2,
								'walker'          => new Codigo_WP_Bootstrap_Navwalker(),
							)
						); ?>

							<div class="menu-register-menu d-flex flex-column justify-content-center">
								<?php wp_nav_menu(
									array(
										'theme_location'  => 'extra-menu',
										'container_class' => '',
										'container_id'    => '',
										'menu_class'      => '',
										'fallback_cb'     => '',
										'menu_id'         => '',
										'depth'           => 2,
										'walker'          => new Codigo_WP_Bootstrap_Navwalker(),
									)
								); ?>
							</div>
					</div>

                    <!-- /.container -->
                </nav>
				<style>
					nav.navbar {
						background-color: <?php echo (get_field('header_background_color','option') ? get_field('header_background_color','option') : "#4E5780") ?>;
					}
					nav.navbar .nav-link {
						color: <?php echo (get_field('header_color','option') ? get_field('header_color','option') : "#F89ABA") ?>;
					}
					nav.navbar .nav-link:hover, nav.navbar .nav-link:focus {
						color: <?php echo (get_field('header_color','option') ? get_field('header_color','option') : "#F89ABA") ?>;
					}
					nav.navbar .menu-register-menu .nav-link {
						border-color: <?php echo (get_field('header_color','option') ? get_field('header_color','option') : "#F89ABA") ?>;
						background-color: <?php echo (get_field('header_background_color','option') ? get_field('header_background_color','option') : "#4E5780") ?>;
						color: <?php echo (get_field('header_color','option') ? get_field('header_color','option') : "#F89ABA") ?>;
					}
					nav.navbar .menu-register-menu .nav-link:hover, nav.navbar .menu-register-menu .nav-link:focus {
						border-color: <?php echo (get_field('header_background_color','option') ? get_field('header_background_color','option') : "#4E5780") ?>;
						background-color: <?php echo (get_field('header_color','option') ? get_field('header_color','option') : "#F89ABA") ?>;
						color: <?php echo (get_field('header_background_color','option') ? get_field('header_background_color','option') : "#4E5780") ?>;
					}
				</style>


			</header>
			<!-- /header -->
