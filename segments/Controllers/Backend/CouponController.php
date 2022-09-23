<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Coupon;
use Models\TransactionLog;

class CouponController
{
	public function index(Request $request) 
    {
        $coupons = Coupon::orderBy('id')->get();
		return render('backend/admin/coupons/index', [
			'coupons' => $coupons
		]);
	}

    public function create(Request $request) 
    {	
        return render('backend/admin/coupons/create');
	}

    public function store(Request $request) 
    {	
        $validator = $request->validate([
			'code' => 'required|unique:coupons,code',
			'percentage' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

        $coupon =  new Coupon();
        $coupon->code = $request->code;
        $coupon->percentage = $request->percentage;
        $coupon->price_limit = $request->price_limit;
        $coupon->activation_count = $request->activation_count;
        $coupon->condition = $request->condition;
        $coupon->save();
        return redirect(route('admin.coupons.index'))->withFlashSuccess('Coupon created successfully!')->go();
	}

    public function edit(Request $request, Coupon $coupon) 
    {	
        return render('backend/admin/coupons/edit', ['coupon' => $coupon]);
	}

    public function update(Request $request, Coupon $coupon) 
    {	
        $validator = $request->validate([
			'code' => 'required|unique:coupons,code,'.$coupon->id,
			'percentage' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

        $coupon->code = $request->code;
        $coupon->percentage = $request->percentage;
        $coupon->price_limit = $request->price_limit;
        $coupon->activation_count = $request->activation_count;
        $coupon->condition = $request->condition;
        $coupon->save();
        return redirect(route('admin.coupons.index'))->withFlashSuccess('Coupon updated successfully!')->go();
	}

	public function changeStatus(Request $request, Coupon $coupon)
	{
        $coupon->status = $request->status;
        $coupon->save();
		return response()->json(['stauts'=> 'success', 'message' => 'Coupon '. ucfirst(strtolower($request->status))]);
	}
}
