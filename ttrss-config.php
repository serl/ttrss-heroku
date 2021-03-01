<?php
	/*
		This file can be used to customize global defaults if environment method is not available (i.e. no Docker).

		Use the following syntax to override defaults (options are declared in classes/config.php, prefixed by TTRSS_):

		putenv('TTRSS_DB_HOST=myserver')
		putenv('TTRSS_SELF_URL_PATH=http://example.com/tt-rss')

		Plugin-required constants also go here, using define():

		define('LEGACY_CONSTANT', 'value');

		etc.

		See this page for more information: https://tt-rss.org/wiki/GlobalConfig
	*/

	function putenv_default($name, $default_value) {
		$var_name = "TTRSS_$name";
		if (!getenv($var_name)) {
			putenv("$var_name=$default_value");
		}
	}

	// *******************************************
	// *** Database configuration (important!) ***
	// *******************************************

	$db_components = parse_url(getenv("DATABASE_URL"));

	if ($db_components['scheme'] != 'postgres')
		die("Wrong database url\n");

	putenv_default('DB_TYPE', "pgsql");
	putenv_default('DB_HOST', $db_components['host']);
	putenv_default('DB_USER', $db_components['user']);
	putenv_default('DB_NAME', substr($db_components['path'], 1));
	putenv_default('DB_PASS', $db_components['pass']);
	putenv_default('DB_PORT', $db_components['port']);

	// ***********************************
	// *** Basic settings (important!) ***
	// ***********************************

	putenv_default('SELF_URL_PATH', 'https://'.$_SERVER['SERVER_NAME'].'/');

	// *****************************
	// *** Files and directories ***
	// *****************************

	putenv_default('PHP_EXECUTABLE', '/app/.heroku/php/bin/php');

	// *********************
	// *** Feed settings ***
	// *********************

	putenv_default('FORCE_ARTICLE_PURGE', 5);

	// ***************************************
	// *** Other settings (less important) ***
	// ***************************************

	putenv_default('LOG_DESTINATION', '');
