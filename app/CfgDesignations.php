<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CfgDesignations extends Model
{
    use SoftDeletes;

   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','code','name','isVisible'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    
    protected $casts = ['id' => 'string'];

    public static function getDesignation()
    {
        $designations = CfgDesignations::where('isVisible','yes')->orderBy('id','asc')->get()->toArray();
        $selectedValue=array();
        foreach ($designations as $item)
        {
            // dd($item);
            $selectedValue[$item['id']]=$item['name'];
        }
        return ($selectedValue);
    }

    public function employee()
    {
        return $this->hasMany('App\Employee','designation');
    }
}

