<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class File extends Model{
    protected $table = "files";
    protected $primaryKey = "file_id";
    protected $fillable = ["file_id","file_name","question_id"];
    public $incrementing = false;

    public function question(){
        return $this->belongsTo("App\Question","question_id");
    }
}
?>