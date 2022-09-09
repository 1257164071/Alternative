<?php

namespace App\Http\Resources\Api;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    =>  $this->id,
            'no'    =>  $this->no,
            'recharge_type'    =>  $this->recharge_type,
            'recharge_status'    =>  $this->recharge_status,
            'recharge_json'    =>  json_decode($this->recharge_json),
            'telephone'    =>  $this->telephone,
            'price'    =>  $this->price,
            'remark'    =>  $this->remark,
            'paid_at'    =>  $this->paid_at,
            'payment_no'    =>  $this->payment_no,
            'refund_status'    =>  $this->refund_status,
            'refund_no'    =>  $this->refund_no,
            'closed'    =>  $this->closed,
            'created_at'    =>  date('Y-m-d H:i:s',strtotime($this->created_at)),
            'updated_at'    =>  date('Y-m-d H:i:s',strtotime($this->updated_at)),
            'recharge_type_name'    =>  Order::$rechargeTypeMap[$this->recharge_type],
            'recharge_status_name'  =>  Order::$shipStatusMap[$this->recharge_status],
            'refund_status_name'    =>  Order::$refundStatusMap[$this->refund_status],
        ];
    }
}
