<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{
	protected $config;
	public function _initSession($dispatcher) {
		/*
		 * start a session 
		 */
		Yaf_Session::getInstance()->start();
	}

	public function _initConfig() {
		$this->config = Yaf_Application::app()->getConfig();
		//print_r($config);
		Yaf_Registry::set("config", $this->config);
	}

	/*public function _initLocalName() {
		Yaf_Loader::getInstance()->registerLocalNamespace(array(
			'Db',
		));
	}*/
	//添加db，memcache
	public function _initMemcache() {
		if (!empty($this->config->cache->caching_system))
		{
			Yaf_Registry::set('cache_exclude_table', explode('|', $this->config->cache->cache_exclude_table));
			Yaf_Loader::import(APPLICATION_PATH . '/library/Cache/Cache.php');
			if (isset($this->config->cache->prefix))
			{
				define('CACHE_KEY_PREFIX', $this->config->cache->prefix);
			}
			if (isset($this->config->cache->object_cache_enable) && $this->config->cache->object_cache_enable)
			{
				define('OBJECT_CACHE_ENABLE', true);
			}
			else
			{
				define('OBJECT_CACHE_ENABLE', false);
			}
		}
		else
		{
			define('MYSQL_CACHE_ENABLE', false);
			define('OBJECT_CACHE_ENABLE', false);
		}
	}

	public function _initDatabase() {
		$servers = array();
		$database = $this->config->database;
		$servers[] = $database->master->toArray();
		$slaves = $database->slaves;
		if (!empty($slaves))
		{
			$slave_servers = explode('|', $slaves->servers);
			$slave_users = explode('|', $slaves->users);
			$slave_passwords = explode('|', $slaves->passwords);
			$slave_databases = explode('|', $slaves->databases);
			$slaves = array();
			foreach ($slave_servers as $key => $slave_server)
			{
				if (isset($slave_users[$key]) && isset($slave_passwords[$key]) && isset($slave_databases[$key]))
				{
					$slaves[] = array('server' => $slave_server, 'user' => $slave_users[$key], 'password' => $slave_passwords[$key], 'database' => $slave_databases[$key]);
				}
			}
			$servers[] = $slaves[array_rand($slaves)];
		}
		Yaf_Registry::set('database', $servers);
		if (isset($database->mysql_cache_enable) && $database->mysql_cache_enable && !defined('MYSQL_CACHE_ENABLE'))
		{
			define('MYSQL_CACHE_ENABLE', true);
		}
		if (isset($database->mysql_log_error) && $database->mysql_log_error && !defined('MYSQL_LOG_ERROR'))
		{
			define('MYSQL_LOG_ERROR', true);
		}
		Yaf_Loader::import(APPLICATION_PATH . '/library/db/Db.php');
		Yaf_Loader::import(APPLICATION_PATH . '/library/db/DbQuery.php');
	}
	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		//$dispatcher->setErrorHandler(array($this, 'error'));
		//echo "_initPlugin call first<br/>\n";
		/**
		 * register a plugin
		 */
		//$user = new UserPlugin();
		//$dispatcher->registerPlugin($user);
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//echo "_initRoute call second<br/>\n";
		$router = Yaf_Dispatcher::getInstance()->getRouter();
		/**
		 * add the routes defined in ini config file
		 */
		$router->addConfig(Yaf_Registry::get("config")->routes);
		/**
		 * test this route by access http://yourdomain.com/product/list/?/?/
		 */
		$route  = new Yaf_Route_Rewrite(
			"/product/list/:id/:name",
			array(
				"controller" => "product",
				"action"	 => "info",
			)
		);

		$router->addRoute('dummy', $route);
	}

	public function _initSmarty(Yaf_Dispatcher $dispatcher) {
		//echo "_initSmarty call third<br/>\n";
		Yaf_Loader::import("smarty/Adapter.php");
		$smarty = new Smarty_Adapter(null, Yaf_Registry::get("config")->get("smarty"));
		Yaf_Registry::set("smarty", $smarty);
	//	$dispatcher->setView($smarty);
	}

	public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
		//echo "_initDefaultName call last<br/>\n";
		/**
		 * actully this is unecessary, since all the parameters here is the default value of Yaf
		 */
		$dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
	}
}
