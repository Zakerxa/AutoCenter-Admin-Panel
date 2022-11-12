<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule as ValidationRule;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = $request->validate([
                'email' => ['required', 'string', ValidationRule::unique('users', 'email')],
                'password' => ['required', 'min:3', 'max:50']
            ]);
        } catch (ValidationException $th) {
            return $th->validator->errors();
        }
        $state = User::create([
            'name' => 'zakerxa',
            'email' => $user['email'],
            'password' => bcrypt($user['password'])
        ]);
        if ($state) {
            return response()->json(['response' => 'success']);
        } else return response()->json(['response' => 'Error Something Wrong']);
    }

    public function login(Request $request)
    {

        $admin = $request->validate([
            'email' => ['required', 'email', ValidationRule::exists('users', 'email'), 'max:255'],
            'password' => ['required', 'min:3', 'max:50']
        ]);
        // Auth User
        if (auth()->attempt($admin)) {
            $user = User::where('email', $admin['email'])->first();
            $token = $user->createToken('auth')->plainTextToken;
            return response()->json([
                'response' => 'success',
                'token' => $token
            ]);
        }
        else return response(['response' => 'The provided credentials do not match our records.']);

    }

    public function index(Request $request)
    {
       $form = client::OrderBy('id', 'desc')->filter(request(['search']))->paginate($request->per_page ?? 5);
       $noti = client::where('read',0)->OrderBy('id','desc')->count();
       return response()->json(['data'=>$form,'noti'=>$noti]);
    }

    public function store(Request $request)
    {
        try {
            $inputProduct = $request->validate([
                'username' => ['required', 'min:3','max:50'],
                'phone' => ['required','numeric','min:10'],
                'email' => ['required','email'],
                'message' => ['required','min:10']
            ]);
        } catch (ValidationException $th) {
            return $th->validator->errors();
        }

        $client = client::create($inputProduct);
        if($client){
            // Mail::to($client->authMail)->send(new ContactFormsMail($client));
            return response()->json(['response' => 'success']);
        }
    }

    public function read($id){
        $form = client::find($id);
        if($form){
            $form->read = 1;
            $form->save();
            return response()->json(['response'=>'success']);
        }
        return response()->json(['response'=>'No Data Found']);
    }

    public function sendmail(Request $request){
        try {
            $valid = $request->validate([
                'title' => 'required|min:3|max:100',
                'name' => 'required|min:3|max:100',
                'email' => 'required|email:rfc,dns|max:255',
                'subject' => 'required|min:5|max:200',
                'adminMail' => 'required',
                'body' => 'required|min:5'
            ]);
        } catch (ValidationException $th) {
            return $th->validator->errors();
        }
        //Use the create file as view for Mail function and send the email
        Mail::send('mail.sendMail',['data' => $valid],function($message) use ($valid) {
            $message->to( $valid['email'], $valid['name'])->from($valid['adminMail'],$valid['title'])->subject($valid['subject']);
        });
        return response()->json(['response' => 'success']);
    }

    public function destory($id){
       try {
          $ids = explode(",", $id);
          client::whereIn('id', $ids)->delete();
          return response()->json(['response'=>'success']);
       }catch (\Throwable $th) {
          return response()->json($th);
       }
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->tokens()->delete();
        return response()->json(['response' => 'logout']);
    }
}
