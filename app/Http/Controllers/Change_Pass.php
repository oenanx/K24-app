<?php
namespace App\Http\Controllers;

use App\Models\Mod_User;
use App\Mail\ResPasswdMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Yajra\Datatables\Datatables;
use Browser;
use Stevebauman\Location\Facades\Location;

class Change_Pass extends Controller
{
    public function change_pass(Request $request)
	{
		if(Session::get('userid'))
		{
			$username	= Session::get('username'); 

			$detail = DB::table('master_user')
					->where('master_user.email', $username)
					->where('master_user.fstatus', '=', 1)
					->select('no_hp','tgl_lahir','sex',DB::raw('(CASE WHEN sex = 0 THEN "Wanita" WHEN sex = 1 THEN "Pria" END) as kelamin'),'no_ktp','nama_file_foto','path_file_foto')
					->first();
			
			if($detail)
			{
				$no_hp			= $detail->no_hp;
				$tgl_lahir		= $detail->tgl_lahir;
				$sex			= $detail->sex;
				$kelamin		= $detail->kelamin;
				$no_ktp			= $detail->no_ktp;
				$nama_file_foto	= $detail->nama_file_foto;
				$path_file_foto	= $detail->path_file_foto;
			}
			else
			{
				$no_hp			= "";
				$tgl_lahir		= "";
				$sex			= "";
				$kelamin		= "";
				$no_ktp			= "";
				$nama_file_foto	= "";
				$path_file_foto	= "";
			}

			return view('home.change_passwd.change_pass_user', compact('no_hp','tgl_lahir','sex','kelamin','no_ktp','nama_file_foto','path_file_foto'));
			//}	
		}
		else
		{
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('parentid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('divisi_name'));
			Auth::logoutOtherDevices(Session::get('pass1'));
			Auth::logoutOtherDevices(Session::get('folder'));
			Auth::logoutOtherDevices(Session::get('apptypeid'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('jml_custno'));
			Auth::logoutOtherDevices(Session::get('login'));
			
			session()->forget('userid');
			session()->forget('parentid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('divisi_name');
			session()->forget('pass1');
			session()->forget('folder');
			session()->forget('apptypeid');
			session()->forget('active');
			session()->forget('jml_custno');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
			echo "<script>window.close();</script>";
		}
	}
	
	public function change_pass_new(Request $request)
	{
		if(Session::get('userid'))
		{
			$username	= Session::get('username'); 

			$detail = DB::table('master_user')
					->where('master_user.email', $username)
					->where('master_user.fstatus', '=', 1)
					->select('no_hp','tgl_lahir','sex',DB::raw('(CASE WHEN sex = 0 THEN "Wanita" WHEN sex = 1 THEN "Pria" END) as kelamin'),'no_ktp','nama_file_foto','path_file_foto')
					->first();
			
			if($detail)
			{
				$no_hp			= $detail->no_hp;
				$tgl_lahir		= $detail->tgl_lahir;
				$sex			= $detail->sex;
				$kelamin		= $detail->kelamin;
				$no_ktp			= $detail->no_ktp;
				$nama_file_foto	= $detail->nama_file_foto;
				$path_file_foto	= $detail->path_file_foto;
			}
			else
			{
				$no_hp			= "";
				$tgl_lahir		= "";
				$sex			= "";
				$kelamin		= "";
				$no_ktp			= "";
				$nama_file_foto	= "";
				$path_file_foto	= "";
			}

			return view('home.change_passwd.change_pass', compact('no_hp','tgl_lahir','sex','kelamin','no_ktp','nama_file_foto','path_file_foto'));
		}
		else
		{
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('parentid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('divisi_name'));
			Auth::logoutOtherDevices(Session::get('pass1'));
			Auth::logoutOtherDevices(Session::get('folder'));
			Auth::logoutOtherDevices(Session::get('apptypeid'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('jml_custno'));
			Auth::logoutOtherDevices(Session::get('login'));
			
			session()->forget('userid');
			session()->forget('parentid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('divisi_name');
			session()->forget('pass1');
			session()->forget('folder');
			session()->forget('apptypeid');
			session()->forget('active');
			session()->forget('jml_custno');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
			echo "<script>window.close();</script>";
		}
	}

	public function update_pass(Request $request)
	{
		if(Session::get('userid'))
        {
			//dd($request->user_id);
			$data 				= array();
			$user_id			= $request->user_id;
			$username			= Session::get('username');
			$current_date_time 	= date('Y-m-d H:i:s');
			$passwdnew 			= $request->passwordnew;
			$passwordnew 		= Hash::make($request->passwordnew);
			
			/*
			*/
			$data = array('password' => $passwordnew, 'update_at' => $current_date_time);

			Mod_User::Update_Pass($user_id, $data);
			
			$data = DB::table('master_user')->where('id', $user_id)->select('realname')->first();
			if ($data)
			{
				$realname	= $data->full_name;
			}

			//Kirim notif uabah password baru ke email.
			$username = Session::get('username');

			$details = [
				'title' => 'Halo '.$realname.',',
				'body1' => '',
				'body2' => 'Notifikasi ini untuk memberi tahu bahwa kata sandi Anda baru saja diubah.',
				'body3' => 'Tentang perubahan ini : ',
				'body4' => '  '.date('Y-m-d H:i:s'),
				'body5' => '  ',
				'body6' => '  ',
				'body7' => 'Jika ini memang Anda, Anda tidak perlu melakukan apa pun.',
				'body8' => 'Jika ini bukan Anda, Silahkan kirim email ke cs@atlasat.co.id.',
				'body9' => 'Terima kasih.',
			];
		   
			Mail::to($username)->send(new \App\Mail\ResPasswdMail($details));

			return response()->json(['success'=>'Password updated successfully.']);
		}
		else
		{
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('parentid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('divisi_name'));
			Auth::logoutOtherDevices(Session::get('pass1'));
			Auth::logoutOtherDevices(Session::get('folder'));
			Auth::logoutOtherDevices(Session::get('apptypeid'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('jml_custno'));
			Auth::logoutOtherDevices(Session::get('login'));
			
			session()->forget('userid');
			session()->forget('parentid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('divisi_name');
			session()->forget('pass1');
			session()->forget('folder');
			session()->forget('apptypeid');
			session()->forget('active');
			session()->forget('jml_custno');
			session()->forget('login');
		
			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
			echo "<script>window.close();</script>";
		}
	}

}
?>