﻿document.addEventListener('DOMContentLoaded', function(e) {

	function SetAnimation(){
		var allDetail=document.querySelectorAll('.btnDetail');
		for (var i = 0; i < allDetail.length; i++) {
			allDetail[i].addEventListener("click",function(){
				var content=document.querySelector('.wrap-content .content');
				content.classList.add('ani_in');
				content.addEventListener("webkitAnimationEnd",function(){
					this.classList.remove("ani_in");
				})
			})
		}
	}


	function createrNoidungGX(data) {
		var noidung="";
		for (var i = 0; i < data.length; i++) {
			noidung+='<hr>';
			noidung+='<div class="row">';
			noidung+='<div class="col-xs-4">'+data[i].TenGiaoXu+'</div>';
			noidung+='<div class="col-xs-2">'+data[i].DienThoai+'</div>';
			noidung+='<div class="col-xs-3 txtWeb"><a target="_blank" href="'+data[i].Website+'">'+data[i].Website+'</a></div>';
			noidung+='<div class="col-xs-3">';
			noidung+='<b class="btn btn-success btnDetail"data-name="'+data[i].TenGiaoXu+'" data-id="'+data[i].ID+'">Tệp</b>';
			noidung+=" ";
			noidung+='<b class="btn btn-primary btn-edit" data-name="'+data[i].TenGiaoXu+'" data-id="'+data[i].ID+'">Sửa giáo xứ</b>';
			noidung+='</div>';
			noidung+='</div>';
		}
		return noidung;
	}
	var path=$('#baseURL').val();
	$('body').on('click', '.page-link a', function(event) {
		url=$(this).attr('href');
		page=$(this).attr('data-ci-pagination-page');
		if (page==1) {
			url+="/0";
		}
		$.ajax({
			url: url+'/1',
			type: 'post',
			dataType: 'json',
		})
		.always(function(dulieu) {
			if (dulieu!=-1) {
				$('.wrap-giaoxu2').empty();
				var noidung=createrNoidungGX(dulieu.data);
				$('.wrap-giaoxu2').append(noidung);
				$('.wrap-giaoxu2').append(dulieu.pagi);
				setClickEdit();
				SetAnimation();
			}
		});
		return false;
	});
	function setClickEdit(){
		$(".btn-edit").click(function(){
			let giaoXuId = $(this).attr('data-id');
			let title = $(this).attr('data-name');
			getGiaoXuEdit(giaoXuId,title);
			$('#edit-modal').modal('show');
		})
	}
	function getGiaoXusRequest(){
		let url = `GiaoXuCL/getGiaoXusRequest`;
		$.ajax({
			url: path+url,
			type: 'post',
			headers:{
				'password':'admin',
			},
			dataType: 'json',
		}).always((data)=>{
			let html = "";
			for(let i = 0;i<data.length;i++){
				html+="<tr>"
				html+=`<td>${data[i].UploadedDate}</td>`;
				html+=`<td>${data[i].TenGiaoXu}</td>`;
				html+=`<td><a href="#" class="consider" data-id='${data[i].ID}' data-name='${data[i].TenGiaoXu}'>Xem xét</a></td>`
				html+="</tr>"
			}
			if(html == ""){
				$("#table-request tbody").html("<h4 class='text-center'>Không có yêu cầu nào!</h4>");
				return false;
			} else {
				$("#table-request tbody").html(html);
			}
			$(".consider").click(function(){
				let giaoXuId = $(this).attr('data-id');
				let title = $(this).attr('data-name');
				getGiaoXuEdit(giaoXuId,title);
				$('#edit-modal').modal('show');
			});
		});
	}
	function getGiaoXu(giaoXuId){
		let url = `GiaoXuCL/getGxById/${giaoXuId}`;
		$.ajax({
			url: path+url,
			type: 'post',
			headers:{
				'password':'admin',
			},
			dataType: 'json',
		}).always((data)=>{
			let giaoXu = data[0];
			$("#txt-giaophan-name").val(giaoXu.TenGiaoPhan);
			$("#txt-giaohat-name").val(giaoXu.TenGiaoHat);
			$("#txt-giaoxu-name").val(giaoXu.TenGiaoXu);
			$("#txt-email").val(giaoXu.Email);
			$("#txt-sdt").val(giaoXu.DienThoai);
			$("#txt-website").val(giaoXu.Website);
			$("#txt-diachi").val(giaoXu.DiaChi);
			$("#txt-giaoxu-id").val(giaoXu.ID);
			getGiaoPhan(giaoXu.MaGiaoPhan,giaoXu.Ma_GiaoHat);
		});
	}
	function getGiaoHat(giaoPhanId,giaoHatCurrentId){
		//getGHByIdGP
		let url = `GiaoHatCL/getGHByIdGP/${giaoPhanId}`;
		$.ajax({
			url: path+url,
			type: 'post',
			headers:{
				'password':'admin',
			},
			dataType: 'json',
		}).always((data)=>{
			let html = "";
			for(let i = 0;i<data.length;i++){
				if(data[i].status == 0) {
					html+=`<option value='${data[i].MaGiaoHat}' status='${data[i].status}'>${data[i].TenGiaoHat + " (Tạo mới) "}</option>`;
				} else {
					html+=`<option value='${data[i].MaGiaoHat}' status='${data[i].status}'>${data[i].TenGiaoHat}</option>`;
				}
			}
			$("#cb-giaohat-name").html(html);
			$("#cb-giaohat-name").val(giaoHatCurrentId);
			if($("#cb-giaohat-name").find(":selected").attr('status') == 0) {
				$(".giaohat-note").css('display','block');
			} else {
				$(".giaohat-note").css('display','none');
			}
		});
	}
	function getGiaoXuEdit(giaoXuId,title){
		$(".edit").text("Giáo xứ " + title);
		getGiaoXu(giaoXuId);
	}
	function getGiaoPhan(maGiaoPhanCurrent,maGiaoHatCurrent){
		let giaoPhanUrl = `GiaoPhanCL/getGPjson`;
		$.ajax({
			url: path+giaoPhanUrl,
			type: 'post',
			headers:{
				'password':'admin',
			},
			dataType: 'json',
		}).always((data)=>{
			let html="";
			for(let i = 0;i<data.length;i++){
				if(data[i].status == 0){
					html+=`<option value='${data[i].MaGiaoPhan}' status='${data[i].status}'>${data[i].TenGiaoPhan + " (Tạo mới) "}</option>`;
				} else {
					html+=`<option value='${data[i].MaGiaoPhan}' status='${data[i].status}'>${data[i].TenGiaoPhan}</option>`;
				}	
			}
			$("#cb-giaophan-name").html(html);
			$("#cb-giaophan-name").val(maGiaoPhanCurrent);
			let selectd = $("#cb-giaophan-name").find(":selected");
			if(selectd.attr('status') == 0) {
				$(".giaophan-note").css('display','block');
			} else {
				$(".giaophan-note").css('display','none');
			}
			getGiaoHat(maGiaoPhanCurrent,maGiaoHatCurrent);
			})
	}
	function showData(nameSS,urlCL,title) {
		$('.TieuDeShow').text(title);
		$.ajax({
			url: path+urlCL,
			type: 'post',
			headers:{
				'password':'admin',
			},
			dataType: 'json',
		})
		.always(function(dulieu) {
			if (dulieu!=-1) {
				var noidung='';
				$('.wrap-giaoxu2').empty();
				noidung=createrNoidungGX(dulieu.data);
				$('.wrap-giaoxu2').append(noidung);
				$('.wrap-giaoxu2').append(dulieu.pagi);
				setClickEdit();
				SetAnimation();
			}
		});
		
	}
	$('.files').click(function() {
		showData("GX","GiaoXuCL/index/0/1","Giáo xứ trên hệ thống");
	});
	function closeEmail() {
		clearEmail();
		$('.SoanEmail').removeClass('active');
	}
	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}
	function clearEmail() {
		$('#inputTo').val('');
		$('#inputSubject').val('');
		$('#txtContentEmail').val('');
		$('#filekem').text('');
	}

	$('.nut-remove').click(function() {
		closeEmail();
	});
	$('#txtContentEmail').text('');

	$('#btnSendEmail').click(function() {
		var email=$('#inputTo').val();
		if (!validateEmail(email)) {
			$('.tooltip').addClass('active');
			setTimeout(function () { 
				$('.tooltip').removeClass('active');
			},2000);
			return;
		}

		$.ajax({
			url: path+'GiaoXuCL/sendMail',
			type: 'post',
			dataType: 'json',
			data: {
				to:$('#inputTo').val(),
				subject:$('#inputSubject').val(),
				content:$('#txtContentEmail').val(),
				path: $(this).data('path'),
			},
		})
		.always(function(data) {
			console.log(data);
			if (data==1) {
				closeEmail();
				$('.EmailSuccess').addClass('active');
				setTimeout(function () { 
					$('.EmailSuccess').removeClass('active');
				},2000);
			}
			else{
				$('.EmailError').addClass('active');
				setTimeout(function () { 
					$('.EmailError').removeClass('active');
				},2000);
			}
		});

	});
	$('.btnFind').click(function() {
		var contentFind=$('#contentFind').val();
		if (contentFind==="") {
			return;
		}
		$.ajax({
			url: path+'GiaoXuCL/findContent',
			type: 'post',
			dataType: 'json',
			data: {
				value: contentFind,
			},
		})
		.always(function(data) {
			var length=0;
			if (data!=-1) {
				length=data.length;
				$('.wrap-giaoxu2').empty();
				var noidung=createrNoidungGX(data);
				$('.wrap-giaoxu2').append(noidung);
				setClickEdit();
				SetAnimation();
			}
			$('.TieuDeShow').text('Kết quả tìm kiếm: '+contentFind);
			var noidung="";
			noidung+='<span class="text-muted">';
			noidung+='<small> ('+length+' kết quả)</small>';
			noidung+='</span>';
			$('.TieuDeShow').append(noidung);
		});

	});

	$('body').on("click",".btnDowload",function(){
		var id=$(this).data('id');
		window.location= path+'BackupCL/dowloadFile/'+id;
	});
	function outNenDen() {
		$('.wrap-content').removeClass('active');
		$('.wrap-backup').empty();
	}
	$(document).ajaxStart(function ()
	{
		$('body').addClass('wait');

	}).ajaxComplete(function () {

		$('body').removeClass('wait');

	});
	$('.wrap-content .den').click(function() {
		outNenDen();
	});

	$('.btnCancel').click(function() {
		outNenDen();
	});
	$('body').on('click', '.btnNewEmail', function() {

		$('.SoanEmail').addClass('active');
		var father=$(this).closest('.one-file');
		var path=father.find('.btnDowload').data('path');
		var name=father.find('.btnDowload').text();
		$('#btnSendEmail').data('path',path);
		$('#filekem').append('<i class="fa fa-paperclip"></i>'+name);

	});
	$('body').on('click', '.btnDetail', function() {
		$('.wrap-content').addClass('active');
		$('#nameGx').text('Giáo xứ: '+$(this).data("name"));
		var id=$(this).data('id');
		$.ajax({
			url: path+'BackupCL/getBackup/'+id,
			type: 'post',
			dataType: 'json',
		})
		.always(function(data) {
			if (data!=-1) {
				var noidung="";
				for (var i = 0; i < data.length; i++) {
					noidung+='<div class="one-file">';
					noidung+='<hr>';
					noidung+='<div class="row">';
					noidung+='<div class="col-xs-4">';
					noidung+='<input type="hidden" name="" value="'+data[i].ID+'">';
					noidung+='<p class="btnDowload" data-id="'+data[i].ID+'" data-path="'+data[i].PathFile+'"><i class="fa fa-file-archive"></i> '+data[i].Name+'</p>';
					noidung+='</div>';
					noidung+='<div class="col-xs-4">'+data[i].Time+'</div>';
					noidung+='<div class="col-xs-4">';
					noidung+='<b class="btn btn-danger btnNewEmail">Soạn Email</b>';
					noidung+='</div>';
					noidung+='</div>';
					noidung+='</div>';
				}
				$('.wrap-backup').append(noidung);
			}
		});

	});
	$(".down-management").click(()=>{
		$("#child-management").toggle();
	})
	$("#management").click(()=>{
		getGiaoXusRequest();
		$('#request-modal').modal('show'); 
	})
	setClickEdit();
	let resultRequest = getGiaoXusRequest();
	if(!resultRequest){
		$('#request-modal').modal('show'); 
	}
	SetAnimation();
	$("#cb-giaohat-name").on('change',function(){
		let selectd = $("#cb-giaohat-name").find(":selected");
		if(selectd.attr('status') == 0) {
			$(".giaohat-note").css('display','block');
		} else {
			$(".giaohat-note").css('display','none');
		}
		$("#hidden-giaohat").val(selectd.attr('status'));
	})
	$("#cb-giaophan-name").on('change',()=>{
		let selectd = $("#cb-giaophan-name").find(":selected");
		if(selectd.attr('status') == 0) {
			$(".giaophan-note").css('display','block');
		} else {
			$(".giaophan-note").css('display','none');
		}
		$("#hidden-giaophan").val(selectd.attr('status'));
		let giaoPhanId = selectd.val();
		getGiaoHat(giaoPhanId);
});
$("#submit-giaoxu-info").click(function(){
	$.ajax({
		url: path+'GiaoXuCL/insertInfo',
		type: 'post',
		dataType: 'json',
		data: {
			'txt-giaoxu-id':$("#txt-giaoxu-id").val(),
			'cb-giaophan-name':$("#cb-giaophan-name").val(),
			'cb-giaophan-name':$("#cb-giaophan-name").val(),
			'hidden-giaophan':$("#hidden-giaophan").val(),
			'cb-giaohat-name':$("#cb-giaohat-name").val(),
			'hidden-giaohat':$("#hidden-giaohat").val(),
			'txt-giaoxu-name':$("#txt-giaoxu-name").val(),
			'txt-email':$("#txt-email").val(),
			'txt-sdt':$("#txt-sdt").val(),
			'txt-website':$("#txt-website").val(),
			'txt-diachi':$("#txt-diachi").val()
		}
	}).done(function(data){
		if(data.success == 'success'){
			alert('Cập nhật thông tin thành công');
			getGiaoXuEdit(("#txt-giaoxu-id").val(),$("#txt-giaoxu-name").val());
		} else {
			alert('Cập nhật thông tin không thành công');
		}
	})
})
});