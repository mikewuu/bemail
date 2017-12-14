<?php

namespace Tests\Unit;

use App\Language;
use App\Payments\MessageReceipt;
use App\Translation\Attachment;
use App\Translation\Message;
use App\Translation\MessageError;
use App\Translation\Recipient;
use App\Translation\TranslationStatus;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{

    use DatabaseTransactions;

    private static $message;

    public function setUp()
    {
        parent::setUp();
        static::$message = factory(Message::class)->create();
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'subject' => 'Super important message',
            'body' => 'Please translate this',
            'translated_body' => 'Some translated text',
            'auto_translate_reply' => 0,
            'send_to_self' => 1,
            'reply_sender_email' => 'foo@bar.com',
            'message_id' => factory(Message::class)->create()->id,
            'user_id' => factory(User::class)->create()->id,
            'translation_status_id' => TranslationStatus::available()->id,
            'lang_src_id' => 1,
            'lang_tgt_id' => 2
        ];

        $message = Message::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($message->{$key}, $value);
        }

    }

    /**
     * @test
     */
    public function it_appends_these_dynamic_properties()
    {
        $dynamicProperties = [
            'hash',
            'word_count',
            'has_recipients'
        ];
        foreach ($dynamicProperties as $property) {
            $this->assertContains($property, array_keys(static::$message->toArray()));
        }
    }

    /**
     * @test
     */
    public function it_retrieves_these_properties_as_carbon_dates()
    {
        $fields = [
            'created_at'
        ];
        foreach ($fields as $field) {
            $this->assertInstanceOf('Illuminate\Support\Carbon', static::$message->{$field});
        }
    }

    /**
     * @test
     */
    public function it_fetches_user_that_sent_the_message()
    {
        $this->assertInstanceOf('App\User', static::$message->user);
    }

    /**
     * @test
     */
    public function it_fetches_original_message_when_it_is_a_reply()
    {
        $this->assertNull(static::$message->originalMessage);
        $originalMessage = factory(Message::class)->create();
        static::$message->update([
            'message_id' => $originalMessage->id
        ]);
        $this->assertEquals($originalMessage->id, static::$message->fresh()->originalMessage->id);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_the_message()
    {
        $this->assertCount(0, static::$message->recipients);
        factory(Recipient::class, 5)->create(['message_id' => static::$message->id]);
        $this->assertCount(5, static::$message->fresh()->recipients);
    }

    /**
     * @test
     */
    public function it_checks_whether_message_has_recipients()
    {
        $this->assertFalse(static::$message->has_recipients);
        factory(Recipient::class, 1)->create(['message_id' => static::$message->id]);
        $this->assertTrue(static::$message->fresh()->has_recipients);
    }

    /**
     * @test
     */
    public function it_fetches_message_attachments()
    {
        $this->assertCount(0, static::$message->attachments);
        factory(Attachment::class, 3)->create([
            'message_id' => static::$message->id
        ]);
        $this->assertCount(3, static::$message->fresh()->attachments);
    }

    /**
     * @test
     */
    public function it_has_a_translation_status()
    {
        $this->assertInstanceOf('App\Translation\TranslationStatus', static::$message->status);
    }

    /**
     * @test
     */
    public function it_has_a_source_language()
    {
        $this->assertInstanceOf('App\Language', static::$message->sourceLanguage);
    }

    /**
     * @test
     */
    public function it_has_a_target_language()
    {
        $this->assertInstanceOf('App\Language', static::$message->targetLanguage);
    }

    /**
     * @test
     */
    public function it_fetches_a_receipt()
    {
        $this->assertNull(static::$message->receipt);
        $receipt = factory(MessageReceipt::class)->create([
            'message_id' => static::$message
        ]);
        $this->assertEquals($receipt->id, static::$message->fresh()->receipt->id);
    }

    /**
     * @test
     */
    public function it_gets_the_right_word_count()
    {
        $this->assertEquals(str_word_count(static::$message->body), static::$message->word_count);
    }

    /**
     * @test
     */
    public function it_checks_whether_there_are_recipients()
    {
        $this->assertFalse(static::$message->has_recipients);
        factory(Recipient::class)->create(['message_id' => static::$message->id]);
        $this->assertTrue(static::$message->fresh()->has_recipients);
    }
    
    /**
     * @test
     */
    public function it_fetches_message_error()
    {
        $this->assertNull(static::$message->error);
        factory(MessageError::class)->create([
            'message_id' => static::$message->id
        ]);
        $this->assertNotNull(static::$message->fresh()->error);
    }

    /**
     *
     * @test
     */
    public function it_updates_message_translation_status()
    {
        $translationStatuses = TranslationStatus::all();
        foreach ($translationStatuses as $status) {
            static::$message->updateStatus($status);
            $this->assertEquals(static::$message->translation_status_id, $status->id);
        }
    }

    /**
     * @test
     */
    public function it_checks_whether_message_is_reply()
    {
        $this->assertFalse(static::$message->is_reply);
        $replyMessage = factory(\App\Translation\Message::class)->create([
            'message_id' => static::$message->id
        ]);
        $this->assertTrue($replyMessage->is_reply);
    }
}
