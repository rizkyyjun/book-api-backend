<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/books",
     *     tags={"Books"},
     *     summary="Get a list of books",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response="200", description="List of books"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     * )
     */
    function index(Request $request) {
        $user = Auth::user();
        $books = Book::where('user_id', $user->id)->get();
        return response()->json([
            'data' => $books
        ]); 
        // return BookResource::collection($books);
    }

    /**
     * @OA\Post(
     *     path="/books/add",
     *     tags={"Books"},
     *     summary="Add a new book",
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="isbn", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Book added successfully"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="422", description="Validation error"),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/books/{book_id}/edit",
     *     tags={"Books"},
     *     summary="Update a book",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="book_id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="isbn", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Book updated successfully"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Book not found"),
     *     @OA\Response(response="422", description="Validation error"),
     * )
     */
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
    
    /**
     * @OA\Delete(
     *     path="/books/{book_id}/delete",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="book_id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Book deleted"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Book not found"),
     * )
     */
    function destroy($id) {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'data' => $book,
            'message' => 'Book deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/books/{book_id}",
     *     tags={"Books"},
     *     summary="Get details of a specific book",
     *     @OA\Parameter(
     *         name="book_id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Book details"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="404", description="Book not found"),
     * )
     */
    function detail($id) {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }
}
