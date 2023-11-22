<?php

namespace Tripteki\User\Console\Commands;

use Tripteki\Helpers\Helpers\ProjectHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "adminer:install:user";

    /**
     * @var string
     */
    protected $description = "Install the user stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        $this->installStack();

        $this->generateSuperuser();

        return 0;
    }

    /**
     * @return int|null
     */
    protected function installStack()
    {
        (new Filesystem)->ensureDirectoryExists(base_path("routes/admin"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/admin/user.php", base_path("routes/admin/user.php"));
        $this->helper->putRoute("api.php", "admin/user.php");

        (new Filesystem)->ensureDirectoryExists(app_path("Console/Commands"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Console/Commands", app_path("Console/Commands"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Admin/User"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Admin/User", app_path("Http/Controllers/Admin/User"));
        (new Filesystem)->ensureDirectoryExists(app_path("Imports/Users"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Imports/Users", app_path("Imports/Users"));
        (new Filesystem)->ensureDirectoryExists(app_path("Exports/Users"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Exports/Users", app_path("Exports/Users"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Admin/Users"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Admin/Users", app_path("Http/Requests/Admin/Users"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Responses"));

        $this->info("Adminer User scaffolding installed successfully.");
    }

    /**
     * @return void
     */
    protected function generateSuperuser()
    {
        if (in_array("App\Console\Commands\GenerateUserCommand", array_values(app(\Illuminate\Contracts\Console\Kernel::class)->all()))) {

            $this->call("adminer:generate:user", [ "--superuser" => true, ]);

            $this->info("Adminer SuperUser generated successfully.");
        }
    }
};
