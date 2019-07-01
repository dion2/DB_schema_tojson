<?php
// 規避訊掲露
if(!class_exists("IniSetting")){
    class IniSetting{
        public function __construct(){
            $ini_file = "../include/config.ini";
            $setting = parse_ini_file($ini_file);
            foreach($setting as $key => $value){
                $this->set_setting($key,$value);
            }
            
        }
        public function get_set($setting_name){
            return $this->$setting_name;
        }
        public function set_setting($setting_name,$setting_value){
            $this->$setting_name = $setting_value;
        }
    }

}

?>