<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\DataTree;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Media;
use App\Models\Menu;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    use DataTree;
    public function show(Request $request)
    {
        $list_categories = Category::orderBy('id', 'desc')->get();
        $countItem = count($list_categories);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = Category::onlyTrashed()->orderBy('id', 'desc')->get();
        $status = $request->input('status');
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        if ($search) {
            $list_categories = Category::where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where('title', 'LIKE', "%{$word}%");
                }
            })->orderBy('id', 'desc')->get();
        }
        if ($status) {
            if ($status == "trash") {
                $list_categories = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_categories = Category::onlyTrashed()->where(function ($query) use ($keywords) {
                        foreach ($keywords as $word) {
                            $query->where('title', 'LIKE', "%{$word}%");
                        }
                    })->orderBy('id', 'desc')->get();
                }
            }
        }
        foreach ($list_categories as $category) {
            if ($category->menu->parent_id != 0) {
                $parent_menu = Menu::find($category->menu->parent_id);
                $category->menu->title = $parent_menu->title . " / " . $category->menu->title;
            }
        }

        return view('admin.categories.show', compact('list_categories', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add()
    {
        $list_menu = $this->data_tree(Menu::withTrashed()->get());
        foreach ($list_menu as $menu) {
            if ($menu->parent_id != 0) {
                $menu->title = "--" . $menu->title;
            }
        }
        return view('admin.categories.add', compact('list_menu'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'image' => ['required', 'image'],
                'menu_id' => ['required', 'integer'],
            ],
            [
                'integer' => 'The menu field is required.'
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
            $category = Category::create([
                'title' => $request->input('title'),
                'desc' => $request->input('desc'),
                'slug' => Str::slug($request->input('title')),
                'menu_id' => $request->input('menu_id'),
                'user_id' => Auth::id(),
            ]);
            $category->image()->create([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/categories')->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $list_menu = $this->data_tree(Menu::withTrashed()->get());
        foreach ($list_menu as $menu) {
            if ($menu->parent_id != 0) {
                $menu->title = "--" . $menu->title;
            }
        }
        $category = Category::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.categories.edit', compact('category', 'list_menu'));
    }
    public function update(Request $request, $id)
    {
        $category = Category::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'menu_id' => ['required', 'integer'],
            ],
            [
                'integer' => 'The menu field is required.'
            ]
        );
        $category->update([
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'slug' => Str::slug($request->input('title')),
            'menu_id' => $request->input('menu_id'),
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

            File::delete('public/' . $category->image->file_path);
            $file->move($upload_dir, $file_name);
            $category->image()->update([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/categories')->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
        $category->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
        $category->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $categories = ($action === 'delete')
                        ? Category::find($item)
                        : Category::onlyTrashed()->where('id', $item)->first();

                    if ($categories) {
                        if ($action === 'delete')
                            $categories->delete();
                        if ($action === 'restore')
                            $categories->restore();
                        if ($action === 'forceDelete')
                            $categories->forceDelete();
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
}
