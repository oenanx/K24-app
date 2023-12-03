@extends('home.header.header')

@section('pageTitle')
	<h1 class="text-dark font-weight-bold my-1 mr-5">List of Users Customers</h1>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
		<li class="breadcrumb-item">
			<a class="text-muted">Forms</a>
		</li>
		<li class="breadcrumb-item">
			<a class="text-muted">Registration</a>
		</li>
		<li class="breadcrumb-item">
			<a href="{{ url('M_Users/index') }}" class="text-muted">Master User</a>
		</li>
	</ul>
@endsection

<style>
#loader {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	background: rgba(0,0,0,0.75) url({{ asset('assets/images/loading2.gif') }}) no-repeat center center;
	z-index: 10000;
}
.modal-dialog {
	width: 100%;
	height: 100%;
	padding: auto;
	margin: auto;
}
.modal-content {
	height: 100%;
    overflow-y: scroll;
	border-radius: 10px;
	color:#333;
	overflow: auto;
}
</style>
@section('content')

<div id="notif" style="display: none;"></div>

@if ($message = Session::get('success'))
	<div id="alert1" class="alert alert-success alert-block">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>{{ $message }}</strong>
		<script type="text/javascript" class="init">
			setTimeout(function () { $('#alert1').hide(); }, 5000);
		</script>
	</div>
@endif

@if ($message = Session::get('failed'))
	<div class="alert alert-danger alert-block">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>{{ $message }}</strong>
	</div>
@endif

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="card card-custom" data-card="true" id="kt_card_2">
	<div class="card-header">
		<div class="card-title">
			<h3 class="card-label"><i class="fa fa-th-list"></i> List of Users Customers DataWiz Dashboard</h3>
		</div>
		<div class="card-toolbar">
			<a href="{{ url('Registration/newusers') }}" id="kt_login_signup">
				<button type="button" id="newuser" name="newuser" class="btn btn-md btn-hover-light-primary mr-1">
					<h3 class="card-label"><i class="flaticon2-add-square text-muted"></i>&nbsp;<b>New Users Customer</b></h3>
				</button>
			</a>
			<a href="#" class="btn btn-icon btn-md btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" data-original-title="Toggle Card">
                <i class="ki ki-arrow-down icon-nm"></i>
            </a>
        </div>
	</div>

	<div class="card-body">
		<div class="row align-items-center" style="width:100%; font-size:10pt; height: auto;">
			<div class="col-lg-8 col-lg-8">
			</div>
			<div class="col-lg-4 col-lg-4">
				<div class="row align-items-right">
                    <div class="col-md-12 col-md-12">
                        <div class="input-icon">
                            <input type="text" class="form-control form-control-sm" placeholder="Search..." id="kt_datatable_search_query" autocomplete="off" />
                            <span>
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="datatable datatable-bordered datatable-head-custom" id="Show-Tables" style="width:100%; font-size: 8pt; height: auto;">

		</div>
	</div>
</div>
<div id="loader"></div>

<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="card">
				<div class="card-header">
					<h2 class="card-title"><b>View Detail User</b></h2>
				</div>
				<div class="card-body">
					<div id="modal-loader" style="display: none; text-align: center;">
						<img src="{{ asset('assets/images/ajax-loader.gif') }}">
					</div>
					<div class="card-body" align="justify">
						<table style="width:100%; font-size:11pt;" border="0">
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> User Id. </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> <input type="text" class="form-control form-control-sm form-control-solid" name="regis1" readonly /></td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> User Name Login </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> <input type="text" class="form-control form-control-sm form-control-solid" name="cpy_name1" readonly /></td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> Real Name </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> <input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_addr1" readonly />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> Mobile No. </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> 
								<input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_addr2" readonly />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> Date of Birth </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> 								
								<input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_addr3" readonly />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> Gender </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> 
								<input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_addr4" readonly />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> KTP No. </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> 
								<input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_addr5" readonly />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:20%;"> Picture File Name </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:77%;"> 
								<input type="text" class="form-control form-control-sm form-control-solid" width="100%" name="cpy_zipcode" readonly />
								</td>
							</tr>
							
						</table>
					</div>
				</div>
				<div class="card-footer" align="right">
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
	
<div id="view-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="card">
				<div class="card-header">
					<h2 class="card-title"><b>Edit Detail User</b></h2>
				</div>
				<div class="card-body">
					<div id="modal-loader" style="display: none; text-align: center;">
						<img src="{{ asset('assets/images/ajax-loader.gif') }}">
					</div>
					<div class="card-body" align="justify">
					<form enctype="multipart/form-data" id="form2" class="form-horizontal">
						@csrf
						<input type="hidden" class="form-control form-control-sm" id="id2" name="id2" readonly />
						<table style="width:100%; font-size:11pt;" border="0">
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> User Name Login * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
								<input type="email" autocomplete="Off" class="form-control form-control-sm" id="useremail" name="useremail" required /></td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> Real Name * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
									<input type="text" class="form-control form-control-sm" autocomplete="Off" required id="realname" name="realname" maxlength="100" />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> Mobile No. * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
									<input type="text" class="form-control form-control-sm" autocomplete="Off" required id="no_hp" name="no_hp" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="15" />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> Date of Birth * </td>
								<td align="center" style="width:3%;"></td>
								<td align="left" style="width:75%;"> 
									<div class="input-group">
										<input type="text" class="form-control form-control-sm" autocomplete="Off" required id="tgl_lahir" name="tgl_lahir" required placeholder="Tanggal Lahir (yyyy-mm-dd)" />
										<div class="input-group-append">
											<span class="input-group-text"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> KTP No. * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
									<input type="text" class="form-control form-control-sm" autocomplete="Off" required id="no_ktp" name="no_ktp" maxlength="16" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> Gender * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
									<select id="sex2" name="sex2" class="form-control form-control-sm" required placeholder="Actived / Inactived) ?">
										<option value="">Select One...</option>
										<option value="0">Wanita</option>
										<option value="1">Pria</option>
									</select>
								</td>
							</tr>
							<tr style="line-height: 1.0;">
								<td align="left" style="width:22%;"> Status * </td>
								<td align="center" style="width:3%;">:</td>
								<td align="left" style="width:75%;"> 
									<select id="status2" name="status2" class="form-control form-control-sm" required placeholder="Actived / Inactived) ?">
										<option value="">Select One...</option>
										<option value="0">INACTIVE</option>
										<option value="1">ACTIVE</option>
									</select>
								</td>
							</tr>
						</table>
					</form>
					</div>
				</div>
				<div class="card-footer" align="right">
					<button type="submit" class="btn btn-primary btn-lg" id="Edit">Update</button>&nbsp;
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
	
@endsection

@push('scripts')
<link href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/propeller.css') }}" rel="stylesheet">

<!-- Untuk autocomplete -->
<script src="{{ asset('assets/auto/bootstrap3-typeahead.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/propeller.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/bootstrap/js/bootstrap-datepicker.js') }}" language="javascript"></script>
<script type="text/javascript" class="init">
var spinner = $('#loader');
var dataTable;
$(document).ready(function() 
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$('body').on('focus',"#tgl_lahir", function(){
		$(this).datepicker({
			format: 'yyyy-mm-dd', todayHighlight: true, inline: true
		});
	});	

    dataTable = $('#Show-Tables').KTDatatable(
	{
        // datasource definition
		data: 	
		{
			type: 'remote',
			source: {
				read: {
					url: '{{ url('M_Users/datatable') }}',
					// sample custom headers
					// headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
					map: function(raw) {
						// sample data mapping
						var dataSet = raw;
						if (typeof raw.data !== 'undefined') {
							dataSet = raw.data;
						}
						return dataSet;
					},
				},
			},
			pageSize: 10,
			serverPaging: true,
			serverFiltering: true,
			serverSorting: true,
		},

		// layout definition
		layout: {
			scroll: false,
			footer: false,
			theme: 'default',
			overlayColor: '#fefefe',
			opacity: 4,
			processing: 'Mohon tunggu sebentar sedang memproses data ...',
		},

		// translate definition
		translate: {
			records: {
				noRecords: 'Data tidak ada ...'
			}
		},

		// column sorting
		//sortable: false,
		pagination: true,
		search: {
			input: $('#kt_datatable_search_query'),
			key: 'generalSearch'
		},

		// columns definition
		columns: [
		{
			field: 'email',
			sortable: false,
			width: 180,
            textAlign: 'center',
			title: '<p style="font-size:12px;">Email User</p>',
			template: function(row) {
				return '<p style="font-size:11px;">'+row.email+'</p>';
			}
		},
		{
			field: 'realname',
			sortable: true,
			width: 140,
			title: '<p style="font-size:12px;">Real Name</p>',
			template: function(row) {
				return '<p style="font-size:11px;">'+row.realname+'</p>';
			}
		},
		{
			field: 'tgl_lahir',
			sortable: true,
			width: 140,
            textAlign: 'center',
			title: '<p style="font-size:12px;">Tgl. Lahir</p>',
			template: function(row) {
				return '<p style="font-size:11px;">'+row.tgl_lahir+'</p>';
			}
		},
		{
			field: 'no_ktp',
			sortable: false,
			width: 120,
            textAlign: 'center',
			title: '<p style="font-size:12px;">No. KTP</p>',
            template: function(row) {
				return '<p style="font-size:11px;">'+row.no_ktp+'</p>';
            }
		},
		{
			field: 'fstatus',
			sortable: false,
			width: 80,
            textAlign: 'center',
			title: 'Status',
            template: function(row) {
                var sts = row.fstatus;

                if (sts == 1)
                {
                    return '<i class="fas fa-sync text-success icon-md" title="Active"></i>';
                }
                else
                {
                    return '<i class="fas fa-sync text-danger icon-md" title="InActived"></i>';
                }
            }
		},
		{
			field: 'Actions',
			sortable: false,
			width: 80,
			title: '<p style="font-size:11px;">Action</p>',
			textAlign: 'center',
			//overflow: 'visible',
			autoHide: false,
			template: function(row) 
			{
				return '<div class="btn-group">\
						<button type="button" class="btn btn-icon btn-sm btn-primary btn-hover-light-primary" data-toggle="dropdown" aria-expanded="false"><i class="ki ki-arrow-down icon-sm"></i>\
						</button>\
						<ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-start">\
							<li style="font-size:9pt;>\
								<a href="javascript:void(0)" class="dropdown-item viewCpy" data-id="'+row.id+'" title="View Details">\
									<span class="svg-icon svg-icon-primary svg-icon-2x">\
										<i class="flaticon-eye icon-md"></i>\
									</span>&nbsp;View Details\
								</a>\
							</li>\
							<li style="font-size:9pt;>\
								<a href="javascript:void(0)" class="dropdown-item editCpy" data-id="'+row.id+'" title="Edit Details">\
									<span class="svg-icon svg-icon-primary svg-icon-2x">\
										<i class="flaticon-upload icon-md"></i>\
									</span>&nbsp;Edit Details\
								</a>\
							</li>\
						</ul>\
					</div>';
			},
		}],
	});

	$("#kt_datatable_search_query").on('change', function() {
		dataTable.search($("#kt_datatable_search_query").val(), 'generalSearch');
	});

});

function reload_table()
{
	dataTable.ajax.reload(null,false); //reload datatable ajax 
}

$('body').on('click', '.viewCpy', function () 
{     
	var id = $(this).data("id");
	$('.help-block').empty(); // clear error string
	$.get("{{ url('M_Users/view_user') }}"+'/'+id, function (data) 
	{
		$('.modal-dialog').css({width:'95%',height:'100%', 'max-height':'100%'});

		$('[name="regis1"]').val(data.id);

		$('[name="cpy_name1"]').val(data.email);
		$('[name="cpy_addr1"]').val(data.realname);
		$('[name="cpy_addr2"]').val(data.no_hp);
		$('[name="cpy_addr3"]').val(data.tgl_lahir);
		$('[name="cpy_addr4"]').val(data.gender);
		$('[name="cpy_addr5"]').val(data.no_ktp);
		$('[name="cpy_zipcode"]').val(data.nama_file_foto);
		$('[name="npwpno1"]').val(data.deskstatus);
		$('#view-modal').modal('show');
	});
});

$('body').on('click', '.editCpy', function () 
{     
	var id = $(this).data("id");
	$('.help-block').empty(); // clear error string
	$.get("{{ url('M_Users/view_user') }}"+'/'+id, function (data) 
	{
		$('.modal-dialog').css({width:'95%',height:'100%', 'max-height':'100%'});

		$('[name="id2"]').val(data.id);
		$('[name="useremail"]').val(data.email);
		$('[name="realname"]').val(data.realname);
		$('[name="no_hp"]').val(data.no_hp);
		$('[name="tgl_lahir"]').val(data.tgl_lahir);
		$('[name="no_ktp"]').val(data.no_ktp);
		$('[name="sex2"]').val(data.sex);
		$('[name="status2"]').val(data.fstatus);
		
		$('#view-modal-edit').modal('show');
	});
});

$('body').on('click', '#Edit', function () 
{
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    var editurl = "{{ url('M_Users/update_user') }}";
	$.ajax({
		url : editurl,
		type: "GET",
		data: $('#form2').serialize(),
		dataType: "JSON",
		success: function(data)
		{
			$('#view-modal-edit').modal('hide');
			$('#form2')[0].reset();
			dataTable.reload();
			$("#notif").html('<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><h4> Data berhasil di ubah !</h4></div>').show();
			setTimeout(function () { $('#notif').hide(); }, 9000);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$("#notif").html('<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><h4> Data gagal diubah, ada kesalahan... !!!</h4></div>').show();
			dataTable.reload();
			//alert('Error Update data from ajax');

			return false;
		}
	});
});

</script>
@endpush

@include('home.footer.footer')

