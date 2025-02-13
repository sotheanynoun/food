<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Menu;
use App\Models\Product;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class RestaurantController extends Controller
{
    //
    public function AllMenu(){
        $menu = Menu::latest()->get();
        return view('client.backend.menu.all_menu', compact('menu'));
    }

    public function AddMenu(){
        return view('client.backend.menu.add_menu');
    }

    public function MenuStore(Request $request){
        if($request-> file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/menu/'.$name_gen));
            $save_url = 'upload/menu/'.$name_gen;

            Menu::create([
                'menu_name'=>$request->menu_name,
                'image'=>$save_url
            ]);
        }
        $notification = array(
            'message'=>'Menu Created Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.menu')->with($notification);
    }

    public function EditMenu($id){
        $menu = Menu::find($id);
        return view('client.backend.menu.edit_menu',compact('menu'));
    }

    public function MenuUpdate(Request $request){
        $menu_id = $request->id;
        
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/menu/'.$name_gen));
            $save_url='upload/menu/'.$name_gen;

            Menu::find($menu_id)->update([
                'menu_name'=>$request->menu_name,
                'image'=>$save_url,
            ]);

            $notification =array(
                'message'=>'Menu Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.menu')->with($notification);
        }else{
            Menu::find($menu_id)->update([
                'menu_name'=>$request->menu_name
            ]);
            $notification=array(
                'message'=>'Menu Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.menu')->with($notification);
        }

    }

    public function DeleteMenu($id){
        $item = Menu::find($id);
        $img = $item->image;
        unlink($img);

        Menu::find($id)->delete();

        $notification = array(
            'message'=>'Menu Deleted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }

    // Product Section Method
    public function AllProduct(){
        $product = Product::latest()->get();
        return view('client.backend.product.all_product', compact('product'));
    }

    public function AddProduct(){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();

        return view('client.backend.product.add_product',compact('category','city','menu'));
    }

    public function ProductStore(Request $request){

        $pcode = IdGenerator::generate(['table'=>'products','field'=>'code','length'=>5,'prefix'=>'PC']);

        if($request-> file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/product/'.$name_gen));
            $save_url = 'upload/product/'.$name_gen;

            Product::create([
                'name'=>$request->name,
                'slug'=>strtolower(str_replace(' ','-',$request->name)),
                'category_id'=>$request->category_id,
                'city_id'=>$request->city_id,
                'menu_id'=>$request->menu_id,
                'code'=>$pcode,
                'qty'=>$request->qty,
                'size'=>$request->size,
                'price'=>$request->price,
                'discount_price'=>$request->discount_price,
                'client_id'=>Auth::guard('client')->id(),
                'most_popular'=>$request->most_popular,
                'best_seller'=>$request->best_seller,
                'status'=>1,
                'created_at'=>Carbon::now(),
                'image'=>$save_url
            ]);
        }
        $notification = array(
            'message'=>'Product Created Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.product')->with($notification);
    }

    public function EditProduct($id){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $product = Product::find($id);
        return view('client.backend.product.edit_product',compact('category','city','menu','product'));
    }

    public function ProductUpdate(Request $request){

        $pro_id =$request->id;

        if($request-> file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/product/'.$name_gen));
            $save_url = 'upload/product/'.$name_gen;

            Product::find($pro_id)->update([
                'name'=>$request->name,
                'slug'=>strtolower(str_replace(' ','-',$request->name)),
                'category_id'=>$request->category_id,
                'city_id'=>$request->city_id,
                'menu_id'=>$request->menu_id,
                'qty'=>$request->qty,
                'size'=>$request->size,
                'price'=>$request->price,
                'discount_price'=>$request->discount_price,
                'most_popular'=>$request->most_popular,
                'best_seller'=>$request->best_seller,
                'status'=>1,
                'created_at'=>Carbon::now(),
                'image'=>$save_url
            ]);

            $notification = array(
                'message'=>'Product Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.product')->with($notification);
        }else{
            Product::find($pro_id)->update([
                'name'=>$request->name,
                'slug'=>strtolower(str_replace(' ','-',$request->name)),
                'category_id'=>$request->category_id,
                'city_id'=>$request->city_id,
                'menu_id'=>$request->menu_id,
                'qty'=>$request->qty,
                'size'=>$request->size,
                'price'=>$request->price,
                'discount_price'=>$request->discount_price,
                'most_popular'=>$request->most_popular,
                'best_seller'=>$request->best_seller,
                'status'=>1,
                'created_at'=>Carbon::now(),
            ]);

            $notification = array(
                'message'=>'Product Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.product')->with($notification);
        }
    }
    public function DeleteProduct($id){
        $item = Product::find($id);
        $img =  $item->image;
        unlink($img);

        Product::find($id)->delete();

        $notification = array(
            'message'=>'Product Deleted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }


    public function ChangeStatus(Request $request){
        $product = Product::find($request->product_id);
        $product->status = $request->status;

        $product->save();
        return response()->json(['success'=>'Status Change Successfully']);
    }

}