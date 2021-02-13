<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Judge extends Model{
    protected $table = "judges";
    protected $primaryKey = "judge_id";
    protected $fillable = ["judge_id","judge_name","username","password","token"];
    public $incrementing = false;
}
?>