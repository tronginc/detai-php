<?php
class restful_api {
    /**
     * Property: $method
     * Method được gọi, GET POST PUT hoặc DELETE
     */
    protected $method = '';
    /**
     * Property: $endpoint
     * Endpoint của api
     */
    protected $endpoint = '';
    /**
     * Property: $params
     * Các tham số khác sau endpoint, ví dụ /<endpoint>/<param1>/<param2>
     */
    protected $params = array();
    /**
     * Property: $file
     * Lưu trữ file của PUT request
     */
    protected $file = null;

    /**
     * Function: __construct
     * Just a constructor
     */
    public function __construct(){
        $this->_input();
        $this->_process_api();
    }

    /**
     * Allow CORS
     * Thực hiện lấy các thông tin của request: endpoint, params và method
     */
    private function _input(){
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        $this->params   = $_GET;
        $this->endpoint = $_GET['endpoint'];

        // Lấy method của request
        $method         = $_SERVER['REQUEST_METHOD'];
        $allow_method   = array('GET', 'POST', 'PUT', 'DELETE');

        if (in_array($method, $allow_method)){
            $this->method = $method;
        }

        // Nhân thêm dữ liệu tương ứng theo từng loại method
        switch ($this->method) {
            case 'POST':
                $this->params = $_POST;
                break;

            case 'GET':
                // Không cần nhận, bởi params đã được lấy từ url
                break;

            case 'PUT':
                $this->file    = file_get_contents("php://input");
                break;

            case 'DELETE':
                // Không cần nhận, bởi params đã được lấy từ url
                break;

            default:
                $this->response(500, "Invalid Method");
                break;
        }
    }

    /**
     * Thực hiện xử lý request
     */
    private function _process_api(){
        if (method_exists($this, $this->endpoint)){
            $this->{$this->endpoint}();
        }
        else {
            $this->response(500, "Unknown endpoint");
        }
    }



    /**
     * Trả dữ liệu về client
     * @param: $status_code: mã http trả về
     * @param: $data: dữ liệu trả về
     */
    protected function response($status_code, $data = NULL){
        header($this->_build_http_header_string($status_code));
        header("Content-Type: application/json");
        echo json_encode($data);
        die();
    }

    /**
     * Tạo chuỗi http header
     * @param: $status_code: mã http
     * @return: Chuỗi http header, ví dụ: HTTP/1.1 404 Not Found
     */
    private function _build_http_header_string($status_code){
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        return "HTTP/1.1 " . $status_code . " " . $status[$status_code];
    }
}
