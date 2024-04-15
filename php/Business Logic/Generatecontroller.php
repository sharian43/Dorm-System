<?php

class Request {
    private $request;
    private $time;

    public function __construct($request, $time) {
        $this->request = $request;
        $this->time = $time;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getTime() {
        return $this->time;
    }
}

class GenerateController {
    public function generateMaintenanceRequest() {
        
        $request = new Request('Some request data', new DateTime());
        return $request;
    }

    public function request($request, $time) {
        echo "Request sent at: " . $time->format('m-d-Y H:i:s') . ", with data: " . $request->getRequest();
    }
}

?>
