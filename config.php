<?php
/**
 * Configuration file
 *
 * @return		constant
 * @author		Aby Dahana
 * @profile		abydahana.github.io
 *
 * Property of DWITRI Media
 * www.dwitrimedia.com
 */

/**
 * Set the default timezone
 */
date_default_timezone_set('Asia/Jakarta');

/**
 * Define the software version
 */
define('SOFTWARE_VERSION', '2.1.9');

/**
 * Set the default site url
 */
define('BASE_URL', ((empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off') ? 'http' : 'https') . '://'. $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

/**
 * Set the default index page (index.php) if any
 */
define('INDEX_PAGE', null);

/**
 * Set the application mode
 */
define('DEMO_MODE', false);

/**
 * Set the default SALT
 */
define('SALT', 'www.dwitrimedia.com');

/**
 * Default database connection
 */
define('DB_DSN', '');
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_PORT', '');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'perencanaan_penganggaran__2021');
define('DB_DEBUG', true);

/**
 * Cookie prefix
 */
define('COOKIE_PREFIX', 'perencanaan_penganggaran_2021_');

/**
 * Set the assets path
 * NO END TRAILING SLASH!
 */
define('CODEIGNITER_PATH', 'vendor/codeigniter');

/**
 * Set the assets path
 * NO END TRAILING SLASH!
 */
define('THEME_PATH', 'themes');

/**
 * Set the assets path
 * NO END TRAILING SLASH!
 */
define('ASSET_PATH', 'public/assets');

/**
 * Set the log path. Make it secret :)
 * NO END TRAILING SLASH!
 */
define('LOG_PATH', 'uploads/logs');

/**
 * Upload config
 */
define('UPLOAD_PATH', 'uploads');
define('MAX_UPLOAD_SIZE', 2048);
define('DOCUMENT_FORMAT_ALLOWED', 'jpg|jpeg|gif|png|pdf|xls|xlsx|doc|docx|csv');
define('IMAGE_FORMAT_ALLOWED', 'jpg|jpeg|gif|png');
define('IMAGE_DIMENSION', 600);
define('THUMBNAIL_DIMENSION', 250);
define('ICON_DIMENSION', 80);

/**
 * PDF configuration
 */
define('PDF_LIB', 'mpdf');
define('PDF_BINARY_PATH', 'xvfb-run wkhtmltopdf'); // centos (used wkhtmltopdf as PDF_LIB)
//define('PDF_BINARY_PATH', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'); // windows (used wkhtmltopdf as PDF_LIB)