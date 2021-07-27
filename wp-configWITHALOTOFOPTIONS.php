 <?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'database');

/** MySQL database username */
define('DB_USER', 'username');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/* Impose the site url */
define('WP_SITEURL', 'http://1093127983.test.prositehosting.co.uk');
define('WP_HOME', 'http://1093127983.test.prositehosting.co.uk');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&AW/7DmV}]IQ|bh?IJ>gxS|E7:(&9*W>stUq2>2m}aBTWs!U?JEwHB?V&$DL!M0L');
define('SECURE_AUTH_KEY',  'Q {xu3,hQ>[e/ -tX1RaVGPqHY8)%^.F]q=>[9~i=I&jP4D{p-kyR&/W8U]TKkH)');
define('LOGGED_IN_KEY',    '~8IQ9C_svF$_ql>#kz$~$r_Rn.@cb#rJl7Ew[se?V{)IkH{kA9U)B}Qq w+1QCbD');
define('NONCE_KEY',        'Lf?`OcpJ25=?O!{<J>k4xq-R5GVrx}^m3<^!w=?U0+,rhur`]9:V9q@b;).7Kvwr');
define('AUTH_SALT',        '$V##89y!tBC4s@VG*aolXvzCa]60;/AOSjfd7h|;AlV2c[}{=-bHYY9>([HKCkY7');
define('SECURE_AUTH_SALT', 'Iu&HoZ ]4-K>pMt%c=u}yf!gCpj)AK%?(,:O/v*Qh,.Hr$9]0UJ?o/-EDwH.MvWf');
define('LOGGED_IN_SALT',   '7x]q7^Y2tQ0Jh>~t$8QahKEc1 z6^Cj/,TX4Er,?SeJ2M<_;_Gf6;4`x7xqs|^86');
define('NONCE_SALT',       '|RVjOIs<o.:RqSe,*^5@asye[ZO_(QQPipcKB]I5pX[u(VhME,/cE::XeO,TfqXV');

/**#@-*/

define('G_MAP_KEY', 'googlekey');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Create a error log file */
ini_set('log_errors',TRUE);
ini_set('error_reporting', E_ALL);
ini_set('error_log', dirname(__FILE__) . '/error.log');

/*************
 * 
 * WRITE ON LOGS
 *

if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}

write_log('THIS IS THE START OF MY CUSTOM DEBUG');
//i can log data like objects
write_log($whatever_you_want_to_log);

*************/


/*************
 * 
 * SHOW ERRORS ON LOGS BUT NOT DISPLAY THEM ON BROWSER
 *

define('WP_DEBUG', true);   
define( 'WP_DEBUG_LOG', true ); //wp-content/debug.log

// Disable display of errors and warnings 
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors',0);

*************/


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



define( 'DISALLOW_FILE_EDIT', true ); //disables file editor
define( 'DISALLOW_FILE_MODS', true ); //disables both file editor and installer (plugin and themes)






