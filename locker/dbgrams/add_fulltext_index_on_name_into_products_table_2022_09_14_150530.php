<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'products';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->setFullText(['name'], 'fulltext_index_on_name_column');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropIndex('fulltext_index_on_name_column');
			return $table;
		});
	}

};
