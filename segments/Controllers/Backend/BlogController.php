<?php

namespace Controllers\Backend;

use Bones\Request;
use Models\Blog;

class BlogController
{
	public function index(Request $request)
	{
		$blogs = Blog::orderBy('id')->get();

		return render('backend/admin/blogs/list', [
			'blogs' => $blogs
		]);
	}

	public function create()
	{
		return render('backend/admin/blogs/create');
	}

	public function store(Request $request)
	{
		$validator = $request->validate([
			'title' => 'required|min:2',
            'description' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->description = $request->description;

        if ($request->hasFile('post_img')) {
			$logo = $request->files('post_img');
			if (!$logo->isImage()) {
				return redirect()->withFlashError('Post image must be type of image')->with('old', $request->all())->back();
			} else {
				$uploadTo = 'assets/uploads/blogs/';
				$uploadAs = 'blog-img-' . uniqid() . '.' . $logo->extension;
				if ($logo->save(pathWith($uploadTo), $uploadAs)) {
                    $logoPath = $uploadTo . $uploadAs;
                    $blog->post_img = $logoPath;
				} else {
					return redirect()->withFlashError('Profile picture upload failed!')->with('old', $request->all())->back();
				}
			}
		}

        $blog->save();

		return redirect(route('admin.blogs.index'))->withFlashSuccess('Post created successfully!')->go();
	}

	public function edit(Request $request, Blog $blog)
	{
		return render('backend/admin/blogs/edit', [
			'blog' => $blog
		]);
	}

	public function update(Request $request, Blog $blog)
	{
		$validator = $request->validate([
			'title' => 'required|min:2',
            'description' => 'required',
		]);

		if ($validator->hasError()) {
			return redirect()->withFlashError(implode('<br>', $validator->errors()))->with('old', $request->all())->back();
		}

        $blog->title = $request->title;
        $blog->description = $request->description;

        if ($request->hasFile('post_img')) {
			$logo = $request->files('post_img');
			if (!$logo->isImage()) {
				return redirect()->withFlashError('Post image must be type of image')->with('old', $request->all())->back();
			} else {
				$uploadTo = 'assets/uploads/blogs/';
				$uploadAs = 'blog-img-' . uniqid() . '.' . $logo->extension;
				if ($logo->save(pathWith($uploadTo), $uploadAs)) {
                    $logoPath = $uploadTo . $uploadAs;
                    $blog->post_img = $logoPath;
				} else {
					return redirect()->withFlashError('Profile picture upload failed!')->with('old', $request->all())->back();
				}
			}
		}

        $blog->save();
        return redirect(route('admin.blogs.index'))->withFlashSuccess('Post updated successfully!')->go();
	}

	public function delete(Request $request, Blog $blog)
	{
		$blog->delete();
		return redirect()->withFlashError('Blog deleted successfully!')->back();
	}
}
