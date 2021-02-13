<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Question extends Model{
    protected $table = "questions";
    protected $primaryKey = "question_id";
    protected $fillable = [
        "question_id","question","point","answer_key","category_id","status"
    ];
    public $incrementing = false;

    public function files()
    {
        return $this->hasMany("App\File","question_id","question_id");
    }

    public function category()
    {
        return $this->belongsTo("App\Category","category_id");
    }
}
?>