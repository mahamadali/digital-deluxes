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
			$table->enum('status', ['Active', 'Deactivate'])->default('Active')->modify();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			return $table;
		});
	}

};
