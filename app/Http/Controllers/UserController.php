<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with('addresses');

            if($request->filled('searchQuery')){
                $query->search($request->searchQuery);
            }

            if($request->filled('minAge') || $request->filled('maxAge')){
                $query->ageRange($request->minAge, $request->maxAge);
            }

            if($request->filled('city')){
                $query->byCity($request->city);
            }

            $users = $query->paginate();

            $users->getCollection()->transform(function ($user){
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'birth_date' => $user->birth_date->format('Y-m-d'),
                    'age' => $user->age,
                    'addresses' => $user->addresses,
                ];
            });

            return response()->json([
                'success' => true,
                'msg' => "users retrived successfully",
                'data' => $users,
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'msg' => "error retrived users",
                'error' => $e->getMessage(),
            ],500);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{
            DB::tranction();
            
            $user = User::create($request->only([
                'first_name',
                'last_name',
                'email',
                'mobile_number',
                'birth_date',
            ]));

            foreach($request->addresses as $addressData){
                $user->addresses()->create($addressData);
            }

            DB::commit();

            $user->load('addresses');

            return response()->json([
                'success' => true,
                'msg' => "User created successfully",
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'birth_date' => $user->birth_date->format('Y-m-d'),
                    'age' => $user->age,
                    'addresses' => $user->addresses,
                ],
            ],201);
        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => "Error creating user",
                'data' => $e->getMessage(),
            ],500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $user = User::with('addresses')->findOrFail($id);

            return response()->json([
                'success' => true,
                'msg' => "User retrived successfully",
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'birth_date' => $user->birth_date->format('Y-m-d'),
                    'age' => $user->age,
                    'addresses' => $user->addresses,
                ],
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'msg' => "User not found",
                'data' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
