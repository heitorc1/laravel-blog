<?php

namespace App\Controllers;

use App\Requests\StorePostRequest;
use App\Requests\UpdatePostRequest;
use App\Entities\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->get();

        return $posts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post;

        $post->title = $request->title;
        $post->subtitle = $request->subtitle;
        $post->body = $request->body;
        $post->user_id = $request->user_id;

        $post->save();

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entities\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $id = $post->id;
        return Post::with('user')->where('id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entities\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Requests\UpdatePostRequest  $request
     * @param  \App\Entities\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->title = $request->title;
        $post->subtitle = $request->subtitle;
        $post->body = $request->body;
        $post->user_id = $request->user_id;

        $post->save();

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entities\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Post::destroy($post);
        return ["result" => "post deleted"];
    }
}
