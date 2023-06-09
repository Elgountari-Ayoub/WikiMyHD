<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Space;
use App\Models\Manual;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $authRole = 'admin';
            // $authRole = Auth::user()->role;

            if ($authRole == 'admin') {
                $articles = Article::with('users', 'space', 'manual')->orderBy('id', 'desc')->get();
                return response()->json([
                    'articles' => $articles
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $articles = $user->articles()->with('users', 'space', 'manual')->latest()->get();
                return response()->json([
                    'articles' => $articles,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'articles' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json([
        //     'request' => $request->all()
        // ]);
        try {
            // VALIDATE REQUEST
            $request->validate([
                'space_id' => 'required',
                'manual_id' => 'required',
                'title' => 'required',
                'content' => 'required'
            ]);

            $article = new Article();
            $article->space_id = $request->space_id;
            $article->manual_id = $request->manual_id;
            $article->title = $request->title;
            $article->content = $request->content;
            $article->save();
            $creator_id = Auth::id();
            $pivotData = [
                'is_creator' => true,
            ];
            $article->users()->syncWithoutDetaching([$creator_id => $pivotData]);

            $article = Article::with('users', 'space', 'manual')->findOrFail($article->id);

            return response()->json([
                'article' => $article
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'article' => null,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // return response()->json([
        //     'articles' => []
        // ]);
        try {
            if (Auth::user()->role == 'admin') {
                $article = Article::with('users', 'space', 'manual')->findOrFail($id);

                return response()->json([
                    'article' => $article
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $article = $user->articles()->with('users', 'space', 'manual')->findOrFail($id);

                return response()->json([
                    'article' => $article
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'article' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // VALIDATE REQUEST
            $request->validate([
                'title' => 'required',
                'content' => 'required'
            ]);

            $article = Article::findOrFail($id);
            $article->title = $request->title;
            $article->content = $request->content;
            $article->save();

            $article = Article::with('users', 'space', 'manual')->find($article->id);
            return response()->json([
                'article' => $article
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'article' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $article = Article::with('users', 'space', 'manual')->findOrFail($id);
            $article->delete();

            return response()->json([
                'article' => $article
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'article' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }
}
