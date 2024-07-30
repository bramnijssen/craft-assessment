<?php

namespace modules\randomimage\widgets;

use Craft;
use craft\base\Widget;
use craft\helpers\App;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RandomImageWidget extends Widget
{
    public function getBodyHtml(): ?string
    {
        $url = $this->getRandomImageUrl();
        return Craft::$app->getView()->renderTemplate('randomimage/_components/widgets/RandomImage/body', [
            'imageUrl' => $url,
        ]);
    }

    private function getRandomImageUrl(): ?string
    {
        $apiKey = App::env('PEXELS_API_KEY');
        if ($apiKey === null) {
            return null;
        }

        $client = new Client([
            'base_uri' => 'https://api.pexels.com/v1/',
            'headers' => [
                'Authorization' => $apiKey
            ]
        ]);
        try {
            $response = $client->get('curated', [
                'query' => [
                    'per_page' => 1
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['photos'][0]['src']['original'] ?? null;
        } catch (RequestException) {
            return null;
        }
    }
}