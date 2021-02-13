<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class DoExam extends Model{
    protected $table = "do_exam";
    protected $primaryKey = "do_exam_id";
    protected $fillable = [
        "do_exam_id","exam_id","start_time","team_id"
    ];
    public $incrementing = false;

    public function exam_details(){
        return $this->hasMany("App\ExamDetail","do_exam_id","do_exam_id");
    }

    public function exam()
    {
        return $this->belongsTo("App\Exam","exam_id");
    }

    public function team()
    {
        return $this->belongsTo("App\Team","team_id");
    }
}
?>