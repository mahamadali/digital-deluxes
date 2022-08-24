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
			$table->string('national_identification_id')->after('profile_image');
			$table->unsignedBigInteger('country')->after('national_identification_id');
			$table->string('city')->after('country');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('national_identification_id');
			$table->dropColumn('country');
			$table->dropColumn('city');
			return $table;
		});
	}

};
