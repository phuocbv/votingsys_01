<?php

use Illuminate\Http\UploadedFile;

class ApiCreatePollTest extends TestCase
{
    /*
    * unit test name more than max
    *
    *
    * @param string name: 101 char
    * @param string email
    * @param string title
    * @param string description
    * @param string multiple
    *
    * @expect status code 302
    * */
    public function testNameMoreThanMax()
    {
        $this->call('POST', '/api/v1/poll', [
            'name' => str_random(101),
            'email' => str_random(10) . '@gmail.com',
            'title' => str_random(100),
            'description' => str_random(100),
            'multiple' => config('settings.type_poll.single_choice'),
        ]);
        $this->assertResponseStatus(API_RESPONSE_CODE_MOVED_TEMPORARILY);
    }

    /*
    * unit test title more than max
    *
    *
    * @param string name
    * @param string email
    * @param string title: 256 char
    * @param string description
    * @param string multiple
    *
    * @expect status code 302
    * */
    public function testTitleMoreThanMax()
    {
        $this->call('POST', '/api/v1/poll', [
            'name' => str_random(10),
            'email' => str_random(10) . '@gmail.com',
            'title' => str_random(256),
            'description' => str_random(100),
            'multiple' => config('settings.type_poll.single_choice'),
        ]);
        $this->assertResponseStatus(API_RESPONSE_CODE_MOVED_TEMPORARILY);
    }

    /*
    * unit test not multiple
    *
    *
    * @param string name
    * @param string email
    * @param string title
    * @param string description
    *
    * @expect status code 302
    * */
    public function testNotMultiple()
    {
        $this->call('POST', '/api/v1/poll', [
            'name' => str_random(10),
            'email' => str_random(10) . '@gmail.com',
            'title' => str_random(100),
            'description' => str_random(100),
        ]);
        $this->assertResponseStatus(API_RESPONSE_CODE_MOVED_TEMPORARILY);
    }

    /*
    * unit test with optionText empty
    *
    *
    * @param string name
    * @param string email
    * @param string title
    * @param string description
    * @param string multiple
    * @param data date_close
    * @param string location
    * @param array setting
    *
    * @expect status code 500
    * @expect error true
    * */
    public function testWithOptionTextEmpty()
    {
        $date = strtotime('tomorrow');
        $this->call('POST', '/api/v1/poll', [
            'name' => str_random(10),
            'email' => str_random(10) . '@gmail.com',
            'title' => str_random(100),
            'description' => str_random(100),
            'multiple' => config('settings.type_poll.single_choice'),
            'date_close' => date('d-m-Y H:i', $date),
            'location' => 'Ha noi',
            'setting' => [
                10 => config('settings.setting.not_same_email'),
            ],
        ]);
        $this->assertResponseStatus(API_RESPONSE_CODE_INTER_SERVER_ERROR);
        $this->seeJson([
            'error' => true,
            'messages' => [
                trans('polls.message.create_fail'),
            ],
        ]);
    }

    /*
    * unit test with optionText empty
    *
    *
    * @param string name
    * @param string email
    * @param string title
    * @param string description
    * @param string multiple
    * @param data date_close
    * @param string location
    * @param array setting
    * @param array optionText
    * @param array optionImage
    * @param string member
    *
    * @expect status code 200
    * @expect error false
    * */
    public function testCreatePollSuccess()
    {
        $date = strtotime('tomorrow');
        $this->call('POST', '/api/v1/poll', [
            'name' => str_random(10),
            'email' => str_random(10) . '@gmail.com',
            'title' => str_random(100),
            'description' => str_random(100),
            'multiple' => config('settings.type_poll.single_choice'),
            'date_close' => date('d-m-Y H:i', $date),
            'location' => 'Ha noi',
            'optionText' => [
                0 => str_random(10),
                1 => str_random(10),
            ],
            'optionImage' => [
                0 => 'http://placeimg.com/640/480/any',
                1 => new UploadedFile(public_path(config('settings.image_default_path')),
                    config('settings.avatar_default'), 'image/jpeg', null, null, true)
            ],
            'setting' => [
                10 => config('settings.setting.not_same_email'),
            ],
            'member' => 'aaaaaaaa@gmail.com,bbbbbbbb@gmail.com',
        ]);
        $this->assertResponseStatus(API_RESPONSE_CODE_OK);
        $this->seeJson([
            'error' => false,
            'messages' => [
                trans('polls.message.create_success'),
            ],
        ]);
    }
}
