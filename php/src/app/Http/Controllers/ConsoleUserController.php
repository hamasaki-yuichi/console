<?php

namespace App\Http\Controllers;

use App\Models\ConsoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConsoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $request->only(['id', 'name', 'email']);

        $consoleUsers = ConsoleUser::query()
            ->when(isset($params['id']), function ($query) use ($params) {
                $query->where('id', '=', $params['id']);
            })
            ->when(isset($params['name']), function ($query) use ($params) {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            })
            ->when(isset($params['email']), function ($query) use ($params) {
                $query->where('email', 'like', '%' . $params['email'] . '%');
            })
            ->get();

        return view('console-users.index', compact('consoleUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('console-users.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->only(['name', 'email']);
        $params['password'] = Hash::make($request->password);

        ConsoleUser::create($params);

        return redirect()->route('console-users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsoleUser $consoleUser)
    {
        return view('console-users.show', compact('consoleUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsoleUser $consoleUser)
    {
        return view('console-users.form', compact('consoleUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsoleUser $consoleUser)
    {
        $params = $request->only(['name', 'email']);

        if (isset($params['password'])) {
            $params['password'] = Hash::make($request->password);
        }

        $consoleUser->update($params);

        return redirect()->route('console-users.show', $consoleUser->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsoleUser $consoleUser)
    {
        $consoleUser->delete();

        return redirect()->back();
    }
}
