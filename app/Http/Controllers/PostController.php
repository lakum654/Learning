<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\User;
use App\Comment;
use App\Reply;
use Illuminate\Support\Carbon;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('user_id',Auth::user()->id)->get();
        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $id = Auth::user()->id; 
         $user = User::find($id);
         $post = new Post;
         $post->title = $request->title;
         $post->desc = $request->desc;
         $user->posts()->save($post);
         return redirect('home');
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
        return view('posts.single',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function addFavirote(Request $request){
        $id = Auth::user()->id;
        $user = User::find($id);
        $post = $request->postId;
        $user->myList()->attach($post);
        return 1;
    }
    public function addComment(Request $request){
       $post = Post::find($request->postId);
       $comment = new Comment;
       $comment->user_id = Auth::user()->id;
       $comment->comment = $request->comment;
       $post->comments()->save($comment);
       return response()->json(['totalComments'=>$post->comments->count(),'lastId'=>$comment->id]);
    }

    public function loadComment(Request $request){
        $post = Post::find($request->postId);
        $comments = array();
        $reply = array();
        foreach($post->comments as $value){
            $comments[] = [
                'id' => $value->id,
                'comment' => $value->comment,
                'user' => $value->user->name,
                'time' => $value->created_at->diffForHumans()
            ];
        }
        

        return response()->json(['comments'=>$comments,'reply'=>$reply,'totalComments'=>$post->comments->count(),'totalLikes'=>$post->like]);
        
    }
    public function addLike(Request $request){
        $post = Post::find($request->postId);
        $post->update(['like'=>$request->like]);
    }

    public function addReply(Request $request){
        $id = $request->commentId;
        $replyText = $request->reply;
        
        $comment = Comment::find($id);
        $reply = new Reply;
        $reply->user_id = Auth::user()->id;
        $reply->reply = $replyText;
        $comment->replies()->save($reply);  
        
        return response()->json(['replyId'=>$reply->id]);
          
    }

    public function replyDelete(Request $request){
        Reply::find($request->replyId)->delete();       
    }

    public function commentDelete(Request $request){
        Comment::find($request->id)->delete();
        return back();
    }
    
}
