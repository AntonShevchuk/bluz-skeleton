<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Application;
use Bluz\Application\Exception\ForbiddenException;
use Bluz\Auth\AuthException;
use Bluz\Proxy\Auth as AuthProxy;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Proxy\Session;
use Bluz\Proxy\Translator;

/**
 * Bootstrap
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 17:38
 */
class Bootstrap extends Application
{
    /**
     * {@inheritdoc}
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    protected function preDispatch($module, $controller, $params = array())
    {
        // example of setup default title
        Layout::title("Bluz Skeleton");

        // apply "remember me" function
        if (!AuthProxy::getIdentity()) {
            if ($token = Request::getHeader('Bluz-Token')) {
                Auth\Table::getInstance()->authenticateToken($token);
            } elseif (!empty($_COOKIE['rToken']) && !empty($_COOKIE['rId'])) {
                // try to login
                try {
                    Auth\Table::getInstance()->authenticateCookie($_COOKIE['rId'], $_COOKIE['rToken']);
                } catch (AuthException $e) {
                    $this->getResponse()->setCookie('rId', '', 1, '/');
                    $this->getResponse()->setCookie('rToken', '', 1, '/');
                }
            }
        }
        parent::preDispatch($module, $controller, $params);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    protected function postDispatch($module, $controller, $params = array())
    {
        parent::postDispatch($module, $controller, $params);
    }

    /**
     * Denied access
     * @param ForbiddenException $exception
     * @return \Bluz\Controller\Controller|null
     */
    public function forbidden(ForbiddenException $exception)
    {
        // for AJAX and API calls (over JSON)
        $jsonOrApi = Request::isXmlHttpRequest()
            || (Request::checkAccept([Request::TYPE_HTML, Request::TYPE_JSON]) === Request::TYPE_JSON);

        // for guest, for requests
        if (!AuthProxy::getIdentity() && !$jsonOrApi) {
            // save URL to session and redirect make sense if presentation is null
            Session::set('rollback', Request::getUri()->__toString());
            // add error notice
            Messages::addError('You don\'t have permissions, please sign in');
            // redirect to Sign In page
            $url = Router::getUrl('users', 'signin');
            return $this->redirect($url);
        }
        return $this->error($exception);
    }

    /**
     * Render with debug headers
     * @return void
     */
    public function render()
    {
        Logger::info('app:render');
        Logger::info('app:files:' . count(get_included_files()));

        if ($this->isDebug() && !headers_sent()) {
            $this->sendInfoHeaders();
        }

        parent::render();
    }

    /**
     * Finish it
     * @return void
     */
    public function end()
    {
        if ($errors = Logger::get('error')) {
            $this->sendErrors($errors);
        }
    }

    /**
     * Send information headers
     *
     * @return void
     */
    protected function sendInfoHeaders()
    {
        $debugString = sprintf(
            '%fsec; %skb',
            microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            ceil((memory_get_usage()/1024))
        );
        $debugString .= '; '. Request::getModule() .'/'. Request::getController();

        Response::setHeader('Bluz-Debug', $debugString);

        if ($info = Logger::get('info')) {
            Response::setHeader('Bluz-Bar', json_encode($info));
        } else {
            Response::setHeader('Bluz-Bar', '{"!":"Logger is disabled"}');
        }
    }

    /**
     * sendErrorBody
     *
     * @param  array $errors
     * @return void
     */
    protected function sendErrors($errors)
    {
        foreach ($errors as $message) {
            errorLog(new \ErrorException($message, 0, E_USER_ERROR));
            if ($this->isDebug() && $message) {
                echo "<div class='container alert alert-danger' role='alert'>$message</div>";
            }
        }
    }
}
