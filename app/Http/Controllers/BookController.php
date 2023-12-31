<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    function index(Request $request) {
        $user = Auth::user();
        $books = Book::where('user_id', $user->id)->get();
        return response()->json([
            'data' => $books
        ]); 
        // return BookResource::collection($books);
    }

    function store(Request $request) {
        $user = Auth::user();
        $validated = $request->validate([
            'isbn' => 'required',
            'title' => 'required'
        ]);
        
        $request["user_id"] = Auth::user()->id;
        $book = Book::create($request->all());
        return response()->json([
            'data' => $book,
            'message' => 'Book created successfully'
        ]);
        
    }

    function update(Request $request, $id) {
        // dd($request->all());
        $user = Auth::user();
        $validated = $request->validate([
            'isbn' => 'required',
            'title' => 'required'
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json([
            'data' => $book,
            'message' => 'Book updated successfully'
        ]);
    }
    
    function destroy($id) {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'data' => $book,
            'message' => 'Book deleted successfully'
        ]);
    }

    function detail($id) {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }
}
