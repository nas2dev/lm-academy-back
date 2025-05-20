<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserListController extends Controller
{
    //

    public function index(Request $request) 
    {
        try {
            $perPage = $request->get('perPage', 10);
            $lists = UserList::paginate($perPage);

            return response()->json([
                'success' => true,
                'lists' => $lists
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'list_name' => 'required|string|between:2,100|unique:user_lists'
            ]);

            if($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $list = UserList::create([
                'list_name' => $request->list_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'List created succesfully',
                'list' => $list
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateListName(Request $request, $id) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }
            

            $validator = Validator::make($request->all(), [
                'list_name' => 'required|string|between:2,100|unique:user_lists,list_name,'.$id
            ]);

            if($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $list->update([
                'list_name' => $request->list_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'List updated succesfully',
                'list' => $list
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }

            $list->delete();

            return response()->json([
                'success' => true,
                'message' => 'List deleted succesfully',
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getUsersInList(Request $request, $id) 
    {
        try {
            $users_list = UserList::with(['users' => function($query) {
                                    $query->select('users.id', 'users.first_name', 'last_name', 'email');
                                }])
                            ->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'users_list' => $users_list
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function getAllAvailableUsers(Request $request, $id) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }

            $existingUserIds = $list->users()->pluck('users.id')->toArray();

            $available_users = User::whereNotIn('id', $existingUserIds)
                                    ->select('id', 'first_name', 'last_name', 'email')
                                    ->get();

            return response()->json([
                'success' => true,
                'available_users' => $available_users
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function addUsersToList(Request $request, $id) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }

            $userId = $request->user_id;
            $user_id = User::find($userId);
            if(!$user_id) {
                return response()->json([
                    'message' => 'User does not exists'
                ], 404);
            }
            
            $existingUser = UserList::with(['users' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])->where('id', $id)->first();

            if(!$existingUser->users->isEmpty()) {
                return response()->json([
                    'message' => 'This users is already added on this list!'
                ], 409);
            }

            $list->users()->attach($user_id, ['created_at' => now(), 'updated_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'User added succesfully',
                'list' => $list
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function removeUserFromList(Request $request, $id) 
    {
        try {
            if(!Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }

            $userId = $request->user_id;
            $user_id = User::find($userId);
            if(!$user_id) {
                return response()->json([
                    'message' => 'User does not exists'
                ], 404);
            }
            
            $existingUser = UserList::with(['users' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])->where('id', $id)->first();

            if($existingUser->users->isEmpty()) {
                return response()->json([
                    'message' => 'User does not exist on this list!'
                ], 409);
            }

            $list->users()->detach($userId);

            return response()->json([
                'success' => true,
                'message' => 'User removed succesfully from list',
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // pick a winner from list
    public function pickRandomWinner(Request $request, $id) 
    {
        try {
            $list = UserList::find($id);
            if(!$list) {
                return response()->json([
                    'message' => 'List does not exists'
                ], 404);
            }

            if($list->users->isEmpty()) {
                return response()->json([
                    'message' => 'No users in this list to pick a winner from'
                ], 422);
            } else if($list->users->count() < 2) {
                return response()->json([
                    'message' => 'This list cannot generat a random winner because it has only 1 user'
                ], 422);
            }

            $winner = $list->users->random()->only(['id', 'first_name', 'last_name', 'email']);

            return response()->json([
                'success' => true,
                'message' => 'Winner selected succesfully',
                'winner_user' => $winner
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
}
