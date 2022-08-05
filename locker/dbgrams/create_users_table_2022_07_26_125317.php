<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'users';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->string('password');
			$table->string('phone')->nullable();
			$table->unsignedBigInteger('role_id');
			$table->timestamps();
			$table->softDelete();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
