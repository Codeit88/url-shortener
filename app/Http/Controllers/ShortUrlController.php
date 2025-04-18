<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Traits\GeneratesShortCode;

class ShortUrlController extends Controller
{
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), ['url'=>'required|url']);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()],400);
        $su = ShortUrl::create(['url'=>$request->url,'short_code'=>$this->generateUniqueCode()]);
        return response()->json($su,201);
    }

    // API: retrieve & count
    public function show($code)
    {
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return response()->json(['error'=>'Not found'],404);
        $su->increment('access_count');
        return response()->json($su,200);
    }

    // API: update
    public function update(Request $request, $code)
    {
        $v = Validator::make($request->all(), ['url'=>'required|url']);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()],400);
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return response()->json(['error'=>'Not found'],404);
        $su->update(['url'=>$request->url]);
        return response()->json($su,200);
    }

    // API: delete
    public function destroy($code)
    {
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return response()->json(['error'=>'Not found'],404);
        $su->delete();
        return response()->json(null,204);
    }

    // API: stats (no increment)
    public function stats($code)
    {
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return response()->json(['error'=>'Not found'],404);
        return response()->json($su,200);
    }

    // WEB: store (redirect back)
    public function storeWeb(Request $request)
    {
        $request->validate(['url'=>'required|url']);
        $su = ShortUrl::create(['url'=>$request->url,'short_code'=>$this->generateUniqueCode()]);
        return redirect('/')->with('short',$su);
    }

    // WEB: redirect
    public function redirect($code)
    {
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return redirect('/')->with('error','Not found');
        $su->increment('access_count');
        return Redirect::to($su->url);
    }

    // WEB: stats view
    public function statsWeb($code)
    {
        $su = ShortUrl::where('short_code',$code)->first();
        if (! $su) return redirect('/')->with('error','Not found');
        return view('stats',compact('su'));
    }
}
