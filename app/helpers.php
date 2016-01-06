<?php

if ( !function_exists('public_path'))
{
	function public_path($path) {
		$path = trim($path, '/');
		return app()->basePath("public/{$path}");
	}
}