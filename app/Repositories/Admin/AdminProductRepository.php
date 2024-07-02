<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Models\Image;
use App\Models\Order;
use App\Models\Video;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductRepository{

    public function index()
    {
        $products = Product::latest()->paginate(10);
        if($products){
            return response()->json([
                'status' => 200,
                'message' => 'All products found!',
                'products' => $products,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find products!',
                'success' => false,
            ]);
        }
    }


    public function create()
    {
        // Validation
        request()->validate([
            'name' => 'required|string',
            'photos*' => 'required|max:5000',
            'description' => 'nullable|string',
            'price' => 'nullable|integer',
            'quantity' => 'nullable|integer',
            'tag_ids' => 'required|array',
            'category_ids' => 'required|array',
        ]);

        // Create a new product
        $product = new Product([
            'name' => request()->name,
            'slug' => Str::slug(request()->name),
            'description' => request()->description,
            'price' => request()->price,
            'quantity' => request()->quantity,
        ]);

        // Save the product within a database transaction
        // DB::transaction(function () use ($product) {
        //     $product->save();

        //     // Handle image uploads
        //     if (request()->hasFile('photos')) {

        //         $images = [];
        //         $baseUrl = config('do-spaces.url');
        //         dd($baseUrl);

        //         foreach (request()->file('photos') as $file) {
        //             $oldName = $file->getClientOriginalName();
        //             $newFileName = time() . '_' . $oldName;
        //             $file_path = Storage::disk('do_spaces')->putFileAs('products/photos', $file, $newFileName, 'public');
        //             $fileUrl = $baseUrl . $file_path;
        //         dd($file_path);

        //             $images[] = new Image([
        //                 'url' => $fileUrl,
        //                 'old_name' => $oldName,
        //                 'file_name' => $newFileName,
        //             ]);
        //         }

        //         // Save the images related to the product
        //         $product->images()->saveMany($images);
        //     }

        //     // Attach categories
        //     $product->categories()->attach(request()->category_ids);
        //     $product->tags()->attach(request()->tag_ids);
        // });

        DB::transaction(function () use ($product) {
            try {
                $product->save();

                // Handle image uploads
                if (request()->hasFile('photos')) {
                    $baseUrl = config('do-spaces.url');

                    foreach (request()->file('photos') as $file) {
                        $oldName = $file->getClientOriginalName();
                        $newFileName = time() . '_' . $oldName;
                        $file_path = Storage::disk('do_spaces')->putFileAs('products/photos', $file, $newFileName, 'public');
                        $fileUrl = $baseUrl . '/' . $file_path;

                        $image = Image::create([
                            'url' => $fileUrl,
                            'old_name' => $oldName,
                            'file_name' => $newFileName,
                            'product_id' => $product->id
                        ]);
                    }

                }

                // Attach categories
                $product->categories()->attach(request()->category_ids);
                $product->tags()->attach(request()->tag_ids);
            } catch (\Exception $e) {
                \Log::error('Error in transaction', ['message' => $e->getMessage()]);
                // throw $e; // Re-throw to rollback the transaction
            }
        });


        $product = Product::with('images')->where('id', $product->id)->get();

        return response()->json([
            'message' => 'Product created successfully!',
            'status' => 201,
            'success' => true,
            'product' => $product,
        ]);
    }

public function edit($id){

    $product = Product::find($id);

    request()->validate([
        'name' => 'nullable|string',
        'photos*' => 'nullable|max:5000',
        'description' => 'nullable|string',
        'price' => 'nullable|integer',
        'quantity' => 'nullable|integer',
        'tag_ids' => 'nullable|array',
        'category_ids' => 'nullable|array',
    ]);


    $product->name = !empty(request()->name) ? request()->name : $product->name;
    $product->slug = !empty(Str::slug(request()->name)) ? Str::slug(request()->name) : $product->slug;
    $product->description = !empty(request()->description) ? request()->description : $product->description;
    $product->price = !empty(request()->price) ? request()->price : $product->price;
    $product->quantity = !empty(request()->quantity) ? request()->quantity : $product->quantity;

    // Save the product within a database transaction
    DB::transaction(function () use ($product) {
        $product->update();

        // Handle image uploads
        if (request()->hasFile('photos')) {
            $baseUrl = config('do-spaces.url');

            foreach (request()->file('photos') as $file) {
                $oldName = $file->getClientOriginalName();
                $newFileName = time() . '_' . $oldName;
                $file_path = Storage::disk('do_spaces')->putFileAs('products/photos', $file, $newFileName, 'public');
                $fileUrl = $baseUrl . '/' . $file_path;

                $image = Image::create([
                    'url' => $fileUrl,
                    'old_name' => $oldName,
                    'file_name' => $newFileName,
                    'product_id' => $product->id
                ]);
            }

        }

        // Attach categories
        // $product->categories()->attach(request()->category_ids);
        // $product->tags()->attach(request()->tag_ids);
        if (request()->has('category_ids')) {
            $product->categories()->attach(request()->category_ids);
        }
        if (request()->has('tag_ids')) {
            $product->tags()->attach(request()->tag_ids);
        }
    });

    $product = Product::with('images')->where('id', $product->id)->get();

    if($product){
        return response()->json([
            'message' => 'Product updated successfully!',
            'status' => 201,
            'success' => true,
            'product' => $product,
        ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
        ]);
    }
}


public function find($id)
{
    $product = Product::find($id);

    if($product){
        return response()->json([
            'status' => 200,
            'message' => 'product has been found!',
            'product' => $product,
            'success' => true,
        ]);
    } else {
        return response()->json([
            'status' => 401,
            'message' => 'Failed to find product!',
            'success' => false,
        ]);
    }
}

public function destroy($id)
{
    $product = Product::find($id);
    $product->delete();

    if($product){
        return response()->json([
                'message' => 'product deleted successfully!',
                'status' => 200,
                'success' => true,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
    }
}


public function dashboard(){
    $pending_orders_total = Order::where('status', 'pending')->count();
    $canceled_orders_total = Order::where('status', 'canceled')->count();
    $inprogress_orders_total = Order::where('status', 'in-progress')->count();
    $delivered_orders_total = Order::where('status', 'delivered')->count();
    $completed_orders_total = Order::where('status', 'completed')->count();
    $orders_total = Order::count();

    $pending_payments_total = Payment::where('status', 'pending')->count();
    $canceled_payments_total = Payment::where('status', 'canceled')->count();
    $inprogress_payments_total = Payment::where('status', 'in-progress')->count();
    $delivered_payments_total = Payment::where('status', 'delivered')->count();
    $completed_payments_total = Payment::where('status', 'completed')->count();
    $payments_total = Payment::count();

    $products_total = Product::count();

    $active_users_total = User::where('status', 'unbanned')->count();
    $banned_users_total = User::where('status', 'banned')->count();
    $users_total = User::count();


    return response()->json([
        'message'=> "Admin Analytics found!",
        'pending_orders_total'=> $pending_orders_total,
        'canceled_orders_total'=> $canceled_orders_total,
        'inprogress_orders_total'=> $inprogress_orders_total,
        'delivered_orders_total'=> $delivered_orders_total,
        'completed_orders_total'=> $completed_orders_total,
        'orders_total'=> $orders_total,
        'pending_payments_total'=> $pending_payments_total,
        'canceled_payments_total'=> $canceled_payments_total,
        'inprogress_payments_total'=> $inprogress_payments_total,
        'delivered_payments_total'=> $delivered_payments_total,
        'completed_payments_total'=> $completed_payments_total,
        'payments_total'=> $payments_total,
        'products_total'=> $products_total,
        'active_users_total'=> $active_users_total,
        'banned_users_total'=> $banned_users_total,
        'users_total'=> $users_total,
        ],200);
    }

}
