<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'testing');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Uh(#c:[2Q^&n[!dwb;)A2m1(rhpsNr;4Z-4gRF)Y1i~j `S]^u-#evVHu/2[@r/.');
define('SECURE_AUTH_KEY',  '<[p/`s; E`Ls,Dd ,2M=Epv{6*e&Dn0_n?h{CV<-yw2#WZm;tg#:([-#qWepr-vy');
define('LOGGED_IN_KEY',    '-1NdO6ny~3{=0,EIq8~G,cK^1?iPE9KCUv3%1ZT(uqQU VfSUXj_MICmgHp]I[:J');
define('NONCE_KEY',        'e}?sj5{iC5lv?n^HFt7C-Y~S3|>EGf=*-T8ES3 <O8-tocfuX6f4%[P!#oP(zr~Z');
define('AUTH_SALT',        '&K|wn0|0Lj$iwo~?PKbc@FTI=;z49[lr4r`OQFG(3EUss9Gg7&P-*:T+iO#szyEb');
define('SECURE_AUTH_SALT', 'sj!duY<^lqEv|lFKQB(W87<aNUH!5s_Du,g Oxlmy-@Y9F3JBzr`JdMk1w(>wK,v');
define('LOGGED_IN_SALT',   'V-_tAr[(MKP]]aeL_65iUsy+wKW-l?fzduLUp2-PCM-@-Pgy=Ua+@%(bT4{Rf![v');
define('NONCE_SALT',       ';qBn2H9;m)=Nl?c2(8k7(nl9/WtJP9HUmb>^0u+nB<h<ufJda7w(QEBhXcT~h7qS');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
