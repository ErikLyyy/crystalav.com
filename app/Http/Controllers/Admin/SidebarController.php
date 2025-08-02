<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\DataTree;
use App\Traits\HasChild;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Sidebar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SidebarController extends Controller
{
    use HasChild, DataTree;
    public function show(Request $request, $type)
    {
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        $query = Sidebar::where('type', $type);

        // Nếu có tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($keywords) {
                // Search sidebar title
                foreach ($keywords as $word) {
                    $q->where('title', 'LIKE', "%{$word}%");
                }

                // Search category title
                $q->orWhereHas('category', function ($q2) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q2->where('title', 'LIKE', "%{$word}%");
                    }
                });

                // Search menu title
                $q->orWhereHas('category.menu', function ($q3) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q3->where('title', 'LIKE', "%{$word}%");
                    }
                });
            });
        }

        $list_sidebar = $query->orderBy('id', 'desc')->get();

        $list_sidebar = $this->data_tree($list_sidebar);
        $countItem = count(Sidebar::where('type', $type)->orderBy('id', 'asc')->get());
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $type == "subcategory" ? $list_trash = Sidebar::where('type', 'subcategory')->onlyTrashed()->get() : $list_trash = Sidebar::where('type', 'filter')->onlyTrashed()->get();
        $status = $request->input('status');
        if ($status) {
            if ($status == "trash") {
                $list_sidebar = Sidebar::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
            }
        }
        foreach ($list_sidebar as $sidebar) {
            if ($sidebar->parent_id == 0) {
                $sidebar->layout = "--";
            }
        }

        return view('admin.sidebar.show_' . $type, compact('list_sidebar', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add($type)
    {
        $list_categories = Category::withTrashed()->get();
        $list_subcategories = Sidebar::withTrashed()->where('parent_id', 0)->where('type', 'subcategory')->orderBy('id', 'asc')->get();
        $list_filter = Sidebar::withTrashed()->where('parent_id', 0)->where('type', 'filter')->orderBy('id', 'asc')->get();
        return view('admin.sidebar.add_' . $type, compact('list_subcategories', 'list_filter', 'list_categories'));
    }
    public function store(Request $request, $type)
    {
        if ($type == "subcategory") {
            $rules = [
                'title' => ['required', 'string', 'max:255'],
                'category_id' => ['required', 'integer'],
                'parent_id' => ['required', 'integer'],
            ];
            $messages = [
                'integer' => 'The :attribute subcategory field is required.',
            ];
            $attributes = [
                'category_id' => 'main category',
                'parent_id' => 'parent subcategory',
            ];

            $list_subcategories = Sidebar::withTrashed()
                ->where('category_id', $request->category_id)
                ->where('parent_id', 0)
                ->where('type', 'subcategory')
                ->orderBy('id', 'asc')
                ->get();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with([
                        'list_subcategories' => $list_subcategories,
                    ]);
            }
        } else {
            $rules = [
                'title' => ['required', 'string', 'max:255'],
                'parent_id' => ['required', 'integer'],
            ];
            $messages = [
                'integer' => 'The :attribute field is required.',
            ];
            $attributes = [
                'parent_id' => 'parent subcategory',
            ];

            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        Sidebar::create([
            'title' => $request->input('title'),
            'type' => $type,
            'category_id' => $request->input('category_id'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);

        return redirect('admin/sidebar/show/' . $type)
            ->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $list_categories = Category::withTrashed()->get();
        $list_filter = Sidebar::where('parent_id', 0)->where('type', 'filter')->where('id', 'not like', $id)->orderBy('id', 'asc')->get();
        $sidebar = Sidebar::withTrashed()->where('id', $id)->firstOrFail();
        if ($sidebar->category_id) {
            $list_subcategories = Sidebar::withTrashed()
                ->where('id', 'not like', $id)
                ->where('category_id', $sidebar->category_id)
                ->where('parent_id', 0)
                ->where('type', 'subcategory')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $list_subcategories = Sidebar::where('parent_id', 0)->where('type', 'subcategory')->where('id', 'not like', $id)->orderBy('id', 'asc')->get();

        }
        return view('admin.sidebar.edit_' . $sidebar->type, compact('sidebar', 'list_subcategories', 'list_filter', 'list_categories'));
    }
    public function update(Request $request, $type, $id)
    {
        $sidebar = Sidebar::withTrashed()->where('id', $id)->firstOrFail();
        if ($type == "subcategory") {
            $request->validate(
                [
                    'title' => ['required', 'string', 'max:255'],
                    'category_id' => ['required', 'integer'],
                    'parent_id' => ['required', 'integer'],
                ],
                [
                    'integer' => 'The :attribute subcategory field is required.',
                ],
                [
                    'category_id' => 'main category',
                    'parent_id' => 'parent subcategory',
                ]
            );
        } else {
            $request->validate(
                [
                    'title' => ['required', 'string', 'max:255'],
                    'parent_id' => ['required', 'integer'],
                ],
                [
                    'integer' => 'The :attribute subcategory field is required.',
                ],
                [
                    'parent_id' => 'parent subcategory',
                ]
            );
        }
        $sidebar->update([
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/sidebar/show/' . $type)->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        // When delete parent sidebar, the children will be delete too
        Sidebar::where('parent_id', $id)->delete();
        $sidebar = Sidebar::find($id);
        $sidebar->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        Sidebar::onlyTrashed()->where('parent_id', $id)->forceDelete();
        $sidebar = Sidebar::onlyTrashed()->where('id', $id)->firstOrFail();
        $sidebar->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        // When restore child sidebar, the parent will be restore too
        $sidebar = Sidebar::onlyTrashed()->where('id', $id)->firstOrFail();
        Sidebar::onlyTrashed()->where('id', $sidebar->parent_id)->restore();
        $sidebar->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $sidebar = ($action === 'delete')
                        ? Sidebar::find($item)
                        : Sidebar::onlyTrashed()->where('id', $item)->first();

                    if ($sidebar) {
                        if ($action === 'delete') {
                            Sidebar::where('parent_id', $item)->delete();
                            $sidebar->delete();
                        }
                        if ($action === 'restore') {
                            Sidebar::onlyTrashed()->where('id', $sidebar->parent_id)->restore();
                            $sidebar->restore();
                        }
                        if ($action === 'forceDelete') {
                            Sidebar::onlyTrashed()->where('parent_id', $item)->forceDelete();
                            $sidebar->forceDelete();
                        }
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
    public function ajax(Request $request)
    {
        $result = [];
        if ($request->category_id && $request->category_id != "") {
            //select sidebar data when edit a sidebar
            $list_subcategories = Sidebar::withTrashed()
                ->where('id', 'not like', $request->edit_value)
                ->where('category_id', $request->category_id)
                ->where('parent_id', 0)
                ->where('type', 'subcategory')
                ->get();
            $result = ['list_subcategory' => $list_subcategories, 'subcategory' => $request->subcategory];
        } elseif ($request->category_id == "") {
            $list_subcategories = Sidebar::withTrashed()
                ->where('parent_id', 0)
                ->where('type', 'subcategory')
                ->get();
            $result = ['list_subcategory' => $list_subcategories, 'subcategory' => $request->subcategory];
        }
        if ($request->subcategory_id && $request->subcategory_id != "") {
            //search category of this sidebar to select
            $category = Sidebar::withTrashed()->where('id', $request->subcategory_id)->first()->category;
            $result = $category;
        }
        return response()->json($result);
    }
}
