<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Team extends Model{
    protected $table = "teams";
    protected $primaryKey = "team_id";
    protected $fillable = ["team_id","team_name","school_id"];
    public $incrementing = false;

    public function school(){
        return $this->belongsTo("App\School","school_id");
    }

    public function members(){
        return $this->hasMany("App\Member","team_id","team_id");
    }

    public function exams(){
        return $this->hasMany("App\Exam","team_id","team_id");
    }
}
?>