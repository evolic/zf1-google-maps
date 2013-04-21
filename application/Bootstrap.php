<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAppAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $this->getOptions());
        return $config;
    }

    protected function _initView()
    {
        $config = $this->getOptions();
        $view = new Zend_View($config);

        // set title
        $view->headTitle()->setSeparator(' :: ');
        $view->headTitle($config['appName']);

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        return $view;
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->setEncoding('UTF-8');
    }

    protected function _initLog()
    {
        if ($this->hasPluginResource("log")) {
            $r = $this->getPluginResource("log");
            $log = $r->getLog();

            $log->info('logger on');
            if (isset($_SERVER['REQUEST_URI'])) {
                $log->info($_SERVER['REQUEST_URI']);
            }
            $config = $this->getOption('resources');
            if (isset($config['log']['stream']['writerParams']['stream'])) {
                $file = $config['log']['stream']['writerParams']['stream'];
                if (!file_exists($file)) {
                    $fh = fopen($file, 'w');
                    fclose($fh);
                }

                // fix permissions if needed (because errors appearing when script is lunched from crontab)
                $perms = fileperms($file);
                if (!($perms & 0x0080) || !($perms & 0x0010) || !($perms & 0x0002)) {
                    @chmod($file, 0666);
                }
            }

            Zend_Registry::set('log', $log);
        }
    }


    protected function _initSession()
    {
        Zend_Session::start();
    }

    protected function _initGoogleMaps()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/maps.ini', 'api');
        Zend_Registry::set('Maps_Config', $config->toArray());

        $log = Zend_Registry::get('log');
        $log->info($config->toArray());
    }
}
