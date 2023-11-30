<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Spiral\RoadRunner;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\Request;
use Eva\Http\HttpProtocolVersionEnum;

include './vendor/autoload.php';

$roadRunnerWorker = RoadRunner\Worker::create();
$psrFactory = new Psr17Factory();
$worker = new RoadRunner\Http\PSR7Worker($roadRunnerWorker, $psrFactory, $psrFactory, $psrFactory);
$application = new App\Application();

function createFromRrRequest(ServerRequestInterface $rrRequest): RequestInterface
{
    $headers = $rrRequest->getHeaders();
    $headers = array_map(static fn(array $headerValueList) => $headerValueList[0], $headers);

    return new Request(
        HttpMethodsEnum::from($rrRequest->getMethod()),
        (string) $rrRequest->getUri(),
        $headers,
        $rrRequest->getBody()->getContents(),
        HttpProtocolVersionEnum::from($rrRequest->getProtocolVersion())
    );
}

while (true) {
    try {
        define("START_TIME", microtime(true));
        $serverRequest = $worker->waitRequest();

        if ($serverRequest === null) {
            break;
        }
    } catch (\Throwable $e) {
        $worker->respond(new Response(400));
        continue;
    }

    try {
        $request = createFromRrRequest($serverRequest);
        $response = $application->handle($request);
        $worker->respond(new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion()->value,
        ));
        $application->terminate($request, $response);
    } catch (\Throwable $e) {
        $worker->getWorker()->error((string) $e);
    }
}

