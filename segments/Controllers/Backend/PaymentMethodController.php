<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\PaymentMethod;

class PaymentMethodController
{
    public function index(Request $request)
	{
		$payment_methods = PaymentMethod::get();
		
		return render('backend/admin/payment-methods/list', [
			'payment_methods' => $payment_methods
		]);
	}
    
    public function changeStatus(Request $request) 
    {
        $paymentMethod = PaymentMethod::find($request->id);
        $paymentMethod->status = $request->status;
        $paymentMethod->save();

        return response()->json(['status' => 200, 'message' => 'Changes saved!']);
    }
}