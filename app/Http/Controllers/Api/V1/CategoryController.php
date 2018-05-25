<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $articles = Category::find(1)->articles;
        $categoies = Category::latest()
            ->where('status', Category::STATUS_ACTIVE)
            ->paginate(10);

        return $categoies;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        
        // save
        $category = new Category;
        $category->name = $request->input('name');
        $category->slug = str_slug($request->input('name'));
        $category->save();

        return response()->json([
            'msg' => 'Tạo thành công!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $paginate_item = 7;
        $category = Category::where('slug', $slug)->where('status', Category::STATUS_ACTIVE)->first();
        $articles = $category->articles()->where('status', Article::STATUS_ACTIVE)->with('comments')->orderBy('id', 'desc')->paginate($paginate_item);

        return compact('category' ,'articles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::findOrFail($id);

        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        
        // save
        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->save();

        return response()->json([
            'msg' => 'Sửa thành công!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete
        $category = new Category;
        $category->delete_cate($id);

        return response()->json([
            'msg' => 'Thành công!'
        ], 200);
    }
}
