<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Menu;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ManageController extends Controller
{
    //

    public function AdminAllProduct(){
        $product = Product::orderBy('id','desc')->get();
        return view('admin.backend.product.all_product',compact('product'));
    }

    public function AdminAddProduct(){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        return view('admin.backend.product.add_product',compact('category','city','menu','client'));
    }

  
    public function AdminProductStore(Request $request){

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
                'client_id'=>$request->client_id,
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
        return redirect()->route('admin.all.product')->with($notification);
    }

    
    public function AdminEditProduct($id){
        $product = Product::find($id);
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        return view('admin.backend.product.edit_product',compact('category','city','menu','product','client'));
    }

    public function AdminProductUpdate(Request $request){

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
                'client_id'=>$request->client_id,
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
            return redirect()->route('admin.all.product')->with($notification);
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
                'client_id'=>$request->client_id,
                'most_popular'=>$request->most_popular,
                'best_seller'=>$request->best_seller,
                'status'=>1,
                'created_at'=>Carbon::now(),
            ]);

            $notification = array(
                'message'=>'Product Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('admin.all.product')->with($notification);
        }
    }
    public function AdminDeleteProduct($id){
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

    //method for manage restuarant pending and approve
    public function PendingRestaurant(){
        $client = Client::where('status',0)->get();

        return view('admin.backend.restaurant.pending_restaurant',compact('client'));
    }

    public function ClientChangeStatus(Request $request){
        $client = Client::find($request->client_id);
        $client->status = $request->status;

        $client->save();
        return response()->json(['success'=>'Status Change Successfully']);
    }

    public function ApproveRestaurant(){
        $client = Client::where('status',1)->get();

        return view('admin.backend.restaurant.approve_restaurant',compact('client'));
    }


    //banner section method
    public function AllBanner(){
        $banner = Banner::latest()->get();

        return view('admin.backend.banner.all_banner', compact('banner'));
    }

    public function BannerStore(Request $request){
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(400,400)->save(public_path('upload/banner/'.$name_gen));
            $save_url = 'upload/banner/'.$name_gen;


            Banner::create([
                'url'=>$request->url,
                'image'=>$save_url
            ]);
        }
        $notification = array(
            'message'=>'Banner Inserted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }

    public function EditBanner($id){
        $banner = Banner::find($id);
        if($banner){
            $banner->image = asset($banner->image);
        }
        return response()->json($banner);
    }


    public function BannerUpdate(Request $request){

        $banner_id = $request->banner_id;

        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(400,400)->save(public_path('upload/banner/'.$name_gen));
            $save_url = 'upload/banner/'.$name_gen;


            Banner::find($banner_id)->update([
                'url'=>$request->url,
                'image'=>$save_url
            ]);

            $notification = array(
                'message'=>'Banner Updated Successfully',
                'alert-type'=>'success'
            );
    
            return redirect()->route('all.banner')->with($notification);
        }
        else{
            Banner::find($banner_id)->update([
                'url'=>$request->url,
            ]);

            $notification = array(
                'message'=>'Banner Updated Successfully',
                'alert-type'=>'success'
            );
    
            return redirect()->route('all.banner')->with($notification);
        }
        
    }

    public function DeleteBanner($id){
        $item = Banner::find($id);
        $img = $item->image;
        unlink($img);

        Banner::find($id)->delete();

        $notification = array(
            'message'=>'Banner Deleted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }


}