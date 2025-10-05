<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    function show(Request $request)
    {
        if (!Gate::allows('user.show')) {
            abort(403);
        }
        Paginator::useBootstrapFive();
        $currentPage = 1;
        if ($request->page) {
            $currentPage = $request->page;
        }


        $list_user = User::orderBy('id', 'desc')->paginate(50);
        $countItem = count(User::all());
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = User::onlyTrashed()->orderBy('id', 'desc')->get();
        $status = $request->input('status');
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        if ($search) {
            $list_user = User::where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where('name', 'LIKE', "%{$word}%")->orWhere('email', 'LIKE', "%{$word}%");
                }
            })->orderBy('id', 'desc')->paginate(50);
        }
        if ($status) {
            if ($status == "trash") {
                $list_user = User::onlyTrashed()->orderBy('id', 'desc')->paginate(50);
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_user = User::onlyTrashed()->where(function ($query) use ($keywords) {
                        foreach ($keywords as $word) {
                            $query->where('title', 'LIKE', "%{$word}%")->orWhere('email', 'LIKE', "%{$word}%");
                        }
                    })->orderBy('id', 'desc')->paginate(50);
                }
            }
        }
        return view('admin.user.show', compact('list_user', 'list_trash', 'actions', 'countItem', 'trash', 'currentPage'));
    }
    function add()
    {
        if (!Gate::allows('user.add')) {
            abort(403);
        }
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }
    function store(Request $request)
    {
        if (!Gate::allows('user.add')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->roles()->attach($request->input('role'));
        event(new Registered($user));
        return redirect('admin/user')->with('status', 'Added successfully!');
    }
    function edit($id)
    {
        if (!Gate::allows('user.edit')) {
            abort(403);
        }
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }
    function update(Request $request, $id)
    {
        if (!Gate::allows('user.edit')) {
            abort(403);
        }
        if ($request->password == "") {
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                ]
            );
            User::where('id', $id)->update([
                'name' => $request->name,
            ]);
        } else {
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]
            );
            User::where('id', $id)->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
        }
        if (Auth::id() != 2) {
            $user = User::find($id);
            $user->roles()->sync($request->input('role', []));
        }
        return redirect('admin/user')->with('status', 'Updated successfully!');
    }
    function delete(Request $request, $id)
    {
        if (!Gate::allows('user.delete')) {
            abort(403);
        }
        if (Auth::id() == $id) {
            return redirect('admin/user')->with('danger', "You can't delete this user!");
        } elseif ($id == 2) {
            return redirect('admin/user')->with('danger', "You can't delete this user!");
        } else {
            User::find($id)->delete();
            return redirect('admin/user')->with('status', 'Deleted successfully!');
        }
    }
    function handleAction(Request $request)
    {
        if (!Gate::allows('user.delete')) {
            abort(403);
        }
        if (!isset($request->checkItem)) {
            return redirect('admin/user')->with('danger', 'You have not selected anything yet');
        }
        if ($request->actions == "delete") {
            foreach ($request->checkItem as $item) {
                if ($item == Auth::id() || $item == 2) {
                    return redirect('admin/user')->with('danger', "You can't delete your account or Admin account");
                }
                $user = User::find($item);
                $user->delete();
            }
            return redirect('admin/user')->with('status', 'Deleted successfully!');
        } elseif ($request->actions == "forceDelete") {
            foreach ($request->checkItem as $item) {
                $user_role = User_role::where('user_id', $item)->get();
                foreach ($user_role as $v) {
                    $v->delete();
                }
                $user = User::onlyTrashed()->where('id', $item)->first();
                $user->forceDelete();
            }
            return redirect('admin/user?status=trash')->with('status', 'Deleted successfully!');
        } elseif ($request->actions == "restore") {
            foreach ($request->checkItem as $item) {
                User::onlyTrashed()->where('id', $item)->restore();
            }
            return redirect('admin/user?status=trash')->with('status', 'Restored successfully!');
        } else {
            return redirect('admin/user')->with('danger', 'You have not chosen action yet');
        }
    }
    function forceDelete($id)
    {
        if (!Gate::allows('user.delete')) {
            abort(403);
        }
        $user_role = User_role::where('user_id', $id)->get();
        foreach ($user_role as $v) {
            $v->delete();
        }
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->forceDelete();
        return redirect('admin/user?status=trash')->with('status', 'Deleted successfully!');
    }
    function restore($id)
    {
        if (!Gate::allows('user.delete')) {
            abort(403);
        }
        User::onlyTrashed()->where('id', $id)->restore();
        return redirect('admin/user')->with('status', 'Restored successfully!');
    }
    function profile()
    {

        $user = Auth::user();

        return view('admin.user.profile', compact('user'));
    }
}

