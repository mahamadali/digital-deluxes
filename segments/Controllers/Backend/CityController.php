<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\City;

class CityController
{
	public function index(Request $request)
	{
		$cities = City::orderBy('id')->get();

		return render('backend/admin/city/list', [
			'cities' => $cities
		]);
	}

	public function create()
	{
		return render('backend/admin/city/create');
	}

	public function store(Request $request)
	{
		$validator = $request->validate([
			'name' => 'required|min:2|max:30',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$cityData = $request->getOnly(['name']);

		$city = City::create($cityData);

		if (!empty($city)) {
			$city->save();
			return redirect(route('admin.cities.list'))->withFlashSuccess('Category created successfully!')->go();
		} else {
			return redirect()->withFlashError('Something went wrong!')->back();
		}
	}

	public function edit(Request $request, City $city)
	{
		return render('backend/admin/city/edit', [
			'city' => $city
		]);
	}

	public function update(Request $request)
	{
		$validator = $request->validate([
			'name' => 'required|min:2|max:30',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$cityData = $request->getOnly(['name']);

		if (City::where('id', $request->id)->update($cityData)) {
			return redirect()->withFlashSuccess('City updated successfully!')->with('old', $request->all())->back();
		} else {
			return redirect()->withFlashError('Oops! Something went wrong!')->back();
		}
	}

	public function delete(Request $request, City $city)
	{
		if (!empty($city)) {
			City::where('id', $city->id)->delete();
			return redirect()->withFlashError('City deleted successfully!')->back();
		} else {
			return redirect()->withFlashError('You have no access to delete this item!')->back();
		}
	}
}
