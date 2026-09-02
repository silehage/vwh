<?php

namespace App\Services;

use App\Models\DestyData;


class DestyDataService
{
    public function store($payload)
    {

        // $orderStatusList = null;
        // $subOrderStatusList = null;

        // if (is_array($payload['orderStatusList'])) {
        //     $orderStatusList = $payload['orderStatusList'][0];
        // } else if (is_string($payload['orderStatusList'])) {
        //     $orderStatusList = $payload['orderStatusList'];
        // }
        // if (is_array($payload['subOrderStatusList'])) {
        //     $subOrderStatusList = $payload['subOrderStatusList'][0];
        // } else if (is_string($payload['subOrderStatusList'])) {
        //     $subOrderStatusList = $payload['subOrderStatusList'];
        // }

        DestyData::updateOrCreate(
            ['orderId' => $payload['orderId']],
            [
                'orderType' => $payload['orderType'],
                'orderCreateTime' => $payload['orderCreateTime'],
                'orderUpdateTime' => $payload['orderUpdateTime'],
                'attempts' => 0,
                'reserved_at' => null,
                'payload' => $payload
            ]
        );
    }
}
