<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '80,5HP(zVr%e(je>W`!Ym+s#F*8,@pT0Is;O40vZdgl;P@@VZyN=}*1DIF9%_Eso' );
define( 'SECURE_AUTH_KEY',  '%d~vzdb&f*q0A *au|k3;~(@}7`0/9@!Ly8GBlU?X*7iy$>in%{!+ZU.#ghGo:Tt' );
define( 'LOGGED_IN_KEY',    '6;45Z:7P|dadB{~xexab cLJ)Ht#~b6xd4O3HC`ff.k6WA)@2pRVOUGi>mI7l9f4' );
define( 'NONCE_KEY',        '4nmX/z( AB.NwQ(f%s?N1e?m3K?]dK^6?fWxk:h;aj.sa@k+P=Z~EQ~$Zr~ysd[e' );
define( 'AUTH_SALT',        'zV0tPjk@&x;9tO}Z>KOX@EOSI1umx/6%CBwM+d<I,XS2UX>Q ,[EA`ovFcS+Ppk+' );
define( 'SECURE_AUTH_SALT', 'GTp1V5~7S@CJTY4]s!wCofxerLw.TqF@s?Ur3Rc3~_*`S<W{OUp,o 38u]!8s%vV' );
define( 'LOGGED_IN_SALT',   'd-k3M[)lf}aE{H%kw}Ng_g*(R*I[m.XD$-w! Y{D8iTtO%h)%~63Y$E;[Q|H*S ]' );
define( 'NONCE_SALT',       'M@CRG/Np`7y#xIEyf[AsR1h`48t7x%Ww`wvnaSp?Nc>_<qG${jYzTNs*}~ome6>$' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
