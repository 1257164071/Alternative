<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    //
    protected $fillable = ['no', 'user_id', 'recharge_type', 'closed','recharge_info', 'telephone', 'product_id', 'price', 'remark', 'paid_at','payment_no','refund_status','refund_no', 'status', 'extra'];
    protected $casts = [
        'extra'     => 'json',
        'recharge_info' =>  'json'
    ];
    protected $dates = [
        'paid_at',
    ];
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_APPLIED = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款',
        self::REFUND_STATUS_APPLIED    => '已申请退款',
        self::REFUND_STATUS_PROCESSING => '退款中',
        self::REFUND_STATUS_SUCCESS    => '退款成功',
        self::REFUND_STATUS_FAILED     => '退款失败',
    ];
    const RECHARGE_STATUS_PENDING = 'pending';
    const RECHARGE_STATUS_DELIVERED = 'delivered';
    const RECHARGE_STATUS_RECEIVED = 'received';

    public static $shipStatusMap = [
        self::RECHARGE_STATUS_PENDING   => '未充值',
        self::RECHARGE_STATUS_DELIVERED => '已发起',
        self::RECHARGE_STATUS_RECEIVED  => '已成功',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->no) {
                    return false;
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }
}
