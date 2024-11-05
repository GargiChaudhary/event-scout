<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class UpdateUser
{
    /** @param array{} $args */
    public function __invoke(null $rootValue, array $args, GraphQLContext $context)
    {
        $this->validate($args);

        $user = User::find($args["id"]);
        $user->name = $args["name"];
        $user->bio = $args["bio"];
        $user->profile_image = $args["profile_image"];
        $user->mobile = $args["mobile"];
        $user->email = $args["email"];

        if ($args["password"])
            $user->password = Hash::make($args["password"]);

        $user->save();

        return $user;
    }

    private function validate(array $input): void
    {
        $rules = [
            "name" => "required",
            "mobile" => "required",
            'email' => 'required|email|unique:users,email,' . $input["id"],
        ];

        if ($input["password"])
            $rules['password'] = 'required|min:8|confirmed';

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);}}
}