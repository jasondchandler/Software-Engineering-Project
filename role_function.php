<?php

	function allow(string $permission) : bool {

		return in_array($permission, $_SESSION["permissions"]);

	}


?>