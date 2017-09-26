<?php
class ccpeDB{
    private static $username = 'iwantout_chat';
    private static $password = '0104jdcreative';
    private static $SQLserver = 'localhost';
    private static $dbname = 'iwantout_other';

	var $connectid;
	var $strstatus = "";
	
	public function connect(){
	    $connect = @mysql_connect(self::$SQLserver, self::$username, self::$password, false, 65536);
    	if (!$connect) {
   			$this->strstatus .= "SQL Server not found.";
			return false;
    	}
	    else {

	     	$this->connectid = $connect;

    	    if (!@mysql_select_db(self::$dbname, $this->connectid)) {
				$this->strstatus .= "Cannot connect to ".self::$dbname." database.";
       			return false;
        	}
    	    else {
			  return true;
			}
	    }	  
	}
	
	public function close(){
	 	mysql_close($this->connectid);
	}

    public function runSQL($SQL){
        $success = $this->connect();
        if($success==1){
            @mysql_query("SET NAMES 'UTF8'");
            if(!$result=mysql_query($SQL,$this->connectid)){
                    return "Cannot execute query";
            }   else{

                while($row = mysql_fetch_array($result))
                    $JSONItem['result'][] = $row;
                return $JSONItem;
            }
        }else{
            return "Cannot connect to database";
        }
    }
    public function runQuery($SQL){
        $success = $this->connect();
        if($success==1){
        	mysql_query("SET NAMES utf8");
            mysql_query($SQL,$this->connectid);
        }
    }
	public function runQueryId($SQL){
				
		        $success = $this->connect();
		        if($success==1){
		        	mysql_query("SET NAMES utf8");
		            mysql_query($SQL,$this->connectid);
		            return mysql_insert_id();
		        }
	}
    public function getJSON($routine,$resultname)
    {
	 	$success = $this->connect();
	 	if ($success==1){
             mysql_query("SET character_set_results=utf8");
            mysql_query("SET NAMES utf8");
	 	 	 $query = "$routine" ;


			if (!$result = mysql_query($query,$this->connectid)){
				return "Cannot execute query";
			}
			else{
                if(strstr($routine,"INSERT INTO"))
                    return '[]';
                else{
                    $JSONItem = array();
                    while($row = @mysql_fetch_assoc($result))
                        $JSONItem[$resultname][] = $row;
                    return json_encode($JSONItem);
                }

            }
		}
		else
			return "Cannot connect to database";
    }

    public function getXML($routine,$resultname){ //the function to get XML on server
        $success = $this->connect();
        if ($success==1){
            $query = "CALL $routine" ;

            if (!$result = mysql_query($query,$this->connectid)){
                return "Cannot execute query";
            }
            else{
                $dom=new DOMDocument('1.0');
                $root = $dom->createElement($resultname);
                $root=$dom->appendChild($root);
                while($row = mysql_fetch_assoc($result)){//here
                    $node = $dom->createElement('number');
                    $node = $root->appendChild($node);
                    $value = $dom->createTextNode('1');
                    $value = $node->appendChild($value);
                    foreach ($row as $fieldname => $fieldvalue){
                        $node = $dom->createElement($fieldname);
                        $node = $root->appendChild($node);
                        $value = $dom->createTextNode($fieldvalue);
                        $value = $node->appendChild($value);
                    }
                }

                //echo $str;
                //return $dom->saveXML();
                //return xmlrpc_encode_request($str);
                return $dom->saveXML();
            }
        }
        else
            return "Cannot connect to database";
    }

    public function convertDateSQL($date){
        $date=explode('/',$date);
        $date2=$date[2].'-'.$date[1].'-'.$date[0];
        return $date2;
    }
    public function convertDateSite($date){
        $date=explode(' ',$date);
        $date=explode('-',$date[0]);
        $date2=$date[2].'/'.$date[1].'/'.$date[0];
        return $date2;
    }
}

?>