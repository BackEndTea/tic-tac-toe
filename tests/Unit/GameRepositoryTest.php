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
        $user = $this->mockaUser();

        $gameRepository = new GameRepository();

        $game = Game::create(
            [
                'player1id'     => $user->id,
                'player1tag'    => 'X',
                'gametype'      => '0'

            ]
        );

        $this->assertEquals($game->gameid, $gameRepository->getById($game->gameid)->gameid);
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

    /**
     * Will create 1 user for testing purposes
     *
     */
    private function mockAUser()
    {
        return User::create(
            [
                'name'      => 'John Doe',
                'email'     => 'johndoe@example.com',
                'password'  => bcrypt('secret'),
            ]
        );

    }
}
