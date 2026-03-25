<?php

	function allow(string $role, string $permission) : bool {

		// store allowed permissions
		
		$sql = 'SELECT * FROM ROLE_PERMISSIONS AS rp
			WHERE rp.role = $role';

		



	}


?>