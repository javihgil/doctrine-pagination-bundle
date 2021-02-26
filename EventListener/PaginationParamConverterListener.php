<?php

namespace Jhg\DoctrinePaginationBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;
use Jhg\DoctrinePaginationBundle\Request\RequestParam;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class PaginationParamConverterListener
 */
class PaginationParamConverterListener implements EventSubscriberInterface
{
    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (is_object($controller)) {
            return;
        }

        $annotationReader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($controller[0]);
        $reflectionMethod = $reflectionClass->getMethod($controller[1]);

        $annotations = $annotationReader->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if ($this->supports($annotation)) {
                $this->parseAnnotation($reflectionMethod, $annotation, $request);
            }
        }
    }

    /**
     * @param object $annotation
     *
     * @return bool
     */
    protected function supports($annotation)
    {
        if ($annotation instanceof Pagination\PaginationAnnotationInterface) {
            return true;
        }

        return false;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param object            $annotation
     * @param Request           $request
     */
    protected function parseAnnotation(\ReflectionMethod $reflectionMethod, $annotation, Request $request)
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

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}