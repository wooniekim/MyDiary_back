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
        $name = explode(".", $_FILES['image']['name']);   // 파일이름 확장자 구분
        $img = $name[0] . strtotime("Now") . '.' . $name[1];    // 파일이름 시간 추가해서 수정
        $request->file('image')->storeAs('images', $img, 'public');

        DB::table('posts')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
        ]);
    }

    // 뭔지 모르겠음
    public function show(Post $post)
    {
        return response()->json([
            'post'=>$post
        ]);
    }

    // 글 수정
    public function update(Request $request, Post $post, $id)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>'nullable'
        ]);

        $posts = DB::table('posts')
        ->where('posts.id', $id)
        ->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        return response()->json([
            'message'=>'성공!!'
        ]);
    }
    
    

    // 글 삭제
    public function destroy(Request $request, $id)
    {
        $posts = DB::table('posts')->where('id', $id)->delete();
        return;
    }
}