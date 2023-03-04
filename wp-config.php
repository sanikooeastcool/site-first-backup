<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'FORCE_SSL_ADMIN', true ); // Redirect All HTTP Page Requests to HTTPS - Security > Settings > Enforce SSL
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'WP_CACHE', false ); // Added by WP Rocket

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'eastcoo2_maindb' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']3Qii?gf2sRXrog:T O5Q?9#aB4R>{lR!flrd8xffOgw9` Ob}FAZ.bL]5sH$5(&' );
define( 'SECURE_AUTH_KEY',  '7,n[@phq.i#hu]HQ3ctVO}*w6|t?jm9AA/12+0?2Vp0-u2U<H1 2 SqWGZJ?`jl=' );
define( 'LOGGED_IN_KEY',    'UcR)TqV<H1u3D!TAy7}[<SCCt?1GFb:IkcG]]<aIS.*Yp*;lEhUW-_.6A(xK[Bi4' );
define( 'NONCE_KEY',        '-t@?i!#j$>(Y87YwOkwKdi3$+os=?ZD=*k`}$OTj2m4gQP60c.rx!;(Eav&Kb,;N' );
define( 'AUTH_SALT',        'y|K,Z}_GUYM}lJG-4jLgI9!CGi$v._eZ80quN2kD{`_4LYr,{56p?hpp#k{]CPq2' );
define( 'SECURE_AUTH_SALT', '<hm>F#&In@]5o`H<Fc,GzYaGo}<PD_1Ij}]530es|XGy@M@+fr9O7`{B5?+7M!QI' );
define( 'LOGGED_IN_SALT',   '8-h#wU&?slYoARrfS c#}WV0TK`84KdbH5b&eW#C7h[xhKvuE;N.jNw{fuKgXnKC' );
define( 'NONCE_SALT',       '3hw4H+bH;ReA9/~TE^S%h`Z?uP|gH<r##Q8JoEkr~IL8y@o{YM/D[-gLZF#s|-vk' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'lqh8m191r4_';

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
define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
