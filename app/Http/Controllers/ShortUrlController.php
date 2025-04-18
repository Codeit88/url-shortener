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
    
    use GeneratesShortCode;

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), ['url'=>'required|url']);
        if ($v->fails()) {
            return response()->json(['errors'=>$v->errors()], 400);
        }

        $short = ShortUrl::create([
            'url'        => $request->url,
            'short_code' => $this->generateUniqueCode()
        ]);

        return response()->json($short, 201);
    }

    // GET /shorten/{code}
    public function show($code)
    {
        $short = ShortUrl::where('short_code', $code)->first();
        if (! $short) {
            return response()->json(['error'=>'Short URL not found'], 404);
        }

        // increment on retrieve
        $short->increment('access_count');

        return response()->json($short, 200);
    }

    // PUT /shorten/{code}
    public function update(Request $request, $code)
    {
        $v = Validator::make($request->all(), ['url'=>'required|url']);
        if ($v->fails()) {
            return response()->json(['errors'=>$v->errors()], 400);
        }

        $short = ShortUrl::where('short_code', $code)->first();
        if (! $short) {
            return response()->json(['error'=>'Short URL not found'], 404);
        }

        $short->update(['url'=>$request->url]);
        return response()->json($short, 200);
    }

    // DELETE /shorten/{code}
    public function destroy($code)
    {
        $short = ShortUrl::where('short_code', $code)->first();
        if (! $short) {
            return response()->json(['error'=>'Short URL not found'], 404);
        }

        $short->delete();
        return response()->json(null, 204);
    }

    // GET /shorten/{code}/stats
    public function stats($code)
    {
        $short = ShortUrl::where('short_code', $code)->first();
        if (! $short) {
            return response()->json(['error'=>'Short URL not found'], 404);
        }

        return response()->json($short, 200);
    }
}
