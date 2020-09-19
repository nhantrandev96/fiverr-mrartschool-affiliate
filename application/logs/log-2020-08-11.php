<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-08-11 13:13:53 --> Query error: Table 'test.ci_sessions' doesn't exist - Invalid query: SELECT 1
FROM `ci_sessions`
WHERE `id` = 'o77jq0t11tvev0iuemdvhtb463v4qf6g'
ERROR - 2020-08-11 11:18:32 --> Query error: Unknown column 'op.vendor_id' in 'where clause' - Invalid query: 
			SELECT 
				SUM(wallet.amount) as total 
			FROM wallet 
				LEFT JOIN order_products op ON op.order_id = wallet.reference_id
			WHERE 
				wallet.type IN('sale_commission')
				AND (op.vendor_id IS NULL OR op.vendor_id = 0)  
				AND wallet.status > 0
				AND  1 
				AND wallet.comm_from='store'
			
ERROR - 2020-08-11 11:18:42 --> Query error: Unknown column 'op.vendor_id' in 'where clause' - Invalid query: 
			SELECT 
				SUM(wallet.amount) as total 
			FROM wallet 
				LEFT JOIN order_products op ON op.order_id = wallet.reference_id
			WHERE 
				wallet.type IN('sale_commission')
				AND (op.vendor_id IS NULL OR op.vendor_id = 0)  
				AND wallet.status > 0
				AND  1 
				AND wallet.comm_from='store'
			
