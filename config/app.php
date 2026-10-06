<?php
// ============================================================
//  config/app.php — konstanta global aplikasi
// ============================================================

define('APP_NAME',    'Creative Studio');
define('APP_VERSION', '1.0.0');
define('APP_ENV',     getenv('APP_ENV') ?: 'development'); // production | development
define('APP_DEBUG',   APP_ENV === 'development');
define('APP_URL',     getenv('APP_URL') ?: 'http://localhost/creative-agency/public');
define('APP_TIMEZONE','Asia/Jakarta');

date_default_timezone_set(APP_TIMEZONE);

// Path
define('ROOT_PATH',    dirname(__DIR__));
define('PUBLIC_PATH',  ROOT_PATH . '/public');
define('UPLOAD_PATH',  ROOT_PATH . '/uploads');
define('VIEW_PATH',    ROOT_PATH . '/app/views');

// Session
define('SESSION_NAME',     'ca_session');
define('SESSION_LIFETIME', 7200); // 2 jam

// Upload limits
define('MAX_UPLOAD_MB',    10);
define('ALLOWED_IMAGES',   ['jpg','jpeg','png','webp','gif']);
define('ALLOWED_DOCS',     ['pdf','doc','docx','xls','xlsx','pptx','zip']);

// Payment Gateway
define('MIDTRANS_SERVER_KEY', getenv('MIDTRANS_SERVER_KEY') ?: 'YOUR_SERVER_KEY');
define('MIDTRANS_CLIENT_KEY', getenv('MIDTRANS_CLIENT_KEY') ?: 'YOUR_CLIENT_KEY');
define('MIDTRANS_ENV',        getenv('MIDTRANS_ENV')        ?: 'sandbox'); // sandbox | production

define('XENDIT_SECRET_KEY',   getenv('XENDIT_SECRET_KEY')   ?: 'YOUR_XENDIT_KEY');
define('XENDIT_WEBHOOK_TOKEN', getenv('XENDIT_WEBHOOK_TOKEN') ?: 'YOUR_XENDIT_WEBHOOK_TOKEN');

// Mail
define('MAIL_FROM',      getenv('MAIL_FROM')      ?: 'no-reply@creativeagency.id');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: APP_NAME);

// Currency
define('CURRENCY', 'IDR');

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
