<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;

class CUrlRule implements UrlRuleInterface {

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param \yii\web\Request $request the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request) {
        $pathInfo = $request->getPathInfo();
        //This rule only applies to paths that start with 'jobs'
        if (strpos($pathInfo, 'jobs') !== 0) {
            return false;
        }
        //controller/action that will handle the request
        $route = 'site/jobs';
        //parameters in the URL (category, subcategory, state, city, page)
        $params = [];
        $parameters = explode('/', $pathInfo);
        if (count($parameters) > 1) {
            $categoryParameters = explode(',', $parameters[1]);
            $params['category'] = $categoryParameters[0];
            $params['subcategory'] = count($categoryParameters) > 1 ? $categoryParameters[1] : '';
        }
        if (count($parameters) > 2) {
            //The page number can come after the category, subcategory information
            if (is_numeric($parameters[2])) {
                $params['page'] = (int) $parameters[2];
            } else {
                $locationParameters = explode(',', $parameters[2]);
                $params['state'] = $locationParameters[0];
                $params['city'] = count($locationParameters) > 1 ? $locationParameters[1] : '';
            }
        }
        //Or the page number can be last
        if (count($parameters) > 3) {
            $params['page'] = (int) $parameters[3];
        }
        if (count($parameters) > 4) {
            return false;
        }
        Yii::trace("Request parsed with URL rule: site/jobs", __METHOD__);
        return [$route, $params];
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params) {
        if ($route !== 'site/jobs') {
            return false;
        }
        //If a parameter is defined and not empty - add it to the URL
        $url = 'jobs/';
        if (array_key_exists('category', $params) && !empty($params['category'])) {
            $url .= $params['category'];
        }
        if (array_key_exists('subcategory', $params) && !empty($params['subcategory'])) {
            $url .= ',' . $params['subcategory'];
        }
        if (array_key_exists('state', $params) && !empty($params['state'])) {
            $url .= '/' . $params['state'];
        }
        if (array_key_exists('city', $params) && !empty($params['city'])) {
            $url .= ',' . $params['city'];
        }
        if (array_key_exists('page', $params) && !empty($params['page'])) {
            $url .= '/' . $params['page'];
        }
        return $url;
    }

}

?>