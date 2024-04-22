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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'telemedicinewp' );

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
define( 'AUTH_KEY',         'G#o36p60A;$y<f:@s+}4TS/e``7[6Z|I]xG]a{k7m}f/ia;mfIe63sf~Lt@_!19t' );
define( 'SECURE_AUTH_KEY',  'q9Bo){C=gj2K#<ET[4dcIAdH4DxuAG)uSQnw8)]Q&V(6xl26h<6pZyW<I=I].oyc' );
define( 'LOGGED_IN_KEY',    '(,0~ }Xo|K1jZ)YvVx&9Yn(p=&r2W4?P3t6q4TwK^$?.mjMByFj:|Of;:_%)dLGY' );
define( 'NONCE_KEY',        ',^-@,H:?JTz!$]$%;l!8Nn)_%w0@}hOwd10ad6xZiou+6q2ZPR>5mn2[QGG2AH`e' );
define( 'AUTH_SALT',        '90btyfc0(h-u$W$C}B^#BR?e&jEkt<7vtVCJ&Q[[S6XVhe=aXp=+l$;P}@Nv[iOw' );
define( 'SECURE_AUTH_SALT', ',|u;@I7%GcF2|nm|T0%75Hhl_&GcVd|ic_NEkMcm3@L^,~1/y5T3xI_SsCUix=Km' );
define( 'LOGGED_IN_SALT',   '+,.|F%18{c_s*d~N-bcw.jC>w7bv6!GXB|^;$evTz>3IGd)-`J.ugVr/XhY>f8zM' );
define( 'NONCE_SALT',       'xU+<u@G_!lL3o+$fEJ5niAd*L0/|G+<lf+oIqT<zHa3sA>NORw=+Xtk^g#PML&I.' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
