<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'transaction_logs';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->enum('kind_of_tx', ['CREDIT', 'DEBIT'])->default('CREDIT')->after('payment_method_id');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('kind_of_tx');
			return $table;
		});
	}

};
