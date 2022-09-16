<?php

namespace Controllers\Frontend;

use Bones\Request;
use Bones\Session;
use Models\Blog;

class BlogController
{
    public function index(Request $request) {
		
		$blogs = Blog::get();
		return render('frontend/blogs/index', [
			'blogs' => $blogs
		]);
	}

    public function view(Request $request, Blog $blog, $slug) {
		
		return render('frontend/blogs/view', [
			'blog' => $blog
		]);
	}
    
    
}