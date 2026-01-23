<?php

namespace Tests;

use Database\Models\User;

class DatabaseTest extends TestCase
{
    public function test_user_model_can_create_and_find(): void
    {
        $this->runMigrations();

        $model = User::make();
        $created = $model->create(['name' => 'Testeur', 'email' => 'test@example.com']);

        $this->assertNotEmpty($created);
        $this->assertEquals('Testeur', $created['name']);

        $found = $model->find($created['id']);

        $this->assertIsArray($found);
        $this->assertSame('test@example.com', $found['email']);
    }

    public function test_user_model_can_update_and_delete(): void
    {
        $this->runMigrations();

        $model = User::make();
        $created = $model->create(['name' => 'Jane', 'email' => 'jane@example.com']);

        $this->assertNotEmpty($created);

        $model->update($created['id'], ['name' => 'Janet']);

        $updated = $model->find($created['id']);
        $this->assertSame('Janet', $updated['name']);

        $model->delete($created['id']);

        $this->assertNull($model->find($created['id']));
    }
}
