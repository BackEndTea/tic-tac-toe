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

    public function testGetAll()
    {
        $this->mockAll();
        $fieldRepository = new FieldRepository();

        $this->assertEquals(5, count($fieldRepository->getAll()));
    }

    public function testCreateNormalGame()
    {
        factory(User::class, 5)->create();
        factory(Game::class, 5)->create();
        $fieldRepository = new FieldRepository();

        $field = $fieldRepository->createNormalGame(1);

        $this->assertDatabaseHas('fields',
            [
                'gameid'    => 1,
                'fieldid'   => $field->fieldid,
                'position1' => Constants::GAME_INPUT_NONE,
                'position2' => Constants::GAME_INPUT_NONE,
                'position3' => Constants::GAME_INPUT_NONE,
                'position4' => Constants::GAME_INPUT_NONE,
                'position5' => Constants::GAME_INPUT_NONE,
                'position6' => Constants::GAME_INPUT_NONE,
                'position7' => Constants::GAME_INPUT_NONE,
                'position8' => Constants::GAME_INPUT_NONE,
                'position9' => Constants::GAME_INPUT_NONE,
                'parentid'  => null,
                'placement' => null,
                'lastplay'  => null,

            ]
        );
    }

    public function testCreateExtremeGame()
    {
        factory(User::class, 5)->create();
        factory(Game::class, 5)->create();
        $fieldRepository = new FieldRepository();

        $field = $fieldRepository->createExtremeGame(1);

        $this->assertDatabaseHas( 'fields',
            [
                'gameid'    => 1,
                'fieldid'   => $field->fieldid,
                'position1' => Constants::GAME_INPUT_FIELD,
                'position2' => Constants::GAME_INPUT_FIELD,
                'position3' => Constants::GAME_INPUT_FIELD,
                'position4' => Constants::GAME_INPUT_FIELD,
                'position5' => Constants::GAME_INPUT_FIELD,
                'position6' => Constants::GAME_INPUT_FIELD,
                'position7' => Constants::GAME_INPUT_FIELD,
                'position8' => Constants::GAME_INPUT_FIELD,
                'position9' => Constants::GAME_INPUT_FIELD,
                'parentid'  => null,
                'placement' => null,
                'lastplay'  => null,
            ]
        );
        $this->assertDatabaseHas('fields',
            [
                'gameid'    => null,
                'position1'  => Constants::GAME_INPUT_NONE,
                'position2'  => Constants::GAME_INPUT_NONE,
                'position3'  => Constants::GAME_INPUT_NONE,
                'position4'  => Constants::GAME_INPUT_NONE,
                'position5'  => Constants::GAME_INPUT_NONE,
                'position6'  => Constants::GAME_INPUT_NONE,
                'position7'  => Constants::GAME_INPUT_NONE,
                'position8'  => Constants::GAME_INPUT_NONE,
                'position9'  => Constants::GAME_INPUT_NONE,
                'parentid'  => $field->fieldid,
                'placement' => 1,
                'lastplay'  => null,
            ]
        );
        $this->assertDatabaseHas('fields',
            [
                'gameid'    => null,
                'position1'  => Constants::GAME_INPUT_NONE,
                'position2'  => Constants::GAME_INPUT_NONE,
                'position3'  => Constants::GAME_INPUT_NONE,
                'position4'  => Constants::GAME_INPUT_NONE,
                'position5'  => Constants::GAME_INPUT_NONE,
                'position6'  => Constants::GAME_INPUT_NONE,
                'position7'  => Constants::GAME_INPUT_NONE,
                'position8'  => Constants::GAME_INPUT_NONE,
                'position9'  => Constants::GAME_INPUT_NONE,
                'parentid'  => $field->fieldid,
                'placement' => 9,
                'lastplay'  => null,
            ]
        );

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

    private function mockAll()
    {
        factory(User::class, 5)->create();
        factory(Game::class, 5)->create();
        factory(Field::class, 5)->create();

    }

}
