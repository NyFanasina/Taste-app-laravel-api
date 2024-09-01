<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Category::all();
        } catch (Exception $e) {
            return response($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $category = Category::create(["name" => $request->name]);
            return $category;
        } catch (Exception $e) {
            return response([
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $category->update([
                "name" => $request->name
            ]);
            return response([
                "message" => "Ressource updated",
                "category" => $category
            ]);
        } catch (Exception $e) {
            return response([
                'code' => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response([
                "message" => "Ressource deleted",
                "category" => $category
            ]);
        } catch (Exception $e) {
            return response([
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }
}
