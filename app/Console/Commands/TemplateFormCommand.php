<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\search;

class TemplateFormCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buat:form {tables}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Dynamic Form and CRUD Controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('tables');
        $tableName = $table;
        $data = getListTable()->where('name', $table)->first();
        $defaultDatabaseTables = getDefaultDatabase();
        if (in_array($table, $defaultDatabaseTables)) {
            $this->error("Cannot Create template for default database tables Laravel");
            return 0;
        }

        $jenis = $this->choice(
            'What is your form type?',
            ['Multiple Datas', 'One Data', 'No Form'],
            0
        );
        $view = "default";
        $controller = "";

        if ($jenis == "One Data") {
            $view = "oneData";
            $controller = "oneData";
        } else if ($jenis == "No Form") {
            $view = "noForm";
            $controller = "noForm";
        }

        $view = str_replace(" ", "", $view);

        if ($data == null) {
            $this->error('Table yang anda masukkan tidak ditemukan!');
        } else {
            $this->clearConsole();
            $table = implode('_', array_map('ucfirst', explode('_', $table)));
            if (substr($table, -3) == 'ies') {
                $table = substr($table, 0, -3) . 'y';
            } else if (substr($table, -2) == 'is' || substr($table, -2) == 'as' || substr($table, -2) == 'es' || substr($table, -2) == 'us' || substr($table, -2) == 'os') {
                $table = $table . "";
            } else if (substr($table, -1) == 's') {
                $table = substr($table, 0, -1);
            }

            $controllerFrom = base_path("app/Http/Controllers/TemplateController$controller.pug");
            $controllerTo = base_path('app/Http/Controllers/' . ucfirst(str_replace("_", "", $table)) . 'Controller.php');

            $viewFrom = base_path("/resources/views/templateEngine/$view.blade.php");
            $viewTo = base_path("/resources/views/admin/$tableName/index.blade.php");

            if (!is_dir(base_path("/resources/views/admin/$tableName/"))) {
                mkdir(base_path("/resources/views/admin/$tableName/"), 0777, true);
            }

            copy($viewFrom, $viewTo);
            $this->info('<options=blink;fg=white;bg=magenta>  Pembuatan Templating untuk table <options=bold;fg=white;bg=magenta>' . $tableName . "  </></>");
            copy($controllerFrom, $controllerTo);
            $this->info('<options=blink;fg=green;bg=white>  Berhasil membuat Controller ke <options=bold;fg=green;bg=white>' . $controllerTo . ' !  </></>');
            if (!copy($controllerFrom, $controllerTo)) {
                $this->error("failed to copy $controllerFrom...\n");
            }
            $this->info('<options=blink;fg=white;bg=magenta>  Penulisan function untuk controller <options=bold;fg=white;bg=magenta>' . ucfirst(str_replace("_", "", $table) . 'Controller   </></>'));

            $file_contents = file_get_contents($controllerTo);
            $file_contents = str_replace("Template", ucfirst(str_replace("_", "", $table)), $file_contents);
            $file_contents = str_replace("tableName", $tableName, $file_contents);
            file_put_contents($controllerTo, $file_contents);

            $this->info('<options=blink;fg=green;bg=white>  Berhasil menulis function ke Controller <options=bold;fg=green;bg=white>' . ucfirst(str_replace("_", "", $table) . 'Controller  </></>'));
            $this->line("<bg=green;fg=white;options=bold>   GIMANA,MANTAP KAN? wkwkk   </><fg=black;bg=yellow;options=blink,bold>  Silahkan masuk ke routes/web.php untuk setting routing nya.  </>");
            $this->newLine();
        }
        return 0;
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'tables' => fn () => search(
                label: 'Search for a table name:',
                placeholder: 'E.g. users',
                scroll: 10,
                options: fn ($value) => (strlen($value) > 0
                    ? getListTable()->filter(function ($item) use ($value) {
                        return false !== stripos($item['name'], $value);
                    })->pluck('name')->all()
                    : []
                ),
            ),
        ];
    }

    public function clearConsole()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
        $this->line("<bg=red;fg=black>=============================</>");
        $this->line("<bg=red;fg=black>*** </><bg=red;options=bold;fg=green>PROSES PEMBUATAN FORM</><bg=red;fg=black> ***</>");
        $this->line("<bg=red;fg=black>=============================</>");
    }
}
