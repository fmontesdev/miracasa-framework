<?php

    require 'autoload.inc.php';

    ob_start(); // activa el almacenamiento de búfer de salida
    session_start();

    class router {
        private $uriModule;
        private $uriFunction;
        private $nameModule;
        static $_instance;
        
        public static function getInstance() {   // Crea el objeto si no existe, solo una vez para ahorrar recursos. En metodos estáticos no podemos utilizat $this, utilizaremos self
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
    
        // function __construct() {   
        //     if(isset($_GET['module'])){
        //         $this -> uriModule = $_GET['module'];
        //     }else{
        //         $this -> uriModule = 'home';
        //     }
        //     if(isset($_GET['op'])){
        //         $this -> uriFunction = ($_GET['op'] === "") ? 'view' : $_GET['op'];
        //     }else{
        //         $this -> uriFunction = 'view';
        //     }
        // }

        function __construct() {   
            if (isset($_GET['module'])) {
                if (isset($_POST['module'])) {
                    $this -> uriModule = $_POST['module'];
                } else {
                    $this -> uriModule = ($_GET['module'] === "") ? 'home' : $_GET['module'];
                }
            } else {
                $this -> uriModule = 'home';
                // echo '<script>console.log("Hola home")</script>';
            }

            if (isset($_POST['op'])) {
                $this -> uriFunction = $_POST['op'];
            } else {
                if (isset($_GET['op'])) {
                    if ($_GET['op'] === 'verify' | $_GET['op'] === 'recover') {
                        $this -> uriFunction = 'view';
                    } else {
                        $this -> uriFunction = ($_GET['op'] === "") ? 'view' : $_GET['op'];
                    }
                } else {
                    $this -> uriFunction = 'view';
                }
            }
            
        }
    
        function routingStart() {
            try {
                call_user_func(array($this -> loadModule(), $this -> loadFunction())); // llamamos al metodo (loadFunction) de la clase (loadModule) con el objeto creado
                // if (isset($_SESSION['tiempo'])) {  
                //     $_SESSION['tiempo'] = time(); //Devuelve la fecha actual
                // }
            }catch(Exception $e) {
                common::load_error();
            }
        }
        
        // comprueba la existencia del modulo, carga el controlador de ese modulo, y devuelve el nombre del modulo (clase)
        private function loadModule() {
            if (file_exists('resources/modules.xml')) {
                $modules = simplexml_load_file('resources/modules.xml');
                foreach ($modules as $row) {
                    if (in_array($this -> uriModule, (Array) $row -> uri)) { // in_array comprueba si el valor de uriModule dado por el constructor existe en el array formado por las uris tomadas del xml
                        $path = MODULES_PATH . $row -> name . '/controller/controller_' . (String) $row -> name . '.class.singleton.php';
                        if (file_exists($path)) {
                            require_once($path); // require es igual que include con la diferencia que en caso de fallo genera error que detiene script. require_once comprueba si el archivo ya ha sido cargado, si es así no lo vuelve a cargar
                            $controllerName = 'controller_' . (String) $row -> name;
                            $this -> nameModule = (String) $row -> name;
                            autoload::loadClasses($this -> nameModule); // llamamos al autoload pasándole el nombre del modulo activo
                            return $controllerName::getInstance();
                        }
                    }
                }
            }
            throw new Exception('Not Module found.');
        }
        
        // comprueba la existencia de la función, y devuelve el nombre de la misma (metodo)
        private function loadFunction() {
            $path = MODULES_PATH . $this -> nameModule . '/resources/function.xml'; 
            if (file_exists($path)) {
                $functions = simplexml_load_file($path);
                foreach ($functions as $row) {
                    if (in_array($this -> uriFunction, (Array) $row -> uri)) {
                        return (String) $row -> name;
                    }
                }
            }
            throw new Exception('Not Function found.');
        }
    }
    
    router::getInstance() -> routingStart(); // metodo estatico se llama con nombre_clase::nombre_metodo, después llamamos a metodo de instancia
