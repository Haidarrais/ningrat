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
        if ($input['role'] == 6 || $input['role'] == 7) {
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
                'role' => ['required','integer']
            ])->validate();
        }else{
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
                'role' => ['required', 'integer'],
                'mou' => ['required'],
                // 'upper' => ['required'],
                'facebook' => ['required', 'string', 'max:255'],
                'instagram' => ['required', 'string', 'max:255'],
                'nowhatsapp' => ['required', 'string', 'max:255'],
                'marketplace' => ['required', 'string', 'max:255'],
            ])->validate();
        }

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if($input['kode_referal']) {
            $upper = User::findReferal($input['kode_referal'])->first();
        }


        if(!$upper) {
            $data['upper'] = 0;
            $data['status'] = 1;
        }
        $user = User::create($data);
        if ($input['role'] == 2 || $input['role'] == 3 || $input['role'] == 4 || $input['role'] == 5) {
            if ($input['mou']) {
                $imagePost = 'File-MOU'.time().$input['mou']->getClientOriginalName();
                $file = $input['mou'];
                $fileName = $imagePost;
                $file->move('uploads/contents',$fileName);
                $image = $fileName;
            }
            $dataMember = [
                'mou'   => $image,
                'marketplace'   => $input['marketplace'],
                'instagram'   => $input['instagram'],
                'facebook'   => $input['facebook'],
                'nowhatsapp'   => $input['nowhatsapp'],
                'ttl'   => $input['ttl'],
            ];
            $user->member()->updateOrCreate([
                'user_id' => $user->id,
            ], $dataMember);
        }

        $user->assignRole($input['role']);

        return $user;

    }
}
