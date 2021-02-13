<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Member extends Model{
    protected $table = "members";
    protected $primaryKey = "member_id";
    protected $fillable = [
        "member_id","member_name","email","username","password","token","team_id"
    ];
    public $incrementing = false;

    public function team(){
        return $this->belongsTo("App\Team","team_id");
    }
}
?>