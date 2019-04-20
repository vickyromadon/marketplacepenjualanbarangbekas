<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(){
    	return $this->view()
    			->with('data', User::find(Auth::user()->id));;
    }

    public function setting(Request $request, $id){
        $validator = $request->validate([
            'name'          => 'nullable|string|max:191',
            'phone'         => ['nullable', 'string', 'phone:ID', Rule::unique('users')->ignore($id)],
            'age'           => 'nullable|numeric',
            'birthplace'    => 'nullable|string',
            'birthdate'     => 'nullable|date',
            'gender'        => 'nullable|in:Male,Female',
            'religion'      => 'nullable|in:Islam,Kristen Protestan,Katolik,Hindu,Buddha,Kong Hu Cu',
        ]);

        $user = User::find($id);
        $user->name         = $request->name;   
        $user->phone        = $request->phone;
        $user->age          = $request->age; //(getdate()['year']) - substr($request->birthdate, 0, 4);
        $user->birthplace   = $request->birthplace;
        $user->birthdate    = $request->birthdate;
        $user->gender       = $request->gender;
        $user->religion     = $request->religion;

        if( $user->save() ){
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

    public function password(Request $request, $id){
        $user = User::find($id);

        $validator = $request->validate([
            'current_password'     => 'required',
            'new_password'         => 'required|min:6',
            'new_password_confirm' => 'required_with:new_password|same:new_password|min:6',
        ]);

        if( !(Hash::check($request->current_password, $user->password)) ){
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi Anda saat ini tidak cocok dengan kata sandi yang Anda berikan. Silakan coba lagi.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        
        if( $user->save() ){
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

    public function avatar(Request $request, $id){
        $validator = $request->validate([
            'file_id'   => 'required|mimes:jpeg,jpg,png|max:5000',
        ]);

        $user = User::find($id);

        if( $request->file_id != null ){
            if( $request->hasFile('file_id') != null ){
                if( $user->file_id != null ){
                    $picture = File::find(intval($user->file_id));
                    Storage::delete($picture->path);
                    $picture->delete();
                }
            }

            $filename  = $request->file('file_id')->getClientOriginalName();
            $path      = $request->file('file_id')->store('owner');
            $extension = $request->file('file_id')->getClientOriginalExtension();
            $size      = $request->file('file_id')->getClientSize();
            
            $file            = new File();
            $file->filename  = time() . '_' . $filename;
            $file->title     = $request->name;
            $file->path      = $path;
            $file->extension = $extension;
            $file->size      = $size;
            $file->save();

            $user->file()->associate($file);
        }

        if( !$user->save() ){
            if ( $request->hasFile('file_id') ) {
               $fileDelete = File::where('path', '=', $file->path)->first();
               Storage::delete($fileDelete->path);
               $fileDelete->delete(); 
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Perubahan gagal disimpan'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Perubahan berhasil disimpan'
            ]);
        }
    }
}
