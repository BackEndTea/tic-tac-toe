<?php

namespace Tests\Unit;

use App\Game;
use App\Repositories\GameRepository;
use App\Repositories\UserRepository;
use App\User;
use App\Util\Constants;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetById()
    {
        $userRepository = new UserRepository();

        $user = User::create(
            [
                'name'      => 'Henk',
                'email'     => 'henk@example.com',
                'password'  => bcrypt('password123'),
            ]
        );

        $this->assertEquals($user->id, $userRepository
            ->getById($user->id)->id);
    }

    public function testGetAll()
    {
        $userRepository = new UserRepository();
        factory(User::class, 5)->create();

        $this->assertEquals(5, count($userRepository->getAll()));
    }

    public function testGetAllGames()
    {
        $userRepository = new UserRepository();
        $user = $this->mockAUserWithGames();

        $this->assertEquals(10, count($userRepository->getAllGames($user->id)));
    }

    public function testGetStartingGames()
    {
        $userRepository = new UserRepository();
        $gameRepository = new GameRepository();
        $user = $this->mockAUserWithGames();

        $this->assertEquals(10, count($userRepository->getStartingGames($user->id)));

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(5, count($gameRepository->getPlayingGames()));

        $this->assertEquals(5, count($userRepository->getStartingGames($user->id)));
    }

    public function testGetPlayingGames()
    {
        $userRepository = new UserRepository();
        $gameRepository = new GameRepository();
        $user = $this->mockAUserWithGames();

        $this->assertEquals(0, count($userRepository->getPlayingGames($user->id)));

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }

        $this->assertEquals(5, count($userRepository->getStartingGames($user->id)));
    }

    public function testGetFinishedGames()
    {
        $userRepository = new UserRepository();
        $gameRepository = new GameRepository();
        $user = $this->mockAUserWithGames();

        $this->assertEquals(0, count($userRepository->getFinishedGames($user->id)));

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }

        $this->assertEquals(0, count($userRepository->getFinishedGames($user->id)));

        for ($i = 6; $i <= 10; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_FINISHED);
        }

        $this->assertEquals(5, count($userRepository->getFinishedGames($user->id)));
    }

    public function testCreate()
    {
        $userRepository = new UserRepository();

        $userRepository->create(
            [
                'name'      => 'Mark',
                'email'     => 'mark@example.com',
                'password'  => bcrypt('password123'),
            ]
        );

        $this->assertDatabaseHas('users',['name' => 'Mark']);
        $this->assertDatabaseHas('users',['email'=> 'mark@example.com']);
    }

    private function mockAUserWithGames()
    {
        $user = User::create(
            [
                'name'      => 'Henk',
                'email'     => 'henk@example.com',
                'password'  => bcrypt('password123'),
            ]
        );
        factory(User::class, 5)->create();
        $gameRepository = new GameRepository();
        factory(Game::class, 10)->create();
        for ($i = 1; $i <= 10; $i++) {
            $game = $gameRepository->getById($i);
            $game->player1id = $user->id;
            $game->save();
        }

        return $user;
    }
}
