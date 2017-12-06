<?php

if(file_exists(dirname(__FILE__) . '/wp-db-local.php')) {
    include_once(dirname(__FILE__) . '/wp-db-local.php');
} else {
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    /** The name of the database for WordPress */
    define('DB_NAME', $_SERVER['DB_NAME']);

    /** MySQL database username */
    define('DB_USER', $_SERVER['DB_USER']);

    /** MySQL database password */
    define('DB_PASSWORD', $_SERVER['DB_PASS']);

    /** MySQL hostname */
    define('DB_HOST', $_SERVER['DB_HOST']);
}

if(isset($_SERVER['SITEURL'])) {
    define('WP_HOME','http://'. $_SERVER['SITEURL']);
    define('WP_SITEURL','http://'. $_SERVER['SITEURL']);
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'jE.hkpnK^0+=ds*Dcz7;|XfvWdYZN&8h>;ycbXWbr|;}l<S-IfMJ-03}2l$x,@Sa');
define('SECURE_AUTH_KEY',  'y~+`|Xi_J+:@vK*iE7M9UA/B_| Mr^`hX%fX+O|!D:erIwzKh/|h egtQncuk/#X');
define('LOGGED_IN_KEY',    'T{~|E,:P6Vh-^@*^m pQ{Lw<V0IU{6oAbIO8N-)1l.P|q;fX-VhgK7Gn-+On5n:9');
define('NONCE_KEY',        '$}mCkC!gjwujVs+&C1=,wF98imK>EM]m%1]U${8M/t5})9Pi*|&$nb4WQOOg`|LV');
define('AUTH_SALT',        '/79a7qh#|P,B-H<KrzL-MAI*:^OAVP$ncVXI,p<+z]2tYj=j54{9]5|H2C&.2yLa');
define('SECURE_AUTH_SALT', 'lh|p[jQ-@Jf~cE)&[iZ}blTT[T^(ys[9 >nJ qS]LDP|@v}lk$Z8LY`l@A]^>yr!');
define('LOGGED_IN_SALT',   '(A./%aKv.,utQ1S5#gE|Qxi^3{M^|KVtGNmC}ot[&:2;h@$.KT|aPBLc0|]J1gaH');
define('NONCE_SALT',       'T;i(5N:UZdd,)-wm:0Kg^Or.}+dQ-w0DEfbr*yz^MJ2|AU7dc|B. zL[/>$[1D!7');

/** Enable W3 Total Cache */
define('WP_CACHE', false); // Added by W3 Total Cache


$table_prefix = 'wp_';

define('WPLANG', '');

define( 'WP_DEBUG', false );
define( 'DBI_AWS_ACCESS_KEY_ID', '' );
define( 'DBI_AWS_SECRET_ACCESS_KEY', '' );
define('DISABLE_WP_CRON', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
