<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //hien thi tat ca nguoi dung
        return Employee::all();
        // $users = DB::table('users')->select('*')->where('deleted_at', null)->get();
        // return $users;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //tao nguoi dung
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //luu nguoi dung
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //hien thi nguoi dung theo id
        return Employee::find($id);
        // $user = DB::table('users')->select('*')->where('id', $id)->get();
        // return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //lay ra nguoi dung theo id de update
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //cap nhat lai nguoi dung
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //xoa nguoi dung (xoa mem)
        $user = Employee::find($id); // Retrieve the user with ID 1
        $user->delete(); // Soft delete the user record
        return response()->json(
            [
                'message' => 'User deleted',
            ], 200
        );
    }

    public function showbin()
    {
        // Retrieve only soft-deleted users
        $deletedUsers = Employee::onlyTrashed()->get();
        // $deletedUsers = DB::table('users')->whereNotNull('deleted_at')->get();
        return $deletedUsers;
    }
}
