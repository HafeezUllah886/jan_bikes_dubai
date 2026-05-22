<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderbookerController extends Controller
{
    public function index()
    {
        $type = 'Order Booker';
        $users = User::orderbookers()->orderBy('id', 'asc')->get();

        return view('users.index', compact('users', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'contact' => 'required',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'role' => 'Order Booker',
        ]);

        return back()->with('success', 'Order Booker Created');
    }

    public function update(Request $request, User $orderbooker)
    {
        $data = [
            'contact' => $request->contact,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $orderbooker->update($data);

        return back()->with('success', 'Order Booker Updated');
    }

    public function destroy(User $orderbooker)
    {
        $orderbooker->delete();

        return back()->with('success', 'Order Booker Deleted');
    }
}
