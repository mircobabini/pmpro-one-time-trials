<?php
// TODO init database structure

// todo detect same credit card
	// pmproott_account_numbers => id, user_id, salted account number

// todo detect same email on different gateway
	// gateway email address maybe? useful to detect same user on different gateways.

// todo detect same user on same gateway
	// pmproott_gateway_customer_id => id, user_id, gateway, gateway_customer_id
