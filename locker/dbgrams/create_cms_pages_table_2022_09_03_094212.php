<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'cms_pages';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('title');
			$table->string('slug');
			$table->longText('description');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
