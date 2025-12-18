<?php

namespace App\Http\Controllers;

use App\Models\User;

    use App\Models\Doctor;
    use App\Models\SearchHistories;
    use Illuminate\Http\Request;

    class SearchHistoryController extends Controller
    {
        // Save User search in history
        public function store(Request $request)
        {
            $user = User::first();
            $token = $user->createToken('test-token')->plainTextToken;
            $request->validate([
                'search_query' => 'required|string|max:255'
            ]);
            $query = $request->input("search_query");
            SearchHistories::create([
                'user_id' => auth()->id(),
                'search_query' => $query
            ]);
            $doctors = Doctor::where('name', 'like', "%$query%")
            ->orWhereHas('specializations', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
             })
            ->get();
            return response()->json([
                'message' => 'search saved',
                'doctors' => $doctors,
                ]);
        }
        public function index()
        {
        $histories = auth()->user()
            ->searchHistories()
            ->latest()
            ->get();
            return response()->json($histories);
        }

    }
