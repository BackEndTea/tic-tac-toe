<?php

namespace Tests\Unit;

use App\Game;
use App\Repositories\GameRepository;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GameRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetById()
    {
        $this->mockSomeUsers();

        $gameRepository = new GameRepository();

        $game = Game::create(
            [
                'player1id'     => '1',
                'playe1tag'     => 'X',

            ]
        );

        $this->assertEquals($game->id, $gameRepository->getById($game->id)->id);
    }

    /**
     * Will create 2 users for testing purposes
     *
     */
    private function mockSomeUsers()
    {
        User::create(
            [
                'name'      => 'John Doe',
                'email'     => 'johndoe@example.com',
                'password'  => bcrypt('secret'),
            ]
        );
        User::create(
            [
                'name'      => 'Jane Bar',
                'email'     => 'janebar@example.com',
                'password'  => bcrypt('secret'),
            ]
        );

    }
}
