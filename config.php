<?php
define('SITE_NAME','Ecommerce');
error_reporting(E_ERROR | E_PARSE);

// Database Configuration
define('HOSTNAME','localhost');
define('USERNAME','u139640761_furnicraft');
define('PASSWORD','furnicraft@2025');
define('DATABASE','u139640761_furnicraft');

// Site URLs and Paths
define('FRONT_SITE_PATH','https://violet-bat-787638.hostingersite.com/');
define('SERVER_IMAGE',$_SERVER['DOCUMENT_ROOT']."/");

// Product Images
define('SERVER_PRODUCT_IMAGE',SERVER_IMAGE."media/product/");
define('SITE_PRODUCT_IMAGE',FRONT_SITE_PATH."media/product/");

// Category Images
define('SERVER_CATEGORY_IMAGE',SERVER_IMAGE."media/category/");
define('SITE_CATEGORY_IMAGE',FRONT_SITE_PATH."media/category/");

// Slider Banner Images
define('SERVER_SLIDERBANNER_IMAGE',SERVER_IMAGE."media/slider/");
define('SITE_SLIDERBANNER_IMAGE',FRONT_SITE_PATH."media/slider/");

// Logo Images
define('SERVER_LOGO_IMAGE',SERVER_IMAGE."media/logo/");
define('SITE_LOGO_IMAGE',FRONT_SITE_PATH."media/logo/");

// Banner Images
define('SERVER_BANNER_IMAGE',SERVER_IMAGE."media/");
define('SITE_BANNER_IMAGE',FRONT_SITE_PATH."media/");

// Admin Profile Images
define('SERVER_ADMINPROFILE_IMAGE',SERVER_IMAGE."media/adminprofile/");
define('SITE_ADMINPROFILE_IMAGE',FRONT_SITE_PATH."media/adminprofile/");

// Page Banner Images
define('SERVER_PAGEBANNER_IMAGE',SERVER_IMAGE."media/pageBanner/");
define('SITE_PAGEBANNER_IMAGE',FRONT_SITE_PATH."media/pageBanner/");
?> 