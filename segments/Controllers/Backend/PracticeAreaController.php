<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\PracticeArea;

class PracticeAreaController
{
	public function index(Request $request)
	{
		$practice_areas = PracticeArea::orderBy('id')->get();

		return render('backend/admin/practice_area/list', [
			'practice_areas' => $practice_areas
		]);
	}

	public function create()
	{
		return render('backend/admin/practice_area/create');
	}

	public function store(Request $request)
	{
		$validator = $request->validate([
			'title' => 'required|min:2|max:30',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$practiceAreaData = $request->getOnly(['title']);

		$practice_area = PracticeArea::create($practiceAreaData);

		if (!empty($practice_area)) {
			$practice_area->save();
			return redirect(route('admin.practice_area.list'))->withFlashSuccess('Practice Area created successfully!')->go();
		} else {
			return redirect()->withFlashError('Something went wrong!')->back();
		}
	}

	public function edit(Request $request, PracticeArea $practice_area)
	{
		return render('backend/admin/practice_area/edit', [
			'practice_area' => $practice_area
		]);
	}

	public function update(Request $request)
	{
		$validator = $request->validate([
			'title' => 'required|min:2|max:30',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

		$practiceAreaData = $request->getOnly(['title']);

		if (PracticeArea::where('id', $request->id)->update($practiceAreaData)) {
			return redirect()->withFlashSuccess('Practice Area updated successfully!')->with('old', $request->all())->back();
		} else {
			return redirect()->withFlashError('Oops! Something went wrong!')->back();
		}
	}

	public function delete(Request $request, PracticeArea $practice_area)
	{
		if (!empty($practice_area)) {
			PracticeArea::where('id', $practice_area->id)->delete();
			return redirect()->withFlashError('Practice Area deleted successfully!')->back();
		} else {
			return redirect()->withFlashError('You have no access to delete this item!')->back();
		}
	}
}
