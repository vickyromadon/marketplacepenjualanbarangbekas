<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
    	return $this->view()
                ->with('data', Admin::find(Auth::user()->id));
    }

    public function setting(Request $request, $id)
    {
        $validator = $request->validate([
            'name'  => 'nullable|string|max:191',
            'phone' => ['nullable', 'string', 'phone:ID', Rule::unique('admins')->ignore($id)],
        ]);

        $admin = Admin::find($id);
        if( $request->name != null && $request->phone == null ){
            $admin->name  = $request->name;
        }
        else if( $request->name == null && $request->phone != null ){
            $admin->phone = $request->phone;
        }
        else{
            $admin->name  = $request->name;   
            $admin->phone = $request->phone;
        }

        if( $admin->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil disimpan',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Perubahan gagal disimpan',
            ]);
        }
    }

    public function password(Request $request, $id)
    {
        $admin = Admin::find($id);

        $validator = $request->validate([
            'current_password'     => 'required',
            'new_password'         => 'required|min:6',
            'new_password_confirm' => 'required_with:new_password|same:new_password|min:6',
        ]);

        if( !(Hash::check($request->current_password, $admin->password)) ){
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi Anda saat ini tidak cocok dengan kata sandi yang Anda berikan. Silakan coba lagi.',
            ]);
        }

        $admin->password = Hash::make($request->new_password);
        
        if( $admin->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil disimpan',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Perubahan gagal disimpan',
            ]);
        }
    }
}
