<?php

class ActiveModel extends Object{
   public static function getUsers() {
		$sql = "select * from jx_user_client where ID=80";
		$result = Db::getInstance(false)->executeS($sql);
		//print_r(Db::getInstance(false));
		if ($result)
		{
			return $result;
		}

		return false;
	}

}
