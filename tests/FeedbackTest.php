<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeedbackTest extends TestCase
{
    /* unit test api api/v1/feedback
     * param name, email, feedback
     * test for case correct email
     * */
    public function testFeedbackSuccess()
    {
        $this->post('api/v1/feedback', [
            'name' => str_random(10),
            'email' => 'buivanphuoc1802@gmail.com',
            'feedback' => str_random(10),
        ]);
        $this->seeStatusCode(API_RESPONSE_CODE_OK);
    }

    /* unit test api api/v1/feedback
     * param name, email, feedback
     * test for case wrong email
     * */
    public function testFeedbackError()
    {
        $this->post('/api/v1/feedback', [
            'name' => str_random(10),
            'email' => str_random(10) . '@gmail.com',
            'feedback' => str_random(10),
        ]);
        $this->seeStatusCode(API_RESPONSE_CODE_UNPROCESSABLE);
    }
}
