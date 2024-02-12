<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use File;
use Image;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $product = Product::orderBy('created_at','desc')->Paginate(10);
        return view('backend.product.index',compact('product'));
}

    public function createfrom(){
      $category = Category::all();
    return view('backend.product.createfrom',compact('category'));
}

    public function edit($product_id){
        $pro = Product::find($product_id);
        $cat = Category::all();
    return view('backend.product.edit',compact('pro','cat'));
}



    public function insert(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'mimes:jpg,jpeg,png'
            ],
        [
            'name.required' => 'กรุณากรอกข้อมูลสินค้า',
            'name.max' => 'กรอกข้อมูลได้ 255 ตัวอักษร',
            'price.required' => 'กรุณากรอกข้อมูลราคาสินค้า',
            'price.max' => 'กรอกข้อมูลได้ 255 ตัวอักษร',
            'description.required' => 'กรุณากรอกข้อมูลประเภทสินค้าสินค้า' ,
            'image.mimes' => 'อัพโหลดภาพที่มีนามสกุล .jpg .jpeg .png ได้เท่านั้น' ,
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        if($request->hasFile('image')){
            $filesname = Str::random(10). '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path().'/backend/product/',$filesname);
            Image::make(public_path().'/backend/product/'. $filesname)->resize(500,450)->save(public_path().'/backend/product/resize/'.$filesname);
            $product->image = $filesname;
        }else{
             $product->image = 'no_image.jpg';
        }
        $product->save( );
        return redirect('admin/product/index');
    }

        public function update(Request $request, $product_id){

            $product = Product::find($product_id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->category_id = $request->category_id;

            if($request->hasFile('image'))

            if ($product->image != 'no_image.jpg')
            {
                File::delete(public_path().'/backend/product/'.$product->image);
                File::delete(public_path().'/backend/product/resize/'.$product->image);
            
            $filename = Str::random(10).'.'.
            $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path().'/backend/product/',$filename);
            Image::make(public_path().'/backend/product/'.$filename)->resize(250,250)->save(public_path().'/backend/product/resize/'.$filename);
            $product->image = $filename;

            }else{
                $product->image = 'no_image.jpg';
            }
            $product->update();
            alert()->success('แก้ไขข้อมูลสำเร็จ','ข้อมูลถูกแก้ไขเรียบร้อยแล้ว');
            return redirect('admin/product/index');
        }

        public function delete ($product_id)
        {
            $product = Product::find($product_id);

               if ($product->file != 'no_image.jpg') {
                File::delete(public_path().'/backend/product/'.$product->image);
                File::delete(public_path().'/backend/product/resize/'.$product->image);
               }
               $product->delete();
               alert()->success('ลบข้อมูลสำเร็จ','ข้อมูลนี้ถูกลบเรียบร้อยแล้ว');
               return redirect('admin/product/index');
            
        }
    }
