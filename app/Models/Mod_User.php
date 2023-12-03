<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mod_User extends Model
{
	protected $table = 'master_user';
	protected $primaryKey = 'id';
    const CREATE_AT = 'create_at';
    const UPDATE_AT = 'update_at';

	//id,email,password,realname,no_hp,tgl_lahir,sex,no_ktp,nama_file_foto,path_file_foto,fstatus,create_at,update_at

    protected $fillable = ['email','password','realname','no_hp','tgl_lahir','sex','no_ktp','nama_file_foto','path_file_foto','fstatus'];
	
	public static function insertMenus($userid,$menus)
	{
		foreach ($menus as $menuid) 
		{
			DB::insert('insert into user_menu (userid,menuid) values (?, ?)', [$userid,$menuid]);
		}
	}

	public static function Update_User($editid,$data)
	{
		//print_r($data);
		//exit();
		DB::table('master_user')->where('id', $editid)->where('fstatus', 1)->update($data);
	}

	public static function Update_Pass($user_id,$data)
	{
		//print_r($data);
		//exit();
		DB::table('master_user')->where('id', $user_id)->where('fstatus', 1)->update($data);
	}

    public function scopeGeneralSearch(Builder $query, string $search): Builder
    {
        return $query->where('email', 'like', '%' . $search . '%')
                     ->orWhere('realname', 'like', '%' . $search . '%');
    }
}
?>