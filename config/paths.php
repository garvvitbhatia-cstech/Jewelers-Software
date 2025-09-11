<?php

/**

 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)

 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)

 *

 * Licensed under The MIT License

 * Redistributions of files must retain the above copyright notice.

 *

 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)

 * @link          https://cakephp.org CakePHP(tm) Project

 * @since         3.0.0

 * @license       MIT License (https://opensource.org/licenses/mit-license.php)

 */



/*

 * Use the DS to separate the directories in other defines

 */

if (!defined('DS')) {

    define('DS', DIRECTORY_SEPARATOR);

}



/*

 * These defines should only be edited if you have cake installed in

 * a directory layout other than the way it is distributed.

 * When using custom settings be sure to use the DS and do not add a trailing DS.

 */



/*

 * The full path to the directory which holds "src", WITHOUT a trailing DS.

 */

define('ROOT', dirname(__DIR__));



/*

 * The actual directory name for the application directory. Normally

 * named 'src'.

 */

define('APP_DIR', 'src');



/*

 * Path to the application's directory.

 */

define('APP', ROOT . DS . APP_DIR . DS);



/*

 * Path to the config directory.

 */

define('CONFIG', ROOT . DS . 'config' . DS);



/*

 * File path to the webroot directory.

 *

 * To derive your webroot from your webserver change this to:

 *

 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`

 */

define('WWW_ROOT', ROOT . DS . 'webroot' . DS);



/*

 * Path to the tests directory.

 */

define('TESTS', ROOT . DS . 'tests' . DS);



/*

 * Path to the temporary files directory.

 */

define('TMP', ROOT . DS . 'tmp' . DS);



/*

 * Path to the logs directory.

 */

define('LOGS', ROOT . DS . 'logs' . DS);



/*

 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.

 */

define('CACHE', TMP . 'cache' . DS);



/**

 * Path to the resources directory.

 */

define('RESOURCES', ROOT . DS . 'resources' . DS);



/**

 * The absolute path to the "cake" directory, WITHOUT a trailing DS.

 *

 * CakePHP should always be installed with composer, so look there.

 */

define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');



/*

 * Path to the cake directory.

 */
define('SITEURL','http://localhost/brj/');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);

define('CAKE', CORE_PATH . 'src' . DS);

define('ADMIN_FOLDER', '/admins/');

define('SITE_TITLE','My Site');



define('CONTROLLER','controller');

define('PLUGIN','plugin');

define('ACTION','action');

define('INDEX','index');

define('AUTHADMIN','Auth.admin');

define('AUTHADMINID','Auth.admin.id');



define('ERROR','Error');

define('SUCCESS','Success');

define('MENDATORY','Mendatory');

define('IN_ACTIVE','inActive');



define('USERNAME','username');

define('PASSWORD','password');



define('COOKIE_USERNAME','cookieUsername');

define('COOKIE_PASS','cookiePassword');

define('COOKIE_REMINDER','cookieRemember');

define('REMEMBER','remember');

define('CHECK_EMPTY','_empty');

define('ID','id');

define('EXT','ext');

define('FLD_DATA','fld_data');

define('FOLDER','folder');

define('CROP_IMG','crop_img');

define('FILENAME','filename');

define('ADMINISTRATORS','Administrators');

define('USER_NAME','user_name');

define('MY_ACCOUNT','my-account');

define('ELEMENT','element');

define('ALERT_ERROR','error');

define('ALERT_SUCCESS','success');

define('OLD_IMAGE','oldImage');

define('ADMINS','Admins');

define('SETTINGS','Settings');

define('SITE_CONFIGRATION','site-configuration');

define('ADMIN_EMAIL','admin_email');

define('COMPANY_NAME','company_name');

define('BUSINESS_ADDRESS','business_address');

define('MOBILE','mobile');

define('VALIDATE','validate');

define('ADMIN_DASHBOARD_LAYOUT','admin/dashboard');

define('FOOTER_CONTENT','footer_content');

define('CHANGE_PASSWORD','change-password');

define('FIELD_CANT_BLANK','Fields cannot be blank.');

define('INTERNAL_ERROR','Something want to wrong, please try after sometime.');

define('EMAIL','email');

define('EMAIL_ADDRESS','email_address');

define('FORGOT_PASSWORD','forgot_password');

define('ADMIN','Admin');

define('SENDEMAILTO','sendEmailTo');

define('EMAIL_NOT_SEND','EmailNotSend');

define('STATIC_CONTENT_MANAGEMENT','StaticContentManagement');

define('STATIC_CONTENT_MANAGEMENT_URL','static-content-management');

define('EDIT_STATIC_CONTENT_URL','edit-static-content');

define('STATIC_CONTENT','StaticContent');

define('LIMIT','limit');

define('CONDITIONS','conditions');

define('ORDER','order');

define('STATUS','status');

define('TITLE','title');

define('NAME','name');

define('PAGENO','pageNo');

define('PAGE','page');

define('ASC','asc');

define('DESC','desc');

define('POSTDATA','postData');

define('LIKE','LIKE');

define('SECTION_NAME','section_name');

define('DESCRIPTIONS','descriptions');

define('EDIT_TOKEN','edit_token');

define('DATATOKEN','dataToken');

define('CURRENTSTATUS','currentStatus');

define('MODEL','model');

define('LOGO','logo');

define('AJAX','ajax');

define('PROFILE_IMAGE','profile_image');

define('COUNTRIES','Countries');

define('CITIES','Cities');

define('STATES','States');

define('ORDERING','ordering');

define('COUNTRYNAME','country_name');

define('COUNTRYCODE','country_code');

define('ZIPCODEFORMAT','zipcode_format');

define('PHONENOFORMAT','phone_no_format');

define('COUNTRYFLAG','country_flag');

define('FLAG_IMAGE','flag_image');

define('BANNERS','Banners');

define('BANNERPATH','img/banners/');

define('CREATED','created');

define('MODIFIED','modified');

define('UPDATE','update');

define('ADD','add');

define('STATEID','state_id');

define('ABBREVIATION','abbreviation');

define('USERS','Users');

define('PROFILE','profile');

define('COUNTRY_ID','country_id');

define('STATE','state');

define('CITY','city');

define('FIRST_NAME','first_name');

define('LAST_NAME','last_name');

define('EDITDATA','editData');

define('COUNTRYLIST','countryList');

define('AGENT_MANAGEMENT','agentsManagement');

define('LOCATIONS','Locations');

define('DROPDOWNS','Dropdowns');

define('AGENTS','Agents');

define('CONTACT','contact');
define('USER_TOKEN','user_token');
define('OTP','otp');
define('TYPE','type');
define('OTP_VERIFIED','otp_verifued');

define('CMSMANAGEMENT','CmsManagement');
define('DESCRIPTION','description');
define('HEADING','heading');
define('SUB_HEADING','sub_heading');
define('EDIT_HEADING','edit_heading');
define('EDIT_SUB_HEADING','edit_sub_heading');
define('BANNER_IMAGE','banner_image');
define('BANNER_STATUS','banner_status');
define('SEO_TITLE','seo_title');
define('SEO_DESCRIPTION','seo_description');
define('SEO_KEYWORDS','seo_keyword'); 
define('SEOTAGS','robot_tags'); 

define('ICON','icon');
define('CHECKUNIQUENAME','checkUniqueName');
define('PRODUCTS','Products');
define('CATEGORYLIST','CategoryList');
define('PRODUCTIMAGES','ProductImages');
define('INNERPAGES','InnerPages');
define('ECOMMERCE','Ecommerce');
define('CATEGORIES','Categories');
define('SERVICE_ID','service_id');
define('AGENT_ID','agent_id');
define('SERVICES','Services');
define('WEIGHTS','Weights');
define('WEIGHT','weight');