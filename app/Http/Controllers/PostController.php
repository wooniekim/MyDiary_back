<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends Controller
{
    // 리스트 보기
    public function index()
    {
        return Post::select('id','title','description','image')->get();
    }

    // 글 저장
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>'required|image'
        ]);

        try{
            $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('post/image', $request->image,$imageName);
            Post::create($request->post()+['image'=>$imageName]);

            return response()->json([
                'message'=>'Post Created Successfully!!'
            ]);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while creating a post!!'
            ],500);
        }
    }

    // 뭔지 모르겠음
    public function show(Post $post)
    {
        return response()->json([
            'post'=>$post
        ]);
    }

    // 글 수정
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>'nullable'
        ]);

        try{

            $post->fill($request->post())->update();

            if($request->hasFile('image')){

                // remove old image
                if($post->image){
                    $exists = Storage::disk('public')->exists("post/image/{$post->image}");
                    if($exists){
                        Storage::disk('public')->delete("post/image/{$post->image}");
                    }
                }

                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('post/image', $request->image,$imageName);
                $post->image = $imageName;
                $post->save();
            }

            return response()->json([
                'message'=>'Post Updated Successfully!!'
            ]);

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while updating a post!!'
            ],500);
        }
    }

    // 글 삭제
    public function destroy(Request $request, $id)
    {
        $posts = DB::table('posts')->where('id', $id)->delete();
        return;
        // return view('board.deleteck_q&a', compact('posts'));
    }
}