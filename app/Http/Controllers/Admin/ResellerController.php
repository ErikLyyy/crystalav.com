<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Models\Reseller;
use App\Models\Media;

class ResellerController extends Controller
{
    public function show(Request $request)
    {
        $list_reseller = Reseller::orderBy('id', 'desc')->get();
        $countItem = count($list_reseller);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = Reseller::onlyTrashed()->orderBy('id', 'desc')->get();
        $status = $request->input('status');
        $search = $request->input('search');

        if ($search) {
            $list_reseller = Reseller::where('url', 'like', "%{$search}%")->orderBy('id', 'desc')->get();
        }
        if ($status) {
            if ($status == "trash") {
                $list_reseller = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_reseller = Reseller::onlyTrashed()->where('url', 'like', "%{$search}%")->orderBy('id', 'desc')->get();
                }
            }
        }

        return view('admin.reseller.show', compact('list_reseller', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add()
    {
        return view('admin.reseller.add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'url' => ['required', 'string', 'max:255'],
                'image' => ['required', 'image'],
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
            // dd($file);
            $file->move($upload_dir, $file_name);

            $reseller = Reseller::create([
                'url' => $request->input('url'),
                'user_id' => Auth::id(),
            ]);
            $reseller->image()->create([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/reseller')->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $reseller = Reseller::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.reseller.edit', compact('reseller'));
    }
    public function update(Request $request, $id)
    {
        $reseller = Reseller::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'url' => ['required', 'string', 'max:255'],
                'image' => ['nullable', 'image'],
            ]
        );
        $reseller->update([
            'url' => $request->input('url'),
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

            File::delete('public/' . $reseller->image->file_path);
            $file->move($upload_dir, $file_name);
            $reseller->image()->update([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/reseller')->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        $reseller = Reseller::find($id);
        $reseller->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        $reseller = Reseller::onlyTrashed()->where('id', $id)->firstOrFail();
        $reseller->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        $reseller = Reseller::onlyTrashed()->where('id', $id)->firstOrFail();
        $reseller->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $reseller = ($action === 'delete')
                        ? Reseller::find($item)
                        : Reseller::onlyTrashed()->where('id', $item)->first();

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
}
