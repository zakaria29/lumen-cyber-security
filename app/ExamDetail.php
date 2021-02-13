<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class ExamDetail extends Model{
    protected $table = "exam_details";
    protected $fillable = [
        "do_exam_id","member_id","question_id","category_id","answer","score"
    ];
    public $incrementing = false;

    public function do_exam()
    {
        return $this->belongsTo("App\DoExam","do_exam_id");
    }

    public function member()
    {
        return $this->belongsTo("App\Member","member_id");
    }

    public function question()
    {
        return $this->belongsTo("App\Question","question_id");
    }

    public function category()
    {
        return $this->belongsTo("App\Category","category_id");
    }
}
?>