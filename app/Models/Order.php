<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'orderId',
        'orderSn',
        'bookingSn',
        'buyerNotes',
        'cancelBy',
        'codOrder',
        'createTime',
        'customerEmail',
        'customerName',
        'customerPhone',
        'discount',
        'hasPaid',
        'includeTax',
        'insuranceCost',
        'logisticStatus',
        'orderStatusList',
        'subOrderStatusList',
        'platformOrderStatusList',
        'paymentMethod',
        'platform',
        'platformName',
        'preOrder',
        'storeId',
        'storeName',
        'subTotal',
        'tax',
        'totalPrice',
        'totalWeight',
        'courier',
        'deliveryDeadline',
        'shippingAddress',
        'shippingArea',
        'shippingCity',
        'shippingCost',
        'shippingFullName',
        'shippingPhone',
        'shippingProvince',
        'trackingNumber',
        'shippingPostCode',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
