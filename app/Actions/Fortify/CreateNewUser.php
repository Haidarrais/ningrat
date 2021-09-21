<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $upper = null;
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => 'integer'
        ])->validate();

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if($input['kode_referal']) {
            $upper = User::findReferal($input['kode_referal'])->first();
        }


        if($upper) {
            $data['upper'] = $upper->id;
            $data['status'] = 1;
        }
        $user = User::create($data);
        if($upper) {
            $user->assignRole($input['role']);
        }
        return $user;

    }
}
