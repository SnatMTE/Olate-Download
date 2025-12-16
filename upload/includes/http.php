<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

class http 
{		
	// Initialize properties to safe defaults to avoid deprecated/null warnings
	var $referer = '';
	var $post_str = '';
		
	var $ret_str = '';
	var $the_data = '';

	var $the_cookies = '';

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
		// Safely extract cookie value to avoid undefined index warnings
		if (empty($this->the_cookies)) {
			return '';
		}
		$pattern = '/(?:^|;)\s*'.preg_quote($name, '/').'=([^;]+)/';
		if (preg_match($pattern, $this->the_cookies, $m)) {
			return urldecode($m[1]);
		}
		return '';
	}
			
	function clear_cookies()
	{
		$this->the_cookies = '';
	}

	function get_content()
	{
		// Avoid warnings when $this->the_data doesn't contain header/body separator
		if (empty($this->the_data) || strpos($this->the_data, "\r\n\r\n") === false) {
			return '';
		}
		list($header, $rest) = explode("\r\n\r\n", $this->the_data, 2);
		// Trim any leading CR/LF from the content
		return ltrim($rest, "\r\n");
	}

	function get_headers()
	{
		if (empty($this->the_data) || strpos($this->the_data, "\r\n\r\n") === false) {
			return '';
		}
		list($header, $rest) = explode("\r\n\r\n", $this->the_data, 2);
		return $header;
	}

	function get_header($name)
	{
		$headers = $this->get_headers();
		if (empty($headers)) {
			return '';
		}
		if (preg_match('/^' . preg_quote($name, '/') . '\s*:\s*(.*)$/im', $headers, $m)) {
			return trim($m[1]);
		}
		return '';
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
		// Use parse_url to robustly parse input and avoid undefined index warnings
		$parts = @parse_url($url);
		$protocol = isset($parts['scheme']) ? $parts['scheme'] : 'http';
		$host = isset($parts['host']) ? $parts['host'] : '';
		$port = isset($parts['port']) ? $parts['port'] : 80;
		$request = isset($parts['path']) ? $parts['path'] : '/';
		if (isset($parts['query']) && $parts['query'] !== '') {
			$request .= '?'.$parts['query'];
		}
		
		// Ensure request is never empty (defensive)
		if ($request === '') {
			$request = '/';
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
		// Use a short timeout to avoid long blocking calls
		$errno = 0;
		$errstr = '';
		$timeout_seconds = 2;
		$fp = @fsockopen($host, $port, $errno, $errstr, $timeout_seconds);
		$ret_str = '';
		if ($fp) 
		{
			stream_set_timeout($fp, $timeout_seconds);
			fwrite($fp, $http_header);
			while(!feof($fp)) 
			{
				$ret_str .= fread($fp, 1024);
			}
			fclose($fp);
		}
		else
		{
			// Connection failed or timed out; return empty string so callers can handle lack of data
			return '';
		}
		return $ret_str;
	}
}
?>