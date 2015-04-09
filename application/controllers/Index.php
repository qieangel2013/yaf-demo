<?php
/**
 * 默认的控制器
 * 当然, 默认的控制器, 动作, 模块都是可用通过配置修改的
 * 也可以通过$dispater->setDefault*Name来修改
 */
class IndexController extends Yaf_Controller_Abstract {
	/**
	 * Action Map
	 */
	public $actions = array(
		"action" => "actions/Index.php"
	);

	/**
	 * 如果定义了控制器的init的方法, 会在__construct以后被调用
	 */
	public function init() {
		//echo "controller init called<br/>";
		$config = Yaf_Application::app()->getConfig();
		$this->getView()->assign("title", "Agile Platform Demo");
		$this->getView()->assign("webroot", $config->webroot);
	}

	public function a_b_li_name_userAction() {
	}

	public function indexAction() {
		Yaf_Loader::import(APPLICATION_PATH."/models/db.php");
		$mArticle = new ActiveModel();
		print_r($mArticle->getUsers());
		exit;
		$session = Yaf_Session::getInstance();
		if ($session->cout) {
			++$session->cout;
		} else {
			$session->cout = 1;
		}

		echo "Session Count : " . $session["cout"] . "<br/>";

		$value  = "laruence";
		$this->getView()->assign("body", "Hello Wrold<br/>");
		//$this->getView()->assignRef("name", $value);
		$action = "test";
		//$this->forward($action, array("name" => "value"));
		unset($action);

		echo $this->getView()->render("index/index.html", array("page" => array("index"=>2)));
	}


	public function testAction() {
		/** 
		 * 关闭视图输出
		 */
		$this->getView()->assign("name", $this->getRequest()->getParam("name"));

		if ($this->getRequest()->isXmlHttpRequest()) {
			Yaf_Dispatcher::getInstance()->disableView();
		}
	}
}
