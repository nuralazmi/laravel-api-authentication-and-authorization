<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    //Yeni üye kayıt
    public function register(Request $request): JsonResponse
    {
        /*
         * Dışarıdan gelen parametreler kontrol edilecek. Validasyon işlemleri
         * 4 parametre alınacak. Şifre 2 defa alınacak aynı olup olmadığı da kontrol edilecek.
         */
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        //Eğer parametreler validasyon kurallarını karşılamazsa hata mesajları gösterilecek ve devam etmeyecek
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        //Eğer validasyon başarılı ise kişi eklenecek.
        User::create([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'email' => $request->input('email')
        ]);
        return response()->json(['ok' => 'true']);
    }

    //Üye girişi
    public function login(Request $request): JsonResponse
    {
        /*
         * Dışarıdan gelen parametreler kontrol edilecek. Validasyon işlemleri
         * * 4 parametre alınacak. Şifre 2 defa alınacak aynı olup olmadığı da kontrol edilecek.
         */
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        //Eğer parametreler validasyon kurallarını karşılamazsa hata mesajları gösterilecek ve devam etmeyecek
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(),], 400);
        }

        //Gelen bilgilere ait üye var mı kontrol ediliyor.
        if (!Auth::attempt($request->all())) {
            return response()->json(['message' => 'Kayıt bulunamadı'], 401);
        }

        //Giriş başarılı. Token ile birlikte giriş yapılacak.
        return response()->json(
            [
                'ok' => true,
                'message' => 'Giriş başarılı',
                'token' => auth()->user()->createToken('API Token')->plainTextToken
            ]
        );
    }

    //Çıkış işlemi
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Tokens Revoked'
        ];
    }


}
