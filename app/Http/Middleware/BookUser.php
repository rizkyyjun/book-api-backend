<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $book = Book::findOrFail($request->book_id);
        
        if ($book->user_id != $currentUser->id){
            return response()->json([
                'message' => 'You are not allowed to access this book'
            ], 403);
        }


        return $next($request);
    }
}
