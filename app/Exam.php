<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model{
    protected $table = "exams";
    protected $primaryKey = "exam_id";
    protected $fillable = ["exam_id","exam_name","status","token"];
    public $incrementing = false;

    public function exam_details(){
        return $this->hasMany("App\DoExam","exam_id","exam_id");
    }

    public function exam_category(){
        return $this->hasMany("App\ExamCategory","exam_id","exam_id");
    }
}
?>