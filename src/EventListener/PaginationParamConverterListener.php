<?php

namespace Jhg\DoctrinePaginationBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;
use Jhg\DoctrinePaginationBundle\Request\InvalidCastingTypeException;
use Jhg\DoctrinePaginationBundle\Request\RequestParam;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PaginationParamConverterListener implements EventSubscriberInterface
{
    /**
     * @param ControllerEvent $event
     *
     * @throws InvalidCastingTypeException
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (is_array($controller = $event->getController())) {
            [$controller, $method] = $controller;
        } elseif (is_object($controller)) {
            $method = '__invoke';
        } else {
            return;
        }
        
        $request = $event->getRequest();

        $annotationReader = new AnnotationReader();

        $reflectionClass = new ReflectionClass($controller);
        $reflectionMethod = $reflectionClass->getMethod($method);

        $annotations = $annotationReader->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if ($this->supports($annotation)) {
                $this->parseAnnotation($reflectionMethod, $annotation, $request);
            }
        }
    }

    protected function supports(object $annotation): bool
    {
        if ($annotation instanceof Pagination\PaginationAnnotationInterface) {
            return true;
        }

        return false;
    }

    /**
     * @throws InvalidCastingTypeException
     */
    protected function parseAnnotation(ReflectionMethod $reflectionMethod, object $annotation, Request $request)
    {
        switch (true) {
            case $annotation instanceof Pagination\Page:
                RequestParam::getQueryValidPage($request, $annotation->getParamName());
                break;

            case $annotation instanceof Pagination\Rpp:
                RequestParam::getQueryValidParam($request, $annotation->getParamName(), $annotation->getDefault(), $annotation->getValid(), 'int');
                break;

            case $annotation instanceof Pagination\Order:
            case $annotation instanceof Pagination\Sort:
                RequestParam::getQueryValidParam($request, $annotation->getParamName(), $annotation->getDefault(), $annotation->getValid());
                break;
        }

        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($parameter->getName() == $annotation->getParamName()) {
                $request->attributes->set($annotation->getParamName(), $request->query->get($annotation->getParamName()));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}