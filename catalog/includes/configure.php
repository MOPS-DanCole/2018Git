<?php
  define('HTTP_SERVER', 'https://www.aquariumsupplies.ca');
  define('HTTPS_SERVER', 'https://www.aquariumsupplies.ca');
  define('ENABLE_SSL', true);
  define('HTTP_COOKIE_DOMAIN', '');
  define('HTTPS_COOKIE_DOMAIN', '');
  define('HTTP_COOKIE_PATH', '/aquarium/');
  define('HTTPS_COOKIE_PATH', '/aquarium/');
  define('DIR_WS_HTTP_CATALOG', '/aquarium/');
  define('DIR_WS_HTTPS_CATALOG', '/aquarium/');
  define('DIR_WS_IMAGES', 'images/');
  
 //added to configure various sites.
  define('DIR_WS_PRODUCT_IMAGES', 'https://www.aquariumsupplies.ca/aquarium');  //usually http://www.aquariumsupplies.ca when on pondsupplies.ca
  define('STORE_ORDERED_ON', 'AQUARIUM');
  define('STORE_SITE', 'AQUARIUM'); // this should be CAD or USD but product query needs to be changed first. Migrate to Store Currency below.
  define('STORE_CATEGORY', 'AQUARIUM');
  define('STORE_CATEGORY2', 'POND');
  define('STORE_CATEGORY3', 'REPTILE');
  define('STORE_CATEGORY4', 'NULL');
  define('STORE_CATEGORY5', 'NULL');
  define('STORE_CURRENCY', 'CAD');
  define('STORE_DEFAULT', 'AQUARIUM');  //to be discontinued.
  
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  //define('DIR_FS_CATALOG', '/home/hsphere/local/home/pondweb/aquariumsupplies.ca/docs/aquarium/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  define('DIR_FS_CATALOG', '/var/www/vhosts/aquariumsupplies.ca/httpdocs/aquarium/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'pondweb');
  define('DB_DATABASE', 'pondweb');
//  define('DB_SERVER_USERNAME', 'pondweb_mops');
//  define('DB_DATABASE', 'pondweb_mops');
  define('DB_SERVER_PASSWORD', 'I8fish2day');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  define('CFG_TIME_ZONE', 'America/Toronto');
?>