<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Logout
{
    /** @param array{} $args */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $context->user();

        if ($user) {
            $user->tokens()->delete();
            return [
                'success' => true,
                'message' => 'Logged out successfully.'
            ];
        }

        return [
            'success' => false,
            'message' => 'User not authenticated.'
        ];
    }
}