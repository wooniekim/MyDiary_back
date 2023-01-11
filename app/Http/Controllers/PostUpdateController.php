<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostUpdateController extends Controller
{
    // 업데이트를 위한 글 조회
    public function inquire(Request $request, $id){
        $posts = DB::table('posts')
        ->select(['title', 'description'])
        ->where('id', $id)
        ->get();
        return json_encode($posts);
    }
}
