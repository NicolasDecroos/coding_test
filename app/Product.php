<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $incrementing = false;
    public $primaryKey = 'id';
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'product';

    /**
     * returns the category of this product
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne('App\Category');
    }
}
