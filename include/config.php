<?
if(session_id()){session_start();}
// ini_set('display_errors', '0');
ini_set('session.gc_maxlifetime', 3600);


error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
date_default_timezone_set('Asia/Taipei');
// class的資料夾路徑
define('class_DIR', dirname(__FILE__) . '/../class/');
define('include_DIR', dirname(__FILE__) . '/../include/');
define('admin_DIR', dirname(__FILE__) . '/../admin/');


// 自行定義autoloader
if (!function_exists("my_autoloader")) {
    function my_autoloader($classname)
    {
        $include_file = $classname . '.php';
        switch ($include_file) {
            case (file_exists(class_DIR . $include_file)):
                require_once class_DIR . $include_file;
            break;
        }
    }
}
// 註冊 自行定義autoloader
spl_autoload_register('my_autoloader');


?>

