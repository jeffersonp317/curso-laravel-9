<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUpdateUserFormRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::where(function ($query) use ($search) {
            if ($search) {
                $query->where('email', $search);
                $query->orWhere('name', 'LIKE', "%{$search}%");
            }
            
        })->get();
        

        return view('users.index', compact('users'));
    }

    public function show($id)
    {

        // $user = User::where('id', $id)->first();

        if(!$user = User::find($id))
            return redirect()->route('users.index');
        

        return view('users.show', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUpdateUserFormRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        if(!$user = User::find($id))
            return redirect()->route('users.index');
        

        return view('users.edit', compact('user'));
    }
    
    public function update(StoreUpdateUserFormRequest $request, $id)
    {
        if(!$user = User::find($id))
            return redirect()->route('users.index');
        
        $data = $request->only('name', 'email');
        if($request->passaword)
            $data['password'] = bcrypt($request->password);

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function delete($id)
    {

        if(!$user = User::find($id))
            return redirect()->route('users.index');
        
            $user->delete();

            return redirect()->route('users.index');
    }
}
