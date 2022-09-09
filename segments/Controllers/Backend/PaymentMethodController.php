<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\PaymentMethod;
use Models\PaymentLogo;

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

     public function addpaymentLogos(Request $request,PaymentMethod $payment) 
    {

     	$logos = [];
		if(!empty($payment)) {
			$logos = PaymentLogo::where('payment_id', $payment->id)->get();
		}
		return render('backend/admin/payment-methods/add_payment_logos',['payment' => $payment , 'logos' => $logos]);
    }

    public function addpaymentLogosPost(Request $request) {
		
    	$validator = $request->validate([
			'logo' => 'required',
			'main_logo' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		if ($request->hasFile('logo')) {
			
			foreach($request->files('logo') as $key => $logo):
				$PaymentLogo = new PaymentLogo();
				$PaymentLogo->payment_id = $request->payment_id;
				
				if (!$logo->isImage()) {
					return redirect()->withFlashError('Logo must be type of image')->with('old', $request->all())->back();
				} else {
					$uploadTo = 'assets/uploads/payment-logos/';
					$uploadAs = 'pl-' . uniqid() . '.' . $logo->extension;
					if ($logo->save(pathWith($uploadTo), $uploadAs)) {
						$logoPath = $uploadTo . $uploadAs;
						$PaymentLogo->logo = $logoPath;
					} else {
						return redirect()->withFlashError('Logos picture upload failed!')->with('old', $request->all())->back();
					}
				}
				$PaymentLogo->save();
			endforeach;
		}

		  if ($request->hasFile('main_logo')) {
			$logo = $request->files('main_logo');
			if (!$logo->isImage()) {
				return redirect()->withFlashError('Main Logo image must be type of image')->with('old', $request->all())->back();
			} else {
				$uploadTo = 'assets/uploads/payment-logos/';
				$uploadAs = 'pl-' . uniqid() . '.' . $logo->extension;
				if ($logo->save(pathWith($uploadTo), $uploadAs)) {
                    $logoPath = $uploadTo . $uploadAs;
                    $PaymentLogoData['main_logo'] = $logoPath;
                    PaymentMethod::where('id', $request->payment_id)->update($PaymentLogoData);
				} else {
					return redirect()->withFlashError('Logos picture upload failed!')->with('old', $request->all())->back();
				}
			}
			
		}
		return redirect(route('admin.settings.payment-methods.add-payment-logo',['payment' => $request->payment_id]))->withFlashSuccess('Logos uploaded successfully')->go();
	}

	public function paymentLogoRemove(Request $request, PaymentLogo $logo) {
		$logo->delete();
		return redirect()->withFlashSuccess('Logo deleted successfully')->back();
	}
}