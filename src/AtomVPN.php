<?php

namespace Cr4sec\AtomVPN;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Cr4sec\AtomVPN\Exceptions\AtomVpnException;
use Cr4sec\AtomVPN\Exceptions\InvalidAccessTokenException;
use Psr\SimpleCache\InvalidArgumentException;

class AtomVPN
{
    const BASE_URI = 'https://atomapi.com';

    /**
     * @param  string  $action
     * @param  string  $path
     * @param  array  $payload
     * @param  array  $headers
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    private function sendRequest(string $action, string $path, array $payload = [], array $headers = []): Response
    {
        $response = Http::withHeaders($headers)->$action(self::BASE_URI . $path, $payload);

        $header = $response->collect('header');

        try {
            if (isset(AtomVpnException::CODES[$header['code']])) {
                $exceptionClass = AtomVpnException::CODES[$header['response_code']];

                throw new $exceptionClass($header['message'], $header['response_code']);
            }

            if ($header['response_code'] !== 1) {
                throw new AtomVpnException($header['message'], $header['response_code']);
            }
        } catch (InvalidAccessTokenException $exception) {
            Cache::store('file')->delete('atom_access_token');
            $this->getAccessToken();

            return $this->sendRequest($action, $path, $payload, $this->getAuthorizeHeaders());
        }

        return $response;
    }

    private function getAuthorizeHeaders(): array
    {
        return [
            'X-AccessToken' => $this->getAccessToken()
        ];
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getAccessToken(): string
    {
        if (!Cache::store('file')->has('atom_access_token')) {
            $response = Http::post(self::BASE_URI . '/auth/v1/accessToken', [
                'secretKey' => config('atom_vpn.secret_key'),
                'grantType' => 'secret'
            ])->json('body');

            Cache::store('file')->put('atom_access_token', $response['accessToken']);
        }

        return Cache::store('file')->get('atom_access_token');
    }

    /**
     * @return int
     */
    public function getResellerId(): int
    {
        return +config('atom_vpn.reseller_id');
    }

    /**
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function getSubscribedServices(): Response
    {
        return $this->sendRequest(
            'get',
            '/inventory/v1/getResellerInventory',
            ['iId' => $this->getResellerId()],
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $deviceType
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function getProtocols(string $deviceType): Response
    {
        return $this->sendRequest(
            'get',
            '/inventory/v1/getProtocols/' . $this->getResellerId() . '/' . $deviceType,
            [],
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function getCountries(): Response
    {
        return $this->sendRequest(
            'get',
            "/inventory/v1/getCountries/" . $this->getResellerId(),
            [],
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $deviceType
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function getCountriesByDeviceType(string $deviceType): Response
    {
        return $this->sendRequest(
            'get',
            "/inventory/v2/countries/" . $deviceType,
            [],
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  array  $payload
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function createAccount(array $payload): Response
    {
        return $this->sendRequest(
            'post',
            '/vam/v1/create',
            $payload,
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $vpnUsername
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function deleteAccount(string $vpnUsername): Response
    {
        return $this->sendRequest(
            'post',
            '/vam/v1/delete',
            compact('vpnUsername'),
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $vpnUsername
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function enableAccount(string $vpnUsername): Response
    {
        return $this->sendRequest(
            'post',
            '/vam/v1/enable',
            compact('vpnUsername'),
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $vpnUsername
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function disableAccount(string $vpnUsername): Response
    {
        return $this->sendRequest(
            'post',
            '/vam/v1/disable',
            compact('vpnUsername'),
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $vpnUsername
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function getLastConnectionDetails(string $vpnUsername): Response
    {
        return $this->sendRequest(
            'post',
            '/ca/v1/getLastConnectionDetails',
            ['sUsername' => $vpnUsername],
            $this->getAuthorizeHeaders()
        );
    }

    /**
     * @param  string  $vpnUsername
     * @return Response
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function accountStatus(string $vpnUsername): Response
    {
        return $this->sendRequest(
            'post',
            '/vam/v1/status',
            compact('vpnUsername'),
            $this->getAuthorizeHeaders()
        );
    }
}
