<?php

namespace Bones;

use Bones\Skeletons\DBFiller\Refill;
use Bones\Skeletons\DBGram\Adjustor;

class Commander
{
    protected $commands = [];
    protected $attribute;
    protected $extraAttrs;
    public $appStopperFile = 'locker/system/stop';
    public $settingDir = 'settings';

    public function __construct($commands = [])
    {
        unset($commands[0]);
        $this->commands = array_values($commands);
    }

    public static function run(string $args)
    {
        $args = 'commander ' . $args;
        $args = explode(' ', $args);
        return (new self($args))->proceed();
    }

    public function proceed()
    {
        $action = $this->commands[0];
        $this->attribute = (!empty($this->commands[1])) ? $this->commands[1] : '';
        unset($this->commands[0]);
        if (count($this->commands) > 1) {
            unset($this->commands[1]);
        }
        $this->extraAttrs = array_values($this->commands);
        $this->authenticate($action);
    }

    public function authenticate(string $action)
    {
        $action = explode(':', $action);
        $commandFor = '';
        if (count($action) > 1) {
            $command = strtolower($action[0]);
            $commandFor = strtolower($action[1]);
        } else {
            $command = strtolower($action[0]);
        }
        switch ($command) {
            case 'create':
                $this->create($commandFor);
                break;
            case 'remove':
                $this->remove($commandFor);
                break;
            case 'start':
                $this->startApp($commandFor);
                break;
            case 'stop':
                $this->stopApp($commandFor);
                break;
            case 'clear':
                $this->clear($commandFor);
                break;
            case 'list':
                $this->list($commandFor);
                break;
            case 'run':
                $this->execute($commandFor);
                break;
            case 'rollback':
                $this->rollback($commandFor);
                break;
            case 'set':
                $this->set($commandFor);
                break;
            default:
                return $this->throwError('%s is not a valid command', [$command]);
                break;
        }
    }

    public function create(string $commandFor)
    {
        switch ($commandFor) {
            case 'model':
                $this->createModelFile();
                break;
            case 'controller':
                $this->createControllerFile();
                break;
            case 'view':
                $this->createViewFile();
                break;
            case 'barrier':
                $this->createBarrierFile();
                break;
            case 'dbgram':
                $this->createDBGramFile();
                break;
            case 'dbfiller':
                $this->createDBFillerFile();
                break;
            case 'mailer':
                $this->createMailerFile();
                break;
            case 'texter':
                $this->createTexterFile();
                break;
            default:
                return $this->throwError('%s is not a valid segment to create', [$commandFor]);
                break;
        }
    }

    public function remove(string $commandFor)
    {
        switch ($commandFor) {
            case 'model':
                $this->removeModelFile();
                break;
            case 'controller':
                $this->removeControllerFile();
                break;
            case 'view':
                $this->removeViewFile();
                break;
            case 'barrier':
                $this->removeBarrierFile();
                break;
            case 'dbgram':
                $this->removeDBGramFile();
                break;
            case 'dbfiller':
                $this->removeDBFillerFile();
                break;
            case 'mailer':
                $this->removeMailerFile();
                break;
            default:
                return $this->throwError('%s is not a valid segment to remove', [$commandFor]);
                break;
        }
    }

    public function clear(string $commandFor)
    {
        switch ($commandFor) {
            case 'builds':
                $this->clearDir('compiler/builds/');
                break;
            case 'db-backups':
                $this->clearDir('locker/system/db/backups/');
                break;
            default:
                return $this->throwError('%s is not a valid segment to clear', [$commandFor]);
                break;
        }
    }

    public function list(string $commandFor)
    {
        switch ($commandFor) {
            case 'routes':
                $this->listRoutes();
                break;
            case 'dbgrams':
                $this->listDBGrams();
                break;
            default:
                return $this->throwError('%s is not a valid segment to list out', [$commandFor]);
                break;
        }
    }

    public function execute(string $commandFor)
    {
        switch ($commandFor) {
            case 'dbgram':
                $this->execDBGrams();
                break;
            case 'dbfiller':
                $this->execDBFillers();
                break;
            default:
                return $this->throwError('%s is not a valid segment to run (NOT EXECUTABLE)', [$commandFor]);
                break;
        }
    }

    public function rollback(string $commandFor)
    {
        switch ($commandFor) {
            case 'dbgram':
                return (new Adjustor())->rollback($this->attribute, $this->extraAttrs);
                break;
            default:
                return $this->throwError('%s is not a valid segment to run (NOT EXECUTABLE)', [$commandFor]);
                break;
        }
    }

    public function set(string $commandFor)
    {
        switch ($commandFor) {
            case 'config':
                return $this->setConfigSettings();
                break;
            default:
                return $this->throwError('%s is not a valid segment to set (NOT CONFIGURABLE)', [$commandFor]);
                break;
        }
    }

    public function createModelFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [Model] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $modelFilePath = 'segments/Models/' . $this->getDeCamelizedPath() . '.php';
        if (file_exists($modelFilePath)) {
            return $this->throwError('[Model] FILE ALREADY EXISTS at %s' . PHP_EOL, [$modelFilePath]);
        }
        $modelFileDoors = explode('/', $modelFilePath);
        $modelFileNameParts = explode('.php', basename($modelFileDoors[count($modelFileDoors) - 1]));
        $nameSpace = 'Models';
        unset($modelFileDoors[count($modelFileDoors) - 1]);
        foreach ($modelFileDoors as $door) {
            if (!in_array($door, ['segments', 'Models'])) {
                $nameSpace .= '\\' . $door;
            }
        }
        if (!file_exists(implode('/', $modelFileDoors))) {
            mkdir(implode('/', $modelFileDoors), 0644, true);
        }
        $f = fopen($modelFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create model file at ', [$modelFilePath]);
        }
        fwrite($f, $this->getBaseModelCode($modelFileNameParts[0], $nameSpace));
        fclose($f);
        return $this->showMsg('Model saved at ' . $modelFilePath . '!');
    }

    public function createControllerFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [Controller] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $controllerFilePath = 'segments/Controllers/' . $this->getDeCamelizedPath() . 'Controller.php';
        if (file_exists($controllerFilePath)) {
            return $this->throwError('[Controller] FILE ALREADY EXISTS at %s' . PHP_EOL, [$controllerFilePath]);
        }
        $controllerFileDoors = explode('/', $controllerFilePath);
        $controllerFileNameParts = explode('.php', basename($controllerFileDoors[count($controllerFileDoors) - 1]));
        $nameSpace = 'Controllers';
        unset($controllerFileDoors[count($controllerFileDoors) - 1]);
        foreach ($controllerFileDoors as $doorName => $door) {
            $controllerFileDoors[$doorName] = Str::decamelize($door);
            if (!in_array($door, ['segments', 'Controllers'])) {
                $nameSpace .= '\\' . $door;
            }
        }
        if (!file_exists(implode('/', $controllerFileDoors))) {
            mkdir(implode('/', $controllerFileDoors), 0644, true);
        }
        $f = fopen($controllerFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create controller file at ', [$controllerFilePath]);
        }
        fwrite($f, $this->getBaseControllerCode($controllerFileNameParts[0], $nameSpace));
        fclose($f);
        return $this->showMsg('Controller saved at ' . $controllerFilePath . '!');
    }

    public function createViewFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [View] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $viewFilePath = 'segments/views/' . $this->attribute . '.jly.php';
        if (file_exists($viewFilePath)) {
            return $this->throwError('[View] FILE ALREADY EXISTS at %s' . PHP_EOL, [$viewFilePath]);
        }
        $viewFileDoors = explode('/', $viewFilePath);
        unset($viewFileDoors[count($viewFileDoors) - 1]);
        if (!file_exists(implode('/', $viewFileDoors))) {
            mkdir(implode('/', $viewFileDoors), 0644, true);
        }
        $f = fopen($viewFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create view file at ', [$viewFilePath]);
        }
        fwrite($f, $this->getBaseViewCode());
        fclose($f);
        return $this->showMsg('View saved at ' . $viewFilePath . '!');
    }

    public function createBarrierFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [Barrier] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $barrierFilePath = 'segments/Barriers/' . $this->getDeCamelizedPath() . '.php';
        if (file_exists($barrierFilePath)) {
            return $this->throwError('[Barrier] FILE ALREADY EXISTS at %s' . PHP_EOL, [$barrierFilePath]);
        }
        $barrierFileDoors = explode('/', $barrierFilePath);
        $barrierFileNameParts = explode('.php', basename($barrierFileDoors[count($barrierFileDoors) - 1]));
        $nameSpace = 'Barriers';
        unset($barrierFileDoors[count($barrierFileDoors) - 1]);
        foreach ($barrierFileDoors as $door) {
            if (!in_array($door, ['segments', 'Barriers'])) {
                $nameSpace .= '\\' . $door;
            }
        }
        if (!file_exists(implode('/', $barrierFileDoors))) {
            mkdir(implode('/', $barrierFileDoors), 0644, true);
        }
        $f = fopen($barrierFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create barrier file at ', [$barrierFilePath]);
        }
        fwrite($f, $this->getBaseBarrierCode($barrierFileNameParts[0], $nameSpace));
        fclose($f);
        return $this->showMsg('Barrier saved at ' . $barrierFilePath . '!');
    }

    public function createDBGramFile()
    {
        return (new Adjustor())->create($this->attribute, $this->extraAttrs);
    }

    public function createDBFillerFile()
    {
        return (new Refill())->create($this->attribute, $this->extraAttrs);
    }

    public function createMailerFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [Mailer] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $mailerFilePath = 'segments/Mail/' . $this->getDeCamelizedPath() . '.php';
        if (file_exists($mailerFilePath)) {
            return $this->throwError('[Mailer] FILE ALREADY EXISTS at %s' . PHP_EOL, [$mailerFilePath]);
        }
        $mailerFileDoors = explode('/', $mailerFilePath);
        $mailerFileNameParts = explode('.php', basename($mailerFileDoors[count($mailerFileDoors) - 1]));
        $nameSpace = 'Mail';
        unset($mailerFileDoors[count($mailerFileDoors) - 1]);
        foreach ($mailerFileDoors as $doorName => $door) {
            $mailerFileDoors[$doorName] = Str::decamelize($door);
            if (!in_array($door, ['segments', 'Mail'])) {
                $nameSpace .= '\\' . $door;
            }
        }
        if (!file_exists(implode('/', $mailerFileDoors))) {
            mkdir(implode('/', $mailerFileDoors), 0644, true);
        }
        $f = fopen($mailerFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create mailer file at ', [$mailerFilePath]);
        }
        fwrite($f, $this->getBaseMailerCode($mailerFileNameParts[0], $nameSpace));
        fclose($f);
        return $this->showMsg('Mailer saved at ' . $mailerFilePath . '!');
    }

    public function createTexterFile()
    {
        if (empty($this->attribute)) {
            return $this->throwError('EMPTY [Texter] FILE CAN NOT BE CREATED' . PHP_EOL);
        }

        $texterFilePath = 'segments/SMS/' . $this->getDeCamelizedPath() . '.php';
        if (file_exists($texterFilePath)) {
            return $this->throwError('[Texter] FILE ALREADY EXISTS at %s' . PHP_EOL, [$texterFilePath]);
        }
        $texterFileDoors = explode('/', $texterFilePath);
        $texterFileNameParts = explode('.php', basename($texterFileDoors[count($texterFileDoors) - 1]));
        $nameSpace = 'SMS';
        unset($texterFileDoors[count($texterFileDoors) - 1]);
        foreach ($texterFileDoors as $doorName => $door) {
            $texterFileDoors[$doorName] = Str::decamelize($door);
            if (!in_array($door, ['segments', 'SMS'])) {
                $nameSpace .= '\\' . $door;
            }
        }
        if (!file_exists(implode('/', $texterFileDoors))) {
            mkdir(implode('/', $texterFileDoors), 0644, true);
        }
        $f = fopen($texterFilePath, 'wb');
        if (!$f) {
            return $this->throwError('%s can not create texter file at ', [$texterFilePath]);
        }
        fwrite($f, $this->getBaseTexterCode($texterFileNameParts[0], $nameSpace));
        fclose($f);
        return $this->showMsg('Texter saved at ' . $texterFilePath . '!');
    }

    public function setConfigSettings()
    {
        if (file_exists($this->settingDir)) {
            $this->showMsgAndContinue($this->settingDir . ' directory already exists. Do you want to remove it and set a fresh config files?' . PHP_EOL);

            $confirm = $this->confirm('Enter Y for [Yes] or N for [No]: ');

            if (strtoupper($confirm) == 'Y' || ucfirst(strtolower($confirm)) == 'Yes') {
                $this->showMsgAndContinue('Setting up setting files in %s' . PHP_EOL, [ $this->settingDir ]);
            } else {
                return $this->showMsg('config setup process stopped' . PHP_EOL);
            }
        }

        if (!file_exists($this->settingDir)) {
            $this->showMsgAndContinue('Creating %s [SETTING DIRECTORY]' . PHP_EOL, [ $this->settingDir ]);
            mkdir($this->settingDir, 655, true);
            $this->showMsgAndContinue('%s [SETTING DIRECTORY] created!' . PHP_EOL, [ $this->settingDir ]);
        }

        return $this->createFreshSettingFiles();
        
    }

    public function createFreshSettingFiles()
    {
        $this->createSettingAppFile();
        $this->createSettingAliasFile();
        $this->createSettingDatabaseFile();
        $this->createSettingSessionFile();
        $this->createSettingAlertFile();
        $this->createSettingTemplateFile();

        return true;
    }

    public function createSettingAppFile()
    {
        $settingAppFile = $this->settingDir . '/app.php';
        
        $this->showMsgAndContinue('Creating %s [FILE]...' . PHP_EOL, [$settingAppFile]);

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Set base url of your project" . PHP_EOL;
        $settingContent .= "\t'base_url' => 'http://localhost'," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Enter sub directory where your project is hosted or set it blank if your project is on host root" . PHP_EOL;
        $settingContent .= "\t'sub_dir' => 'jolly'," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Set application stage (local || production)" . PHP_EOL;
        $settingContent .= "\t'stage' => 'local'," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t'title' => 'Jolly - A tiny PHP Framework'," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Set default language for your application, this will be used when there is no language has been set or if any translation string is not" . PHP_EOL;
        $settingContent .= "\t//found then it will be returned from default_lang" . PHP_EOL;
        $settingContent .= "\t'default_lang' => 'en'," . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingAppFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingAppFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingAppFile]);
    }

    public function createSettingAliasFile()
    {
        $settingAliasFile = $this->settingDir . '/aliases.php';

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= 'use Barriers\VerifyRequest;' . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Add Barrier aliases to use as an alias" . PHP_EOL;
        $settingContent .= "\t'Barriers' => [" . PHP_EOL;
        $settingContent .= "\t\t'verify-request' => VerifyRequest::class," . PHP_EOL;
        $settingContent .= "\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingAliasFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingAliasFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingAliasFile]);
    }

    public function createSettingDatabaseFile()
    {
        $settingDatabaseFile = $this->settingDir . '/database.php';

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Set this false if you do not want to use database" . PHP_EOL;
        $settingContent .= "\t'enable' => true," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Database details [ Key of this set is database name ]" . PHP_EOL;
        $settingContent .= "\t'database_name' => [" . PHP_EOL;
        $settingContent .= "\t\t'host' => 'localhost'," . PHP_EOL;
        $settingContent .= "\t\t'username' => 'root'," . PHP_EOL;
        $settingContent .= "\t\t'password' => ''," . PHP_EOL;
        $settingContent .= "\t\t'port' => 3306," . PHP_EOL;
        $settingContent .= "\t\t'prefix' => ''," . PHP_EOL;
        $settingContent .= "\t\t'charset' => 'utf8'," . PHP_EOL;
        $settingContent .= "\t\t'socket' => null," . PHP_EOL;
        $settingContent .= "\t\t'is_primary' => true," . PHP_EOL;
        $settingContent .= "\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingDatabaseFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingDatabaseFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingDatabaseFile]);
    }

    public function createSettingSessionFile()
    {
        $settingSessionFile = $this->settingDir . '/session.php';

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t'age' => 14400, // seconds" . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingSessionFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingSessionFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingSessionFile]);
    }

    public function createSettingAlertFile()
    {
        $settingAliasFile = $this->settingDir . '/alert.php';

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// Mail configuration" . PHP_EOL;
        $settingContent .= "\t'mail' => [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'via' => 'smtp', // default | SMTP" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'from' => [" . PHP_EOL;
        $settingContent .= "\t\t\t\t'email' => 'admin@administration.com'," . PHP_EOL;
        $settingContent .= "\t\t\t\t'name' => 'Administration'," . PHP_EOL;
        $settingContent .= "\t\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'reply' => [" . PHP_EOL;
        $settingContent .= "\t\t\t\t'email' => 'reply@administration.com'," . PHP_EOL;
        $settingContent .= "\t\t\t\t'name' => 'Administration'," . PHP_EOL;
        $settingContent .= "\t\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'smtp' => [" . PHP_EOL;
        $settingContent .= "\t\t\t\t'host' => 'smtp.example.com'," . PHP_EOL;
        $settingContent .= "\t\t\t\t'username' => 'username'," . PHP_EOL;
        $settingContent .= "\t\t\t\t'password' => 'password'," . PHP_EOL;
        $settingContent .= "\t\t\t\t'port' => 465," . PHP_EOL;
        $settingContent .= "\t\t\t\t'encryption' => 'tls', // SSL | TLS" . PHP_EOL;
        $settingContent .= "\t\t\t\t'debug' => false," . PHP_EOL;
        $settingContent .= "\t\t\t\t'auth' => true," . PHP_EOL;
        $settingContent .= "\t\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t// SMS configuration" . PHP_EOL;
        $settingContent .= "\t'sms' => [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'via' => 'twilio', // twilio" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t\t'twilio' => [" . PHP_EOL;
        $settingContent .= "\t\t\t'account_sid' => 'TWILIO_ACCOUNT_SID'," . PHP_EOL;
        $settingContent .= "\t\t\t'auth_token' => 'TWILIO_AUTH_TOKEN'," . PHP_EOL;
        $settingContent .= "\t\t\t'from_number' => 'TWILIO_FROM_NUMBER'," . PHP_EOL;
        $settingContent .= "\t\t\t'api_endpoint' => 'https://api.twilio.com/2010-04-01/'," . PHP_EOL;
        $settingContent .= "\t\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "\t]," . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingAliasFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingAliasFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingAliasFile]);
    }

    public function createSettingTemplateFile()
    {
        $settingAliasFile = $this->settingDir . '/templates.php';

        $settingContent = '<?php' . PHP_EOL . PHP_EOL;
        $settingContent .= "\$sysDefaultDir = 'defaults/';" . PHP_EOL . PHP_EOL;
        $settingContent .= "return [" . PHP_EOL . PHP_EOL;
        $settingContent .= "\t'404' => \$sysDefaultDir . '404'," . PHP_EOL;
        $settingContent .= "\t'503' => \$sysDefaultDir . '503'," . PHP_EOL . PHP_EOL;
        $settingContent .= "];";

        $f = fopen($settingAliasFile, 'wb');
        if (!$f) {
            return $this->throwError('Setting file can not be created at %s' . PHP_EOL, [ $settingAliasFile ]);
        }

        fwrite($f, $settingContent);
        fclose($f);

        $this->showMsgAndContinue('%s [SETTING FILE] created!' . PHP_EOL, [$settingAliasFile]);
    }

    public function getBaseModelCode(string $name, string $nameSpace)
    {
        $baseModelCode = '<?php' . PHP_EOL . PHP_EOL;
        $baseModelCode .= 'namespace ' . $nameSpace . ';' . PHP_EOL . PHP_EOL;
        $baseModelCode .= 'use Models\Base\Model;' . PHP_EOL . PHP_EOL;
        $baseModelCode .= 'class ' . Str::decamelize($name) . ' extends Model' . PHP_EOL;
        $baseModelCode .= '{' . PHP_EOL;
        if (!empty($this->extraAttrs)) {
            foreach ($this->extraAttrs as $extraAttr) {
                $attribute = explode('=', $extraAttr);
                $attrName = $attribute[0];
                if (Str::startsWith($attrName, '--') && count($attribute) > 0) {
                    $attrVal = $attribute[1];
                    $attrName = str_replace('--', '', $attrName);
                    $baseModelCode .= "\tprotected \$" . $attrName . " = '" . $attrVal . "';" . PHP_EOL;
                }
            }
        }
        $baseModelCode .= PHP_EOL . '}';
        return $baseModelCode;
    }

    public function getBaseControllerCode(string $name, string $nameSpace)
    {
        $baseControllerCode = '<?php' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'namespace ' . $nameSpace . ';' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'use Bones\Request;' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'class ' . Str::decamelize($name) . PHP_EOL;
        $baseControllerCode .= '{' . PHP_EOL;
        $baseControllerCode .= "\tpublic function index(Request \$request)" . PHP_EOL;
        $baseControllerCode .= "\t{" . PHP_EOL;
        $baseControllerCode .= "\t\t// Paint your jolly stuff with code..." . PHP_EOL;
        $baseControllerCode .= "\t}" . PHP_EOL;
        if (!empty($this->extraAttrs)) {
        }
        $baseControllerCode .= '}';
        return $baseControllerCode;
    }

    public function getBaseMailerCode(string $name, string $nameSpace)
    {
        $baseControllerCode = '<?php' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'namespace ' . $nameSpace . ';' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'use Contributors\Mail\Mailer;' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'class ' . Str::decamelize($name) . ' extends Mailer' . PHP_EOL;
        $baseControllerCode .= '{' . PHP_EOL;
        $baseControllerCode .= "\tprotected \$data;" . PHP_EOL . PHP_EOL;
        $baseControllerCode .= "\tpublic function __construct(\$data)" . PHP_EOL;
        $baseControllerCode .= "\t{" . PHP_EOL;
        $baseControllerCode .= "\t\t\$this->data = \$data;" . PHP_EOL;
        $baseControllerCode .= "\t}" . PHP_EOL.PHP_EOL;
        $baseControllerCode .= "\tpublic function prepare()" . PHP_EOL;
        $baseControllerCode .= "\t{" . PHP_EOL;
        $baseControllerCode .= "\t\treturn \$this->html(content('mails/welcome', ['data' => \$this->data]))" . PHP_EOL;
        $baseControllerCode .= "\t\t\t\t\t->to('recepient_address')" . PHP_EOL;
        $baseControllerCode .= "\t\t\t\t\t->subject('subject')" . PHP_EOL;
        $baseControllerCode .= "\t\t\t\t\t->attach('path/to/file', 'attachment_alias');" . PHP_EOL;
        $baseControllerCode .= "\t}" . PHP_EOL;
        if (!empty($this->extraAttrs)) {
        }
        $baseControllerCode .= '}';
        return $baseControllerCode;
    }

    public function getBaseTexterCode(string $name, string $nameSpace)
    {
        $baseControllerCode = '<?php' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'namespace ' . $nameSpace . ';' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'use Contributors\SMS\Texter;' . PHP_EOL . PHP_EOL;
        $baseControllerCode .= 'class ' . Str::decamelize($name) . ' extends Texter' . PHP_EOL;
        $baseControllerCode .= '{' . PHP_EOL;
        $baseControllerCode .= "\tprotected \$data;" . PHP_EOL . PHP_EOL;
        $baseControllerCode .= "\tpublic function __construct(\$data)" . PHP_EOL;
        $baseControllerCode .= "\t{" . PHP_EOL;
        $baseControllerCode .= "\t\t\$this->data = \$data;" . PHP_EOL;
        $baseControllerCode .= "\t}" . PHP_EOL.PHP_EOL;
        $baseControllerCode .= "\tpublic function prepare()" . PHP_EOL;
        $baseControllerCode .= "\t{" . PHP_EOL;
        $baseControllerCode .= "\t\treturn \$this->template(content('sms/account-activated', ['data' => \$this->data]))" . PHP_EOL;
        $baseControllerCode .= "\t\t\t\t\t->to('recepient_number');" . PHP_EOL;
        $baseControllerCode .= "\t}" . PHP_EOL;
        if (!empty($this->extraAttrs)) {
        }
        $baseControllerCode .= '}';
        return $baseControllerCode;
    }

    public function getBaseViewCode()
    {
        $baseViewCode = "@php " . PHP_EOL . "\t// Paint your jolly stuff with code... " . PHP_EOL . "@endphp";
        return $baseViewCode;
    }

    public function getBaseBarrierCode(string $name, string $nameSpace)
    {
        $baseBarrierCode = '<?php' . PHP_EOL . PHP_EOL;
        $baseBarrierCode .= 'namespace ' . $nameSpace . ';' . PHP_EOL . PHP_EOL;
        $baseBarrierCode .= 'use Bones\Request;' . PHP_EOL . PHP_EOL;
        $baseBarrierCode .= 'class ' . Str::decamelize($name) . PHP_EOL;
        $baseBarrierCode .= '{' . PHP_EOL;
        $baseBarrierCode .= "\tpublic \$excludeRoutes = [" . PHP_EOL;
        $baseBarrierCode .= "\t\t// define routes to exclude from barrier check" . PHP_EOL;
        $baseBarrierCode .= "\t];" . PHP_EOL . PHP_EOL;
        $baseBarrierCode .= "\tpublic function check(Request \$request)" . PHP_EOL;
        $baseBarrierCode .= "\t{" . PHP_EOL;
        $baseBarrierCode .= "\t\treturn true;" . PHP_EOL;
        $baseBarrierCode .= "\t}" . PHP_EOL;
        if (!empty($this->extraAttrs)) {
        }
        $baseBarrierCode .= '}';
        return $baseBarrierCode;
    }

    public function removeModelFile()
    {
        $modelFilePath = 'segments/Models/' . $this->attribute . '.php';
        return $this->removeAsset($modelFilePath, 'Model');
    }

    public function removeControllerFile()
    {
        $controllerFilePath = 'segments/Controllers/' . $this->attribute . 'Controller.php';
        return $this->removeAsset($controllerFilePath, 'Controller');
    }

    public function removeViewFile()
    {
        $viewFilePath = 'segments/Views/' . $this->attribute . '.jly.php';
        return $this->removeAsset($viewFilePath, 'View');
    }

    public function removeBarrierFile()
    {
        $barrierFilePath = 'segments/Barriers/' . $this->attribute . '.php';
        return $this->removeAsset($barrierFilePath, 'Barrier');
    }

    public function removeDBGramFile()
    {
        if (empty(trim($this->attribute)) && $this->attribute != '--all') {
            return $this->throwError('dbgram file path must be given or apply remove:dbgram --all to remove all dbgram files');
        }
        return (new Adjustor())->removeAllFiles($this->attribute);
    }

    public function removeDBFillerFile()
    {
        if (empty(trim($this->attribute)) && $this->attribute != '--all') {
            return $this->throwError('dbfiller file path must be given or apply remove:dbfiller --all to remove all dbfiller files');
        }
        return (new Refill())->removeAllFiles($this->attribute);
    }

    public function removeMailerFile()
    {
        $mailerFilePath = 'segments/Mail/' . $this->attribute . '.php';
        return $this->removeAsset($mailerFilePath, 'Mailer');
    }

    public function removeAsset($assetPath, $assetType = '')
    {
        if (!file_exists($assetPath))
            return $this->throwError('[' . $assetType . ' File] %s does not exists', [$assetPath]);
        if (!unlink($assetPath))
            return $this->throwError('Error while removing [' . $assetType . ' File] %s', [$assetPath]);

        return $this->showMsg('[' . $assetType . ' File] %s removed successfully!', [$assetPath]);
    }

    public function startApp()
    {
        if (!file_exists('locker/system')) {
            mkdir('locker/system/', 655);
        }

        if (file_exists($this->appStopperFile)) {
            if (!unlink($this->appStopperFile)) {
                return $this->throwError('Processing Issue: App can not be started. Kindly remove %s file to start the app manually.', [$this->appStopperFile]);
            } else {
                $this->writeMessageToAppStopper();
                return $this->showMsg('App successfully started!');
            }
        } else {
            $this->writeMessageToAppStopper();
            return $this->showMsg('App is already in running mode!');
        }
    }

    public function stopApp()
    {
        if (!file_exists('locker/system')) {
            mkdir('locker/system/', 655);
        }
        
        $this->writeMessageToAppStopper();
        if (!file_exists($this->appStopperFile)) {
            if (!touch($this->appStopperFile)) {
                return $this->throwError('Processing Issue: App can not be stopped. Kindly add %s file to stop the app manually.', [$this->appStopperFile]);
            } else {
                $this->setAppStopperMsg();
                return $this->showMsg('App successfully stopped!');
            }
        } else {
            $this->setAppStopperMsg();
            return $this->showMsg('App is already in stop mode!');
        }
    }

    public function setAppStopperMsg()
    {
        if (!empty($this->attribute) && gettype($this->attribute) == 'string' && Str::startsWith($this->attribute, '--message')) {
            $messageParts = explode('=', $this->attribute);
            if (count($messageParts) > 1 && $messageParts[0] == '--message' && !empty($messageParts[1])) {
                $this->writeMessageToAppStopper($messageParts[1]);
            }
        }
    }

    public function writeMessageToAppStopper(string $msg = '')
    {
        if (!file_exists($this->appStopperFile))
            return false;

        $f = fopen($this->appStopperFile, 'wb');
        if (!$f) {
            return $this->throwError('{%s} can not be set as message as app stopper ', [$msg]);
        }
        fwrite($f, $msg);
        fclose($f);
    }

    public function clearDir(string $dir)
    {
        if (file_exists($dir) && is_dir($dir)) {
            foreach (glob($dir . '*') as $file) {
                unlink($file);
            }
            return $this->showMsg('{%s} [DIR] is clear now' . PHP_EOL, [$dir]);
        }
        return $this->throwError('{%s} [DIR] does not exists ' . PHP_EOL, [$dir]);
    }

    public function listRoutes()
    {
        $routes = Router::list();
        if (count($routes) === 0) {
            return $this->throwError('No routes defined' . PHP_EOL);
        }

        echo '----------------------------------------------------------------------------------------------------' . PHP_EOL;
        echo count($routes) . ' routes registered' . PHP_EOL;
        echo '----------------------------------------------------------------------------------------------------' . PHP_EOL;
        $mask = "%6s | %-30.30s | %-10s | %s\n";

        foreach ($routes as $route) {
            if (empty($route['caption'])) $route['caption'] = '/';
            $namedAs = (!empty($route['namedAs']) && !$route['nameFromParent']) ? $route['namedAs'] : 'N/A';
            $barriersCount = (!empty($route['barriers'])) ? count($route['barriers']) . ' barrier(s)' : 'No barrier';
            printf($mask, strtoupper($route['method']), $namedAs, $barriersCount, $route['caption']);
        }

        return true;
    }

    public function listDBGrams()
    {
        $dbGrams = (new Adjustor())->getDBGrams($this->attribute, $this->extraAttrs);

        if (count($dbGrams) === 0) {
            return $this->throwError('No dbgram(s) found' . PHP_EOL);
        }

        echo '====================================================================================================' . PHP_EOL;
        echo count($dbGrams) . ' dbgram(s) found (Columns order is Is_Adjusted, Stack (If Adjusted), Name)' . PHP_EOL;
        echo '----------------------------------------------------------------------------------------------------' . PHP_EOL;

        foreach ($dbGrams as $dbGram) {
            $routeLine = ($dbGram['is_adjusted']) ? 'Adjusted' : 'Not Adjusted';
            $routeLine .= "\t";
            $routeLine .= (!empty($dbGram['stack'])) ? 'Stack #' . $dbGram['stack'] : 'N/A';
            $routeLine .= "\t";
            $routeLine .= $dbGram['name'];
            $routeLine .= PHP_EOL;
            echo $routeLine;
        }

        return true;
    }

    public function execDBGrams()
    {
        return (new Adjustor())->proceedDBGramAdjustment($this->attribute, $this->extraAttrs);
    }

    public function execDBFillers()
    {
        return (new Refill())->proceedDBFillers($this->attribute);
    }

    public function getDeCamelizedPath()
    {
        $relativePathParts = explode('/', $this->attribute);
        foreach ($relativePathParts as &$part) {
            $part = Str::decamelize($part);
        }

        return implode('/', $relativePathParts);
    }

    public function throwError(string $errMsg, array $args = [])
    {
        $errMsg = '[COMMANDER VIA JOLLY]: ' . $errMsg;
        $errorParts = array_merge([$errMsg], $args);
        echo call_user_func_array('sprintf', $errorParts);
        return false;
    }

    public function confirm($message)
    {
        return readline($message);
    }

    public function showMsg(string $errMsg, array $args = [])
    {
        $errMsg = '[COMMANDER VIA JOLLY]: ' . $errMsg;
        $errorParts = array_merge([$errMsg], $args);
        echo call_user_func_array('sprintf', $errorParts);
        return true;
    }

    public function showMsgAndContinue(string $errMsg, array $args = [])
    {
        $errMsg = '[COMMANDER VIA JOLLY]: ' . $errMsg;
        $errorParts = array_merge([$errMsg], $args);
        echo call_user_func_array('sprintf', $errorParts);
    }
}