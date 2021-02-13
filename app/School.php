<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class School extends Model{
    protected $table = "schools";
    protected $primaryKey = "school_id";
    protected $fillable = ["school_id","school_name","school_address"];
    public $incrementing = false;

    public function teams(){
        return $this->hasMany("App\Team","school_id","school_id");
    }
}
?>