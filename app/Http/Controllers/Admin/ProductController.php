<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Keysearch;
use App\Models\Media;
use App\Models\Product;
use App\Models\Sidebar;
use App\Traits\DataTree;
use App\Traits\HasChild;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use HasChild, DataTree;

    private function getFileType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
    }
    public function show(Request $request)
    {
        if (!Gate::allows('product.show')) {
            abort(403);
        }
        //delete storage when the user go in this page
        $files = Storage::disk('public')->files('tmp');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        Paginator::useBootstrapFive();
        $currentPage = 1;
        if ($request->page) {
            $currentPage = $request->page;
        }

        $query = Product::select('products.*')
            ->with([
                'media' => fn($q) => $q->where('media_type', 'thumbnail'),
                'user',
                'category'
            ]);

        if ($request->input('status') === 'trash') {
            $trash = true;
            $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
            $sort = $request->input('sort', 'deleted_at');
            $order = $request->input('order', 'desc');
            $query->onlyTrashed();
        } else {
            $trash = false;
            $actions = ['delete' => "Delete"];
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
        }

        // If you sort by user or category then join
        if (in_array($sort, ['users.name', 'categories.title'])) {
            $query->leftJoin('users', 'products.user_id', '=', 'users.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id');
        }

        $search = $request->input('search');
        if ($search) {
            $keywords = $search ? explode(' ', trim($search)) : [];
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where('products.name', 'LIKE', "%{$word}%");
                }
            });

        }
        $list_products = $query->orderBy($sort, $order)->paginate(20);
        $countItem = Product::count();
        $list_trash = Product::onlyTrashed()->get();

        return view('admin.product.show', compact('list_products', 'countItem', 'actions', 'trash', 'list_trash', 'currentPage'));
    }
    public function add()
    {
        if (!Gate::allows('product.add')) {
            abort(403);
        }
        $list_categories = Category::withTrashed()->orderBy('id', 'desc')->get();
        $list_subcategories = $this->data_tree(Sidebar::withTrashed()->where('type', 'subcategory')->orderBy('id', 'asc')->get());
        $list_filter = $this->data_tree(Sidebar::withTrashed()->where('type', 'filter')->orderBy('id', 'asc')->get());
        return view('admin.product.add', compact('list_categories', 'list_subcategories', 'list_filter'));
    }
    public function store(Request $request)
    {
        if (!Gate::allows('product.add')) {
            abort(403);
        }
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'image' => ['required', 'image'],
                'category' => ['required'],
                'warehouse_status' => ['required'],
                'privacy' => ['required'],
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->image;

            $upload_dir = "public/media/images/";
            $file_name = $file->getClientOriginalName();
            if (file_exists($upload_dir . $file_name)) {
                $type = explode('.', $file_name);
                $file_name = $type[0] . "-Copy." . $type[1];
                $k = 1;
                while (file_exists($upload_dir . $file_name)) {
                    $file_name = $type[0] . "-Copy({$k})." . $type[1];
                    $k++;
                }
            }
            $file->move($upload_dir, $file_name);

            $product = Product::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'short_desc' => $request->input('desc'),
                'detail' => $request->input('detail'),
                'warehouse_status' => $request->input('warehouse_status'),
                'privacy' => $request->input('privacy'),
                'category_id' => $request->input('category'),
                'user_id' => Auth::id(),
            ]);

            //add thumb nail for product
            $product->media()->create([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'thumbnail',
                'user_id' => Auth::id()
            ]);

            //connect to sidebars
            $product->sidebars()->attach($request->input('sidebar'));

            //connect to keysearches
            if ($request->keysearch != "") {

                $keysearch = explode(',', $request->keysearch); // split string into array
                $keysearch = array_map('trim', $keysearch);     // remove extra spaces
                // dd($keysearch);
                $keysearchIds = [];
                foreach ($keysearch as $keyword) {
                    //add non-existent keyseach
                    $key = Keysearch::firstOrCreate(['keyword' => $keyword]);
                    $keysearchIds[] = $key->id;
                }

                $product->keysearches()->sync($keysearchIds);
            }

            //add media for product
            $mediaPaths = json_decode($request->input('uploaded_media'), true); // Dữ liệu input hidden

            if (is_array($mediaPaths)) {
                foreach ($mediaPaths as $tempPath) {
                    $sourcePath = storage_path('app/public/' . $tempPath['path']);
                    // Original file path in storage/app/tmp
                    if (file_exists($sourcePath)) {
                        $filename = basename($tempPath['path']);
                        $type = $this->getFileType($filename);
                        $newPath = public_path('media/' . $type . 's/' . $filename);
                        // move media from storage to public
                        if (rename($sourcePath, $newPath)) {
                            $product->media()->create([
                                'name' => $file_name,
                                'file_path' => 'media/' . $type . 's/' . $filename,
                                'media_type' => $type,
                                'user_id' => Auth::id()
                            ]);
                        }

                    }
                }
            }
        }
        return redirect('admin/product')->with('success', 'Added successfully!');

        // return redirect()->back()->with('success', 'Add successfully!');
    }
    public function edit(Request $request, $id)
    {
        if (!Gate::allows('product.edit')) {
            abort(403);
        }
        $list_categories = Category::withTrashed()->orderBy('id', 'desc')->get();
        $list_subcategories = $this->data_tree(Sidebar::withTrashed()->where('type', 'subcategory')->orderBy('id', 'asc')->get());
        $list_filter = $this->data_tree(Sidebar::withTrashed()->where('type', 'filter')->orderBy('id', 'asc')->get());
        $product = Product::withTrashed()->with('media')->findOrFail($id);
        $keysearches = "";
        $keysearches_array = [];
        if ($product->keysearches) {
            foreach ($product->keysearches as $item) {
                $keysearches_array[] = $item->keyword;
            }
            $keysearches = implode(", ", $keysearches_array);
        }
        $thumbnail = $product->media->where('media_type', 'thumbnail')->first();
        $media = $product->media->where('media_type', '!=', 'thumbnail')->values()->map(function ($m, $index) {
            return [
                'index' => $index,
                'path' => $m->file_path
            ];
        });
        $sidebars = [];
        if ($product->sidebars) {

            foreach ($product->sidebars as $item) {
                $sidebars[] = $item->id;
            }
        }
        return view('admin.product.edit', compact('list_categories', 'list_filter', 'list_subcategories', 'product', 'thumbnail', 'media', 'sidebars', 'keysearches'));
    }
    public function update(Request $request, $id)
    {
        if (!Gate::allows('product.edit')) {
            abort(403);
        }
        $product = Product::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'image' => ['image'],
                'category' => ['required'],
                'warehouse_status' => ['required'],
                'privacy' => ['required'],
            ]
        );
        $product->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'short_desc' => $request->input('desc'),
            'detail' => $request->input('detail'),
            'warehouse_status' => $request->input('warehouse_status'),
            'privacy' => $request->input('privacy'),
            'category_id' => $request->input('category'),
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $file = $request->image;

            $upload_dir = "public/media/images/";
            $file_name = $file->getClientOriginalName();
            if (file_exists($upload_dir . $file_name)) {
                $type = explode('.', $file_name);
                $file_name = $type[0] . "-Copy." . $type[1];
                $k = 1;
                while (file_exists($upload_dir . $file_name)) {
                    $file_name = $type[0] . "-Copy({$k})." . $type[1];
                    $k++;
                }
            }
            $file->move($upload_dir, $file_name);
            //add thumb nail for product
            $product->media->where('media_type', 'thumbnail')->first()->forceDelete();
            $product->media()->create([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'thumbnail',
                'user_id' => Auth::id()
            ]);

        }


        // connect to sidebars
        $product->sidebars()->sync($request->input('sidebar'));

        //connect to keysearches
        $keysearchIds = [];
        if ($request->keysearch != "") {

            $keysearch = explode(',', $request->keysearch); // split string into array
            $keysearch = array_map('trim', $keysearch);     // remove extra spaces

            foreach ($keysearch as $keyword) {
                //add non-existent keyseach
                $key = Keysearch::firstOrCreate(['keyword' => $keyword]);
                $keysearchIds[] = $key->id;
            }

        }
        $product->keysearches()->sync($keysearchIds);

        //add media for product
        $uploadedMedia = json_decode($request->uploaded_media, true) ?? [];

        // old files and new files
        $oldMediaPaths = [];
        $newMediaFiles = [];

        foreach ($uploadedMedia as $m) {
            if (isset($m['new']) && $m['new'] === true) {
                $newMediaFiles[] = $m;
            } else {
                $oldMediaPaths[] = $m['path'];
            }
        }

        // Get old list of deleted media (not yet in $oldMediaPaths)
        $toDelete = $product->media()->where('media_type', '!=', 'thumbnail')->whereNotIn('file_path', $oldMediaPaths)->get();

        // Delete file in public
        foreach ($toDelete as $media) {
            $filePath = public_path($media->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // 3️⃣ Delete record in DB
        $product->media()->where('media_type', '!=', 'thumbnail')->whereNotIn('file_path', $oldMediaPaths)->forceDelete();


        // 3️⃣ Move new file from tmp → public and create DB
        foreach ($newMediaFiles as $file) {
            $sourcePath = storage_path('app/public/' . $file['path']);
            if (file_exists($sourcePath)) {
                $filename = basename($file['path']);
                $type = $this->getFileType($filename);
                $newPath = public_path('media/' . $type . 's/' . $filename);

                if (rename($sourcePath, $newPath)) {
                    $product->media()->create([
                        'name' => $filename,
                        'file_path' => 'media/' . $type . 's/' . $filename,
                        'media_type' => $type,
                        'user_id' => Auth::id()
                    ]);
                }
            }
        }

        return redirect('admin/product')->with('success', 'Updated successfully!');
    }
    public function delete($id)
    {
        if (!Gate::allows('product.delete')) {
            abort(403);
        }
        Product::find($id)->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
    public function forceDelete($id)
    {
        if (!Gate::allows('product.delete')) {
            abort(403);
        }
        $product = Product::onlyTrashed()->find($id);
        $product->media()->delete();
        $product->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
    public function restore($id)
    {
        if (!Gate::allows('product.delete')) {
            abort(403);
        }
        Product::onlyTrashed()->find($id)->restore();
        return redirect()->back()->with('success', 'Restored successfully!');
    }
    public function handleAction(Request $request)
    {
        if (!Gate::allows('product.delete')) {
            abort(403);
        }
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $reseller = ($action === 'delete')
                        ? Product::find($item)
                        : Product::onlyTrashed()->where('id', $item)->first();

                    if ($reseller) {
                        if ($action === 'delete')
                            $reseller->delete();
                        if ($action === 'restore')
                            $reseller->restore();
                        if ($action === 'forceDelete')
                            $reseller->forceDelete();
                    }
                }

                $message = match ($action) {
                    'delete' => 'Deleted successfully!',
                    'restore' => 'Restored successfully!',
                    'forceDelete' => 'Permanently deleted successfully!',
                };
                return redirect()->back()->with('success', $message);
            }

            return redirect()->back()->with('danger', "You haven't chosen any valid action or selected items.");
        }
    }

    public function deleteMedia(Request $request)
    {
        $path = $request->input('path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'File not found'], 404);
    }
    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            if ($file->getError() === UPLOAD_ERR_INI_SIZE || $file->getError() === UPLOAD_ERR_FORM_SIZE) {
                return response()->json([
                    'error' => 'File vượt quá dung lượng cho phép (giới hạn server: ' . ini_get('upload_max_filesize') . ').'
                ], 413); // 413 Payload Too Large
            }
            $path = $file->store('tmp', 'public'); // Save file in storage/app/public/tmp
            return response()->json(['path' => $path], 200);
        }
        return response()->json(['error' => 'No file uploaded'], 400);

    }
    public function ajax(Request $request)
    {
        if ($request->category == "") {
            $result = [];
        } else {
            $category = Category::withTrashed()->where('id', $request->category)->firstOrFail();
            $list_sidebar = $category->sidebars;
            $list_subcategories = [];
            if (count($list_sidebar) > 0) {
                foreach ($list_sidebar as $sidebar) {
                    if ($sidebar->type == "subcategory") {
                        $list_subcategories[] = $sidebar;
                    }
                }
                if (count($list_subcategories) > 0) {
                    $list_subcategories_result = $this->data_tree($list_subcategories);
                } else {
                    $list_subcategories_result = [];
                }
                $result = $list_subcategories_result;
            } else {
                $result = [];
            }
        }
        return response()->json($result, 200);

    }

    public function keysearchShow()
    {
        if (!Gate::allows('keysearch.show')) {
            abort(403);
        }
        $list_keysearches = Keysearch::all();
        foreach ($list_keysearches as $keysearch) {
            $keysearch->result = count($keysearch->products);
        }
        return view('admin.product.keysearchShow', compact('list_keysearches'));
    }
    public function keysearchDelete($id)
    {
        if (!Gate::allows('keysearch.delete')) {
            abort(403);
        }
        Keysearch::find($id)->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
    public function keysearchActions(Request $request)
    {
        if (!Gate::allows('keysearch.delete')) {
            abort(403);
        }
        $checkItems = $request->input('checkItem');
        foreach ($checkItems as $item) {
            Keysearch::where('id', $item)->delete();
        }

        return redirect()->back()->with('success', 'Deleted successfully!');
    }
}
