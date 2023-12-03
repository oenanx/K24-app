<?php
namespace App\Http\Controllers;

use App\Models\Mod_login;
use App\Models\Mod_User;
use App\Mail\SentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

class Home extends Controller
{
    public function index()
    {
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		Auth::logoutOtherDevices(Session::get('userid'));
		Auth::logoutOtherDevices(Session::get('username'));
		Auth::logoutOtherDevices(Session::get('realname'));
		Auth::logoutOtherDevices(Session::get('active'));
		Auth::logoutOtherDevices(Session::get('factive'));
		Auth::logoutOtherDevices(Session::get('login'));

		session()->forget('userid');
		session()->forget('username');
		session()->forget('realname');
		session()->forget('active');
		session()->forget('factive');
		session()->forget('login');

		session()->flush();
		Auth::logout();
		DB::disconnect('mysql');

		return view('templates.login');
    }

    public function forget()
    {
        return view('templates.forget');
    }

    public function new_user()
    {
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		Auth::logoutOtherDevices(Session::get('userid'));
		Auth::logoutOtherDevices(Session::get('username'));
		Auth::logoutOtherDevices(Session::get('realname'));
		Auth::logoutOtherDevices(Session::get('active'));
		Auth::logoutOtherDevices(Session::get('factive'));
		Auth::logoutOtherDevices(Session::get('login'));

		session()->forget('userid');
		session()->forget('username');
		session()->forget('realname');
		session()->forget('active');
		session()->forget('factive');
		session()->forget('login');

		session()->flush();
		Auth::logout();
		DB::disconnect('mysql');

		return view('templates.login');
    }

    public function userindex(Request $request)
	{
		if(Session::get('userid'))
		{
			//jika memang session sudah terdaftar
			if ($request->ajax()) 
			{
                dataTableGeneralSearch($request, function($search) {
                    return [
                        'filter' => [
                            'general_search' => $search
                        ]
                    ];
                });

				//id,email,password,realname,no_hp,tgl_lahir,sex,no_ktp,nama_file_foto,path_file_foto,fstatus,create_at,update_at
				$data = QueryBuilder::for(Mod_User::class)
						->select('master_user.id', 'email','realname','no_hp','tgl_lahir','sex',DB::raw('(CASE WHEN sex = 0 THEN "Wanita" WHEN sex = 1 THEN "Pria" END) as gender'),'no_ktp','nama_file_foto', 'fstatus', DB::raw('(CASE WHEN fstatus = 0 THEN "InActived" WHEN fstatus = 1 THEN "Actived" END) as deskstatus'))
						->orderBy('master_user.id','DESC')
						->allowedFilters(
							AllowedFilter::scope('general_search')
						)
						->paginate($request->query('perpage', 10))
						->appends(request()->query());

                return response()->paginator($data);
			}
			
			$username			= Session::get('username'); 

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

			return view('home.master_users.index', compact('no_hp','tgl_lahir','sex','kelamin','no_ktp','nama_file_foto','path_file_foto'));
		}
		else
		{
			//jika session belum terdaftar, maka redirect ke halaman login
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
		}
	}

	public function view_user($id)
    {
        if(Session::get('userid'))
        {
			$data = DB::table('master_user')
					->where('master_user.id', $id)
					->select('master_user.id', 'email','realname','no_hp','tgl_lahir','sex',DB::raw('(CASE WHEN sex = 0 THEN "Wanita" WHEN sex = 1 THEN "Pria" END) as gender'),'no_ktp','nama_file_foto', 'fstatus', DB::raw('(CASE WHEN fstatus = 0 THEN "InActived" WHEN fstatus = 1 THEN "Actived" END) as deskstatus'))
					->first();

			return response()->json($data);
        }
		else
		{
			//jika session belum terdaftar, maka redirect ke halaman login
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
		}
    }

	public function update_user(Request $request)
	{
		if(Session::get('userid'))
        {
			$data 				= array();
			$editid				= $request->id2;
			$current_date_time 	= date('Y-m-d H:i:s');
			//dd($editid);
			
			$data = array(
				'email' => $request->useremail, 
				'realname' => $request->realname, 
				'no_hp' => $request->no_hp, 
				'tgl_lahir' => $request->tgl_lahir, 
				'no_ktp' => $request->no_ktp, 
				'sex' => $request->sex2,
				'fstatus' => $request->status2
			);

			Mod_User::Update_User($editid, $data);

			return back()
	            ->with('success','Master User was updated successfully.');
		}
		else
		{
			//jika session belum terdaftar, maka redirect ke halaman login
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
		}
	}

    public function home(Request $request)
    {
		if(Session::get('userid'))
		{
			$active				= Session::get('active'); 
			$username			= Session::get('username'); 

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

			return view('home.dashboard.home_user', compact('no_hp','tgl_lahir','sex','kelamin','no_ktp','nama_file_foto','path_file_foto'));			
		}
		else
		{
			//jika session belum terdaftar, maka redirect ke halaman login
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','You were Logout');
		}
    }

	public function resetpasswd(Request $request)
	{
		$request->validate([
            'useremail' => ['required', 'email:dns']
        ]);
		
		//Cek dahulu email yang di input valid atau tidak.
		$username = $request->useremail;
		//dd($username);
		
		$data = DB::table('master_user')
				->where('fstatus', 1)
				->where('master_user.email', $username)
				->select('email')
				->distinct()
				->first();
		//dd($data->username);
		
		if($data)
		{
			$email				= $data->email;
			$passwdnew 			= Str::random(16);
			$passwordnew 		= Hash::make($passwdnew);
			$current_date_time 	= date('Y-m-d H:i:s');
			
			DB::table('master_user')
				->where('master_user.email', $username)
				->where('master_user.fstatus', 1)
				->update(
					[
						'password'	=> $passwordnew,
						'update_at' => $current_date_time,
					]);
					
			//Kirim password baru ke email.
			$to = $email;
			
			$details = [
				'title' => 'Reset Password K24 Test Application',
				'body' => 'This is your new Password : '.$passwdnew
			];
		   
			Mail::to($to)->send(new \App\Mail\SentMail($details));
			
			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect('/')->with('alert','Please check your email, we sent your new password at your email !');
			
		}
		else 
		{
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');

			return redirect( url('forget') )->with('alert','Username is not Valid !');
		}
	}
	
    public function daftar(Request $request)
    {
		//dd($request);
		//$request->validate([
        //    'email'		=> ['required', 'email:dns'],
        //    'password'	=> ['required'],
        //    'myPict'	=> 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        //]);
		
		$email		 		= $request->email;
		$password	 		= $request->password;
		$passwdnew			= Hash::make($password);
		$realname	 		= $request->realname;
		$no_hp		 		= $request->no_hp;
		$tgl_lahir	 		= $request->tgl_lahir;
		$sex		 		= $request->sex;
		$no_ktp		 		= $request->no_ktp;
		$create_at			= date('Y-m-d H:i:s');
		$files		 		= $request->myPict;

		$getSize			= $files->getSize();
		//dd($files->getSize());
		if (($getSize / 1024) > 1024)
		{
			return response()->json(['errorfile' => 'File image melebihi 1 MB !!!']);
		}
		
		$OriginalNames	 	= $files->getClientOriginalName();
		$paths			 	= $files->move(storage_path('app/public/uploads'), $OriginalNames);
		
		$emailcek = DB::table('master_user')->select(DB::raw('COUNT(id) as tot_id'))->where('email', $email)->get();
		foreach ($emailcek as $cek)
		{
			$tot_id = $cek->tot_id;
		}
		//dd($tot_id);
		
		if ($tot_id !== 0)
		{
			return response()->json(['erroremail' => 'Email user sudah ada !!!']);
		}
		
		//id,email,password,realname,no_hp,tgl_lahir,sex,no_ktp,nama_file_foto,path_file_foto,fstatus,create_at,update_at
		DB::table('master_user')->insert(
			[
				'email'				=> $email,
				'password'			=> $passwdnew,
				'realname'			=> $realname,
				'no_hp'				=> $no_hp,
				'tgl_lahir'			=> $tgl_lahir,
				'sex'				=> $sex,
				'no_ktp'			=> $no_ktp,
				'nama_file_foto'	=> $OriginalNames,
				'path_file_foto'	=> "app/public/uploads",
				'fstatus'			=> 1,
				'create_at'			=> $create_at
			]
		);

		//Kirim password baru ke email.
		$to = $email;
		
		$details = [
			'title' => 'Pendaftaran User K24 Test Application',
			'body' => 'Berikut ini adalah password anda : '.$passwdnew
		];
	   
		Mail::to($to)->send(new \App\Mail\SentMail($details));
		
		session()->flush();
		Auth::logout();
		DB::disconnect('mysql');
		
		return response()->json(['success' => 'Done.']);
	}

    public function authenticate(Request $request)
    {
		$request->validate([
            'username' => ['required', 'email:dns'],
            'password' => ['required'],
			'captcha'  => 'required|captcha'
        ]);

		//get data dari FORM
		$username = $request->username;
		$password = $request->password;

		$data = DB::table('master_user')
				->where('master_user.email', $username)
				->where('master_user.fstatus', '=', 1)
				->select('master_user.id','email','password','realname','fstatus',DB::raw('(CASE WHEN fstatus = 0 THEN "InActived" WHEN fstatus = 1 THEN "Active" END) as dstatus'))
				->distinct()
				->first();
		//dd($data);
		
		if($data)
		{
			$userid			= $data->id;
			$email			= $data->email;
			$passwd			= $data->password;
			$realname		= $data->realname;
			$fstatus		= $data->fstatus;
			$dstatus		= $data->dstatus;

			if(Hash::check($password, $passwd))
			{
				//dd("benar");
				Session::put('userid',$userid);
				Session::put('username',$email);
				Session::put('realname',$realname);
				Session::put('active',$fstatus);
				Session::put('factive',$dstatus);			
				Session::put('login',TRUE);
				
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

				return view('home.dashboard.home_user', compact('no_hp','tgl_lahir','sex','kelamin','no_ktp','nama_file_foto','path_file_foto'));			
			} 
			else
			{
				//dd("salah");
				header("cache-Control: no-store, no-cache, must-revalidate");
				header("cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

				Auth::logoutOtherDevices(Session::get('userid'));
				Auth::logoutOtherDevices(Session::get('username'));
				Auth::logoutOtherDevices(Session::get('realname'));
				Auth::logoutOtherDevices(Session::get('active'));
				Auth::logoutOtherDevices(Session::get('factive'));
				Auth::logoutOtherDevices(Session::get('login'));

				session()->forget('userid');
				session()->forget('username');
				session()->forget('realname');
				session()->forget('active');
				session()->forget('factive');
				session()->forget('login');

				session()->flush();
				Auth::logout();
				DB::disconnect('mysql');

				return redirect('/')->with('alert','Username or Password, Incorrect !');
			}
		}
		else
		{
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			Auth::logoutOtherDevices(Session::get('userid'));
			Auth::logoutOtherDevices(Session::get('username'));
			Auth::logoutOtherDevices(Session::get('realname'));
			Auth::logoutOtherDevices(Session::get('active'));
			Auth::logoutOtherDevices(Session::get('factive'));
			Auth::logoutOtherDevices(Session::get('login'));

			session()->forget('userid');
			session()->forget('username');
			session()->forget('realname');
			session()->forget('active');
			session()->forget('factive');
			session()->forget('login');

			session()->flush();
			Auth::logout();
			DB::disconnect('mysql');
		
			Artisan::call('cache:clear');

			return redirect('/')->with('alert','Username is not Valid !');
		}
	}

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
	
    public function logout()
    {
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		Auth::logoutOtherDevices(Session::get('userid'));
		Auth::logoutOtherDevices(Session::get('username'));
		Auth::logoutOtherDevices(Session::get('realname'));
		Auth::logoutOtherDevices(Session::get('active'));
		Auth::logoutOtherDevices(Session::get('factive'));
		Auth::logoutOtherDevices(Session::get('login'));

		session()->forget('userid');
		session()->forget('username');
		session()->forget('realname');
		session()->forget('active');
		session()->forget('factive');
		session()->forget('login');

		session()->flush();
		
		Auth::logout();
		DB::disconnect('mysql');
		
		Artisan::call('cache:clear');

        return redirect('/')->with('alert','You were Logout');
    }

}
?>