<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Laravel\Sanctum\Sanctum;
use RoleSeeder;
use Tests\TestCase;

class UsersTest extends TestCase
{
    /**
     * @var string
     */
    protected $resourceType = 'users';

    /**
     * @var User
     */
    protected $adminUser;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create roles and abilities
        $this->seed(RoleSeeder::class);
        $adminRole = Role::where(['name' => 'admin'])->first();
        $this->adminUser = factory(User::class)->create();
        $this->adminUser->assignRole($adminRole);
    }

    /**
     * Test the create resource route.
     */
    public function testCreate(): void
    {
        Sanctum::actingAs($this->adminUser);

        $user = factory(User::class)->make();
        $data = [
            'type' => 'users',
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'password',
                'password-confirmation' => 'password',
            ]
        ];

        $id = $this->jsonApi()
            ->data($data)
            ->post('/api/v1/users')
            ->id();

        $this->assertDatabaseHas('users', [
            'id' => $id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function testUpdate(): void
    {
        Sanctum::actingAs($this->adminUser);

        $user = factory(User::class)->create();
        $data = [
            'type' => 'users',
            'id' => $user->getRouteKey(),
            'attributes' => [
                'name' => 'Buyanov Danila',
                'email' => 'info@saity74.ru',
            ]
        ];

        $this->jsonApi()
            ->data($data)
            ->patch('/api/v1/users/' . $user->getRouteKey());

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Test the delete resource route.
     */
    public function testDelete(): void
    {
        Sanctum::actingAs($this->adminUser);
        $user = factory(User::class)->create();
        $this->jsonApi()
            ->delete('/api/v1/users/' . $user->getRouteKey());
        $this->assertDatabaseMissing('users', ['id' => $user->getKey()]);
    }

    /**
     * Test the read resource route.
     */
    public function testRead(): void
    {
        factory(User::class, 10)->create();

        $this->jsonApi()
            ->get('/api/v1/users')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'email',
                            'created-at',
                            'updated-at'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test the read resource route.
     */
    public function testReadPerPage(): void
    {
        factory(User::class, 30)->create();

        $this->jsonApi()
            ->get('/api/v1/users?page[number]=2&page[size]=10')
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta' => [
                    'page' => [
                        'current-page',
                        'per-page',
                        'from',
                        'to',
                        'total',
                        'last-page'
                    ]
                ],
                'links' => [
                    'first',
                    'prev',
                    'next',
                    'last'
                ],
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'email',
                            'created-at',
                            'updated-at'
                        ]
                    ]
                ]
            ]);
    }
}
