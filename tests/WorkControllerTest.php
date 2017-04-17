<?php
use App\User;
use \Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        (new \DatabaseSeeder())->run(); // テストデータ登録
    }
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testindex()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $this->visit('/')
             ->see('')
             ->see('勤務開始')
             ->see('勤務終了')
             ->see('(株)エム・テー・デー')
             ->see('原口　康太郎')
             ->see('テスト　太郎')
             ->press('勤務開始');

        //check db
        $this->seeInDatabase('works',[
          'user_id'=>$user->id,
          'date_at'=>Carbon::now('Asia/Tokyo')->format('Y-m-d 00:00:00')
        ]);
    }
}
