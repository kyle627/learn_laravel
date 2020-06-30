<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //$posts =  Post::all(); 
        //$posts =  Post::orderBy('title', 'desc')->get();
        $posts = DB::select('SELECT * FROM posts');
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        //limits a non- logged in user from creating posts
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable']);

        //file upload
        if ($request->hasFile('cover_image')) {
            //get file name
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //file name
            $fileName = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('cover_image')->storeAs('public/cover_images' , $fileNameToStore);
        }else{
            $fileNameToStore = 'no_image.jpeg';
        }

        $post = new Post;
        $post->title =  $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success' , 'Post created!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $post = Post::find($id);
        if (auth()->user()->id <> $post->user_id) {
            return redirect('/posts')->with('error', 'unauthorized');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable']);

        //file upload
        if ($request->hasFile('cover_image')) {
            //get file name
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //file name
            $fileName = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('cover_image')->storeAs('public/cover_images' , $fileNameToStore);
        }

        $post = Post::find($id);
        $post->title =  $request->input('title');
        $post->body = $request->input('body');
        if ($request_>hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success' , 'Post Updated!!');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (auth()->user()->id <> $post->user_id) {
            return redirect('/posts')->with('error', 'unauthorized');
        }

        if ($post->cover_image <> 'no_image.jpeg') {
            Storage::delete('public/cover_images/'. $post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post was deleted');
    }

    public function imageUploadLogic(){
        //file upload
        if ($request->hasFile('cover_image')) {
            //get file name
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //file name
            $fileName = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('cover_image')->storeAs('public/cover_images' , $fileNameToStore);
        }else {
            $fileNameToStore = 'no_image.jpeg';
        }
    }

    public function imageEditLogic(){
        //file upload
        if ($request->hasFile('cover_image')) {
            //get file name
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //file name
            $fileName = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('cover_image')->storeAs('public/cover_images' , $fileNameToStore);
        }
    }
}
