<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\TransactionLog;

class TransactionController
{
	public function index(Request $request) {
		
        $transactions = TransactionLog::orderBy('id')->get();
		return render('backend/admin/transactions/index', [
			'transactions' => $transactions
		]);
	}

	public function delete(Request $request)
	{
		
		TransactionLog::whereIn('id',$request->transactionIds)->delete();
		return response()->json(['stauts'=> 'success', 'msg' => 'Transaction deleted successfully']);
	}
}
