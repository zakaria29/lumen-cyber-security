<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $table = "categories";
    protected $primaryKey = "category_id";
    protected $fillable = ["category_id","category_name","status"];
    public $incrementing = false;

    public function questions(){
        return $this->hasMany("App\Question","category_id","category_id");
    }
}
?>