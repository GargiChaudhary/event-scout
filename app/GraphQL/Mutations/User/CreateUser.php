<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class CreateUser
{
    /** @param array{} $args */
    public function __invoke(null $rootValue, array $args, GraphQLContext $context)
    {
        $this->validate($args);

        $insArr = [
            "name" => $args["name"],
            "email" => $args["email"],
            "password" => Hash::make($args["password"]),
            "bio" => $args["bio"],
            "profile_image"=> $args["profile_image"],
            "country_code" => "91",
            "mobile" => $args["mobile"],
        ];

        $user = User::create($insArr);

        return $user;
    }

    private function validate(array $input): void
    {
        $rules = [
            "name" => "required",
            "mobile" => "required",
            'email' => 'required|email|unique:users,email',
        ];

        if ($input["password"])
            $rules['password'] = 'required|min:8|confirmed';

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}