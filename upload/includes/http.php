<?php
/**********************************
* Olate Download 3.4.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Updated: $Date: 2005-12-17 11:22:39 +0000 (Sat, 17 Dec 2005) $
*/

class http 
{		
	var $referer;
	var $post_str;
		
	var $ret_str;
	var $the_data;

	var $the_cookies;

	function set_referer($referer)
	{
		$this->referer = $referer;
	}

	function add_field($name, $value)
	{
		$this->post_str .= $name . '=' . $this->html_encode($value) . '&';
	}
		
	function clear_fields()
	{
		$this->post_str = '';
	}
	
	function check_cookies()
	{
		$cookies = explode("Set-Cookie:", $this->the_data );
		$i = 0;
		if ( count($cookies)-1 > 0 ) 
		{
			while(list($foo, $the_cookie) = each($cookies)) 
			{
				if (! ($i == 0) ) 
				{
					@list($the_cookie, $foo) = explode(';', $the_cookie);
					list($cookie_name, $cookie_value) = explode('=', $the_cookie);
					@list($cookie_value, $foo) = explode('\r\n', $cookie_value); 
					$this->set_cookies(trim($cookie_name), trim($cookie_value));
				}
				$i++;
			}
		}
	}

	function set_cookies($name, $value)
	{
		$total = count(explode($name, $this->the_cookies));

		if ( $total > 1 ) 
		{
			list($foo, $value)  = explode($name, $this->the_cookies);
			list($value, $foo)  = explode("';", $value);
				
			$this->the_cookies = str_replace($name . $value . ";", '', $this->the_cookies);
		}
		$this->the_cookies .= $name . '=' . $this->html_encode($value) . ";"; 
	}

	function get_cookies($name)
	{
		list($foo, $value)  = explode($name, $this->the_cookies);
		list($value, $foo)  = explode(";", $value);
		return substr($value, 1);
	}
			
	function clear_cookies()
	{
		$this->the_cookies = '';
	}

	function get_content()
	{
		list($header, $foo)  = explode("\r\n\r\n", $this->the_data);
		list($foo, $content) = explode($header, $this->the_data);
		return substr($content, 4);
	}

	function get_headers()
	{
		list($header, $foo)  = explode("\r\n\r\n", $this->the_data);
		list($foo, $content) = explode($header, $this->the_data);
		return $header;
	}

	function get_header($name)
	{
		list($foo, $part1) = explode($name . ":", $this->the_data);
		list($val, $foo)  = explode("\r\n", $part1);
		return trim($val);
	}
	
	function post_page($url)
	{			
		$info = $this->parse_request($url);
		$request = $info['request'];
		$host    = $info['host'];
		$port    = $info['port'];

		$this->post_str = substr($this->post_str, 0, -1);
	
		$http_header  = "POST $request HTTP/1.0\r\n";
		$http_header .= "Host: $host\r\n";
		$http_header .= "Connection: Close\r\n";
		$http_header .= "User-Agent: cHTTP/0.1b - Olate Download\r\n";
		$http_header .= "Content-type: application/x-www-form-urlencoded\r\n";
		$http_header .= "Content-length: " . strlen($this->post_str) . "\r\n";
		$http_header .= "Referer: " . $this->referer . "\r\n";

		$http_header .= "Cookie: " . $this->the_cookies . "\r\n";

		$http_header .= "\r\n";
		$http_header .= $this->post_str;
		$http_header .= "\r\n\r\n";
				
		$this->the_data = $this->download_data($host, $port, $http_header);
			
		$this->check_cookies();
	}

	function get_page($url)
	{			
		$info = $this->parse_request($url);
		$request = $info['request'];
		$host    = $info['host'];
		$port    = $info['port'];

		$http_header  = "GET $request HTTP/1.0\r\n";
		$http_header .= "Host: $host\r\n";
		$http_header .= "Connection: Close\r\n";
		$http_header .= "User-Agent: cHTTP/0.1b - Olate Download\r\n";
		$http_header .= "Referer: " . $this->referer . "\r\n";
			
		$http_header .= "Cookie: " . substr($this->the_cookies, 0, -1) . "\r\n";

		$http_header .= "\r\n\r\n";
			
		$this->the_data = $this->download_data($host, $port, $http_header);
	}
		
	function parse_request($url)
	{
		list($protocol, $url) = explode("://", $url);
		list($host, $foo) = explode("/", $url);
		list($foo, $request) = explode($host, $url); 
		@list($host, $port) = explode(":", $host);
				
		if (strlen($request) == 0) 
		{
			$request = "/";
		}
		
		if (strlen($port) == 0)    
		{
			$port = '80';
		}
			
		$info = array();
		$info['host']     = $host;
		$info['port']     = $port;
		$info['protocol'] = $protocol;
		$info['request']  = $request;

		return $info;
	}

	function html_encode($html)
	{
		$html = urlencode($html);
		return $html;
	}

	function download_data($host, $port, $http_header)
	{
		$fp = fsockopen($host, $port);
		$ret_str = '';
		if ($fp) 
		{
			fwrite($fp, $http_header);
			while(!feof($fp)) 
			{
				$ret_str .= fread($fp, 1024);
			}
			fclose($fp);
		}
		return $ret_str;
	}
}
?>