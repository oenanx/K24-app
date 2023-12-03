@extends('home.header.header')

@section('pageTitle')
	<h4 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h4>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
		<li class="breadcrumb-item">
			<a href="{{ url('Home/home') }}" class="text-muted">Dashboard User</a>
		</li>
	</ul>
@endsection

<script src="https://code.iconify.design/1/1.0.6/iconify.min.js"></script>

@section('content')
<div class="card card-danger">
	<div class="card-header with-border">
		<h4 class="card-title">Selamat datang,&nbsp;<i>{{ Session::get('realname') }}</i>&nbsp;di Aplikasi K24 Test,</h4>
	</div>
	@csrf
	<div class="card-body with-border">
		<div class="page-wrapper">
			<div class="page-body">

				<div class="row">

					<div class="col-xl-12 col-xl-12">
						<table style="width:100%; font-size:12pt;" border="0">
							<tr>
								<td align="center" style="width:20%;">
									<img src="{{ url('storage/uploads/'.$nama_file_foto) }}" class="h-180px" />
								</td>
								<td align="left" style="width:12%;">
									<label>Real Name</label><br />
									<label>Email</label><br />
									<label>Tgl. Lahir</label><br />
									<label>No. NIK</label><br />
									<label>No. Hp</label><br />
									<label>Kelamin</label>
								</td>
								<td align="center" style="width:3%;">
									<label> : </label><br />
									<label> : </label><br />
									<label> : </label><br />
									<label> : </label><br />
									<label> : </label><br />
									<label> : </label>
								</td>
								<td align="left" style="width:65%;">
									<label>{{ Session::get('realname') }}</label><br />
									<label>{{ Session::get('username') }}</label><br />
									<label>{{ $tgl_lahir }}</label><br />
									<label>{{ $no_ktp }}</label><br />
									<label>{{ $no_hp }}</label><br />
									<label>{{ $kelamin }}</label>
								</td>
							</tr>
						</table>
					</div>
					
				</div>

			</div>
		</div>
	</div><!-- /.card-body -->
</div><!-- /.card -->
@endsection


@push('scripts')
<script type="text/javascript" class="init">
$(document).ready(function()
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
});
</script>
@endpush


@include('home.footer.footer')
