<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FoodController extends Controller
{
    static private $rules = [
        'name' => 'required|unique:foods,name',
        'price' => 'required|numeric',
        'image' => 'required|url'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Food::all();
        } catch (Exception $e) {
            return response($e, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(self::$rules);

            $food = Food::create($request->all());
            return response($food, 201);
        } catch (ValidationException | Exception $e) {
            return response(["message" => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        try {

            return response($food);
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
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate(self::$rules);
            $food = Food::find($id);
            $food->update($validated);

            return response($food);
        } catch (Exception $e) {
            return response($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);

        if ($food) {
            $isDeleted = $food->delete();
            return response($food);
        } else {
            return response([
                "message" => "Error: food id_$id not found"
            ]);
        }
    }
}
