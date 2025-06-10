<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'restaurant')->get();
        return response()->json($reviews);
    }

    public function store(StoreReviewRequest $request)
    {

        try {
            $review = Review::create($request->validated());
            return response()->json(['success' => true, 'data' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $review = Review::with('user', 'restaurant')->find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        return response()->json($review);
    }

    public function update(UpdateReviewRequest $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        try {
            $review->update($request->validated());
            return response()->json(['success' => true, 'data' => $review]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        try {
            $review->delete();
            return response()->json(['message' => 'Reseña eliminada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
