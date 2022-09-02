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
			$table->string('currency')->after('type');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('currency');
			return $table;
		});
	}

};
