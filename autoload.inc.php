<?php

    require_once("paths.php");

    class autoload {
        public static function loadClasses($moduleName) {
            spl_autoload_extensions('.php,.inc.php,.class.php,.class.singleton.php'); // autoload solo tiene en cuenta las extensiones indicadas
            spl_autoload_register(function ($className) use ($moduleName){
                // carga las rutas de las clases pertenecientes al módulo que le pasamos en la llamada, junto las rutas de las clases comunes para todos los módulos
            
                $breakClass = explode('_', $className);
                $modelName = "";
                
                if (isset($breakClass[1])) {
                    $modelName = strtoupper($breakClass[1]);
                }

                $extensions_modelPath = ['.class.singleton.php', '.class.php', '.inc.php'];

                if (file_exists(MODULES_PATH . $moduleName . '/model/'. $modelName . '/' . $className . '.class.singleton.php')) {
                    set_include_path(MODULES_PATH . $moduleName . '/model/' . $modelName.'/');
                    spl_autoload($className);
                }else if (file_exists(UTILS . $className . '.inc.php')) {
                    set_include_path(UTILS);
                    spl_autoload($className); // hace el mismo trabajo que un require_once
                } else {
                    foreach ($extensions_modelPath as $extension) {
                        if (file_exists(MODEL_PATH . $className . $extension)){
                            set_include_path(MODEL_PATH);
                            spl_autoload($className);
                        }
                    }
                }
            });
        }
    }