<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'search', 'searchjs']]);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function search(Request $request)
    {
        if ($request->has('q')) {
            $request->flashOnly('q');
            $results = Post::search($request->q)->paginate(10);
        } else {
            $results = [];
        }

        return view('posts.search')->with('results', $results);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::query()->paginate(25);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $user = Auth::user();

        $post = $user->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'published' => $request->has('published'),
        ]);

        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $post = Post::query()->findOrFail($post->id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post = Post::query()->findOrFail($post->id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->published = $request->has('published') ? true : false;
        $post->save();

        return redirect()->route('posts.show', [$post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Post::destroy($post->id);
    }
}
