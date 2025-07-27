<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Models\About;
use App\Models\Media;

class AboutController extends Controller
{
    public function show(Request $request)
    {
        $list_about = About::orderBy('id', 'desc')->get();
        $countItem = count($list_about);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = About::onlyTrashed()->orderBy('id', 'desc')->get();
        $status = $request->input('status');
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        if ($search) {
            $list_about = About::where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where('title', 'LIKE', "%{$word}%");
                }
            })->orderBy('id', 'desc')->get();
        }
        if ($status) {
            if ($status == "trash") {
                $list_about = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_about = About::onlyTrashed()->where(function ($query) use ($keywords) {
                        foreach ($keywords as $word) {
                            $query->where('title', 'LIKE', "%{$word}%");
                        }
                    })->orderBy('id', 'desc')->get();
                }
            }
        }

        return view('admin.about.show', compact('list_about', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add()
    {
        return view('admin.about.add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required'],
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
            $file->move($upload_dir, $file_name);
            $about = About::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'user_id' => Auth::id(),
            ]);
            $about->image()->create([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/about')->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $about = About::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.about.edit', compact('about'));
    }
    public function update(Request $request, $id)
    {
        $about = About::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required'],
                'image' => ['nullable', 'image'],
            ]
        );
        $about->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
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

            File::delete('public/' . $about->image->file_path);
            $file->move($upload_dir, $file_name);
            $about->image()->update([
                'name' => $file_name,
                'file_path' => 'media/images/' . $file_name,
                'media_type' => 'image',
                'user_id' => Auth::id()
            ]);
        }
        return redirect('admin/about')->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        $about = About::find($id);
        $about->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        $about = About::onlyTrashed()->where('id', $id)->firstOrFail();
        $about->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        $about = About::onlyTrashed()->where('id', $id)->firstOrFail();
        $about->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $about = ($action === 'delete')
                        ? About::find($item)
                        : About::onlyTrashed()->where('id', $item)->first();

                    if ($about) {
                        if ($action === 'delete')
                            $about->delete();
                        if ($action === 'restore')
                            $about->restore();
                        if ($action === 'forceDelete')
                            $about->forceDelete();
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
