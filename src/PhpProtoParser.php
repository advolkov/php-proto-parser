<?php

/**
 * @author <advolkov1@gmail.com>
 */
class PhpProtoParser
{
    private $proto_str;
    
    public function __construct($proto_path)
    {
        $proto_str = $this->getProtoAsStr($proto_path);
        $this->proto_str = $this->removeComments($proto_str);
    }

    public function getAllEnum()
    {
        preg_match_all("#(?<=enum\\s).*(?=\\s{)#", $this->proto_str, $result);

        return $result[0];
    }

    public function getAllMsg()
    {
        preg_match_all("#(?<=message\\s).*(?=\\s{)#", $this->proto_str, $result);

        return $result[0];
    }

    public function getMessageParams($message)
    {
        $params_arr = [];
        preg_match("#message\\s$message\\s{(\n(.*?))+}#", $this->proto_str, $matches);
        preg_match_all("#((\\w+\\s+\\w+\\s+\\w+\\s+=\\s+\\w+)(\\s+\\[\\w+\\s+=\\s+\\w+\\]|))+#", $matches[0], $params);
        if (empty($params[0])) {
            throw new \Exception("Unable to parse $message params");
        }
        foreach ($params[0] as $param) {
            $param = explode("=", $param);
            $tmp = preg_split("#\\s+#", trim($param[0]));
            $params_arr[$tmp[2]] = [
                "field_type" => $tmp[0],
                "value_type" => $tmp[1],
                "number" => trim($param[1]),
            ];
        }

        return $params_arr;
    }

    public function getEnumParams($enum)
    {
        $params_arr = [];
        preg_match("#enum\\s$enum\\s{(\n(.*?))+}#", $this->proto_str, $matches);
        preg_match_all("#(\\w+\\s+=\\s+\\w+)+#", $matches[0], $params);
        if (empty($params[0])) {
            throw new \Exception("Unable to parse $enum params");
        }
        foreach ($params[0] as $param) {
            $tmp = explode("=", $param);
            $params_arr[trim($tmp[0])] = trim($tmp[1]);
        }

        return $params_arr;
    }

    private function getProtoAsStr($proto_path)
    {
        $cmd = "cat " . escapeshellarg($proto_path);
        exec($cmd, $out, $ret);

        return implode("\n", $out);
    }

    private function removeComments($proto_str)
    {
        $proto_str = preg_replace("#(?<=\\/\\*).*(?=\\*\\/)#", "", $proto_str);
        $proto_str = preg_replace("#\\/\\*\\*\\/#", "", $proto_str);
        $proto_str = preg_replace('#//.*#', '', $proto_str);

        return $proto_str;
    }
}
