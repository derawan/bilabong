<?php
declare(strict_types=1);
namespace Irfansjah\Gtx\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class LogManagerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    const REGEX_DATE_PATTERN     = '\d{4}(-\d{2}){2}';
    const REGEX_TIME_PATTERN     = '\d{2}(:\d{2}){2}';
    const REGEX_DATETIME_PATTERN = self::REGEX_DATE_PATTERN.' '.self::REGEX_TIME_PATTERN;
    /**
     * Parsed data.
     *
     * @var array
     */
    protected static $parsed = [];

    public function GetChannels()
    {
        $default_channels =config("logging.channels")[config('logging.default')];
        $channels = config("logging.channels");
        $allowable_channels = collect($channels);
        return $allowable_channels;
    }

    public function Index() {
        return view("gtx::Admin.pages.manager-log");
    }

    public function GetAllowedChannel()
    {
        $allowable_channels = $this->GetChannels()->filter(function($value, $key){return (in_array($value["driver"],["single","daily"]));});
        return $allowable_channels;
    }

    public function GetLogChannel() {
        $channels = ($this->GetAllowedChannel())->all();
        $fs = new \Illuminate\Filesystem\Filesystem();
        $ps = collect($channels)->map(function($value, $key) use ($fs){
            $driver = $value["driver"];
            $extension = pathinfo($value["path"])["extension"];
            $filename = pathinfo($value["path"])["filename"];
            $pattern = "/";
            if ($driver == "single")
                $pattern = "/$filename.$extension";
            if ($driver == "daily")
                $pattern = "/$filename-*.$extension";
            $files = $fs->glob(pathinfo($value["path"])["dirname"].$pattern);
            $pk=[];
            if (count($files)>0)
            {
                $pk = collect($files)->map(function($val) use ($fs){
                    $info = pathinfo($val);
                    $info["size"] = $fs->size($val);
                    $info["sizex"] = $this->formatBytes($fs->size($val),4);
                    $info["path"] = str_replace(base_path()."/","",$info["dirname"]);
                    $info['b64'] = base64_encode($info["path"] . "/" . $info['basename']);
                    unset($info["dirname"]);
                    return $info;
                })->all();

            }
            $value["lpath"] = str_replace(base_path()."/","",$value["path"]);
            $value["files"] = $pk;
            unset($value['path']);
            return $value;
        });
        return response()->json($ps);
    }

    function formatBytes($bytes, $precision = 2) {
        $base = log($bytes, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    // Log Parser

    private static function parseRawData($raw)
    {
        $pattern = '/\['.self::REGEX_DATETIME_PATTERN.'\].*/';
        preg_match_all($pattern, $raw, $headings);
        $data    = preg_split($pattern, $raw);

        if ($data[0] < 1) {
            $trash = array_shift($data);
            unset($trash);
        }

        return [$headings, $data];
    }

    /**
     * Parse file content.
     *
     * @param  string  $raw
     *
     * @return array
     */
    public static function parse($raw)
    {
        static::$parsed          = [];
        list($headings, $data) = static::parseRawData($raw);

        // @codeCoverageIgnoreStart
        if ( ! is_array($headings)) {
            return static::$parsed;
        }
        // @codeCoverageIgnoreEnd

        foreach ($headings as $heading) {
            for ($i = 0, $j = count($heading); $i < $j; $i++) {
                static::populateEntries($heading, $data, $i);
            }
        };

        unset($headings, $data);

        return array_reverse(static::$parsed);
    }

    /**
     * Check if header has a log level.
     *
     * @param  string  $heading
     * @param  string  $level
     *
     * @return bool
     */
    private static function hasLogLevel($heading, $level)
    {
        return Str::contains($heading, strtoupper(".{$level}:"));
    }

    /**
     * Populate entries.
     *
     * @param  array  $heading
     * @param  array  $data
     * @param  int    $key
     */
    private static function populateEntries($heading, $data, $key)
    {
        $levels = ["All","Emergency","Alert","Critical","Error","Warning","Notice","Info","Debug"];
        foreach ($levels as $level) {
            if (static::hasLogLevel($heading[$key], $level)) {
                //static::extractDate($heading[$key]);
                static::$parsed[] = [
                    'level'    => $level,
                    'date'     => static::extractDate($heading[$key]),
                    'datetime' => static::extractDateTime($heading[$key]),
                    'channels' => static::extractChannels($heading[$key]),
                    'message'  => static::extractMessage($heading[$key]),
                    'header'   => $heading[$key],
                    'stack'    => $data[$key]
                ];
            }
        }
    }

     /**
     * Extract the date.
     *
     * @param  string  $string
     *
     * @return string
     */
    public static function extractDate(string $string): string
    {
        return preg_replace('/.*('.self::REGEX_DATE_PATTERN.').*/', '$1', $string);
    }

    public static function extractDateTime(string $string): string
    {
        return preg_replace('/.*('.self::REGEX_DATETIME_PATTERN.').*/', '$1', $string);
    }

    public static function extractChannels(string $string): string
    {
        $s1 = trim(str_replace("[".static::extractDateTime($string)."]", "",$string));
        $s2 = preg_split("/\./",$s1)[0];
        return $s2;
    }

    public static function extractMessage(string $string): string
    {
        $s1 = trim(str_replace("[".static::extractDateTime($string)."]", "",$string));
        $s2 = trim(substr($s1,strpos($s1,":")+1));
        return $s2;
    }

    public function GetLogFiles(Request $request)
    {
        $q = base64_decode($request->all('q')['q']);

        $logFile = base_path() ."/". $q; //storage_path('logs/laravel-2020-05-31.log');
        $reader = (file_get_contents($logFile));
        return response()->json(self::parse($reader));
    }

    public function DeleteLogFiles(Request $request)
    {
        $q = base64_decode($request->all('q')['q']);

        $logFile = base_path() ."/". $q; //storage_path('logs/laravel-2020-05-31.log');
        $reader = unlink($logFile);
        return response()->json(true);
    }

    public function DownloadLogFiles(Request $request)
    {
        $q = base64_decode($request->all('q')['q']);
        ;
        $content = file_get_contents(base_path() . "/" . $q);
        return  response()->streamDownload(function() use($content){
            echo $content;
        },pathinfo($q)["basename"]);
    }

}
