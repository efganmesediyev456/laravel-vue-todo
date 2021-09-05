<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Models\Tag;
use App\Models\Category;

class TestController extends Controller
{
    public function create(Request $request){
       
        $request->validate([
            'name'=>'required'
        ]);

        return Tag::create([
            'name'=>$request->name,
        ]);
       
    }
    public function cats_create(Request $request){
       
        $request->validate([
            'name'=>'required',
            'imageName'=>'required'
        ]);

        return Category::create([
            'name'=>$request->name,
            'image'=>$request->imageName
        ]);
       
    }

    

    
    public function upload(Request $request){
        $request->validate([
            'file'=>'required|mimes:jpg,png,jpeg|max:10000'
        ]);
        $filename=time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$filename);
        return $filename;
    }
    public function get_tags(){
        return Tag::orderBy('id','desc')->get();
    }
    public function get_categories(){
        return Category::orderBy('id','desc')->get();
    }
    public function cats_edit(Request $request){
        return Category::whereId($request->id)->update([
            'name'=>$request->name,
            'image'=>$request->imageName,
    ]);
    }
    public function tags_edit(Request $request){
        return Tag::whereId($request->id)->update([
                'name'=>$request->name,
        ]);
    }
    public function delete_photo(Request $request){
        $imageName=$request->imageName;
        $path=public_path('uploads/'.$imageName);
        if(file_exists($path)){
            @unlink($path);
            return 'ok';
        }
        return 'silinmedi';
    }
    public function tags_delete(Request $request){
        return Tag::whereId($request->id)->delete();
    }
    public function cats_delete(Request $request){
        $request->imageName=$request->image;
        $this->delete_photo($request);
        return Category::whereId($request->id)->delete();
    }
}
