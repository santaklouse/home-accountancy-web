<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Config_JSON_Reader implements Kohana_Config_Reader
{
	protected $_configuration_group;

	// Has the config group changed?
	protected $_configuration_modified = FALSE;
	/**
	 * Constructs the database reader object
	 *
	 * @param array Configuration for the reader
	 */
	public function __construct($directory = 'config')
	{
		$this->_directory = trim($directory , '/');
	}

	/**
	 * Tries to load the specificed configuration group
	 *
	 * Returns FALSE if group does not exist or an array if it does
	 *
	 * @param  string $group Configuration group
	 * @return boolean|array
	 */
	public function load($group)
	{
		$config = array();
		if ($files = Kohana::find_file($this->_directory, $group, 'json', TRUE))
		{


			foreach ($files as $file)
			{
				$config = Arr::merge($config, json_decode(file_get_contents($file),TRUE));
			}
		}
		return $config;
	}
}
