<?php

namespace App\Http\Controllers;

use App\Mail\ShareArticleMail;
use App\Models\User;
use App\Models\Space;
use App\Models\Manual;
use App\Models\Article;
use App\Models\ArticleVersion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Pdf;
use Barryvdh\DomPDF\Facade\Pdf;


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
                $articles = Article::with('users', 'space', 'manual', 'versions')->orderBy('id', 'desc')->get();
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
            // $creator_id = 2;
            $pivotData = [
                'is_creator' => true,
            ];
            $article->users()->syncWithoutDetaching([$creator_id => $pivotData]);

            $article = Article::with('users', 'space', 'manual')->findOrFail($article->id);

            // Get the maximum version number for the article
            $maxVersionNumber = $article->versions()->max('version_number');
            $versionNumber = $maxVersionNumber ? $maxVersionNumber + 0.1 : 1;

            // Create a new version in the article_versions table
            ArticleVersion::create([
                'article_id' => $article->id,
                'user_id' => Auth::id(),
                'title' => $article->title,
                'content' => $article->content,
                'version_number' => $versionNumber
            ]);

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
        try {
            if (Auth::user()->role == 'admin') {
                $article = Article::with('users', 'space', 'manual')->findOrFail($id);

                return response()->json([
                    'article' => $article
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $article = Article::findOrFail($id);
                $manual = $article->manual;
                $manualBelongsToUser = $user->manuals()->where('manual_id', $manual->id)->exists();
                if ($manualBelongsToUser) {
                    $article = Article::with('users', 'space', 'manual')->findOrFail($id);
                } else {
                    $article = null;
                    throw new Exception("the user hasn't the access to the manual of the this article", 1);
                }
                // Worning  
                // $article = Article::with('users', 'space', 'manual')->findOrFail($id);

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
    public function exportArticle($id)
    {
        try {
            $article = Article::find($id);
            $lastArticle = ArticleVersion::where('article_id', $article->id)->latest('id')->first();
            $data = [
                'article' => $article
            ];
            $filename = $article->title . '-' . $lastArticle->version_number . '.pdf';

            $pdf = Pdf::loadView('pdf.article', $data);
            $pdfContents = $pdf->download();

            return response($pdfContents)
                ->header('Content-Type', 'application/pdf')
                ->header('Access-Control-Expose-Headers', 'Content-Disposition')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (Exception $e) {
            return response()->json([
                'article' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    public function assignUsersToArticle(Request $request)
    {
        try {
            $request->validate([
                'article_id' => 'required',
                'users' => 'required|array'
            ]);
            $article = Article::findOrFail($request->article_id);
            $data = [
                'article' => $article
            ];

            $pdf = Pdf::loadView('pdf.article', $data);
            foreach ($request->users as $userId) {
                $user = User::find($userId);

                Mail::to("$user->email")->send(
                    new ShareArticleMail(
                        $pdf,
                    )
                );
                // return;
            }

            return response()->json([
                'article' => $article,
                'users_assigned' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'users_assigned' => false,
                'message' => $e->getMessage()
            ], 402);
        }
    }
    public function showByVersion($id, $version)
    {
        try {
            if (Auth::user()->role == 'admin') {
                $article = Article::with('users', 'space', 'manual')->findOrFail($id);

                if ($version) {
                    $articleVersion = $article->versions()->where('id', $version)->first();

                    if ($articleVersion) {
                        return response()->json([
                            'article' => $articleVersion
                        ], 200);
                    } else {
                        throw new Exception("Article version not found.$version");
                    }
                }

                return response()->json([
                    'article' => $article
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $article = $user->articles()->with('users', 'space', 'manual')->findOrFail($id);

                if ($version) {
                    $articleVersion = $article->versions()->with('users', 'space', 'manual')->where('version_number', $version)->first();

                    if ($articleVersion) {
                        return response()->json([
                            'article' => $articleVersion
                        ], 200);
                    } else {
                        throw new Exception("Article version not found.");
                    }
                }

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

            // Get the maximum version number for the article
            $maxVersionNumber = $article->versions()->max('version_number');
            $versionNumber = $maxVersionNumber ? $maxVersionNumber + 0.1 : 1;


            // Update the article with the new data
            $article->title = $request->title;
            $article->content = $request->content;
            $article->save();

            // Create a new version in the article_versions table
            ArticleVersion::create([
                'article_id' => $article->id,
                'user_id' => Auth::id(),
                'title' => $article->title,
                'content' => $article->content,
                'version_number' => $versionNumber
            ]);


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

    public function getArticleByManualId($manualId)
    {

        try {
            $authRole = 'admin';
            if ($authRole == 'admin') {
                $articles = Article::with('users', 'space', 'manual')->where('manual_id', $manualId)->orderBy('id', 'desc')->get();
                return response()->json([
                    'articles' => $articles
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $articles = $user->articles()->with('users', 'space', 'manual')->where('manual_id', $manualId)->latest()->get();
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
}