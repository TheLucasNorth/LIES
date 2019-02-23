<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request) {
        function random_code($limit)
        {
            return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
        }

        for ($i = 1; $i <= $request->numberUsers; $i++) {


            $code1 = random_code(3);
            $code2 = random_code(3);
            $code3 = random_code(3);
            $codes = array($code1, $code2, $code3);
            $coderaw = implode("-", $codes);
            $code = strtoupper($coderaw);

            $passwordPlain = strtoupper(random_code(6));
            $passwordHashed = Hash::make($passwordPlain);

            $user = new User();
            $user->votingCode = $code;
            $user->passwordPlain = $passwordPlain;
            $user->password = $passwordHashed;
            $user->save();
        }
            return redirect('admin')->with(['adminStatus' => 'Voters created successfully!']);



    }

    public function index() {
        $users = User::where('admin', 0)->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function export() {

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=LIES exported Users.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = User::select('votingCode', 'passwordPlain')->where('admin', 0)->get('votingCode', 'passwordPlain')->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, ['voting code', 'security code']);

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        function random_code($limit)
        {
            return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
        }
            $code1 = random_code(3);
            $code2 = random_code(3);
            $code3 = random_code(3);
            $codes = array($code1, $code2, $code3);
            $coderaw = implode("-", $codes);
            $code = strtoupper($coderaw);
        return view('admin.add-admin', compact('code'));
    }

    public function storeAdmin(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->votingCode = $request->votingCode;
        $user->password = Hash::make($request->password);
        $user->admin = 1;

        if($user->save()) {
            return redirect('admin')->with(['adminStatus' => 'Admin created successfully!']);
        }
        else {
            return redirect('admin')->with(['adminStatus' => 'Admin could not be created successfully, please try again']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->id === $user->id) {
            return view('admin.edit-admin');
        }
        else {
            return redirect('admin')->with(['adminStatus' => 'You can only update your own details.']);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = User::find($user->id);
        $user->name = $request->plainName;
        $user->votingCode = $request->username;
        $user->password = Hash::make($request->password);
        $user->admin = 1;

        if($user->save()) {
            return redirect('admin')->with(['adminStatus' => 'Details updated successfully!']);
        }
        else {
            return redirect('admin')->with(['adminStatus' => 'Details could not be updated successfully, please try again']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
