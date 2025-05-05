<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function booksPerYear()
    {
        $stats = Book::select('year', DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return response()->json($stats);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::with('author')->orderBy('id', 'desc');
        if (request()->filled('search')) {
            $name = request()->input('search');
            $query->where('title', 'like', '%' . $name . '%');
        }

        $book = $query->paginate(5);
        return response()->json($book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'publisher' => 'required|boolean',
            'date' => 'required|date',
            'author_id' => 'nullable|exists:authors,id',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
            'publisher' => 'sometimes|required|boolean',
            'date' => 'sometimes|required|date',
            'author_id' => 'sometimes|nullable|exists:authors,id',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(null, 204);
    }
}
