<?php
function getVideoIds($url, $apiKey) {

    $firstCheckUrl = strpos($url, 'index');

    if ($firstCheckUrl > -1) {
        $posIndex = strpos($url, '&index=');
        $playlistId = substr($url, strpos($url, 'list=') + 5, $posIndex);
        $posIndex = strpos($playlistId, '&index=');

        $playlistId = substr_replace($playlistId, '', $posIndex);
    } else {
        $playlistId = substr($url, strpos($url, 'list=') + 5);
    }

    $apiCallUrl = 'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=' . $playlistId . '&key=' . $apiKey . '';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_URL, $apiCallUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $data = json_decode(curl_exec($curl));

    curl_close($curl);

    $videosIds = "";
    $totalResults = $data->pageInfo->totalResults;

    for ($i = 0; $i < $totalResults; $i++) {
        $videoId = $data->items[$i]->contentDetails->videoId;
        $i < $totalResults - 1 ? $videosIds .= $videoId . ", " : $videosIds .= $videoId;
    }

    return $values = [
        'totalResults' => $totalResults,
        'videosIds' => $videosIds,
    ];
}