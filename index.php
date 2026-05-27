<?php
/**
 * InfoHub Platform
 * Main Entry Point
 */

// Start session
session_start();

// Define root path
define('ROOT_PATH', __DIR__);

// Load environment variables from .env if present
require_once ROOT_PATH . '/config/env.php';

// Load configuration
require_once ROOT_PATH . '/config/database.php';

// Load core classes
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Logger.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/GoogleAuth.php';

// Initialize router
$router = new Router();

// =====================================================
// AUTH ROUTES
// =====================================================
$router->route('auth/register', 'AuthController@register', 'GET');
$router->route('auth/register', 'AuthController@handleRegister', 'POST');
$router->route('auth/login', 'AuthController@login', 'GET');
$router->route('auth/login', 'AuthController@handleLogin', 'POST');
$router->route('auth/logout', 'AuthController@logout', 'GET');
$router->route('auth/google/login', 'AuthController@googleLogin', 'GET');
$router->route('auth/google/callback', 'AuthController@googleCallback', 'GET');
$router->route('auth/forgot-password', 'AuthController@forgotPassword', 'GET');
$router->route('auth/forgot-password', 'AuthController@handleForgotPassword', 'POST');
$router->route('auth/reset-password', 'AuthController@resetPassword', 'GET');
$router->route('auth/reset-password', 'AuthController@handleResetPassword', 'POST');

// =====================================================
// HOME & PUBLIC ROUTES
// =====================================================
$router->route('', 'HomeController@index', 'GET');
$router->route('home', 'HomeController@index', 'GET');

// =====================================================
// NEWS/POSTS ROUTES
// =====================================================
$router->route('news', 'NewsController@index', 'GET');
$router->route('news/{slug}', 'NewsController@show', 'GET');
$router->route('news/category/{slug}', 'NewsController@category', 'GET');
$router->route('news/search', 'NewsController@search', 'GET');
$router->route('news/newsletter', 'NewsController@newsletter', 'POST');
$router->route('news/trending', 'NewsController@trending', 'GET');
$router->route('news/featured', 'NewsController@featured', 'GET');
$router->route('news/bookmark/{id}', 'NewsController@bookmark', 'POST');
$router->route('news/bookmarks', 'NewsController@bookmarks', 'GET');
$router->route('news/recommendations', 'NewsController@recommendations', 'GET');

// Admin posts routes
$router->route('admin/posts', 'Admin/PostsController@index', 'GET');
$router->route('admin/posts/create', 'Admin/PostsController@create', 'GET');
$router->route('admin/posts/store', 'Admin/PostsController@store', 'POST');
$router->route('admin/posts/{id}/edit', 'Admin/PostsController@edit', 'GET');
$router->route('admin/posts/{id}/update', 'Admin/PostsController@update', 'POST');
$router->route('admin/posts/{id}/delete', 'Admin/PostsController@delete', 'POST');

// =====================================================
// JOBS ROUTES
// =====================================================
$router->route('jobs', 'JobsController@index', 'GET');
$router->route('jobs/{slug}', 'JobsController@show', 'GET');
$router->route('jobs/{id}/apply', 'JobsController@applyForm', 'GET');
$router->route('jobs/apply', 'JobsController@apply', 'POST');

// SCHOLARSHIPS ROUTES
$router->route('scholarships', 'ScholarshipsController@index', 'GET');
$router->route('scholarships/{slug}', 'ScholarshipsController@show', 'GET');

// Employer routes
$router->route('employer/jobs', 'EmployerController@jobs', 'GET');
$router->route('employer/jobs/create', 'EmployerController@createJob', 'GET');
$router->route('employer/jobs/create', 'EmployerController@storeJob', 'POST');
$router->route('employer/jobs/{id}/edit', 'EmployerController@editJob', 'GET');
$router->route('employer/jobs/{id}/update', 'EmployerController@updateJob', 'POST');
$router->route('employer/jobs/{id}/applications', 'EmployerController@applications', 'GET');

// =====================================================
// BUSINESS ROUTES
// =====================================================
$router->route('business', 'BusinessController@index', 'GET');
$router->route('business/{slug}', 'BusinessController@show', 'GET');

// Business owner routes
$router->route('business-owner/dashboard', 'BusinessOwnerController@dashboard', 'GET');
$router->route('business-owner/profile', 'BusinessOwnerController@profile', 'GET');
$router->route('business-owner/profile/update', 'BusinessOwnerController@updateProfile', 'POST');

// =====================================================
// ADMIN ROUTES
// =====================================================
$router->route('admin', 'Admin/DashboardController@index', 'GET');
$router->route('admin/dashboard', 'Admin/DashboardController@index', 'GET');
$router->route('admin/users', 'Admin/UsersController@index', 'GET');
$router->route('admin/users/{id}', 'Admin/UsersController@show', 'GET');
$router->route('admin/users/{id}/edit', 'Admin/UsersController@edit', 'POST');
$router->route('admin/users/{id}/delete', 'Admin/UsersController@delete', 'POST');
$router->route('admin/logs', 'Admin/LogsController@index', 'GET');
$router->route('admin/logs/activity', 'Admin/LogsController@activity', 'GET');
$router->route('admin/logs/auth', 'Admin/LogsController@auth', 'GET');
$router->route('admin/logs/errors', 'Admin/LogsController@errors', 'GET');
$router->route('admin/businesses', 'Admin/BusinessController@index', 'GET');
$router->route('admin/businesses/{id}/verify', 'Admin/BusinessController@verify', 'POST');

// =====================================================
// SEO ROUTES
// =====================================================
$router->route('sitemap.xml', 'SeoController@sitemap', 'GET');
$router->route('robots.txt', 'SeoController@robots', 'GET');

// =====================================================
// USER PROFILE ROUTES
// =====================================================
$router->route('profile', 'ProfileController@show', 'GET');
$router->route('profile/edit', 'ProfileController@edit', 'GET');
$router->route('profile/edit/personal', 'ProfileController@editPersonal', 'GET');
$router->route('profile/edit/professional', 'ProfileController@editProfessional', 'GET');
$router->route('profile/edit/security', 'ProfileController@editSecurity', 'GET');
$router->route('profile/edit/account', 'ProfileController@editAccount', 'GET');
$router->route('profile/update', 'ProfileController@update', 'POST');
$router->route('profile/delete', 'ProfileController@delete', 'GET');
$router->route('profile/delete', 'ProfileController@delete', 'POST');

// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

try {
    $router->dispatch($url, $method);
} catch (Exception $e) {
    Logger::logError('Router Error', $e->getMessage(), $e->getFile(), $e->getLine());
    http_response_code(500);
    die('An error occurred. Please try again later.');
}