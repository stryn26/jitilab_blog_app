<?php

namespace App\Http\Controllers;

use App\Http\Resources\blogResource;
use App\Models\Blog as BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class Blog extends Controller
{

    public function index(){
        $blog = BlogController::latest()->paginate(5);
        return new blogResource(true, 'List Data Blog', $blog);
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,webp',
        ]);        

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        //uploading image process
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());
        $uuid = Uuid::generate()->string;

        $blog = BlogController::create([
            'title' => $request->title,
            'uuid' => $uuid,
            'description' => $request->description,
            'image' => $image->hashName()
        ]);

        return new blogResource(true,'Data Blog Berhasil Ditambahkan!', $blog);
    }

    public function show($uuid)
    {
        $blog = BlogController::where('uuid',$uuid)->first();
        return new blogResource(true, 'Detail Blog ', $blog);
    }

    public function update(Request $request,$uuid)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required'
        ]);
        
        if($validator->fails())
        {
            return response()->json([$validator->errors(),$request->all()] ,422);
        }
        
        $blog = BlogController::where('uuid',$uuid)->first();

        if($request->hasFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            Storage::delete('public/blogs',basename($blog->image));
            $blog->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $image->hashName(),
            ]);
        }else {
            $blog->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        }

        return new blogResource(true, 'Data berhasil diperbarui', $blog);
    }

    public function destroy($uuid){
        $blog = BlogController::where('uuid',$uuid)->first();

        Storage::delete('public/blogs'.basename($blog->image));

        $blog->delete();

        return new blogResource(true,'Data berhasil dihapus',null);
    }
}
