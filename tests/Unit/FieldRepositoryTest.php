<?php

namespace Tests\Unit;

use App\Field;
use App\Game;
use App\Repositories\FieldRepository;
use App\User;
use App\Util\Constants;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FieldRepositoryTest extends TestCase
{

    use DatabaseMigrations;

    public function testGetById()
    {
        $fieldRepository = new FieldRepository();
        $field = $this->mockTestField();

        $this->assertEquals($field->fieldid, $fieldRepository->getById($field->fieldid)->fieldid);


    }

    private function mockTestField()
    {
        return $this->mockAField($this->mockAGame(
            $this->mockaUser()->id, Constants::GAME_TYPE_NORMAL)->gameid);

    }

    private function mockAField($gameid)
    {
        return Field::create(
            [
                'gameid'        => $gameid,
                'position1'     => Constants::GAME_INPUT_NONE,
                'position2'     => Constants::GAME_INPUT_NONE,
                'position3'     => Constants::GAME_INPUT_NONE,
                'position4'     => Constants::GAME_INPUT_NONE,
                'position5'     => Constants::GAME_INPUT_NONE,
                'position6'     => Constants::GAME_INPUT_NONE,
                'position7'     => Constants::GAME_INPUT_NONE,
                'position8'     => Constants::GAME_INPUT_NONE,
                'position9'     => Constants::GAME_INPUT_NONE,


            ]
        );

    }

    private function mockAGame($userID, $gameType)
    {
        return Game::create(
            [
                'player1id'     => $userID,
                'gametype'      => $gameType,
            ]
        );

    }

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
