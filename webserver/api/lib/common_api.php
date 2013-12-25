

<?php
abstract class CommonAPI
{
    protected $method = '';
 
    protected $endpoint = '';
 
    protected $verb = '';
 
    protected $args = Array();
 
    protected $file = Null;

    public function __construct($request) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        
        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);
        if (array_key_exists(0, $this->args)) {
            $this->verb = array_shift($this->args);
        }
        
        $this->method = $_SERVER['REQUEST_METHOD'];

        switch($this->method) {
        case 'GET':
            $this->args = $_GET;
            break;          
        case 'POST':
            $this->args =$_POST;
            break;
        case 'PUT':
            $this->args = $_POST;
            $this->file = file_get_contents("php://input");
            break;
        case 'DELETE':
            $this->args =$_POST;
            break;                    
        default:
            $this->_response('Invalid Method', 405);
            break;
        }
    }
    
    
    public function processAPI() {
        if ((int)method_exists($this, $this->endpoint) > 0) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }
        return $this->_response("No Endpoint: $this->endpoint, 404");
    }

    private function _response($data) {
        header("HTTP/1.1 " . $data['status'] . " " . $this->_requestStatus($data['status']));
        unset($data['status']);
        return json_encode($data);
    }

    private function _requestStatus($code) {
        $status = array(  
            100 => 'Continue',  
            101 => 'Switching Protocols',  
            200 => 'OK',  
            201 => 'Created',  
            202 => 'Accepted',  
            203 => 'Non-Authoritative Information',  
            204 => 'No Content',  
            205 => 'Reset Content',  
            206 => 'Partial Content',  
            300 => 'Multiple Choices',  
            301 => 'Moved Permanently',  
            302 => 'Found',  
            303 => 'See Other',  
            304 => 'Not Modified',  
            305 => 'Use Proxy',  
            306 => '(Unused)',  
            307 => 'Temporary Redirect',  
            400 => 'Bad Request',  
            401 => 'Unauthorized',  
            402 => 'Payment Required',  
            403 => 'Forbidden',  
            404 => 'Not Found',  
            405 => 'Method Not Allowed',  
            406 => 'Not Acceptable',  
            407 => 'Proxy Authentication Required',  
            408 => 'Request Timeout',  
            409 => 'Conflict',  
            410 => 'Gone',  
            411 => 'Length Required',  
            412 => 'Precondition Failed',  
            413 => 'Request Entity Too Large',  
            414 => 'Request-URI Too Long',  
            415 => 'Unsupported Media Type',  
            416 => 'Requested Range Not Satisfiable',  
            417 => 'Expectation Failed',  
            500 => 'Internal Server Error',  
            501 => 'Not Implemented',  
            502 => 'Bad Gateway',  
            503 => 'Service Unavailable',  
            504 => 'Gateway Timeout',  
            505 => 'HTTP Version Not Supported' 
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }    
    
}
?>

