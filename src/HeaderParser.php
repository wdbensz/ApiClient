<?php

namespace wbensz\ApiClientLib;

class HeaderParser
{
    public static function parseResponseHeaders($curl_headers) {
        $headers = array();
        $curl_headers_exploded = explode("\r\n\r\n", $curl_headers);

        // Array count minus 1 is to avoid addition of an empty row
        //because of the additional line break before the body of the response.
        for ($i = 0; $i < count($curl_headers_exploded) - 1; $i++) {
            foreach (explode("\r\n", $curl_headers_exploded[$i]) as $j => $line)
            {
                if (0 !== $j)
                {
                    list($key, $value) = explode(': ', $line);
                    // $headers[$i][$key] = $value;
                    $headers[$key] = $value;
                }
            }
        }

        return $headers;

    }
}
