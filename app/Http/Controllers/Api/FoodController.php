<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use LDAP\Result;
use PharIo\Manifest\Url;

class FoodController extends Controller
{
    private function getImagePath(Request $request, string $image): string
    {
        return $request->schemeAndHttpHost() . '/storage/images/' . $image;
    }

    static private $rules = [
        'name' => 'required|unique:foods,name',
        'price' => 'required|numeric',
        'image' => 'required|file',
        'description' => 'nullable',
        'category_id' => 'nullable'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $foods = Food::all();
            // dd(resp);
            foreach ($foods as $food) {
                $food->image = self::getImagePath($request, $food->image);
            }

            return response($foods);
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
            $validated = $request->validate(self::$rules);
            $path_image = $request->file('image')->store('public/images');
            $validated['image'] = substr($path_image, 14);    //filename
            $food = Food::create($validated);
            $food->image = self::getImagePath($request, $food->image);
            return response($food, 201);
        } catch (ValidationException | Exception $e) {
            return response(["message" => $e->getMessage()], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Food $food)
    {
        try {
            $food->image = self::getImagePath($request, $food->image);
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
    public function update(Request $request, int $id)
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
    public function destroy(int $id)
    {
        $food = Food::find($id);

        if ($food) {
            $food->delete();
            Storage::delete('public/images/' . $food->image);
            return response($food);
        } else {
            return response([
                "message" => "Error: food id_$id not found"
            ]);
        }
    }
}
