<?php

namespace Tests\Unit;

use App\Game;
use App\Repositories\GameRepository;
use App\User;
use App\Util\Constants;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
                'gametype'      => '0',

            ]
        );

        $this->assertEquals($game->gameid, $gameRepository->getById($game->gameid)->gameid);
    }

    public function testgetAll()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(10, count($gameRepository->getAll()));
    }

    public function testGetStartingGames()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(10, count($gameRepository->getStartingGames()));

        for($i = 1; $i<=5; $i++){
            $gameRepository->setGameState($i,Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(5, count($gameRepository->getStartingGames()));
    }

    public function testGetPlayingGames()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(0, count($gameRepository->getPlayingGames()));

        for($i = 1; $i<=5; $i++){
            $gameRepository->setGameState($i,Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(5, count($gameRepository->getPlayingGames()));
    }

    public function testGetFinishedGames()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(0, count($gameRepository->getFinishedGames()));

        for($i = 1; $i<=5; $i++){
            $gameRepository->setGameState($i,Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(0, count($gameRepository->getFinishedGames()));

        for($i = 6; $i<=10; $i++){
            $gameRepository->setGameState($i,Constants::GAME_STATE_FINISHED);
        }
        $this->assertEquals(5, count($gameRepository->getFinishedGames()));

    }

    public function testCreate()
    {
        $gameRepository = new GameRepository();
        $this->mockAUser();

        $game = $gameRepository->create(
            [
                'player1id'     => 1,
                'player1tag'    => 'B',
                'gametype'      => Constants::GAME_TYPE_EXTREME,
            ]
        );
        $this->assertEquals(1, $game->player1id);
        $this->assertEquals('B', $game->player1tag);
        $this->assertEquals(Constants::GAME_TYPE_EXTREME, $game->gametype);


    }

    /**
     * Will create 1 user for testing purposes.
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
