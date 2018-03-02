<?php
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Resource;

use BEAR\Resource\Exception\MethodNotAllowedException;
use Ray\Di\Di\Named;

final class Invoker implements InvokerInterface
{
    /**
     * @var NamedParameterInterface
     */
    private $params;

    /**
     * @var RenderInterface
     */
    private $optionsRenderer;

    /**
     * @Named("optionsRenderer=options")
     */
    public function __construct(NamedParameterInterface $params, RenderInterface $optionsRenderer)
    {
        $this->params = $params;
        $this->optionsRenderer = $optionsRenderer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws MethodNotAllowedException
     */
    public function invoke(AbstractRequest $request)
    {
        $onMethod = 'on' . ucfirst($request->method);
        if (method_exists($request->resourceObject, $onMethod) === true) {
            return $this->invokeMethod($request, $onMethod);
        }
        if ($request->method === Request::OPTIONS) {
            return $this->invokeOptions($request);
        }

        throw new MethodNotAllowedException(get_class($request->resourceObject) . "::{($request->method}()", 405);
    }

    private function invokeMethod(AbstractRequest $request, string $onMethod) : ResourceObject
    {
        if ($request->resourceObject->uri instanceof AbstractUri) {
            $request->resourceObject->uri->query = $request->query;
            $request->resourceObject->uri->method = $request->method;
        }
        $params = $this->params->getParameters([$request->resourceObject, $onMethod], $request->query);
        $response = call_user_func_array([$request->resourceObject, $onMethod], $params);

        if (! $response instanceof ResourceObject) {
            $request->resourceObject->body = $response;
            $response = $request->resourceObject;
        }

        return $response;
    }

    private function invokeOptions(AbstractRequest $request) : ResourceObject
    {
        $ro = $request->resourceObject;
        $ro->view = $this->optionsRenderer->render($request->resourceObject);

        return $ro;
    }
}
