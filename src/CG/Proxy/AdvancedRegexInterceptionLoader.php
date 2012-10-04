<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CG\Proxy;

/**
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Rabaix <thomas.rabaix@gmail.com>
 */
class AdvancedRegexInterceptionLoader implements AdvancedInterceptorLoaderInterface
{
    private $interceptors;

    /**
     * @param array $interceptors
     */
    public function __construct(array $interceptors = array())
    {
        $this->interceptors = $interceptors;
    }

    /**
     * @param \ReflectionClass  $class
     * @param \ReflectionMethod $method
     *
     * @return array
     */
    public function loadInterceptors(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $signature = $class->name.'::'.$method->name;

        $matchingInterceptors = array();
        foreach ($this->interceptors as $pattern => $interceptor) {
            if (preg_match('#'.$pattern.'#', $signature)) {
                $matchingInterceptors[] = $this->initializeInterceptor($interceptor);
            }
        }

        return $matchingInterceptors;
    }

    /**
     * @param $interceptor
     *
     * @return mixed
     */
    protected function initializeInterceptor($interceptor)
    {
        return $interceptor;
    }
}
