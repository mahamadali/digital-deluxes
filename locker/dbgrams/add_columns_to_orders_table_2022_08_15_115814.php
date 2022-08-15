<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'orders';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->string('kg_orderid')->nullable()->after('user_id');
			$table->string('kg_order_status')->nullable()->after('kg_orderid');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('kg_order_status');
			$table->dropColumn('kg_orderid');
			return $table;
		});
	}

};
