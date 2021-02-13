<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model{
    protected $table = "admins";
    protected $primaryKey = "admin_id";
    protected $fillable = ["admin_id","admin_name","username","password","token"];
    public $incrementing = false;
}
?>