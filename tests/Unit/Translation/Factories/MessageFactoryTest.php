<?php

namespace Tests\Unit\Translation\Factories;

use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

class MessageFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Generate fake fields.
     *
     * @var \Faker\Generator
     */
    private $faker;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = FakerFactory::create();
    }


    /**
     * @test
     */
    public function it_instantiates_from_a_user()
    {
        $user = factory(User::class)->create();
        $factory = MessageFactory::newMessageFromUser($user);
        $this->assertInstanceOf(MessageFactory::class, $factory);
        $this->assertEquals($user->email, $factory->senderEmail());
        $this->assertEquals($user->name, $factory->senderName());
        $this->assertEquals($user, $factory->owner());
    }

    /**
     * @test
     */
    public function it_instantiates_from_a_message_as_a_reply()
    {
        $message = factory(Message::class)->create();
        $factory = MessageFactory::newReplyToMessage($message);
        $this->assertInstanceOf(MessageFactory::class, $factory);
        $this->assertEquals($message->id, $factory->messageId());
        $this->assertEquals(1, $factory->autoTranslateReply());
        $this->assertEquals(0, $factory->sendToSelf());
        $this->assertEquals($message->owner, $factory->owner());
        $this->assertEquals($message->lang_tgt_id, $factory->langSrcId());
        $this->assertEquals($message->lang_src_id, $factory->langTgtId());
    }

}


