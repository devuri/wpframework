<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use WPframework\Env\EnvTypes;
use WPframework\Http\EnvSwitcherInterface as Switcher;

class Setup implements SetupInterface
{
    protected $appPath;
    protected $isMultiTenant;
    protected $dotenv;
    protected static $instance;
    protected $environment;
    protected $errorHandler;
    protected $errorLogDir;
    protected $envFiles = [];
    protected $shortCircuit;
    protected $switcher;
    protected $envTypes = [];
    protected $tenant;
    protected $configManager;

    public function __construct(string $appPath, array $envFileNames = [], bool $shortCircuit = true)
    {
        $this->tenant        = new Tenant($appPath);
        $constantBuilder     = new ConstantBuilder();
        $this->configManager = new AppConfig($constantBuilder);
        $this->appPath       = $this->tenant->getCurrentPath();
        $this->shortCircuit  = $shortCircuit;
        $this->envFiles      = array_merge($this->getDefaultFileNames(), $envFileNames);

        $this->filterExistingEnvFiles();
        $this->envTypes = EnvTypes::getAll();
        $this->initializeDotenv();

        $this->configManager->setConstantMap();
    }

    public function tenant(): Tenant
    {
        return $this->tenant;
    }

    public function getAppPath()
    {
        return $this->appPath;
    }

    public function getAppConfig(): AppConfig
    {
        return $this->configManager;
    }

    public static function init(string $appPath): self
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self($appPath);
        }

        return self::$instance;
    }

    /**
     * @param null|(null|false|mixed|string)[] $environment
     *
     * @return static
     *
     * @psalm-param array{environment: 'testing'|mixed|null, error_log: string, debug: false, errors: mixed}|null $environment
     */
    public function config(?array $environment = null, ?bool $setup = true): SetupInterface
    {
        $this->isRequired();

        if (\is_null($setup)) {
            $this->environment = $environment;

            return $this;
        }

        $environment        = $this->normalizeEnvironment($environment);
        $this->errorLogDir  = $environment['error_log'] ?? null;
        $this->errorHandler = $environment['errors'] ?? null;
        $this->environment  = $this->determineEnvironment($environment['environment']);

        if (false === $setup) {
            $this->setEnvironment()
                ->debug($this->errorLogDir)
                ->setErrorHandler()
                ->database()
                ->salts();

            return $this;
        }

        if (true === $setup) {
            $this->setEnvironment()
                ->debug($this->errorLogDir)
                ->setErrorHandler()
                ->database()
                ->siteUrl()
                ->assetUrl()
                ->memory()
                ->optimize()
                ->forceSsl()
                ->autosave()
                ->salts();
        }

        return $this;
    }

    public function setSwitcher(Switcher $switcher): void
    {
        $this->switcher = $switcher;
    }

    /**
     * @return static
     */
    public function setEnvironment(): self
    {
        $this->configManager->addConstant('WP_DEVELOPMENT_MODE', self::wpDevelopmentMode());

        if (false === $this->environment && env('WP_ENVIRONMENT_TYPE')) {
            $this->configManager->addConstant('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE'));

            return $this;
        }

        if ($this->isEnvironmentNull()) {
            $this->configManager->addConstant('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE') ?? self::getConstant('environment'));

            return $this;
        }

        $this->configManager->addConstant('WP_ENVIRONMENT_TYPE', $this->environment);

        return $this;
    }

    public function getShortCircuit(): bool
    {
        return $this->shortCircuit;
    }

    public function getEnvFiles(): array
    {
        return $this->envFiles;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return static
     */
    public function setErrorHandler(?string $handler = null): SetupInterface
    {
        if ( ! $this->enableErrorHandler()) {
            return $this;
        }

        if (\is_null($this->errorHandler)) {
            return $this;
        }

        if ( ! \in_array($this->environment, [ 'debug', 'development', 'dev', 'local' ], true)) {
            return $this;
        }

        if ($handler) {
            $this->errorHandler = $handler;
        }

        if ('symfony' === $this->errorHandler) {
            Debug::enable();
        } elseif ('oops' === $this->errorHandler) {
            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        }

        return $this;
    }

    /**
     * @param null|string $errorLogsDir
     *
     * @return static
     */
    public function debug(?string $errorLogsDir): SetupInterface
    {
        if (false === $this->environment && env('WP_ENVIRONMENT_TYPE')) {
            $this->resetEnvironment(env('WP_ENVIRONMENT_TYPE'));
        }

        if ($this->isEnvironmentNull() && env('WP_ENVIRONMENT_TYPE')) {
            $this->resetEnvironment(env('WP_ENVIRONMENT_TYPE'));
        }

        if ( ! EnvTypes::isValid($this->environment)) {
            $this->switcher->createEnvironment('production', $this->errorLogDir);

            return $this;
        }

        $this->switcher->createEnvironment($this->environment, $this->errorLogDir);

        return $this;
    }

    public function required(string $name): void
    {
        if ( ! \defined($name)) {
            $this->dotenv->required($name)->notEmpty();
        }
    }

    /**
     * @return static
     */
    public function siteUrl(): SetupInterface
    {
        $this->configManager->addConstant('WP_HOME', env('WP_HOME'));
        $this->configManager->addConstant('WP_SITEURL', env('WP_SITEURL'));

        return $this;
    }

    /**
     * @return static
     */
    public function assetUrl(): SetupInterface
    {
        $this->configManager->addConstant('ASSET_URL', env('ASSET_URL'));

        return $this;
    }

    /**
     * @return static
     */
    public function optimize(): SetupInterface
    {
        $this->configManager->addConstant('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?? self::getConstant('optimize'));

        return $this;
    }

    /**
     * @return static
     */
    public function memory(): SetupInterface
    {
        $this->configManager->addConstant('WP_MEMORY_LIMIT', env('MEMORY_LIMIT') ?? self::getConstant('memory'));
        $this->configManager->addConstant('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?? self::getConstant('memory'));

        return $this;
    }

    /**
     * @return static
     */
    public function forceSsl(): SetupInterface
    {
        $this->configManager->addConstant('FORCE_SSL_ADMIN', env('FORCE_SSL_ADMIN') ?? self::getConstant('ssl_admin'));
        $this->configManager->addConstant('FORCE_SSL_LOGIN', env('FORCE_SSL_LOGIN') ?? self::getConstant('ssl_login'));

        return $this;
    }

    /**
     * @return static
     */
    public function autosave(): SetupInterface
    {
        $this->configManager->addConstant('AUTOSAVE_INTERVAL', env('AUTOSAVE_INTERVAL') ?? self::getConstant('autosave'));
        $this->configManager->addConstant('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?? self::getConstant('revisions'));

        return $this;
    }

    /**
     * @return static
     */
    public function database(): SetupInterface
    {
        $this->configManager->addConstant('DB_NAME', env('DB_NAME'));
        $this->configManager->addConstant('DB_USER', env('DB_USER'));
        $this->configManager->addConstant('DB_PASSWORD', env('DB_PASSWORD'));
        $this->configManager->addConstant('DB_HOST', env('DB_HOST') ?? self::getConstant('db_host'));
        $this->configManager->addConstant('DB_CHARSET', env('DB_CHARSET') ?? 'utf8mb4');
        $this->configManager->addConstant('DB_COLLATE', env('DB_COLLATE') ?? '');

        return $this;
    }

    /**
     * @return static
     */
    public function salts(): SetupInterface
    {
        $this->configManager->addConstant('AUTH_KEY', env('AUTH_KEY'));
        $this->configManager->addConstant('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
        $this->configManager->addConstant('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
        $this->configManager->addConstant('NONCE_KEY', env('NONCE_KEY'));
        $this->configManager->addConstant('AUTH_SALT', env('AUTH_SALT'));
        $this->configManager->addConstant('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
        $this->configManager->addConstant('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
        $this->configManager->addConstant('NONCE_SALT', env('NONCE_SALT'));

        $this->configManager->addConstant('DEVELOPER_ADMIN', env('DEVELOPER_ADMIN') ?? '0');

        return $this;
    }

    /**
     * @param null|string[] $environment
     *
     * @psalm-param array<string>|null $environment
     *
     * @return (null|false|string)[]
     *
     * @psalm-return array{environment: null|string, error_log: null|string, debug: false|null|string, errors: false|null|string,...}
     */
    protected function normalizeEnvironment(?array $environment): array
    {
        if ( ! \is_array($environment)) {
            $environment = [ 'environment' => $environment ];
        }

        return array_merge(
            [
                'environment' => null,
                'error_log'   => null,
                'debug'       => false,
                'errors'      => false,
            ],
            $environment
        );
    }

    /**
     * @param null|string $environment
     */
    protected function determineEnvironment(?string $environment)
    {
        if (\is_bool($environment) || \is_string($environment)) {
            return $environment;
        }

        return trim((string) $environment);
    }

    protected function filterExistingEnvFiles(): void
    {
        foreach ($this->envFiles as $key => $file) {
            if ( ! file_exists($this->appPath . '/' . $file)) {
                unset($this->envFiles[$key]);
            }
        }
    }

    protected function initializeDotenv(): void
    {
        $this->dotenv = Dotenv::createImmutable($this->appPath, $this->envFiles, $this->shortCircuit);

        try {
            $this->dotenv->load();
        } catch (Exception $e) {
            $debug = [
                'class'     => static::class,
                'object'    => $this,
                'path'      => $this->appPath,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit([ $e->getMessage(), 500, $debug ]);
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return list{'env', '.env', '.env.secure', '.env.prod', '.env.staging', '.env.dev', '.env.debug', '.env.local', 'env.local'}
     */
    protected function getDefaultFileNames(): array
    {
        return [
            'env',
            '.env',
            '.env.secure',
            '.env.prod',
            '.env.staging',
            '.env.dev',
            '.env.debug',
            '.env.local',
            'env.local',
        ];
    }

    protected function enableErrorHandler(): bool
    {
        if ($this->errorHandler) {
            return true;
        }

        return false;
    }

    protected function isRequired(): void
    {
        try {
            $this->required('WP_HOME');
            $this->required('WP_SITEURL');

            $this->dotenv->required('DISABLE_WP_APPLICATION_PASSWORDS')->allowedValues([ 'true', 'false' ]);

            $this->dotenv->required('DB_HOST')->notEmpty();
            $this->dotenv->required('DB_NAME')->notEmpty();
            $this->dotenv->required('DB_USER')->notEmpty();
            $this->dotenv->required('DB_PASSWORD')->notEmpty();

            $this->dotenv->required('AUTH_KEY')->notEmpty();
            $this->dotenv->required('SECURE_AUTH_KEY')->notEmpty();
            $this->dotenv->required('LOGGED_IN_KEY')->notEmpty();
            $this->dotenv->required('NONCE_KEY')->notEmpty();
            $this->dotenv->required('AUTH_SALT')->notEmpty();
            $this->dotenv->required('SECURE_AUTH_SALT')->notEmpty();
            $this->dotenv->required('LOGGED_IN_SALT')->notEmpty();
            $this->dotenv->required('NONCE_SALT')->notEmpty();
        } catch (Exception $e) {
            $debug = [
                'class'     => static::class,
                'object'    => $this,
                'path'      => $this->appPath,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit([ $e->getMessage(), 500, $debug ]);
        }// end try
    }

    protected static function getConstant(string $key)
    {
        $constant['environment'] = 'production';
        $constant['debug']       = true;
        $constant['db_host']     = 'localhost';
        $constant['optimize']    = true;
        $constant['memory']      = '256M';
        $constant['ssl_admin']   = true;
        $constant['ssl_login']   = true;
        $constant['autosave']    = 180;
        $constant['revisions']   = 10;

        return $constant[$key] ?? null;
    }

    private static function wpDevelopmentMode(): string
    {
        return env('WP_DEVELOPMENT_MODE') ?? '';
    }

    private function isEnvironmentNull(): bool
    {
        return empty($this->environment);
    }

    private static function getEnv(string $name)
    {
        if (\is_null(env($name))) {
            return null;
        }

        return env($name);
    }

    private function resetEnvironment($reset): void
    {
        $this->environment = $reset;
    }
}
