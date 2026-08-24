<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\{Blogs};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};
use Illuminate\Support\Str;
class BlogController
{
    public function blogs(Request $request)
    {
        try {
            $method = $request->method();
            if ($method == 'GET') {
                $data = Blogs::whereNull('is_delete')->orderBy('publishdate', 'desc')->paginate(5);
                $recentblog = Blogs::whereNull('is_delete')->orderBy('publishdate', 'desc')->paginate(5);
                $categoryCounts = Blogs::whereNull('is_delete')->select('category', DB::raw('count(*) as total'))
                    ->groupBy('category')
                    ->get();
                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'recentblog' => $recentblog,
                    'categoryCounts' => $categoryCounts
                ]);
            }

            if ($request->id) {
                $blogs = Blogs::find($request->id);
                if ($request->hasFile('featuredimage')) {
                    $file = $request->file('featuredimage');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $folder = 'front/blog';
                    $file->move(public_path($folder), $filename);
                    $blogs->image = $folder . '/' . $filename;
                }
                $blogs->title = $request->title ?? null;
                $blogs->slug = $request->slug;
                $blogs->shortdes = $request->shortdes ?? null;
                $blogs->content = $request->content ?? null;
                $blogs->category = $request->category ?? null;
                $blogs->tags = $request->tags ?? null;
                $blogs->author = $request->author ?? null;
                $blogs->publishdate = $request->publishdate ?? null;
                $blogs->metadescription = $request->metadescription ?? null;
                $blogs->metatitle = $request->metatitle ?? null;
                $blogs->keywords = $request->keywords ?? null;
                $blogs->status = $request->status ?? null;
                $blogs->featured = $request->featured ?? null;
                $blogs->updated_at = now();
                $blogs->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Blog updated successfully',
                    'blogs' => $blogs
                ]);
            } else {
                $blog = new Blogs();
                if ($request->hasFile('featuredimage')) {
                    $file = $request->file('featuredimage');

                    $filename = time() . '_' . $file->getClientOriginalName();

                    $folder = 'front/blog';
                    $file->move(public_path($folder), $filename);

                    $blog->image = $folder . '/' . $filename;
                }

                $blog->title = $request->title ?? null;
                $blog->slug = $request->slug;
                $blog->shortdes = $request->shortdes ?? null;
                $blog->content = $request->content ?? null;
                $blog->category = $request->category ?? null;
                $blog->tags = $request->tags ?? null;
                $blog->author = $request->author ?? null;
                $blog->publishdate = $request->publishdate ?? null;
                $blog->metadescription = $request->metadescription ?? null;
                $blog->metatitle = $request->metatitle ?? null;
                $blog->keywords = $request->keywords ?? null;
                $blog->status = $request->status ?? null;
                $blog->featured = $request->featured ?? null;
                $blog->created_at = now();
                $blog->updated_at = now();
                $blog->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Blog created successfully',
                    'blog' => $blog
                ]);

            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function blogView(Request $request)
    {
        try {
            $id = $request->input('data.id');
            $category = $request->input('data.category');

            if (!empty($id)) {
                $blog = Blogs::find($id);

                if ($blog) {
                    return response()->json([
                        'status' => true,
                        'data' => $blog
                    ]);
                }
            } elseif (!empty($category)) {
                $blogs = Blogs::where('category', $category)->whereNull('is_delete')->paginate(2);
                $categoryCounts = Blogs::whereNull('is_delete')->select('category', DB::raw('count(*) as total'))
                    ->groupBy('category')
                    ->get();
                $recent = Blogs::select('id', 'title')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                if ($blogs->isNotEmpty()) {
                    return response()->json([
                        'status' => true,
                        'data' => $blogs,
                        'categoryCounts' => $categoryCounts,
                        'recent' => $recent
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Please provide either an ID or a category.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function deleteBlog(Request $request)
    {
        $arequest = $request->data;
        $blogId = $arequest['id'];
        $product = Blogs::find($blogId)->delete();
        if ($product) {
            return response()->json([
                'status' => true,
                'message' => 'Blog deleted successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Blog not deleted .'
        ]);
    }
    public function trashBlog(Request $request)
    {
        $arequest = $request->data;
        $blogId = $arequest['id'];
        $product = Blogs::find($blogId);
        $product->is_delete = now();
        $product->updated_at = now();
        $product->save();
        //$product->delete();
        return response()->json([
            'status' => true,
            'message' => 'blog deleted successfully.'
        ]);
    }
    public function recycleBlog(Request $request)
    {
        $arequest = $request->data;
        $blogId = $arequest['id'];
        $product = Blogs::find($blogId);
        $product->is_delete = null;
        $product->updated_at = now();
        $product->save();
        //$product->delete();
        return response()->json([
            'status' => true,
            'message' => 'blog restored successfully.'
        ]);

    }

}