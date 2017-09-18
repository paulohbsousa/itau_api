<?php

class ApiRestClientException extends Exception {}

class ApiRestClient {

	private $options;
	private $reponse;
	private $headers;
	public $error;
	public $info;

	public function __construct($options = array()){
		$default_options = array(
            'headers' => array(), 
            'parameters' => array(), 
            'curl_options' => array(), 
            'user_agent' => "RestClient", 
            'base_url' => NULL, 
            'format' => NULL, 
            'format_regex' => "/(\w+)\/(\w+)(;[.+])?/",
            'username' => NULL,
            'password' => NULL
        );
        
        $this->options = array_merge($default_options, $options);
	}

    public function get($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'GET', $parameters, $headers);
    }
    
    public function post($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'POST', $parameters, $headers);
    }
    
    public function put($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'PUT', $parameters, $headers);
    }
    
    public function delete($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'DELETE', $parameters, $headers);
    }

    private function execute($url, $method='GET',$parameters=array(), $headers=array()){
    	$client = clone $this;
        $client->url = $url;
        $client->handle = curl_init();
        $curlopt = array(
            CURLOPT_HEADER => TRUE, 
            CURLOPT_RETURNTRANSFER => TRUE, 
            CURLOPT_USERAGENT => $client->options['user_agent']
        );
        
        if($client->options['username'] && $client->options['password'])
            $curlopt[CURLOPT_USERPWD] = sprintf("%s:%s", 
                $client->options['username'], $client->options['password']);
        
        if(count($client->options['headers']) || count($headers)){
            $curlopt[CURLOPT_HTTPHEADER] = array();
            $headers = array_merge($client->options['headers'], $headers);
            foreach($headers as $key => $value){
                $curlopt[CURLOPT_HTTPHEADER][] = sprintf("%s:%s", $key, $value);
            }
        }
        
        if($client->options['format'])
            $client->url .= '.'.$client->options['format'];
        
        // Allow passing parameters as a pre-encoded string (or something that
        // allows casting to a string). Parameters passed as strings will not be
        // merged with parameters specified in the default options.
        if(is_array($parameters)){
            $parameters = array_merge($client->options['parameters'], $parameters);
            $parameters_string = $client->format_query($parameters);
        }
        else
            $parameters_string = (string) $parameters;
        
 
       if(strtoupper($method) == 'POST'){
            $curlopt[CURLOPT_POST] = TRUE;
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif(strtoupper($method) != 'GET'){
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif($parameters_string){
            $client->url .= strpos($client->url, '?')? '&' : '?';
            $client->url .= $parameters_string;
        }
        
        if($client->options['base_url']){
            if($client->url[0] != '/' && substr($client->options['base_url'], -1) != '/')
                $client->url = '/' . $client->url;
            $client->url = $client->options['base_url'] . $client->url;
        }
        $curlopt[CURLOPT_URL] = $client->url;
        
        if($client->options['curl_options']){
            // array_merge would reset our numeric keys.
            foreach($client->options['curl_options'] as $key => $value){
                $curlopt[$key] = $value;
            }
        }
        curl_setopt_array($client->handle, $curlopt);
        $client->parse_response(curl_exec($client->handle));
        $client->info = (object) curl_getinfo($client->handle);
        $client->error = curl_error($client->handle);
        curl_close($client->handle);
        return $client;
    }

	public function format_query($parameters, $primary='=', $secondary='&'){
        $query = "";
        foreach($parameters as $key => $value){
	    if(substr($value,0,1) == "@"){ return $parameters; }
            $pair = array(urlencode($key), urlencode($value));
            $query .= implode($primary, $pair) . $secondary;
        }
        return rtrim($query, $secondary);
    }

    public function parse_response($response){
        $headers = array();
        $http_ver = strtok($response, "\n");
        $linecount = 0;
        while($line = strtok("\n")){
            $linecount++;
            if(strlen(trim($line)) == 0 && $linecount > 2) break;
            
            @list($key, $value) = explode(':', $line, 2);
            $key = trim(strtolower(str_replace('-', '_', $key)));
            $value = trim($value);
            if(empty($headers[$key]))
                $headers[$key] = $value;
            elseif(is_array($headers[$key]))
                $headers[$key][] = $value;
            else
                $headers[$key] = array($headers[$key], $value);

        }
        $this->headers = (object) $headers;
        $this->response = strtok("");
    }
}
