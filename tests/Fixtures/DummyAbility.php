<?php //phpcs:ignoreFile

declare(strict_types=1);

namespace WP\MCP\Tests\Fixtures;

use RuntimeException;
use WP_Ability;

final class DummyAbility
{
    public static function register_all(): void
    {
        // AlwaysAllowed: returns text array
        wp_register_ability(
            'test/always-allowed',
            [
                'label' => 'Always Allowed',
                'description' => 'Returns a simple payload',
                'input_schema' => ['type' => 'object'],
                'output_schema' => [],
                'execute_callback' => fn(array $input) => ['ok' => true, 'echo' => $input],
                'permission_callback' => fn(array $input) => true,
                'meta' => [
                    'annotations' => ['group' => 'tests'],
                ],
            ]
        );

        // PermissionDenied: has_permission false
        wp_register_ability(
            'test/permission-denied',
            [
                'label' => 'Permission Denied',
                'description' => 'Permission denied ability',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => fn(array $input) => ['should' => 'not run'],
                'permission_callback' => fn(array $input) => false,
            ]
        );

        // Exception in permission
        wp_register_ability(
            'test/permission-exception',
            [
                'label' => 'Permission Exception',
                'description' => 'Throws in permission',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => fn(array $input) => ['never' => 'executed'],
                'permission_callback' => function (array $input) {
                    throw new RuntimeException('nope');
                },
            ]
        );

        // Exception in execute
        wp_register_ability(
            'test/execute-exception',
            [
                'label' => 'Execute Exception',
                'description' => 'Throws in execute',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => function (array $input) {
                    throw new RuntimeException('boom');
                },
                'permission_callback' => fn(array $input) => true,
            ]
        );

        // Image ability: returns image payload
        wp_register_ability(
            'test/image',
            [
                'label' => 'Image Tool',
                'description' => 'Returns image bytes',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => fn(array $input) => [
                    'type' => 'image',
                    'results' => "\x89PNG\r\n",
                    'mimeType' => 'image/png',
                ],
                'permission_callback' => fn(array $input) => true,
            ]
        );

        // Resource ability with URI in meta
        wp_register_ability(
            'test/resource',
            [
                'label' => 'Resource',
                'description' => 'A text resource',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => fn(array $input) => 'content',
                'permission_callback' => fn(array $input) => true,
                'meta' => [
                    'uri' => 'WordPress://local/resource-1',
                    'annotations' => ['group' => 'tests'],
                ],
            ]
        );

        // Prompt ability with arguments
        wp_register_ability(
            'test/prompt',
            [
                'label' => 'Prompt',
                'description' => 'A sample prompt',
                'input_schema' => ['type' => 'object'],
                'execute_callback' => fn(array $input) => [
                    'messages' => [
                        [
                            'role' => 'assistant',
                            'content' => ['type' => 'text', 'text' => 'hi']
                        ],
                    ],
                ],
                'permission_callback' => fn(array $input) => true,
                'meta' => [
                    'arguments' => [
                        ['name' => 'code', 'description' => 'Code to review', 'required' => true],
                    ],
                ],
            ]
        );
    }

    public static function unregister_all(): void
    {
        $names = [
            'test/always-allowed',
            'test/permission-denied',
            'test/permission-exception',
            'test/execute-exception',
            'test/image',
            'test/resource',
            'test/prompt',
        ];

        // Ensure abilities API is initialized so the registry exists
        if ( ! did_action( 'abilities_api_init' ) ) {
            do_action( 'abilities_api_init' );
        }

        foreach ( $names as $name ) {
            if ( wp_get_ability( $name ) instanceof WP_Ability ) {
                wp_unregister_ability( $name );
            }
        }
    }
}


