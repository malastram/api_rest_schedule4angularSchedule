<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . "/include/bootstrap.php";

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos específicos
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
// Permitir encabezados específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar solicitudes OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Terminar la solicitud y devolver solo los encabezados CORS
    exit(0);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[3]) && $uri[3] == 'user')) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[4] . 'Action';
    $objFeedController->{$strMethodName}();
} else if(isset($uri[4])&& $uri[4] =="getevent" && isset($_GET['id'])){
   
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $id = $_GET['id'];
    $objFeedController->geteventAction($id);
 
}else if((isset($uri[3]) && $uri[3] == 'dailyevent')){
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $id = $_GET['id'];
    $objFeedController->getdailyeventAction($id);

}
else if
((isset($uri[3]) && $uri[3] == 'event')) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[4] . 'Action';
    if (
        isset($_GET['id']) && $_GET['date']
        && $_GET['title'] && $_GET['description'] && $_GET['priority']
    ) {
        $id = $_GET['id'];
        $date = $_GET['date'];
        $title = $_GET['title'];
        $description = $_GET['description'];
        $priority = $_GET['priority'];
    
        $arrayEvent = [$id, $date, $title, $description, $priority];

        $objFeedController->{$strMethodName}($arrayEvent);
        exit();
      
    }else{
        echo "fails";
    }
}else if((isset($uri[3]) && $uri[3] == 'delevent')) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[4] . 'Action';
    if (
        isset($_GET['id'])
    ) {
        $id = $_GET['id'];
           

        $objFeedController->{$strMethodName}($id);
        exit();
   
   
    }else{
        echo "fails";
    }
}else if((isset($uri[3]) && $uri[3] == 'deldailyevent')) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[4] . 'Action';
    if (
        isset($_GET['id'])
    ) {
        $id = $_GET['id'];
           

        $objFeedController->{$strMethodName}($id);
        exit();
   
   
    }else{
        echo "fails";
    }

} else if((isset($uri[3]) && $uri[3] == 'success')  && isset($_GET['idevent'])  && isset($_GET['iduser'])) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    // $strMethodName = $uri[4] . 'Action';
    $strMethodName = $uri[3] . 'Action';
    if (
        isset($_GET['idevent']) && $_GET['iduser']
        
    ) {
        $idevent = $_GET['idevent'];
        $iduser = $_GET['iduser'];        
        $arrayEvent = [$idevent, $iduser];
        $objFeedController->{$strMethodName}($arrayEvent);
        exit();
      
    }else{
        echo "fails";
    }


} else if((isset($uri[3]) && $uri[3] == 'discard') && isset($_GET['idevent'])  && isset($_GET['iduser'])) {
  require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    // $strMethodName = $uri[4] . 'Action';
    $strMethodName = $uri[3] . 'Action';
    if (
        isset($_GET['idevent']) && $_GET['iduser']
        
    ) {
        $idevent = $_GET['idevent'];
        $iduser = $_GET['iduser'];        
        $arrayEvent = [$idevent, $iduser];
        $objFeedController->{$strMethodName}($arrayEvent);
        exit();
      
    }else{
        echo "fails";
    }

} else if((isset($uri[3]) && $uri[3] == 'percent')  && isset($_GET['iduser'])) {
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    // $strMethodName = $uri[4] . 'Action';
    $strMethodName = $uri[3] . 'Action';
    if ((isset($_GET['iduser']))) {
        $iduser = $_GET['iduser'];        
        $objFeedController->{$strMethodName}($iduser);
        exit();
      
    }else{
        echo "fails";
    }
}else if((isset($uri[3]) && $uri[3] == 'addDailyEvent')  &&  isset($_GET['userid'] )&& isset($_GET['daysOfWeek'] ) && isset($_GET['startTime'] )&& isset($_GET['endTime'] )&& isset($_GET['title'] )&& isset($_GET['description'] )
&& isset($_GET['priority'] )){
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
  
        $id = $_GET['userid'];
        $daysofweek = $_GET['daysOfWeek'];
        $start = $_GET['startTime'];
        $end = $_GET['endTime'];
        $title = $_GET['title'];
        $description = $_GET['description'];
        $priority = $_GET['priority'];
    
        $arrayEvent = [$id, $daysofweek, $start, $end, $title,$description, $priority];

        $objFeedController->{$strMethodName}($arrayEvent);
        exit();
}else if ((isset($uri[3]) && $uri[3] == 'login' && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')) {
    $data = json_decode(file_get_contents('php://input'), true);

    // error_log(print_r($_POST, true)); // Esto imprimirá los datos en los logs de PHP
    require PROJECT_ROOT_PATH . "/controller/api/userController.php";
    $objFeedController = new UserController();

    $username = isset($data['username']) ? $data['username'] : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $loginArray = [$username, $password];
    $objFeedController->loginAction($loginArray);
    exit();
} else {
    echo "Fail";
    header("HTTP/1.1 404 Not Found");
    exit();
}
