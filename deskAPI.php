<?php

const ZOHOBASE_URL = "https://desk.zoho.in/api/v1/";

function logio($txt) {
    echo $txt . "<br><br>";
}

class zohodeskAPI_Object {

    function __construct($name, $requires = null) {
        $this->name = $name;
        $this->requiredFields = $requires;
    }

    public function __call($method, $args) {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

    function create($data, $obj) {
        if (!$this->passedRequires($data)) {
            return false;
        }
        $url = $this->buildURL($this->getPrimaryURL());
        return $obj->httpPOST($url, $this->objToString($data));
    }

    function update($id, $data, $obj) {
        logio($this->objToString($data));
        $url = $this->buildURL($this->getPrimaryURL($id));
        return $obj->httpPATCH($url, $this->objToString($data));
    }

    function delete($id, $obj) {
        $url = $this->buildURL($this->getPrimaryURL($id));
        return $obj->httpDELETE($url);
    }

    function get($id, $params, $obj) {
        $param = ($params) ? $this->handleParameters($params) : "";
        $url = $this->buildURL($this->getPrimaryURL($id), $param);
        return $obj->httpGET($url);
    }

    function all($params, $obj) {
        $param = ($params) ? $this->handleParameters($params) : "";
        $url = $this->buildURL($this->getPrimaryURL(), $param);
        return $obj->httpGET($url);
    }

    function buildURL($url, $params = null) {
        return ($params !== null) ? $url . $params : $url;
    }

    function getPrimaryURL($id = null) {
        $returnURL = ZOHOBASE_URL;
        if ($id !== null) {
            $returnURL .= $this->name . "/" . $id;
        } else {
            $returnURL .= $this->name;
        }
        return $returnURL;
    }

    function handleParameters($data) {
        $params = "";
        if (gettype($data) === "object") {
            foreach ($data as $key => $value) {
                $params .= $key . "=" . $value . "&";
            }
        } else {
            return "?" . $data;
        }
        return "?" . $params . substr(0, strlen($params) - 1);
    }

    function passedRequires($data) {
        try {
            $dataObj = (gettype($data) === "array") ? $data : $data;
            $dataType = gettype($data);
            if (gettype($data) == "array" || gettype($data) == "object") {
                foreach ($this->requiredFields as $item => $value) {
                    if (($dataType == "array") ? array_key_exists($item, $dataObj) : property_exists($dataObj, $item)) {
                        if ($value) {
                            logio("ERROR : Field " . $item . " is required to create new " . $this->name . "");
                            $this->printRequired();
                            return false;
                        }
                    } else {
                        logio("ERROR : Field " . $item . " is required to create new " . $this->name . "");
                        $this->printRequired();
                        return false;
                    }
                }
            }
        } catch (Exception $e) {
            logio("ERROR : Data is not valid JSON" . $e);
            return false;
        }
        return true;
    }

    function required() {
        $this->printRequired();
    }

    function printRequired() {
        logio("Required fields to create new " . $this->name . " are ");
        $i = 0;
        foreach ($this->requiredFields as $key => $value) {
            logio(( ++$i) . " : " . $key);
        }
        logio("-------------");
    }

    function objToString($data) {
        $json = "";
        if (gettype($data) == "array") {
            return $data;
        } else {
            $json = $data;
            if ($this->validJson($data)) {
                // $json= json_decode($data);
            } else {
                logio($data . "is not a valid json");
            }
        }
        return $json;
    }

    function validJson($string) {
        if (gettype($string) == "object")
            return TRUE;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}

class zohodeskAPI_ReadOnly_Obj extends zohodeskAPI_Object {

    function create($a, $b) {
        
    }

    function update($a, $b, $c) {
        
    }

    function delete($a, $b) {
        
    }

}

class zohodeskAPI_Secondary_Object {

    function __construct($name, $parent, $requires = null) {
        $this->name = $name;
        $this->parent_name = $parent;
        $this->requiredFields = $requires;
    }

    function create($parent_id, $data, $obj) {
        if (!$this->passedRequires($data)) {
            return false;
        }
        $url = $this->buildURL($this->getPrimaryURL($parent_id));
        return $obj->httpPOST($url, $this->objToString($data));
    }

    function update($parent_id, $id, $data, $obj) {
        $url = $this->buildURL($this->getPrimaryURL($parent_id, $id));
        return $obj->httpPATCH($url, $this->objToString($data));
    }

    function delete($parent_id, $id, $obj) {
        $url = $this->buildURL($this->getPrimaryURL($parent_id, $id));
        return $obj->httpDELETE($url);
    }

    function get($parent_id, $id, $params, $obj) {
        $param = ($params) ? $this->handleParameters($params) : "";
        $url = $this->buildURL($this->getPrimaryURL($parent_id, $id), $param);
        return $obj->httpGET($url);
    }

    function all($parent_id, $params, $obj) {
        $param = ($params) ? $this->handleParameters($params) : "";
        $url = $this->buildURL($this->getPrimaryURL($parent_id), $param);
        return $obj->httpGET($url);
    }

    function buildURL($url, $params = null) {
        return ($params !== null) ? $url . $params : $url;
    }

    function getPrimaryURL($parent_id = null, $id = null) {
        $returnURL = ZOHOBASE_URL;
        $type = $this->name;
        if ($parent_id !== null && trim($parent_id) !== "") {
            $returnURL .= $this->parent_name . "/" . $parent_id;
            if ($id !== null) {
                $returnURL .= "/" . $this->name . "/" . $id;
            } else {
                $returnURL .= ($type === $this->name) ? "/" . $this->name : "";
            }
        } else {
            try {
                throw new Exception("ERROR : " . $this->parent_name . "-ID is empty or missing ");
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            return FALSE;
        }
        return $returnURL;
    }

    function handleParameters($data) {
        $params = "";
        if (gettype($data) === "array") {
            foreach ($data as $item) {
                $params .= $item . "=" . $data[$item] . "&";
            }
        } else {
            return "?" . $data;
        }
        return "?" . $params . substr(0, $params . length - 1);
    }

    function passedRequires($data) {
        try {
            $dataObj = (gettype($data) === "object") ? $data : json_decode($data);
            foreach ($this->requiredFields as $item => $value) {
                if (property_exists($dataObj, $item)) {
                    if (!$data->$item) {
                        logio("ERROR : Field " . $item . " is empty & required to create new " . $this->name . "");
                        $this->printRequired();
                        return false;
                    }
                } else {
                    logio("ERROR : Field " . $item . " is required to create new " . $this->name . "");
                    $this->printRequired();
                    return false;
                }
            }
        } catch (Exception $e) {
            logio("ERROR : Data is not valid JSON" . $e->getMessage());
            return false;
        }
        return true;
    }

    function required() {
        $this->printRequired();
    }

    function printRequired() {
        logio("Required fields to create new " . $this->name . " are ");
        $i = 0;
        foreach ($this->requiredFields as $item => $value) {
            logio(( ++$i) . " : " . $item);
        }
        logio("-------------");
    }

    function objToString($data) {
        $json = "";
        if (gettype($data) == "array") {
            return $data;
        } else {
            $json = $data;
            if ($this->validJson($data)) {
                // $json= json_decode($data);
            } else {
                logio($data . "is not a valid json");
            }
        }
        return $json;
    }

    function validJson($string) {
        if (gettype($string) == "object")
            return TRUE;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}

//$tickets = new zohodeskAPI_Object("tickets",zohodeskAPI_vars.requiredFields.tickets);
//$comments = new zohodeskAPI_Secondary_Object("comments", "tickets", zohodeskAPI_vars.requiredFields.comments);
//$contacts = new zohodeskAPI_Object("contacts", zohodeskAPI_vars.requiredFields.contacts);
//$accounts = new zohodeskAPI_Object("accounts", zohodeskAPI_vars.requiredFields.accounts);
//$tasks = new zohodeskAPI_Object("tasks", zohodeskAPI_vars.requiredFields.tasks);
//$agents = new zohodeskAPI_ReadOnly_Obj("agents");
//$departments = new zohodeskAPI_ReadOnly_Obj("departments");
class zohodeskAPI {

    function __construct($auth_token, $orgId) {
        $zohodeskAPI_vars = array(
            "content_json" => "application/json; charset=utf-8",
            "appBaseURL" => "https://desk.zoho.com/api/v1/",
            "requiredFields" => array(
                "tickets" => array("subject" => "", "departmentId" => "", "contactId" => ""),
                "comments" => array("content" => "", "isPublic" => ""),
                "contacts" => array("lastName" => ""),
                "accounts" => array("accountName" => ""),
                "tasks" => array("departmentId" => "", "subject" => "")
            )
        );
        $this->authtoken = $auth_token;
        $this->orgId = $orgId;
        $this->tickets = new zohodeskAPI_Object("tickets", $zohodeskAPI_vars['requiredFields']['tickets']);

        $this->tickets->quickCreate = function ($subject, $departmentId, $contactId, $productId = "", $email = "", $phone = "", $description = "") {
            return json_encode(array(
                "subject" => $subject,
                "departmentId" => $departmentId,
                "contactId" => $contactId,
                "productId" => $productId,
                "email" => $email,
                "phone" => $phone,
                "description" => $description
            ));
        };
    }

    static function getBaseUrl() {
        return ZOHOBASE_URL;
    }

    function createTicket($data) {
        $arguments = func_get_args();
        $dataJsonObj = $this->getValidJson($data);
        $dataObj = ($dataJsonObj) ? $dataJsonObj : call_user_func_array($this->tickets->quickCreate, $arguments);
        return $this->tickets->create($dataObj, $this);
    }

    function updateTicket($id, $data) {
        return $this->tickets->update($id, $data, $this);
    }

    function getTicket($id, $params = "") {
        return $this->tickets->get($id, $params, $this);
    }

    function getTickets($params = "") {
        return $this->tickets->all($params, $this);
    }

    function deleteTicket($params = "") {
        return $this->tickets->delete($params, $this);
    }

    function allDepartments($params = "") {
        return $this->departments->all($params, $this);
    }

    function getDepartment($id, $params = "") {
        return $this->departments->get($id, $params, $this);
    }

    function buildURL($url, $params = null) {
        return ($params !== null) ? $url . $params : $url;
    }

    function httpGET($url) {
        return $this->httpExecute($url, $this->httpHeaders(), "GET");
    }

    function httpPOST($url, $data) {
        return $this->httpExecute($url, $this->httpHeaders(), "POST", $data);
    }

    function httpPATCH($url, $data) {
        return $this->httpExecute($url, $this->httpHeaders(), "PATCH", $data);
    }

    function httpDELETE($url) {
        return $this->httpExecute($url, $this->httpHeaders(), "DELETE");
    }

    function httpHeaders() {
        $authtoken = $this->authtoken;
        return array(
            "Authorization: $authtoken",
            "orgId: $this->orgId",
            "contentType: application/json; charset=utf-8",
        );
    }

    function httpExecute($url, $headers, $method, $data = "") {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        if ($method == "POST" || $method == "PATCH") {
            curl_setopt($curl, CURLOPT_POSTFIELDS, (gettype($data) === "string") ? $data : json_encode($data));
        }
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    function httpSettings($method, $headers, $data = "") {
//        $settingsObj = {
//            method: method,
//            headers: headers,
//            mode: 'cors'
//        };
//        if (method === "POST" || method === "PATCH" || method === "PUT") {
//            settingsObj->body = $data;
//        }
//        return settingsObj;
    }

    function getValidJson($string) {
        switch (gettype($string)) {
            case "array":
            case "object":
                return $string;
                break;
            case "string":
                $obj = json_decode($string);
                if (json_last_error() == JSON_ERROR_NONE) {
                    return (gettype($obj) === "object") ? $obj : FALSE;
                }
                return FALSE;
                break;
            default :
                return FALSE;
        }
    }

    function getPrimaryURL($type, $ticketID = null, $commentID = null) {
        $returnURL = ZOHOBASE_URL;
        if ($ticketID !== null) {
            $returnURL .= "tickets" . "/" . $ticketID;
            if ($commentID !== null) {
                $returnURL .= "/" . "comments" . "/" . $commentID;
            } else {
                $returnURL .= ($type === "comments") ? "/" . "comments" : "";
            }
        } else {
            $returnURL .= "tickets";
        }
        return $returnURL;
    }

    function assignDefaults() {
        $this->authtoken = "2e4740934d006ac74de79025ce3ed073";
        $this->orgId = 60001280952;
    }

}

?>