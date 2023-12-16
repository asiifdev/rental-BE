<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Browser;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use hisorange\BrowserDetect\Parser;
use Illuminate\Support\Facades\DB;

class WriteVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->WriteVisitor();
        return $next($request);
    }

    public function writeVisitor()
    {
        $url = request()->url();
        $ip_address = request()->ip();
        $path = request()->decodedPath();
        // Determine the user's device type is simple as this:
        $browser = new Parser(null, null, [
            'cache' => [
                'interval' => 86400 // This will override the default configuration.
            ]
        ]);
        $deviceType = $browser->deviceType();
        $date = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('Y_m_');
        $year = Carbon::now()->format('Y_');
        $latestData = Visitor::latest()->first() != NULL ? Visitor::latest()->first()->created_at : Carbon::now();
        $latestData = Carbon::parse($latestData)->format('Y_m_');
        $latestLog =  VisitorLog::latest()->first() != NULL ? VisitorLog::latest()->first()->created_at : Carbon::now();
        $latestLog = Carbon::parse($latestLog)->format('Y_');

        if($month != $latestData){
            DB::select("CREATE TABLE " . $month . "visitors" . " LIKE visitors");
            DB::select("INSERT " . $month . "visitors" . " SELECT * FROM visitors");
            DB::select("TRUNCATE visitors");
        }

        if($year != $latestLog){
            DB::select("CREATE TABLE " . $year . "visitor_logs" . " LIKE visitor_logs");
            DB::select("INSERT " . $year . "visitor_logs" . " SELECT * FROM visitor_logs");
            DB::select("TRUNCATE visitor_logs");
        }

        $result = $browser->detect()->toArray();
        $visitor = [
            'url' => $url,
            'ip_address' => $ip_address,
            'path' => $path,
            'userAgent' => $result['userAgent'],
            'browserName' => $result['browserName'],
            'browserVersion' => $result['browserVersion'],
            'browserFamily' => $result['browserFamily'],
            'platformName' => $result['platformName'],
            'platformVersion' => $result['platformVersion'],
            'platformFamily' => $result['platformFamily'],
            'isMobile' => $result['isMobile'] == 'true' ? true : false,
            'isTablet' => $result['isTablet'] == 'true' ? true : false,
            'isChrome' => $result['isChrome'] == 'true' ? true : false,
            'isDesktop' => $result['isDesktop'] == 'true' ? true : false,
            'isBot' => $result['isBot'] == 'true' ? true : false,
            'isFirefox' => $result['isFirefox'] == 'true' ? true : false,
            'isOpera' => $result['isOpera'] == 'true' ? true : false,
            'isSafari' => $result['isSafari'] == 'true' ? true : false,
            'isEdge' => $result['isEdge'] == 'true' ? true : false,
            'isInApp' => $result['isInApp'] == 'true' ? true : false,
            'isIE' => $result['isIE'] == 'true' ? true : false,
        ];

        $visitorLog = [
            'url' => $url,
            'path' => $path,
            'deviceType' => $deviceType,
            'date' => $date,
            'count' => 1
        ];

        $cekLog = VisitorLog::where('deviceType', $deviceType)->where('path', $path)->where('url', $url)->where('date', $date)->first();

        if($cekLog){
            $log = VisitorLog::find($cekLog->id);
            $visitorLog['count'] = 1 + $log->count;
            $log->update($visitorLog);
        }
        else{
            VisitorLog::create($visitorLog);
        }
        Visitor::create($visitor);
    }
}
