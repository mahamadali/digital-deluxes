<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\CMS;

class CMSController
{

	public function create()
	{
        $pages = CMS::get();
		return render('backend/admin/cms/create', ['pages' => $pages]);
	}

	public function store(Request $request, CMS $page)
	{
		$validator = $request->validate([
            'description' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}
        $page->description = $request->description;
        $page->save();

		return redirect(route('admin.cms.create'))->withFlashSuccess($page->title.' info updated successfully!')->go();
	}
}
