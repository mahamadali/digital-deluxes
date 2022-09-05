<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'users';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->string('country_code')->nullable()->after('password');
			$table->string('age')->nullable()->after('phone');
			$table->string('address')->nullable()->after('age');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('country_code');
			$table->dropColumn('address');
			$table->dropColumn('age');
			return $table;
		});
	}

};
