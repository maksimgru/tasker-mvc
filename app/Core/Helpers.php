<?php

namespace App\Core;

class Helpers
{
    /*
     * @param mixed  $data
     * @param string $type
     *
     * @return int or string
     */
    public static function clean($data, $type = 'str')
    {
        switch ($type) {
            case 'str':
                return nl2br(htmlspecialchars(stripslashes(trim(strip_tags($data)))));
                break;
            case 'int':
                return (int) $data;
            default:
                return nl2br(htmlspecialchars(stripslashes(trim(strip_tags($data)))));
                break;
        }
    }

    /*
    * @param string $path The rel-path to the assets
    *
    * @return string $uri The full uri to the assets
    */
    public static function asset($path = ''): string
    {
        return BASE_PATH . '/' . BASE_ASSET_PATH . '/' . $path;
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    public static function path(
        string $path = '',
        array $params = []
    ): string
    {
        $queryString = self::buildQueryString($params);
        $queryString = $queryString ? '?' . $queryString : '';

        return rtrim(BASE_PATH, '/') . '/' . rtrim($path, '/') . $queryString;
    }

    /**
     * @return array $parseUri
     *
     */
    public static function parseURI(): array
    {
        $parseUri = [] ;
        if (isset($_GET['uri'])) {
            $parseUri = explode(
                '/',
                filter_var(
                    rtrim(self::clean($_GET['uri']), '/'),
                    FILTER_SANITIZE_URL
                )
            );
        }

        return $parseUri;
    }

    /**
     * @return string $queryString
     */
    public static function getQueryString(): string
    {
        $queryString = filter_var(
            rtrim($_SERVER['QUERY_STRING'], '/'),
            FILTER_SANITIZE_URL
        );
        $queryString = explode('&', $queryString);

        unset($queryString[0]);

        $queryString = implode('&', $queryString);

        return $queryString;
    }

    /**
     * @return array $parsedData
     */
    public static function parseQueryString(): array
    {
        $parsedData = [];
        $queryString = self::getQueryString();
        $splitData = $queryString ? explode('&', $queryString) : [];

        foreach ($splitData as $pair) {
            [$key, $value] = explode('=', $pair);
            $parsedData[$key] = $value;
        }

        return $parsedData;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public static function buildQueryString(array $data = []): string
    {
        $string = '';
        foreach ($data as $key => $val) {
            $string .= $key . '=' . $val . '&';
        }

        return rtrim($string, '&');
    }

    /**
     * @param int $totalItems
     * @param int $countItems
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
     public static function calculatePaginationMeta(
        int $totalItems = 0,
        int $countItems = 0,
        int $page = 1,
        int $limit = PER_PAGE
    ): array {
        $totalItems = $totalItems < 0 ? 0 : $totalItems;
        $countItems = $countItems < 0 ? 0 : $countItems;
        $page = $page < 1 ? 1 : $page;
        $limit = $limit < 0 ? PER_PAGE : $limit;

        $total = $totalItems > 0 && $limit > 0
            ? ceil($totalItems / $limit)
            : 0
        ;

        return [
            'previous'   => $total > 0 ? $page - 1 : 0,
            'next'       => $page >= $total ? 0 : $page + 1,
            'current'    => $page,
            'limit'      => $limit,
            'total'      => $total,
            'countItems' => $countItems,
            'totalItems' => $totalItems,
        ];
    }

    /**
     * @return string $current_uri
     */
    public static function getCurrentURI(): string
    {
        $parsedUri = self::parseURI();

        return $parsedUri ? implode('/', $parsedUri) : '';
    }

    /**
     * @param string $path
     *
     * @return boolean $bool
     */
    public static function isCurrentURI($path = ''): bool
    {
        $currentUri = self::getCurrentURI();
        $strPos = ($path !== '') ? strpos($currentUri, $path, 0) : -1;

        return $strPos === 0 || $path === $currentUri;
    }

    /**
     * @param string $method
     *
     * @return boolean $bool
     */
    public static function isRequestMethod($method = 'POST'): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }

    /**
     * @return boolean $bool
     */
    public static function isAdminAuth(): bool
    {
        return isset($_SESSION['admin_auth']) && $_SESSION['admin_auth'];
    }

    /**
     * @return boolean
     */
    public static function adminAuth(): bool
    {
        return $_SESSION['admin_auth'] = true;
    }

    /**
     * @return void
     */
    public static function adminLogout()
    {
        unset($_SESSION['admin_auth']);
        //header('Location: ' . Helpers::path('users/table'));
        //exit;
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public static function redirectTo(string $path = '')
    {
        header('Location: ' . self::path($path));
        exit;
    }
}
