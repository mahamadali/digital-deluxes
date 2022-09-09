<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'payment_methods';

	public function fill()
	{
		Database::__insertMulti([
			[
				'title' => 'Wompi',
				'type' => 'both',
				'currency' => 'COP',
				'status' => 'ACTIVE'
			],
			[
				'title' => 'Stripe',
				'type' => 'wallet',
				'currency' => 'USD',
				'status' => 'ACTIVE'
			],
			[
				'title' => 'Mercado Pago',
				'type' => 'both',
				'currency' => 'COP',
				'status' => 'ACTIVE'
			],
			[
				'title' => 'Paypal',
				'type' => 'both',
				'currency' => 'USD',
				'status' => 'ACTIVE'
			],
			[
				'title' => 'Coinbase',
				'type' => 'both',
				'currency' => 'USD',
				'status' => 'ACTIVE'
			],
		], null, $this->table);
	}

};