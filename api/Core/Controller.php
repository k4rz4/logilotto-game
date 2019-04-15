<?php
class Controller
{
    public function jsonSuccess($returnData) {
        echo json_encode(["status" => 200, "data" => $returnData]);
        return;
    }

    public function jsonError($returnData) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(["status" => 500, "Error" => $returnData]);
        die();
    }
}
?>
