<?php
namespace Controller;

class HelloController {
	public static function coba($request, $response)
	{
		$name = $request->getAttribute('name');
	    $response->getBody()->write("Hello, $name");

	    return $response;
	}

	public static function a()
	{
		return 'aa';
	}
}