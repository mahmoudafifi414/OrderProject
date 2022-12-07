<?php

namespace Package\Infrastructure\Mysql\Model\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Package\Domain\Domainable;
use Package\Domain\Model\Order\Order as OrderDomainModel;
use Package\Domain\ValueObject\Order\OrderId;

class Order extends Model implements Domainable
{
    use HasFactory;

    /**
     * @return OrderDomainModel
     */
    public function convertToDomainModel(): OrderDomainModel
    {
        return new OrderDomainModel(
            OrderId::of($this->id)
        );
    }
}
