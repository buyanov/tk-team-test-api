<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Role;
use App\Task;
use App\User;
use Laravel\Sanctum\Sanctum;
use RoleSeeder;
use Tests\TestCase;

class TasksTest extends TestCase
{
    /**
     * @var string
     */
    protected $resourceType = 'tasks';

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

        $task = factory(Task::class)->make();

        $data = [
            'type' => 'tasks',
            'attributes' => [
                'user-id' => $this->adminUser->getKey(),
                'title' => $task->title,
                'description' => $task->description,
                'status' => TaskStatus::WIP(),
            ]
        ];

        $id = $this->jsonApi()
            ->data($data)
            ->post('/api/v1/tasks')
            ->id();

        $this->assertDatabaseHas('tasks', [
            'id' => $id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => TaskStatus::WIP(),
        ]);
    }

    public function testUpdate(): void
    {
        Sanctum::actingAs($this->adminUser);

        $task = factory(Task::class)->make();
        $this->adminUser->tasks()->save($task);

        $data = [
            'type' => 'tasks',
            'id' => $task->getRouteKey(),
            'attributes' => [
                'user-id' => $this->adminUser->getKey(),
                'title' => 'Some task',
                'description' => 'Task description',
                'status' => $task->status,
            ]
        ];

        $this->jsonApi()
            ->data($data)
            ->patch('/api/v1/tasks/' . $task->getRouteKey());

        $this->assertDatabaseHas('tasks', [
            'id' => $task->getKey(),
            'user_id' => $this->adminUser->getKey(),
            'title' => 'Some task',
            'description' => 'Task description',
            'status' => $task->status,
        ]);
    }

    /**
     * Test the delete resource route.
     */
    public function testDelete(): void
    {
        Sanctum::actingAs($this->adminUser);

        $task = factory(Task::class)->make();
        $this->adminUser->tasks()->save($task);

        $this->jsonApi()
            ->delete('/api/v1/tasks/' . $task->getRouteKey());

        $this->assertDatabaseMissing('tasks', ['id' => $task->getKey()]);
    }

    /**
     * Test the read resource route.
     */
    public function testRead(): void
    {
        $this->adminUser->tasks()->createMany(
            factory(Task::class, 10)->make()->toArray()
        );

        $this->jsonApi()
            ->get('/api/v1/tasks')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'description',
                            'status',
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
        $this->adminUser->tasks()->createMany(
            factory(Task::class, 30)->make()->toArray()
        );

        $this->jsonApi()
            ->get('/api/v1/tasks?page[number]=2&page[size]=10')
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
                            'title',
                            'description',
                            'status',
                            'created-at',
                            'updated-at'
                        ]
                    ]
                ]
            ]);
    }

//    /**
//     * Test the search resource route.
//     */
//    public function testSearch():void
//    {
//        $this->adminUser->tasks()->createMany(
//            factory(Task::class, 100)->make()->toArray()
//        );
//
//        $randomTitle = $this->adminUser->tasks->random()->title;
//
//        $this->artisan('scout:import', ['model' => Task::class]);
//
//        $this->jsonApi()
//            ->get("/api/v1/tasks?filter[query]=title:($randomTitle)")
//            ->assertStatus(200)
//            ->assertJsonStructure([
//                'data' => [
//                    '*' => [
//                        'type',
//                        'id',
//                        'attributes' => [
//                            'title',
//                            'description',
//                            'status',
//                            'created-at',
//                            'updated-at'
//                        ]
//                    ]
//                ]
//            ]);
//
//        $this->artisan('scout:flush', ['model' => Task::class]);
//    }
}
