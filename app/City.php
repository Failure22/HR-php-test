<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property float $longitude
 * @property float $latitude
 * @property int|null $temperature
 *
 * @method static Builder where(string $field, string $comparator, mixed $value)
 */
class City extends Model
{
    public $timestamps = false;

    public $fillable = ['code', 'name', 'longitude', 'latitude', 'temperature'];
}
