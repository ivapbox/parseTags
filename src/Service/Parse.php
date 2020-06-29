<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class Parse
{
    /**
     * @param Request $request
     * @return array
     */
    public function parse(Request $request): array
    {
        $url = $request->get('url');
        if ($this->validUrl($url)) {
            $response = $this->action($url);
        } else {
            $response = ['error' => 'Невалидный URL'];
        }
        return $response;
    }

    /**
     * @param string $url
     * @return bool
     */
    private function validUrl(string $url): bool
    {
        $file_headers = get_headers($url);
        if ($file_headers === false) return false;
        $code = 0;
        foreach ($file_headers as $header) {
            if (preg_match("/^Location: (http.+)$/", $header, $m)) $url = $m[1];
            if (preg_match("/^HTTP.+\s(\d\d\d)\s/", $header, $m)) $code = $m[1];
        }
        if ($code == 200) return true;
        else return false;
    }

    /**
     * @param string $url
     * @return array
     */
    private function action(string $url): array
    {
        $response = [];
        $out = file_get_contents($url);
        preg_match_all('!<([\w]+)!si', $out, $data);
        $data = $data[1] ?? [];
        foreach ($data as $datum) {
            $response[$datum] = isset($response[$datum]) ? $response[$datum] + 1 : 1;
        }
        return $response;
    }
}