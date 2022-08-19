<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\PriceProfit;
use Models\Setting;

class SettingController
{
    public function index(Request $request)
	{
		$settings = Setting::get();
		
		return render('backend/admin/setting/list', [
			'settings' => $settings
		]);
	}

	

	public function update(Request $request)
	{
		$validator = $request->validate([
            'setting_keys' => 'required',
			'setting_values' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		foreach ($request->setting_keys as $index => $settingKey) {
            if (empty($settingValue = $request->setting_values[$index])) {
                return redirect()->withFlashError($settingKey . ' setting can not be empty')->with('old', $request->all())->back();
            }

            Setting::where('id', $request->setting_ids[$index])->update([
                'value' => $settingValue
            ]);
        }
        
        return redirect()->withFlashSuccess('Settings updated successfully!')->with('old', $request->all())->back();
	}

	public function priceProfits(Request $request)
	{
		$profits = PriceProfit::get();
		
		return render('backend/admin/setting/price-profits', [
			'profits' => $profits
		]);
	}

	public function profitUpdate(Request $request) {
		
		foreach($request->min_price as $key => $min_price) {
			$profit = PriceProfit::find($key);
			$profit->min_price = $min_price;
			$profit->max_price = $request->max_price->{$key};
			$profit->profit_perc = $request->profit_perc->{$key};
			$profit->save();
		}

		return redirect()->withFlashSuccess('Profit Price updated successfully!')->with('old', $request->all())->back();
	}
	

}