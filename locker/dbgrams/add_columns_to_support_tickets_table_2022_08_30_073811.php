<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'support_tickets';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->enum('status', ['PENDING', 'COMPLETED'])->default('PENDING')->after('attachments');
			$table->enum('is_read', ['READ', 'UNREAD'])->default('UNREAD')->after('status');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('status');
			$table->dropColumn('is_read');
			return $table;
		});
	}

};
