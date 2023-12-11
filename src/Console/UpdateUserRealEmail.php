<?php

namespace Osiset\ShopifyApp\Console;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateUserRealEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magefan:user-real-email:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user with real email';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $path = '/admin/api/' . \Osiset\ShopifyApp\Util::getShopifyConfig('api_version') . '/shop.json';

        $users = User::all();
        foreach ($users as $user) {
            try {
                $shopEmail = $user->api()->rest('GET', $path, ['fields' => 'email']);
                if (!empty($shopEmail['body']) && !is_string($shopEmail['body'])) {
                    $email = $shopEmail['body']->toArray()['shop']['email'];
                    if ($email === $user->email) {
                        continue;
                    }
                    $user->email = $email;
                    $user->save();    
                } else {
                    //var_dump($shopEmail['body']) ;
                    //echo PHP_EOL;
                }
            } catch (\Exception $e) {
               //echo $e->getMessage() . PHP_EOL; 
            }  
        }
    }
}
