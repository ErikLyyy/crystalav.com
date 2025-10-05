<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Models\About;
use App\Models\Media;
use Illuminate\Support\Facades\Gate;

class AboutController extends Controller
{
    public function show(Request $request)
    {
        if (!Gate::allows('about-us.show')) {
            abort(403);
        }
        $list_about = About::orderBy('id', 'desc')->get();
        $countItem = count($list_about);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = About::onlyTrashed()->orderBy('id', 'desc')->get();
        $status = $request->input('status');
        if ($status) {
            if ($status == "trash") {
                $list_about = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
            }
        }

        return view('admin.about.show', compact('list_about', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add()
    {
        if (!Gate::allows('about-us.add')) {
            abort(403);
        }
        return view('admin.about.add');
    }
    public function store(Request $request)
    {
        if (!Gate::allows('about-us.add')) {
            abort(403);
        }
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
        if (!Gate::allows('about-us.edit')) {
            abort(403);
        }
        $about = About::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.about.edit', compact('about'));
    }
    public function update(Request $request, $id)
    {
        if (!Gate::allows('about-us.edit')) {
            abort(403);
        }
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
        if (!Gate::allows('about-us.delete')) {
            abort(403);
        }
        $about = About::find($id);
        $about->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        if (!Gate::allows('about-us.delete')) {
            abort(403);
        }
        $about = About::onlyTrashed()->where('id', $id)->firstOrFail();
        $about->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        if (!Gate::allows('about-us.delete')) {
            abort(403);
        }
        $about = About::onlyTrashed()->where('id', $id)->firstOrFail();
        $about->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if (!Gate::allows('about-us.delete')) {
            abort(403);
        }
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
