<?php

namespace Controllers\Frontend;

use Bones\Request;
use Bones\Session;
use Models\Blog;
use Models\BlogView;

class BlogController
{
    public function index(Request $request) {
		
		$blogs = Blog::get();
		return render('frontend/blogs/index', [
			'blogs' => $blogs
		]);
	}

    public function view(Request $request, Blog $blog, $slug) {
		$blogView = new BlogView();
		$blogView->blog_id = $blog->id;
		$blogView->ip = get_client_ip();
		$blogView->save();
		return render('frontend/blogs/view', [
			'blog' => $blog
		]);
	}
    
    
}