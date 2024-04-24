<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\User; // this represents the users table
use Illuminate\Support\Facades\Hash; //use for hashing a password (encryption)

class AdminSeeder extends Seeder
{

    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->name = 'Administrator1212'; //'Administrator1212' any name!
        $this->user->email = 'Administrator1212@mail.com'; // 'Administrator1212@mail.com' any email!
        $this->user->password = Hash::make('administrator1212'); //'administrator1212' any password! // make "inside(::)" the Hash
        $this->user->role_id - User::ADMIN_ROLE_ID; // 1 for administrator! // ADMIN_ROLE_ID "inside(::)" the User
        $this->user->save();
    }
}
