<?php

namespace Tests\Unit;

use App\Field;
use App\Game;
use App\Repositories\GameRepository;
use App\Repositories\UserRepository;
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
                'gametype'      => Constants::GAME_TYPE_NORMAL,

            ]
        );

        $this->assertEquals($game->gameid, $gameRepository
            ->getById($game->gameid)->gameid);
    }

    public function testGetAll()
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

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(5, count($gameRepository->getStartingGames()));
    }

    public function testGetPlayingGames()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(0, count($gameRepository->getPlayingGames()));

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(5, count($gameRepository->getPlayingGames()));
    }

    public function testGetFinishedGames()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 10)->create();

        $this->assertEquals(0, count($gameRepository->getFinishedGames()));

        for ($i = 1; $i <= 5; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_PLAYING);
        }
        $this->assertEquals(0, count($gameRepository->getFinishedGames()));

        for ($i = 6; $i <= 10; $i++) {
            $gameRepository->setGameState($i, Constants::GAME_STATE_FINISHED);
        }
        $this->assertEquals(5, count($gameRepository->getFinishedGames()));
        $this->assertEquals(null, $gameRepository->getById(1)->finished_at);
        $this->assertNotEquals(null, $gameRepository->getById(10)->finished_at);
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
        $this->assertDatabaseHas('fields', ['gameid' => $game->gameid]);
    }

    public function testSetGameState()
    {
        $gameRepository = new GameRepository();
        factory(User::class, 5)->create();
        factory(Game::class, 1)->create();

        $this->assertEquals($gameRepository->getById(1)->gamestate,
            Constants::GAME_STATE_NOT_YET_STARTED);

        $gameRepository->setGameState(1, Constants::GAME_STATE_PLAYING);

        $this->assertEquals($gameRepository->getById(1)->gamestate,
            Constants::GAME_STATE_PLAYING);

        $gameRepository->setGameState(1, Constants::GAME_STATE_FINISHED);

        $this->assertEquals($gameRepository->getById(1)->gamestate,
            Constants::GAME_STATE_FINISHED);
    }

    public function testSetPlayerTwo()
    {
        $gameRepository = new GameRepository();
        $userOne = $this->mockAUser();
        $userTwo = User::create(
            [
                'name'      => 'Wollah',
                'email'     => 'ikbeneen@vietnamees.com',
                'password'  => bcrypt('password456'),
            ]
        );

        $game = $this->mockAGame($userOne->id);

        $gameRepository->setPlayerTwo($game->gameid, $userTwo->id);

        $this->assertDatabaseHas('games', ['player2id' => $userTwo->id]);
    }

    public function testSetPlayerOneTag()
    {
        $gameRepository = new GameRepository();
        $userOne = $this->mockAUser();
        $game = $this->mockAGame($userOne->id);

        $gameRepository->setPlayerOneTag($game->gameid, 'B');

        $this->assertDatabaseHas('games', ['player1tag'=> 'B']);
        $this->assertNotEquals(Game::find($game->gameid)->player2tag, 'B');
    }

    public function testSetPlayerTwoTag()
    {
        $gameRepository = new GameRepository();
        $userOne = $this->mockAUser();
        $game = $this->mockAGame($userOne->id);

        $gameRepository->setPlayerTwoTag($game->gameid, 'V');

        $this->assertNotEquals(Game::find($game->gameid)->player1tag, 'V');
        $this->assertDatabaseHas('games', ['player2tag'=> 'V']);
    }

    public function testPlayerOneRelation()
    {
        $gameRepository = new GameRepository();
        $userRepository = new UserRepository();

        $user = $this->mockaUser();

        $game = $this->mockAGame($user->id);

        $this->assertEquals($user->id, $gameRepository->getPlayerOne($game->gameid)->id);
    }

    public function testPlayerTwoRelation()
    {
        $gameRepository = new GameRepository();
        $userRepository = new UserRepository();

        $user = $this->mockAUser();
        $game = $this->mockAGame($user->id);

        $userTwo = User::create(
            [
                'name'      => 'Wollah',
                'email'     => 'ikbeneen@vietnamees.com',
                'password'  => bcrypt('password456'),
            ]
        );

        $game->player2id = $userTwo->id;
        $game->save();

        $this->assertEquals($gameRepository->getPlayerTwo($game->gameid)->id, $userTwo->id);
    }

    public function testPlayersRelation()
    {
        $gameRepository = new GameRepository();
        $userRepository = new UserRepository();

        $user = $this->mockAUser();
        $game = $this->mockAGame($user->id);

        $userTwo = User::create(
            [
                'name'      => 'Wollah',
                'email'     => 'ikbeneen@vietnamees.com',
                'password'  => bcrypt('password456'),
            ]
        );

        $game->player2id = $userTwo->id;
        $game->save();

        $this->assertEquals(2, count($gameRepository->getPlayers($game->gameid)));
        $this->assertEquals($user->id, $gameRepository
            ->getPlayers($game->gameid)->first()->id);
        $this->assertEquals($userTwo->id, $gameRepository
            ->getPlayers($game->gameid)->reverse()->first()->id);

        $this->assertNotEquals($user->id, $gameRepository
            ->getPlayers($game->gameid)->reverse()->first()->id);
        $this->assertNotEquals($userTwo->id, $gameRepository
            ->getPlayers($game->gameid)->first()->id);
    }

    public function testFieldRelationNormal()
    {
        $user = $this->mockAUser();
        $gameRepository = new GameRepository();
        $game = $gameRepository->create(
            [
                'player1id'     => $user->id,
                'gametype'      => Constants::GAME_TYPE_NORMAL,
            ]
        );

        $fields = $gameRepository->getFields($game->gameid);

        $this->assertEquals(1, $fields->first()->fieldid);
        $this->assertEquals($game->gameid, $fields->first()->gameid);
    }

    public function testFieldRelationExtreme()
    {
        $user = $this->mockAUser();
        $gameRepository = new GameRepository();
        $game = $gameRepository->create(
            [
                'player1id'     => $user->id,
                'gametype'      => Constants::GAME_TYPE_EXTREME,
            ]
        );
        $fields = $gameRepository->getFields($game->gameid);

        $this->assertEquals(1, $fields->first()->fieldid);
        $this->assertEquals($game->gameid, $fields->first()->gameid);
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

    /**
     * Will create 1 game for testing purposes.
     */
    private function mockAGame($userID)
    {
        return Game::create(
            [
                'player1id'     => $userID,
                'gametype'      => Constants::GAME_TYPE_NORMAL,
            ]
        );
    }
}
