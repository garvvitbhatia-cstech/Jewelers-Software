<?php

/**

 * Routes configuration.

 *

 * In this file, you set up routes to your controllers and their actions.

 * Routes are very important mechanism that allows you to freely connect

 * different URLs to chosen controllers and their actions (functions).

 *

 * It's loaded within the context of `Application::routes()` method which

 * receives a `RouteBuilder` instance `$routes` as method argument.

 *

 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)

 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)

 *

 * Licensed under The MIT License

 * For full copyright and license information, please see the LICENSE.txt

 * Redistributions of files must retain the above copyright notice.

 *

 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)

 * @link          https://cakephp.org CakePHP(tm) Project

 * @license       https://opensource.org/licenses/mit-license.php MIT License

 */



use Cake\Http\Middleware\CsrfProtectionMiddleware;

use Cake\Routing\Route\DashedRoute;

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;



/*

 * The default class to use for all routes

 *

 * The following route classes are supplied with CakePHP and are appropriate

 * to set as the default:

 *

 * - Route

 * - InflectedRoute

 * - DashedRoute

 *

 * If no call is made to `Router::defaultRouteClass()`, the class used is

 * `Route` (`Cake\Routing\Route\Route`)

 *

 * Note that `Route` does not do any inflections on URLs which will result in

 * inconsistently cased URLs when used with `:plugin`, `:controller` and

 * `:action` markers.

 */

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->setRouteClass(DashedRoute::class);



$routes->scope('/', function (RouteBuilder $builder) {

    // Register scoped middleware for in scopes.

    $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([

        'httpOnly' => true,

    ]));

    /*

     * Apply a middleware to the current route scope.

     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`

     */

    $builder->applyMiddleware('csrf');



    /*

     * Here, we are connecting '/' (base path) to a controller called 'Pages',

     * its action called 'display', and we pass a param to select the view file

     * to use (in this case, templates/Pages/home.php)...

     */

    //$builder->connect('/', [CONTROLLER => 'Pages', ACTION => 'display', 'home']);
    $builder->connect('/', [CONTROLLER => ADMINS, ACTION => INDEX]);
    $builder->connect(ADMIN_FOLDER.'my-account/', [CONTROLLER => ADMINS, ACTION => 'myAccount']);
    $builder->connect(ADMIN_FOLDER.'site-configuration/', [CONTROLLER => ADMINS, ACTION => 'siteConfiguration']);
    $builder->connect(ADMIN_FOLDER.'change-password/', [CONTROLLER => ADMINS, ACTION => 'changePassword']);
    $builder->connect(ADMIN_FOLDER.'forgot-password/', [CONTROLLER => ADMINS, ACTION => 'forgotPassword']);
	
	#inner page managent
    $builder->connect(ADMIN_FOLDER.'inner-page-management/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'innerPages']);
    $builder->connect(ADMIN_FOLDER.'inner-pages-filter/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'innerPagesFilter']);
	$builder->connect(ADMIN_FOLDER.'edit-inner-page/*', [CONTROLLER => CMSMANAGEMENT, ACTION => 'editInnerPage']);
	
	#cms managent
	$builder->connect(ADMIN_FOLDER.'cms/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'cms']);
    $builder->connect(ADMIN_FOLDER.'cms-filter/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'cmsFilter']);
	$builder->connect(ADMIN_FOLDER.'add-cms/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'addCms']);
	$builder->connect(ADMIN_FOLDER.'edit-cms/*', [CONTROLLER => CMSMANAGEMENT, ACTION => 'editCms']);
	
	#header navigation managent
	$builder->connect(ADMIN_FOLDER.'header-navigations/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'headerNavigations']);
    $builder->connect(ADMIN_FOLDER.'header-navigation-filter/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'headerNavigationFilter']);
	$builder->connect(ADMIN_FOLDER.'add-header-navigation/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'addHeaderNavigation']);
	$builder->connect(ADMIN_FOLDER.'edit-header-navigation/*', [CONTROLLER => CMSMANAGEMENT, ACTION => 'editHeaderNavigation']);
	
	#header navigation managent
	$builder->connect(ADMIN_FOLDER.'footer-navigations/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'footerNavigations']);
    $builder->connect(ADMIN_FOLDER.'footer-navigation-filter/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'footerNavigationFilter']);
	$builder->connect(ADMIN_FOLDER.'add-footer-navigation/', [CONTROLLER => CMSMANAGEMENT, ACTION => 'addFooterNavigation']);
	$builder->connect(ADMIN_FOLDER.'edit-footer-navigation/*', [CONTROLLER => CMSMANAGEMENT, ACTION => 'editFooterNavigation']);

    #set static content routing
    $builder->connect(ADMIN_FOLDER.'static-content-management/', [CONTROLLER => STATIC_CONTENT_MANAGEMENT, ACTION => 'staticContentManagement']);
    $builder->connect(ADMIN_FOLDER.'static-content-filter/', [CONTROLLER => STATIC_CONTENT_MANAGEMENT, ACTION => 'staticContentFilter']);
    $builder->connect(ADMIN_FOLDER.'edit-static-content/*', [CONTROLLER => STATIC_CONTENT_MANAGEMENT, ACTION => 'editStaticContent']);

    #Weight types
	$builder->connect(ADMIN_FOLDER.'weight-types/', [CONTROLLER => WEIGHTS, ACTION => 'weightTypes']);
	$builder->connect(ADMIN_FOLDER.'weight-type-filter/', [CONTROLLER => WEIGHTS, ACTION => 'weightTypeFilter']);
	$builder->connect(ADMIN_FOLDER.'add-weight-type/', [CONTROLLER => WEIGHTS, ACTION => 'addWeightType']);
	$builder->connect(ADMIN_FOLDER.'edit-weight-type/*', [CONTROLLER => WEIGHTS, ACTION => 'editWeightType']);

    #Weight management
	$builder->connect(ADMIN_FOLDER.'weight-management/', [CONTROLLER => WEIGHTS, ACTION => 'weights']);
	$builder->connect(ADMIN_FOLDER.'weight-filter/', [CONTROLLER => WEIGHTS, ACTION => 'weightFilter']);
	$builder->connect(ADMIN_FOLDER.'add-weight/', [CONTROLLER => WEIGHTS, ACTION => 'addWeight']);
	$builder->connect(ADMIN_FOLDER.'edit-weight/*', [CONTROLLER => WEIGHTS, ACTION => 'editWeight']);

	#Distance management
	$builder->connect(ADMIN_FOLDER.'distance-management/', [CONTROLLER => WEIGHTS, ACTION => 'distances']);
	$builder->connect(ADMIN_FOLDER.'distance-filter/', [CONTROLLER => WEIGHTS, ACTION => 'distanceFilter']);
	$builder->connect(ADMIN_FOLDER.'add-distance/', [CONTROLLER => WEIGHTS, ACTION => 'addDistance']);
	$builder->connect(ADMIN_FOLDER.'edit-distance/*', [CONTROLLER => WEIGHTS, ACTION => 'editDistance']);
	
	#service management
	$builder->connect(ADMIN_FOLDER.'categories/', [CONTROLLER => DROPDOWNS, ACTION => 'categories']);
	$builder->connect(ADMIN_FOLDER.'categories-filter/', [CONTROLLER => DROPDOWNS, ACTION => 'categoriesFilter']);
	$builder->connect(ADMIN_FOLDER.'add-category/', [CONTROLLER => DROPDOWNS, ACTION => 'addCategory']);
	$builder->connect(ADMIN_FOLDER.'edit-category/*', [CONTROLLER => DROPDOWNS, ACTION => 'editCategory']);
	
	#service person management
	$builder->connect(ADMIN_FOLDER.'products/', [CONTROLLER => DROPDOWNS, ACTION => 'products']);
	$builder->connect(ADMIN_FOLDER.'products-filter/', [CONTROLLER => DROPDOWNS, ACTION => 'productsFilter']);
	$builder->connect(ADMIN_FOLDER.'add-product/', [CONTROLLER => DROPDOWNS, ACTION => 'addProduct']);
	$builder->connect(ADMIN_FOLDER.'edit-product/*', [CONTROLLER => DROPDOWNS, ACTION => 'editProduct']);
	
	#sales manager
	$builder->connect(ADMIN_FOLDER.'sales-manager/', [CONTROLLER => ECOMMERCE, ACTION => 'salesManager']);
	$builder->connect(ADMIN_FOLDER.'sales-manager-filter/', [CONTROLLER => ECOMMERCE, ACTION => 'salesManagerFilter']);
	$builder->connect(ADMIN_FOLDER.'add-invoice/', [CONTROLLER => ECOMMERCE, ACTION => 'addInvoice']);
	$builder->connect(ADMIN_FOLDER.'edit-invoice/*', [CONTROLLER => ECOMMERCE, ACTION => 'editInvoice']);
	$builder->connect(ADMIN_FOLDER.'view-invoice/*', [CONTROLLER => ECOMMERCE, ACTION => 'viewInvoice']);
	$builder->connect(ADMIN_FOLDER.'print-invoice/*', [CONTROLLER => ECOMMERCE, ACTION => 'printInvoice']);
	
	#sales manager
	$builder->connect(ADMIN_FOLDER.'order-manager/', [CONTROLLER => ECOMMERCE, ACTION => 'orders']);
	$builder->connect(ADMIN_FOLDER.'order-manager-filter/', [CONTROLLER => ECOMMERCE, ACTION => 'ordersFilter']);
	$builder->connect(ADMIN_FOLDER.'add-order/', [CONTROLLER => ECOMMERCE, ACTION => 'addOrder']);
	$builder->connect(ADMIN_FOLDER.'edit-order/*', [CONTROLLER => ECOMMERCE, ACTION => 'editOrder']);
	
	#country management
	$builder->connect(ADMIN_FOLDER.'country-management/', [CONTROLLER => LOCATIONS, ACTION => 'countries']);
	$builder->connect(ADMIN_FOLDER.'countries-filter/', [CONTROLLER => LOCATIONS, ACTION => 'countriesFilter']);
	$builder->connect(ADMIN_FOLDER.'add-country/', [CONTROLLER => LOCATIONS, ACTION => 'addCountry']);
	$builder->connect(ADMIN_FOLDER.'edit-country/*', [CONTROLLER => LOCATIONS, ACTION => 'editCountry']);

	#state management
	$builder->connect(ADMIN_FOLDER.'state-management/', [CONTROLLER => LOCATIONS, ACTION => 'states']);
	$builder->connect(ADMIN_FOLDER.'states-filter/', [CONTROLLER => LOCATIONS, ACTION => 'statesFilter']);
	$builder->connect(ADMIN_FOLDER.'add-state/', [CONTROLLER => LOCATIONS, ACTION => 'addState']);
	$builder->connect(ADMIN_FOLDER.'edit-state/*', [CONTROLLER => LOCATIONS, ACTION => 'editState']);

	#city management
	$builder->connect(ADMIN_FOLDER.'city-management/', [CONTROLLER => LOCATIONS, ACTION => 'cities']);
	$builder->connect(ADMIN_FOLDER.'cities-filter/', [CONTROLLER => LOCATIONS, ACTION => 'citiesFilter']);
	$builder->connect(ADMIN_FOLDER.'add-city/', [CONTROLLER => LOCATIONS, ACTION => 'addCity']);
	$builder->connect(ADMIN_FOLDER.'edit-city/*', [CONTROLLER => LOCATIONS, ACTION => 'editCity']);

	#user management
	$builder->connect(ADMIN_FOLDER.'user-management/', [CONTROLLER => 'Users', ACTION => 'index']);
	$builder->connect(ADMIN_FOLDER.'users-filter/', [CONTROLLER => 'Users', ACTION => 'usersFilter']);
	$builder->connect(ADMIN_FOLDER.'add-user/', [CONTROLLER => 'Users', ACTION => 'addUser']);
	$builder->connect(ADMIN_FOLDER.'edit-user/*', [CONTROLLER => 'Users', ACTION => 'editUser']);
	$builder->connect(ADMIN_FOLDER.'transaction-history/*', [CONTROLLER => 'Users', ACTION => 'walletHistory']);
	$builder->connect(ADMIN_FOLDER.'transaction-history-filter/*', [CONTROLLER => 'Users', ACTION => 'walletHistoryFilter']);
	
	#customer manager
	$builder->connect(ADMIN_FOLDER.'customer-management/', [CONTROLLER => 'Customers', ACTION => 'index']);
	$builder->connect(ADMIN_FOLDER.'customers-filter/*', [CONTROLLER => 'Customers', ACTION => 'customersFilter']);
	$builder->connect(ADMIN_FOLDER.'add-customer/', [CONTROLLER => 'Customers', ACTION => 'addCustomer']);
	$builder->connect(ADMIN_FOLDER.'edit-customer/*', [CONTROLLER => 'Customers', ACTION => 'editCustomer']);
	$builder->connect(ADMIN_FOLDER.'view-customer/*', [CONTROLLER => 'Customers', ACTION => 'viewCustomer']);

	#contact-us management
	$builder->connect(ADMIN_FOLDER.'contact-us/', [CONTROLLER => 'Users', ACTION => 'contactUs']);
	$builder->connect(ADMIN_FOLDER.'contact-us-filter/', [CONTROLLER => 'Users', ACTION => 'contactUsFilter']);
	$builder->connect(ADMIN_FOLDER.'view-contact-us/*', [CONTROLLER => 'Users', ACTION => 'viewContactUs']);

	
	#travellar management
	$builder->connect(ADMIN_FOLDER.'transporters-management/', [CONTROLLER => 'Users', ACTION => 'transporters']);
	$builder->connect(ADMIN_FOLDER.'transporters-filter/', [CONTROLLER => 'Users', ACTION => 'transportersFilter']);
	$builder->connect(ADMIN_FOLDER.'add-transporter/', [CONTROLLER => 'Users', ACTION => 'addTransporter']);
	$builder->connect(ADMIN_FOLDER.'edit-transporter/*', [CONTROLLER => 'Users', ACTION => 'editTransporter']);
	
	#vehicle management
	$builder->connect(ADMIN_FOLDER.'vehicles-management/', [CONTROLLER => 'Users', ACTION => 'vehicles']);
	$builder->connect(ADMIN_FOLDER.'vehicles-filter/', [CONTROLLER => 'Users', ACTION => 'vehiclesFilter']);
	$builder->connect(ADMIN_FOLDER.'add-vehicle/', [CONTROLLER => 'Users', ACTION => 'addVehicle']);
	$builder->connect(ADMIN_FOLDER.'edit-vehicle/*', [CONTROLLER => 'Users', ACTION => 'editVehicle']);
	
	#testimonials management
	$builder->connect(ADMIN_FOLDER.'testimonials/', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'testimonials']);
	$builder->connect(ADMIN_FOLDER.'testimonials-filter/', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'testimonialsFilter']);
	$builder->connect(ADMIN_FOLDER.'add-testimonial/', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'addTestimonial']);
	$builder->connect(ADMIN_FOLDER.'view-testimonial/*', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'viewTestimonial']);
	$builder->connect(ADMIN_FOLDER.'edit-testimonial/*', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'editTestimonial']);

	#agent managent
    $builder->connect(ADMIN_FOLDER.'agent-management/', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'agents']);
    $builder->connect(ADMIN_FOLDER.'agents-filter/', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'agentsFilter']);
    $builder->connect(ADMIN_FOLDER.'add-agent/*', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'addAgent']);
	$builder->connect(ADMIN_FOLDER.'edit-agent/*', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'editAgent']);
	$builder->connect(ADMIN_FOLDER.'permissions/*', [CONTROLLER => AGENT_MANAGEMENT, ACTION => 'permissions']);
	
	#order management
	$builder->connect(ADMIN_FOLDER.'order-management/', [CONTROLLER => ECOMMERCE, ACTION => 'orders']);
	$builder->connect(ADMIN_FOLDER.'orders-filter/', [CONTROLLER => ECOMMERCE, ACTION => 'ordersFilter']);
	$builder->connect(ADMIN_FOLDER.'view-order/*', [CONTROLLER => ECOMMERCE, ACTION => 'viewOrder']);
	
	#banner management
	$builder->connect(ADMIN_FOLDER.'banner-management/', [CONTROLLER => 'Banners', ACTION => 'index']);
	
	#banner management
	$builder->connect(ADMIN_FOLDER.'barcode/*', [CONTROLLER => 'Barcode', ACTION => 'barcode']);
	$builder->connect(ADMIN_FOLDER.'barcodes/', [CONTROLLER => 'Barcode', ACTION => 'barcodes']);
	$builder->connect(ADMIN_FOLDER.'barcodes-filter/', [CONTROLLER => 'Barcode', ACTION => 'barcodesFilter']);
	$builder->connect(ADMIN_FOLDER.'generate-barcode/', [CONTROLLER => 'Barcode', ACTION => 'generateProductBarcode']);
		
	#report sales items
	$builder->connect(ADMIN_FOLDER.'sales-item-report/', [CONTROLLER => 'Reports', ACTION => 'salesItemsReport']);
	$builder->connect(ADMIN_FOLDER.'sales-item-report-filter/', [CONTROLLER => 'Reports', ACTION => 'salesItemsReportFilter']);
	
	#report old gold
	$builder->connect(ADMIN_FOLDER.'old-gold-report/', [CONTROLLER => 'Reports', ACTION => 'oldGoldReport']);
	$builder->connect(ADMIN_FOLDER.'old-gold-report-filter/', [CONTROLLER => 'Reports', ACTION => 'oldGoldReportFilter']);
	
    /*

     * ...and connect the rest of 'Pages' controller's URLs.

     */

    $builder->connect('/pages/*', [CONTROLLER => 'Pages', ACTION => 'display']);

    $builder->fallbacks();

});

Router::prefix('webservice1',function(RouteBuilder $routes){
	$routes->fallbacks(DashedRoute::class);
});