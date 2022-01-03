<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use App\Controllers\Blog;
use App\Models\BlogModel;
use CodeIgniter\Session\Session;

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
$routes->setPrioritize();
//$routes->setTranslateURIDashes(true);
/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

//user routes
$routes->get('user/register', 'User/Register::index');
$routes->post('user/register/save', 'User/Register::save');
$routes->get('user/register/verify_email/(any)$1', 'User/Register::verify_email');
$routes->get('user/login', 'User/Login::index');
$routes->post('user/login/auth', 'User/Login::auth');
$routes->get('user/account', 'User/User::account');
$routes->post('user/account/update', 'User/User::update');
$routes->get('user/settings', 'User/User::settings');
$routes->get('user/logout', 'User/Login::logout');
$routes->post('user/tokensignin', 'User/Login::tokensignin');


//betting-calculator routes
$routes->get('betting-system/(:num)/(:any)/add', 'BettingCalculator\BettingCalculator::add/$1/$2', ['filter' => 'admin-auth']);
$routes->get('betting-system/(:num)/(:any)/pull-externally', 'BettingCalculator\BettingCalculator::pull_externally/$1/$2', ['filter' => 'admin-auth']);
$routes->get('betting-system/(:num)/(:any)/edit/(:num)', 'BettingCalculator\BettingCalculator::edit/$1/$2/$3', ['filter' => 'admin-auth']);
$routes->get('betting-system/(:num)/(:any)/delete/(:num)', 'BettingCalculator\BettingCalculator::delete/$1/$2/$3', ['filter' => 'admin-auth']);
$routes->get('betting-system/(:num)/(:any)/delete/(all)/(:num)', 'BettingCalculator\BettingCalculator::delete/$1/$2/$3/$4', ['filter' => 'admin-auth']);
$routes->get('betting-system/(:num)/(:any)', 'BettingCalculator\BettingCalculator::system/$1/$2');
$routes->get('/betting-system', 'BettingCalculator\BettingCalculator::index');
$routes->get('/', 'BettingCalculator\BettingCalculator::index');
$routes->get('betting-/system/(:num)', function (){
    return redirect()->route('/');
});
$routes->post('/save/(:num)', 'BettingCalculator\BettingCalculator::save/$1');
$routes->post('/save', 'BettingCalculator\BettingCalculator::save');
$routes->post('/pull', 'BettingCalculator\BettingCalculator::pull');
$routes->post('/see-how-much', 'BettingCalculator\BettingCalculator::see_how_much');


//admin routes
$routes->get('admin', 'Admin\Admin::index');
$routes->add('admin/posts/', 'Admin\Posts::index/$1');


//blog routes
$routes->get('blog/(:segment)', 'Blog::view/$1');
$routes->get('blog', 'Blog::index');
$routes->add('(.*)', function($slug){
    $model = new BlogModel();

    $data = $model->where('slug', $slug)->first();
    if (!empty($data)){
        $c = new Blog();
        return $c->view($slug);
    }else{
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

}, ['priority' => 1020]);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
