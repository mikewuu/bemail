<?php

namespace Tests\Unit;

use App\Language;
use App\Payment\CreditTransactionType;
use App\Translation\Message;
use App\Translation\Recipient;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var User
     */
    private static $user;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp();
        static::$user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function it_is_be_able_to_mass_assign_these_fields()
    {
        $fields = [
            'name' => 'John',
            'email' => 'John@example.com',
            'password' => bcrypt('secret'),
            'word_credits' => 10,
            'language_id' => 1
        ];

        $user = User::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($user->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_hides_these_fields()
    {
        $fields = array_keys(static::$user->toArray());
        $this->assertNotContains('password', $fields);
        $this->assertNotContains('remember_token', $fields);
    }

    /**
     * @test
     */
    public function it_has_a_default_language()
    {
        $this->assertInstanceOf('App\Language', static::$user->defaultLanguage);
    }
    
    /**
     * @test
     */
    public function it_fetches_user_messages()
    {
        $this->assertCount(0, static::$user->messages);
        factory(Message::class, 3)->create([
            'user_id' => static::$user->id
        ]);

        $this->assertCount(3, static::$user->fresh()->messages);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_messages_sent_by_user()
    {
        $message1 = factory(Message::class)->create([
            'user_id' => static::$user->id
        ]);
        factory(Recipient::class, 3)->create(['message_id' => $message1->id]);

        $message2 = factory(Message::class)->create([
            'user_id' => static::$user->id
        ]);
        factory(Recipient::class, 2)->create(['message_id' => $message2->id]);

        $this->assertCount(5, static::$user->recipients);

    }

    /**
     * @test
     */
    public function it_adjusts_word_credits()
    {
        static::$user->update([
            'word_credits' => 10
        ]);

        $type = CreditTransactionType::all()->random();

        $this->assertEmpty(static::$user->creditTransactions);

        static::$user->adjustCredits($type, 5);

        $this->assertEquals(15, static::$user->fresh()->word_credits);
        $this->assertEquals(1, static::$user->fresh()->creditTransactions);
        $this->assertEquals(static::$user->fresh()->creditTransactions->first()->id, $type->id);

        static::$user->adjustCredits( $type, -15);
        $this->assertEquals(0, static::$user->fresh()->word_credits);
        $this->assertEquals(2, static::$user->fresh()->creditTransactions);

    }
}

