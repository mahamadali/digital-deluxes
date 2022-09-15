<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'payment_methods';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->string('transaction_fee')->after('status');
			$table->enum('new_users', [1, 0])->default(1)->after('transaction_fee');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('transaction_fee');
			$table->dropColumn('new_users');
			return $table;
		});
	}

};
