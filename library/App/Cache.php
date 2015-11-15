<?php
class Cache
{
	public function destroy()
	{
		$frontendOptions = array();
		$backendOptions = array('cache_dir' => './tmp/');
		$cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}
}