<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Get verify email token
     * @return string
     */
    public static function makeUUID(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Get per page count
     * @return int
     */
    public static function getPerPage(): int
    {
        return request()->input(Constant::PAGE_SIZE) ?: Constant::DEFAULT_PER_PAGE;
    }

    /**
     * Get page name
     * @return string
     */
    public static function getPageName(): string
    {
        return Constant::CURRENT_PAGE;
    }

    /**
     * Get current page
     * @return int
     */
    public static function getCurrentPage(): int
    {
        return request()->input(self::getPageName()) ?: 1;
    }

    /**
     * Custom collection paginate
     *
     * @param $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public static function customCollectionPaginate($items, int $perPage = 10, int $page = null, array $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
    }

    /**
     * Model class
     *
     * @param $className
     * @param false $toLower
     * @return string
     */
    public static function getClass($className, bool $toLower = false): string
    {
        $name = substr(strrchr($className, '\\'), 1);
        if ($toLower) {
            return strtolower($name);
        }
        return $name;
    }

    /**
     * Format phone number string
     * 1. Remove all non-numeric characters
     * 2. Replace 0 number at the beginning with 84
     *
     * @param string|null $phoneNumber
     * @return string|null
     */
    public static function formatPhoneNumberValue(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (!Str::startsWith($phoneNumber, '0') && strlen($phoneNumber) === 9) {
            $phoneNumber = '0' . $phoneNumber;
        }
        if (strlen($phoneNumber) >= 9 && !Str::startsWith($phoneNumber, '84')) {
            return preg_replace('/^(0)(\d*)/', '84$2', $phoneNumber);
        }

        if (strlen($phoneNumber) > 15) {
            return substr($phoneNumber, 0, 15);
        }

        return $phoneNumber;
    }

    /**
     * Function report interval
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return string
     */
    public static function reportInterval(Carbon $startDate, Carbon $endDate): string
    {
        $dayDiff = $endDate->diffInDays($startDate);
        $type = 'month';
        if ($dayDiff <= 1) {
            $type = 'hour';
        }
        else if ($dayDiff <= 31) {
            $type = 'day';
        }
        else if ($dayDiff < 90) {
            $type = 'week';
        }

        return $type;
    }

    public static function carbonParse($value)
    {
        return !empty($value) ? Carbon::parse($value)->timestamp: null;
    }

    /**
     * Check string is json
     *
     * @param $string
     * @return bool
     */
    public static function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Parse date string to Carbon object with specific format
     * Optional convert to timezone
     *
     * @param string $dateStr
     * @param string|null $format
     * @param string|null $timezone
     * @return Carbon|null
     */
    public static function parseStringToDate(string $dateStr, ?string $format = null, ?string $timezone = null): ?Carbon
    {
        if (empty($dateStr)) {
            return null;
        }

        // If timezone string is set, convert it to DateTimeZone object
        if (!empty($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        // If dateStr is numeric, we consider it as timestamp
        if (is_numeric($dateStr)) {
            $parsed = new \DateTime('now');
            $parsed->setTimestamp($dateStr);

            if (!empty($timezone)) {
                $parsed->setTimezone($timezone);
            }

            return Carbon::instance($parsed);
        }

        // If format is not set, we will auto detect the format from $dateStr
        if (empty($format)) {
            $format = self::autoDetectDateFormat($dateStr, 'Y-m-d H:i:s');
        }

        $parsed = null;

        if (!empty($format)) {
            $parsed = \DateTime::createFromFormat($format, $dateStr, $timezone);
        }

        if (!$parsed) {
            $ts = strtotime($dateStr);
            $parsed = new \DateTime('now');
            $parsed->setTimestamp($ts);

            if (!empty($timezone)) {
                $parsed->setTimezone($timezone);
            }
        }

        return (!$parsed ? null : Carbon::instance($parsed));
    }

    /**
     * Auto detect
     *
     * @param string $dateStr
     * @param string|null $defaultFormat
     * @return string
     */
    public static function autoDetectDateFormat(string $dateStr, ?string $defaultFormat = null): string
    {
        // check Day -> (0[1-9]|[1-2][0-9]|3[0-1])
        // check Month -> (0[1-9]|1[0-2])
        // check Year -> [0-9]{4} or \d{4}
        $patterns = [
            '/\b\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.\d{3,8}Z\b/' => 'Y-m-d\TH:i:s.u\Z', // format DATE ISO 8601
            '/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
            '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
            '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',

            '/\b\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\b/' => 'Y/d/m',
            '/\b\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y/m/d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\b/' => 'd/m/Y',
            '/\b(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\b/' => 'm/d/Y',

            '/\b\d{4}\.(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y.m.d',
            '/\b\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\b/' => 'Y.d.m',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.\d{4}\b/' => 'd.m.Y',
            '/\b(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\b/' => 'm.d.Y',

            // for 24-hour | hours seconds
            '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'H:i:s.u',
            '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'H:i:s',
            '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9]\b/' => 'H:i',

            // for 12-hour | hours seconds
            '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'h:i:s.u',
            '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'h:i:s',
            '/\b(?:1[012]|0[0-9]):[0-5][0-9]\b/' => 'h:i',

            '/\.\d{3}\b/' => '.v'
        ];

        $dateStr = preg_replace(array_keys($patterns), array_values($patterns), $dateStr);

        $format = preg_match('/\d/', $dateStr) ? $defaultFormat : $dateStr;

        // Replace AM/am/PM/pm to A/a
        if (!empty($format)) {
            $format = str_replace(['AM', 'PM', 'am', 'pm'], ['A', 'A', 'a', 'a'], $format);
        }

        return $format;
    }

    public static function orderField(?string $sortField, array $orderFieldList = ['id']): string
    {
        if (empty($sortField) || empty($orderFieldList) || !in_array($sortField, $orderFieldList)) {
            return 'id';
        }

        return $sortField;
    }

    public static function sortDirection(?string $sortDirection): string
    {
        if (empty($sortDirection) || !in_array($sortDirection, ['asc', 'desc'])) {
            return 'desc';
        }

        return $sortDirection;
    }

    public static function orderBy(?string $orderField, ?string $sortDirection, array $orderFieldList = ['id']): array
    {
        $orderField = self::orderField($orderField, $orderFieldList);
        $sortDirection = self::sortDirection($sortDirection);

        return [$orderField => $sortDirection];
    }

    public static function randomNumber($length): string
    {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    public static function getHostnameUrl(?string $url):? string
    {
        if (empty($url)) {
            return null;
        }

       return parse_url('http://' . str_replace(array('https://', 'http://'), '', $url), PHP_URL_HOST);
    }
}

