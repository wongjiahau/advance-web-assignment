<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bouncer;
use App\User;

class InitBouncer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->defineRoles();
        $this->defineAbilities();
        $this->assignAbilities();
        $this->assignRoleToUsers();
    }

    private $admin;
    private $user;
    private function defineRoles()
    {
        $this->admin = Bouncer::role()->firstOrCreate([
            'name'  => 'admin',
            'title' => 'Administrator'
        ]);

        $this->user = Bouncer::role()->firstOrCreate([
            'name'  => 'user',
            'title' => 'User'
        ]);
    }

    private $manageUsers;
    private $createGroups;
    private $viewProfiles;
    private $manageMessages;
    private function defineAbilities()
    {
        $this->manageUsers = Bouncer::ability()->create([
            'name'  => 'manage-users',
            'title' => 'Manage Users'
        ]);
        $this->viewProfiles = Bouncer::ability()->create([
            'name'  => 'view-profiles',
            'title' => 'View profiles'
        ]);
        $this->createGroups = Bouncer::ability()->create([
            'name'  => 'manage-groups',
            'title' => 'Manage groups'
        ]);
        $this->manageMessages = Bouncer::ability()->create([
            'name'  => 'manage-messages',
            'title' => 'Manage messages'
        ]);
    }

    private function assignAbilities()
    {
        Bouncer::allow($this->admin)->to($this->manageUsers);
        Bouncer::allow($this->user)->to($this->createGroups);
        Bouncer::allow($this->user)->to($this->manageMessages);
        Bouncer::allow($this->user)->to($this->viewProfiles);
    }

    private function assignRoleToUsers()
    {
        Bouncer::assign($this->admin)->to(User::where('name', 'Admin01')->first());
        Bouncer::assign($this->user)->to(User::where('name', 'John')->first());
        Bouncer::assign($this->user)->to(User::where('name', 'Lee')->first());
        
    }
}
