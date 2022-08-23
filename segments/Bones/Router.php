<?php

namespace Bones;

use Barriers\System\PreventCSRFToken;
use Bones\Str;
use Bones\Request;
use Bones\Session;
use Bones\Commander;
use Closure;
use Jolly\Engine;
use JollyException\RouteException;
use JollyException\BadMethodException;

class Router
{
    protected static $routes;
    protected static $current;
    protected static $parent;
    protected static $parentPrefixSet;
    protected static $parentSettingSet;
    private static $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null)
            self::$_instance = new self;
        return self::$_instance;
    }

    public static function __callStatic(string $method, array $arguments)
    {
        if (in_array(strtoupper(strtolower($method)), ['ANY', 'GET', 'PUT', 'POST', 'DELETE', 'PATCH', 'UPDATE', 'OPTIONS'])) {
            return self::init($arguments[0], strtolower($method), $arguments[1]);
        }

        throw new BadMethodException('Request Method [' . strtoupper(strtolower($method)) . '] not supported for route ' . $arguments[0], 404);
    }

    public static function init($route, string $method, $callback)
    {
        $parentSettings = [];
        if (!empty(self::$parent)) {
            $parent = self::bunchAttrs(self::$parent);
            $parentPrefix = (!empty($parent[0])) ? $parent[0] : '';
            $route = $parentPrefix . $route;
            $parentSettings = (!empty($parent[1])) ? $parent[1] : [];
        }
        if (gettype($route) === 'string') {
            $route = trim($route, '/');
            self::register($route, $method, $callback, $parentSettings);
        } else if (gettype($route) === 'array') {
            foreach ($route as $subRoute) {
                $subRoute = trim($subRoute, '/');
                self::register($subRoute, $method, $callback, $parentSettings);
            }
        }
        self::$current = $route;
        return self::getInstance();
    }

    public static function bunch(string $prefix, array $settings = [], $callback = null)
    {
        if (empty(self::$parent)) self::$parent = [];
        self::$parent[] = [$prefix, $settings];
        call_user_func($callback);
        array_pop(self::$parent);
    }
    
    public static function bunchAttrs($parentAttrs)
    {
        $prefixSet = '';
        $settingSet = [];

        foreach ($parentAttrs as $parentAttr) {
            if (!empty($parentAttr[0]) && gettype($parentAttr[0]) === 'string')
                $prefixSet .= $parentAttr[0];

            if (!empty($parentAttr[1]) && gettype($parentAttr[1]) === 'array') {
                if (!empty($parentAttr[1]['barrier']) && gettype($parentAttr[1]['barrier']) === 'array') {
                    if (!empty($settingSet['barrier'])) {
                        array_push($settingSet['barrier'], $parentAttr[1]['barrier']);
                    } else {
                        $settingSet['barrier'] = $parentAttr[1]['barrier'];
                    }
                }

                if (!empty($parentAttr[1]['as']) && gettype($parentAttr[1]['as']) === 'string') {
                    if (!empty($settingSet['as'])) {
                        $settingSet['as'] .= $parentAttr[1]['as'];
                    } else {
                        $settingSet['as'] = $parentAttr[1]['as'];
                    }
                }

            }
        }

        return [$prefixSet, $settingSet];
    }

    public static function register($route, string $method, $callback, array $parentSettings = [])
    {
        $route = trim($route, '/');
        if (empty($method)) $method = 'ANY';
        self::$routes[$route]['caption'] = $route;
        self::$routes[$route]['method'] = strtolower($method);
        self::$routes[$route]['callback'] = $callback;
        if (!empty($parentSettings)) {
            if (!empty($parentSettings['barrier'])) {
                self::barrier($parentSettings['barrier'], $route);
            }
            if (!empty($parentSettings['as'])) {
                self::name($parentSettings['as'], $route, true);
            }
            if (!empty($parentSettings['response'])) {
                self::response($parentSettings['response'], $route);
            }
        }
    }

    public static function dispatch($route)
    {
        if (!self::__validateGlobalChecks()) {
            return false;
        }
        
        $route = trim($route, '/');
        Session::appendSetReserved('latest_routes', $route);
        self::clearSavedRoutes();
        if (empty(self::$routes[$route])) {
            if (!empty($matchedRoute = self::checkRoutePatternMatch($route))) {
                if (empty($matchedRoute['route']))
                    return self::setError(404);

                $callback = self::$routes[$matchedRoute['route']]['callback'];
                if (!self::validateRequest(self::$routes[$matchedRoute['route']])) {
                    return self::setError(402);
                }
                
                if (!empty($matchedRoute['optionalParams'])) {
                    $callbackParams = array_values($matchedRoute['optionalParams']);
                    if (gettype($callback) == 'object') {
                        $response = call_user_func_array($callback, array_merge([new Request($_REQUEST, $_FILES, self::$routes[$matchedRoute['route']])], $callbackParams));
                        self::serve($response, self::$routes[$matchedRoute['route']]);
                    } else if (gettype($callback) == 'array') {
                        $invokableCallbackParams = $callback;
                        $classToInvoke = $invokableCallbackParams[0];
                        $methodToInvoke = $invokableCallbackParams[1];
                        if (!class_exists($classToInvoke))
                            throw new RouteException($classToInvoke . ' not found in route file', 404);
                        if (!method_exists($classToInvoke, $methodToInvoke))
                            throw new RouteException($methodToInvoke . ' not found in ' . $classToInvoke, 404);
                        $response = self::verifyBarriers(self::$routes[$matchedRoute['route']], [
                            $classToInvoke,
                            $methodToInvoke,
                            $callbackParams
                        ]);
                        self::serve($response, self::$routes[$matchedRoute['route']]);
                    } else {
                        op($callback);
                    }
                }
            } else {
                return self::setError(404);
            }
        } else {
            $callback = self::$routes[$route]['callback'];
            if (!self::validateRequest(self::$routes[$route])) {
                return self::setError(402);
            }
            if ($callback instanceof Closure) {
                $response = call_user_func($callback, new Request($_REQUEST, $_FILES, self::$routes[$route]));
                self::serve($response, self::$routes[$route]);
            } else if (gettype($callback) == 'array') {
                $callbackParams = $callback;
                $classToInvoke = $callbackParams[0];
                $methodToInvoke = $callbackParams[1];
                if (!class_exists($classToInvoke))
                    throw new RouteException($classToInvoke . ' not found in route file', 404);
                if (!method_exists($classToInvoke, $methodToInvoke))
                    throw new RouteException($methodToInvoke . ' not found in ' . $classToInvoke, 404);
                $response = self::verifyBarriers(self::$routes[$route], [
                    $classToInvoke,
                    $methodToInvoke,
                    []
                ]);
                self::serve($response, self::$routes[$route]);
            } else {
                op($callback);
            }
        }
    }

    public static function validateRequest(array $route = [])
    {
        if ($route['method'] == 'any') return true;
        $isValid = true;
        if (!empty($route['method'])) {
            $method = strtoupper($route['method']);
            if ($method !== strtoupper($_SERVER['REQUEST_METHOD'])) {
                $isValid = false;
            }

            if (!in_array($method, ['GET', 'POST'])) {
                if (!empty($_REQUEST['_method']) && $method == strtoupper($_REQUEST['_method'])) {
                    $isValid = true;
                }
            }

            if (!in_array($method, ['GET'])) {
                $preventCSRFTokenBarrierClass = PreventCSRFToken::class;
                if (class_exists($preventCSRFTokenBarrierClass)) {
                    $preventCSRFTokenBarrier = new $preventCSRFTokenBarrierClass();

                    // Skip defined $excludeRoutes from csrf-token check
                    $skipCSRFCheck = false;
                    if (isset($preventCSRFTokenBarrier->excludeRoutes) && !empty($excludeRoutes = $preventCSRFTokenBarrier->excludeRoutes)) {
                        foreach ($excludeRoutes as $excludedRoute) {
                            if (!$skipCSRFCheck && request()->matchesTo($excludedRoute)) {
                                $skipCSRFCheck = true;
                            }
                        }
                    }

                    if (!$skipCSRFCheck && !$preventCSRFTokenBarrier->check(request())) {
                        throw new RouteException('Unauthenticated: request denied by ' . $preventCSRFTokenBarrierClass . ' check', 402);
                    }
                } else {
                    throw new RouteException('Unauthenticated: Barriers\System\PreventCSRFToken must exist with check method to prevent CSRF attack', 402);
                }
            }

        }
        
        return $isValid;
    }

    public static function verifyBarriers(array $route = [], array $closureParams = [])
    {
        if (!empty($route)) {
            if (!empty($route['barriers'])) {
                foreach ($route['barriers'] as $barrier) {
                    if (empty($barrier['name'])) {
                        throw new RouteException('Empty barrier found for route ' . $route['caption']);
                    }

                    if (class_exists($barrier['name'])) {
                        $barrierClass = $barrier['name'];
                    } else {
                        if ((Str::startsWith($barrier['name'], 'Barrier') && Str::endsWith($barrier['name'], 'Barrier')))
                            $barrierClass = $barrier['name'];
                        else if(Str::startsWith($barrier['name'], 'Barrier'))
                            $barrierClass = $barrier['name'];
                        else
                            $barrierClass = '\\Barriers\\' . $barrier['name'];
                    }

                    if (!class_exists($barrierClass))
                        $barrierClass = self::findBarrierByName($barrier['name']);

                    if (class_exists($barrierClass)) {
                        if (!method_exists($barrierClass, 'check')) {
                            throw new BadMethodException('Method not found: check() method must present in ' . $barrierClass, 404);
                        }

                        $barrierObj = new $barrierClass();

                        // Skip defined $excludeRoutes from csrf-token check
                        $skipBarrierCheck = false;
                        if (isset($barrierObj->excludeRoutes) && !empty($excludeRoutes = $barrierObj->excludeRoutes)) {
                            foreach ($excludeRoutes as $excludedRoute) {
                                if (!$skipBarrierCheck && request()->matchesTo($excludedRoute)) {
                                    $skipBarrierCheck = true;
                                }
                            }
                        }

                        if (!$skipBarrierCheck && !$barrierObj->check(request())) {
                            throw new RouteException('Unauthenticated: request denied by ' . $barrierClass . ' check', 402);
                        }
                    } else {
                        throw new RouteException(((!empty($barrierClass)) ? $barrierClass : $barrier['name']) . ' barrier not found');
                    }
                }
            }
        }
        return call_user_func_array([(new $closureParams[0]()), $closureParams[1]], array_merge([(new Request($_REQUEST, $_FILES, $route))], $closureParams[2]));
    }

    public static function findBarrierByName($barrier)
    {
        if (class_exists($barrier)) {
            return $barrier;
        }

        $aliases = Str::array_change_key_case_recursive(setting('aliases', []), CASE_LOWER);

        if (empty($aliases) || empty($aliases['barriers'])) {
            return null;
        }

        return $aliases['barriers'][$barrier];
    }

    public static function checkRoutePatternMatch($pageRoute)
    {
        if (empty(self::$routes)) {
            throw new RouteException('No routes defined for the application');
        }

        $pageRouteSegments = explode('/', $pageRoute);
        $pageRouteSegments = array_map(function ($pageRouteSegment) {
            return urldecode($pageRouteSegment);
        }, $pageRouteSegments);
        $routeNames = array_keys(self::$routes);
        $matchedRoute = null;
        $optionalParams = [];
        foreach ($routeNames as $route) {
            if (empty($matchedRoute) && Str::contains($route, '{') && Str::contains($route, '}')) {
                $routeSegments = explode('/', $route);
                $routeSegmentsPresence = [];
                $requiredSegmentsCount = 0;
                $optionalSegmentsCount = 0;
                foreach ($routeSegments as $segmentIndex => $segment) {
                    $routeSegmentsPresence[$segment] = self::getRouteSegmentPresenseAttrs($segment);
                    if ($routeSegmentsPresence[$segment]['type'] == 'required') {
                        $requiredSegmentsCount++;
                    } else {
                        $optionalSegmentsCount++;
                    }
                }

                self::$routes[$route]['syntax'] = $routeSegmentsPresence;

                // self::debugRoutePatterns([self::$routes[$route], $pageRouteSegments, $routeSegmentsPresence, $routeSegments], true);

                if (count($pageRouteSegments) >= $requiredSegmentsCount && count($pageRouteSegments) <= ($requiredSegmentsCount + $optionalSegmentsCount)) {
                    $rsAttendanceIndex = 0;
                    $isMatched = true;
                    foreach ($routeSegmentsPresence as $rsAttendanceName => $rsAttendance) {
                        if ((string) $rsAttendance['mode'] == 'static' && (string) $rsAttendance['type'] == 'required' && (empty($pageRouteSegments[$rsAttendanceIndex]) || $pageRouteSegments[$rsAttendanceIndex] != $rsAttendanceName)) {
                            $isMatched = false;
                            break;
                        }

                        if ((string) $rsAttendance['mode'] != 'static') {
                            $byPassWhereChecks = false;
                            if ((string) $rsAttendance['type'] == 'optional' && empty($pageRouteSegments[$rsAttendanceIndex])) {
                                $byPassWhereChecks = true;
                            }
                            if (!$byPassWhereChecks && !self::verifySegmentWhereChecks($route, $rsAttendanceName, (!empty($pageRouteSegments[$rsAttendanceIndex])) ? $pageRouteSegments[$rsAttendanceIndex] : '')) {
                                throw new RouteException('Status 402: route ' . $route . ' does not match regex required for ' . $rsAttendanceName . ' segment');
                            }
                            $optionalParams[$rsAttendanceName] = (!empty($pageRouteSegments[$rsAttendanceIndex])) ? $pageRouteSegments[$rsAttendanceIndex] : '';
                        }

                        $rsAttendanceIndex++;
                    }
                    if ($isMatched) {
                        $matchedRoute = $route;
                        $callback = self::$routes[$matchedRoute]['callback'];
                        if ($callback instanceof Closure || $callback instanceof String) {
                            $closureInfo = new \ReflectionFunction($callback);
                            $optionalParams = self::bindParamsImplicitly($matchedRoute, $closureInfo, $optionalParams);
                        } else if (is_array($callback)) {
                            $classToInvoke = $callback[0];
                            $methodToInvoke = $callback[1];
                            $classReflectionInfo = new \ReflectionMethod($classToInvoke, $methodToInvoke);
                            $optionalParams = self::bindParamsImplicitly($matchedRoute, $classReflectionInfo, $optionalParams);
                        }
                    }
                }
            }
        }

        return [
            'route' => $matchedRoute,
            'optionalParams' => $optionalParams
        ];
    }

    public static function bindParamsImplicitly(string $matchedRoute, $closure, $optionalParams = [])
    {
        $optionalParamsClone = array_values($optionalParams);
        $paramCount = 0;
        foreach ($closure->getParameters() as $param) {
            if ($paramCount >= count($optionalParamsClone)) {
                throw new RouteException('Invalid Route Syntax: `' . self::$routes[$matchedRoute]['caption'] . '`. Dynamic parameters count [' . count($optionalParamsClone) . '] must be same with associated closure');
            }
            if ($param->hasType()) {
                $paramClass = (phpversion() >= 8) ? $param->getType() : $param->getClass()->name;
                if (!Str::contains($paramClass, 'Bones\Request')) {
                    $bindModelImplicitly = (string) $paramClass;
                    $modelObj = (new $bindModelImplicitly);
                    $columnToBind = (property_exists($modelObj, 'route_bind_column')) ? self::accessProtected($modelObj, 'route_bind_column') : (self::accessProtected($modelObj, 'primary_key'));
                    $columnValueToCompare = $optionalParamsClone[$paramCount];
                    $optionalParamsClone[$paramCount] = $modelObj->where($columnToBind, $columnValueToCompare)->first();
                    if (empty($optionalParamsClone[$paramCount])) {
                        throw new RouteException('No data found for {' . $columnValueToCompare . '} as {' . $columnToBind . '} [route_bind_column] in {' . $bindModelImplicitly . '} while implicit binding');
                    }
                    $paramCount++;
                }
            }
        }

        if (!empty($optionalParamsClone) && (count($optionalParams) == count($optionalParamsClone))) {
            $optionalParamCount = 0;
            foreach ($optionalParams as &$optionalParam) {
                $optionalParam = $optionalParamsClone[$optionalParamCount];
                $optionalParamCount++;
            }
        }

        return $optionalParams;
    }

    public static function accessProtected($obj, $prop) {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
      }

    public static function getRouteSegmentPresenseAttrs($segment)
    {
        if (empty($segment)) {
            return [
                'mode' => 'static',
                'type' => 'required'
            ];
        }

        if (Str::startsWith($segment, '{') && Str::endsWith($segment, '}') && !Str::startsWith($segment, '{?')) {
            return [
                'mode' => 'dynamic',
                'type' => 'required'
            ];
        } else if (Str::startsWith($segment, '{?') && Str::endsWith($segment, '}')) {
            return [
                'mode' => 'dynamic',
                'type' => 'optional'
            ];
        } else {
            return [
                'mode' => 'static',
                'type' => 'required'
            ];
        }
    }

    public static function verifySegmentWhereChecks(string $route, string $segmentKey, string $segmentValue)
    {
        if (empty(self::$routes[$route]['routeParamChecks'])) return true;
        $whereChecks = self::$routes[$route]['routeParamChecks'];
        $segmentKey = Str::multiReplace($segmentKey, ['{?', '{', '}'], ['', '', '']);
        $hasPassed = true;
        foreach ($whereChecks as $where) {
            if ($where['name'] == $segmentKey) {
                $where['regex'] = '/' . $where['regex'] . '/';
                try {
                    if (!preg_match($where['regex'], $segmentValue)) {
                        return false;
                    }
                } catch (\Throwable $e) {
                    throw new RouteException('Invalid regex found for route ' . $route . ' for segment ' . $segmentKey);
                }
            }
        }
        return $hasPassed;
    }

    public static function name(string $name, $route = null, bool $nameFromParent = false)
    {
        $route = (!empty($route)) ? $route : self::$current;

        if (is_array($route)) {
            foreach ($route as $route) {
                $route = trim($route, '/');
                self::$routes[$route]['namedAs'] = (!empty(self::$routes[$route]['namedAs'])) ? self::$routes[$route]['namedAs'] . $name : $name;
                self::$routes[$route]['nameFromParent'] = $nameFromParent;
            }
        } else {
            self::$routes[$route]['namedAs'] = (!empty(self::$routes[$route]['namedAs'])) ? self::$routes[$route]['namedAs'] . $name : $name;
            self::$routes[$route]['nameFromParent'] = $nameFromParent;
        }
        return self::getInstance();
    }

    public static function where(string $param, string $regex = '')
    {
        if (is_array(self::$current)) {
            foreach (self::$current as $route) {
                $route = trim($route, '/');
                self::$routes[$route]['routeParamChecks'][] = ['name' => $param, 'regex' => $regex];
            }
        } else {
            self::$routes[self::$current]['routeParamChecks'][] = ['name' => $param, 'regex' => $regex];
        }
        return self::getInstance();
    }

    public static function barrier($barriers, $route = null)
    {
        $route = (!empty($route)) ? $route : self::$current;
        if (is_array($route)) {
            foreach ($route as $route) {
                $route = trim($route, '/');
                if (gettype($barriers) == 'string') {
                    $barriers = explode(',', $barriers);
                }
                self::setBarriers($route, $barriers);
            }
        } else {
            if (gettype($barriers) == 'string') {
                $barriers = explode(',', $barriers);
            }
            self::setBarriers($route, $barriers);
        }
        return self::getInstance();
    }

    public static function withoutBarrier($barriers, $route = null)
    {
        $route = (!empty($route)) ? $route : self::$current;
        if (is_array($route)) {
            foreach ($route as $route) {
                $route = trim($route, '/');
                if (gettype($barriers) == 'string') {
                    $barriers = explode(',', $barriers);
                }
                self::removeBarriers($route, $barriers);
            }
        } else {
            if (gettype($barriers) == 'string') {
                $barriers = explode(',', $barriers);
            }
            self::removeBarriers($route, $barriers);
        }
        return self::getInstance();
    }

    public static function setBarriers(string $route, array $barriers = [])
    {
        if (!is_array($barriers)) return self::$routes[$route];
        foreach ($barriers as $barrier) {
            if (is_array($barrier)) {
                self::setBarriers($route, $barrier);
            } else {
                $barrier = trim($barrier);
                if (!empty($barrierAlias = self::findBarrierByName($barrier))) {
                    $barrier = $barrierAlias;
                }
                self::$routes[$route]['barriers'][] = ['name' => $barrier];
            }
        }
        return self::$routes[$route];
    }

    public static function removeBarriers(string $route, array $barriers = [])
    {
        if (!is_array($barriers)) return self::$routes[$route];

        if (empty(self::$routes[$route]['barriers'])) return self::$routes[$route];

        foreach ($barriers as $barrierIndex => $barrier) {
            $barrier = trim($barrier);
            if (!empty($barrierAlias = self::findBarrierByName($barrier))) {
                $barriers[$barrierIndex] = $barrierAlias;
            }
        }

        foreach (self::$routes[$route]['barriers'] as $barrierIndex => $barrier) {
            foreach ($barrier as $barrierName) {
                if (in_array($barrierName, $barriers)) {   
                    unset(self::$routes[$route]['barriers'][$barrierIndex]);
                }
            }
        }
        return self::$routes[$route];
    }

    public static function response(string $response, $route = null)
    {
        $route = (!empty($route)) ? $route : self::$current;
        self::setResponse($route, $response);
        return self::getInstance();
    }

    public static function setResponse(string $route, string $response = '')
    {
        self::$routes[$route]['response'] = $response;
        return self::$routes[$route];
    }

    public static function serveAs($route = null)
    {
        if (!empty($route) && !empty($route['response']))
            return $route['response'];
        return '';
    }

    public static function serve($content, $route = null)
    {
        response()->format(self::serveAs($route));

        if (is_string($content)) {
            echo $content;
        } else {
            if (is_array($content)) {
                if (empty($response) || $response == 'application/json') {
                    response()->format(self::serveAs($route));
                    echo json_encode($content);
                }
            } else if (is_object($content)) {
                echo json_encode($content);
            }
        }
    }

    /** Debug route patterns logs 
     * 
     * @param array $logsOf
     * @param bool $stopExecution to print and die
     * 
     * return print $logsOf
     */
    public static function debugRoutePatterns(array $logsOf = [], $stopExecution = false)
    {
        if ($stopExecution)
            opd($logsOf);
        else
            op($logsOf);
    }

    public static function setError($status = 404, string $customMsg = '')
    {
        switch ($status) {
            case 404:
                return render(setting('templates.404', 'defaults/404'), [
                    'error' => 'Page not found.' . $customMsg
                ]);
                break;
            case 402:
                return Engine::error([
                    'error' => '402 - Unathenticated'
                ]);
                break;
        }
        throw new RouteException('Error code ' . $status . ' returned');
    }

    public static function clearSavedRoutes()
    {
        $cachedRoutes = Session::getReserved('latest_routes');
        if (!empty($cachedRoutes) && is_array($cachedRoutes)) {
            krsort($cachedRoutes);
            $cachedRoutes = array_unique($cachedRoutes);
            $latest_routes = [];
            $cachedRoutesCount = 1;
            foreach ($cachedRoutes as $cachedRoute) {
                if ($cachedRoutesCount > 2)
                    break;
                $latest_routes[]  = $cachedRoute;
                $cachedRoutesCount++;
            }
            Session::setReserved('latest_routes', $latest_routes);
        }
        return true;
    }

    public static function prevRoute()
    {
        $cachedRoutes = Session::getReserved('latest_routes');
        return (!empty($cachedRoutes) && is_array($cachedRoutes) && count($cachedRoutes) > 1) ? url($cachedRoutes[1]) : url('/');
    }

    public static function currentRoute()
    {
        $cachedRoutes = Session::getReserved('latest_routes');
        return (!empty($cachedRoutes) && is_array($cachedRoutes) && count($cachedRoutes) >= 1) ? url($cachedRoutes[0]) : url('/');
    }

    public static function list()
    {
        return self::$routes;
    }

    public static function url(string $path)
    {
        $url = setting('app.base_url') .'/';
        if (!empty($subDir = setting('app.sub_dir', ''))) {
            $url .= $subDir . '/';
        }
        return $url . $path;
    }

    public static function exists(string $routeToCheck, $return = false)
    {
        $isExists = false;
        foreach (self::$routes as $routePattern => $route) {
            if (isset($route['nameFromParent']) && !$route['nameFromParent'] && !empty($route['namedAs']) && $route['namedAs'] == $routeToCheck) {
                if ($return) {
                    return [
                        'info' => $route,
                        'pattern' => $routePattern
                    ];
                }
                $isExists = true;
                break;
            }
        }
        return $isExists;
    }

    public static function find(string $route)
    {
        return self::exists($route, true);
    }

    public static function prepare(string $route, array $segmentValues = [])
    {
        if (self::exists($route)) {
            $routeInfo = self::find($route);
            if (!empty($routeInfo['pattern'])) {
                $routeSyntax = explode('/', $routeInfo['pattern']);
                $finalRouteBlocks = [];
                
                if (!empty($segmentValues) && !empty($segmentValues[0]) && !is_array($segmentValues[0])) {
                    $dynamicSegmentCount = 0;
                    foreach ($routeSyntax as $segmentCount => $segment) {
                        if (Str::startsWith($segment, '{') && Str::endsWith($segment, '}')) {
                            if (isset($segmentValues[$dynamicSegmentCount])) {
                                $finalRouteBlocks[$segment] = $segmentValues[$dynamicSegmentCount];
                                $dynamicSegmentCount++;
                            }
                        } else {
                            $finalRouteBlocks[$segment] = $segment;
                        }
                    }       
                } else {
                    foreach ($routeSyntax as $segment) {
                        if (Str::startsWith($segment, '{') && !Str::startsWith($segment, '{?') && Str::endsWith($segment, '}')) {
                            $segmentMustPresent = Str::removeWords($segment, ['{', '}']);
                            if (!array_key_exists($segmentMustPresent, $segmentValues)) {
                                throw new RouteException('{' . $segmentMustPresent . '} must present in route ' . $routeInfo['pattern']);
                            } else {
                                $finalRouteBlocks[$segment] = $segmentValues[$segmentMustPresent];
                            }
                        } else if (Str::startsWith($segment, '{?') && Str::endsWith($segment, '}')) {
                            $segmentOptional = Str::removeWords($segment, ['{?', '}']);
                            if (array_key_exists($segmentOptional, $segmentValues)) {
                                $finalRouteBlocks[$segment] = $segmentValues[$segmentOptional];
                            }
                        } else {
                            $finalRouteBlocks[$segment] = $segment;
                        }
                    }
                }

                return url(implode('/', $finalRouteBlocks));
            } else {
                return url($routeInfo['pattern']);
            }
        } else {
            throw new RouteException('404: Route ' . $route . ' not found');
        }
    }

    public static function __validateGlobalChecks()
    {
        $proceedToNext = true;
        if (file_exists('locker/system/stop')) {
            render(setting('templates.503', 'defaults/503'), [
                'stop_msg' => file_get_contents((new Commander())->appStopperFile)
            ]);
            return false;
        }

        return $proceedToNext;
    }

}