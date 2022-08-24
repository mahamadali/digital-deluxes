<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'countries';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('phone_code');
			$table->string('country_code');
			$table->string('country_name');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
