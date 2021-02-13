<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model{
    protected $table = "exam_category";
    protected $fillable = ["exam_id","category_id"];
    public $incrementing = false;

    public function exam(){
        return $this->belongsTo("App\Exam","exam_id");
    }

    public function category(){
        return $this->belongsTo("App\Category","category_id");
    }
}
?>