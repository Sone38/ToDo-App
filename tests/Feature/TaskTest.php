<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Http\Requests\CreateTask;

class TaskTest extends TestCase
{
    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setUp() :void
    {
        parent::setUp();

        // テストケース実行前にフォルダデータを作成する
        $this->seed('FoldersTableSeeder');
    }

    /**
     * 期限日が日付ではない場合はバリデーション
     * @test
     */
    public function due_date_should_be_date()
    {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_date' => 123, //不正なデータ（数値）
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には日付を入力してください。',
        ]);
    }

    /**
     * 期限日が過去日付の場合はバリデーションエラー
     * @test
     */
    public function due_date_should_not_be_past()
    {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_date' => Carbon::yesterday()->format('Y/m/d'), // 不正なデータ（昨日の日付）
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には今日以降の日付を入力してください。',
        ]);
    }

    /**
     * 状態が定義された値ではない場合はバリデーションエラー
     * @test
     */
    public function status_should_be_within_defined_numbers()
    {
        $this->seed('TasksTableSeeder');

        $response = $this->post('/folders/1/tasks/1/edit', [
            'title' => 'Sample task',
            'due_date' => Carbon::today()->format("Y/m/d"),
            'status' => 999,
        ]);
    }
}
