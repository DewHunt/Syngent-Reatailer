<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Category;
use DB, Validator;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('name','asc')->get();

        if (isset($categories) && $categories->isNotEmpty()) {
            Log::info('Load Category Lists');
        } else {
            Log::warning('Category Lists Not Found');
        }
        return view('admin.categories.index')->with(compact('categories'));
    }

    public function add() {
        return view('admin.categories.add');
    }

    public function save(Request $request) {
        // dd($request->all());
        $msg = "";
        $categoryInfo = Category::create([
            "name"=> $request->name,
            "status"=> $request->status,
            "created_at"=> date('Y-m-d'),
        ]);

        if ($categoryInfo) {
            Log::info('Create Category Successfully');
            $msg = "Create Category Successfully";
        } else {
            Log::info('Create Category Failed');
            $msg = "Create Category Failed";
        }

        return redirect(route('category.index'))->with('msg',$msg);
    }

    public function edit($id) {
        $categoryInfo = Category::find($id);
        return view('admin.categories.edit')->with(compact('categoryInfo'));
    }

    public function update(Request $request) {
        // dd($request->all());
        $msg = "";
        $categoryId = $request->categoryId;
        $categoryInfo = Category::find($categoryId);
        $categoryInfo->update([
            "name"=> $request->name,
            "status"=> $request->status,
            "updated_at"=> date('Y-m-d'),
        ]);

        if ($categoryInfo) {
            Log::info('Update Category Successfully');
            $msg = "Update Category Successfully";
        } else {
            Log::info('Update Category Failed');
            $msg = "Update Category Failed";
        }

        return redirect(route('category.index'))->with('msg',$msg);
    }

    public function delete($id) {
        $msg = "";
        $result = Category::destroy($id);

        if ($result) {
            Log::info('Delete Category Successfully');
            $msg = "Delete Category Successfully";
        } else {
            Log::info('Delete Category Failed');
            $msg = "Delete Category Failed";
        }

        return redirect(route('category.index'))->with('msg',$msg);
    }
}
