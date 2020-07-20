document.addEventListener('DOMContentLoaded', function (e) {

	function SetAnimation() {
		var allDetail = document.querySelectorAll('.btnDetail');
		for (var i = 0; i < allDetail.length; i++) {
			allDetail[i].addEventListener("click", function () {
				var content = document.querySelector('.wrap-content .content');
				content.classList.add('ani_in');
				content.addEventListener("webkitAnimationEnd", function () {
					this.classList.remove("ani_in");
				})
			})
		}
	}


	function createrNoidungGX(data) {
		var noidung = "";
		for (var i = 0; i < data.length; i++) {
			noidung += '<hr>';
			noidung += '<div class="row">';
			noidung += '<div class="col-xs-4">' + data[i].TenGiaoXu + '</div>';
			noidung += '<div class="col-xs-2">' + data[i].DienThoai + '</div>';
			noidung += '<div class="col-xs-3 txtWeb"><a target="_blank" href="' + data[i].Website + '">' + data[i].Website + '</a></div>';
			noidung += '<div class="col-xs-3">';
			noidung += '<b class="btn btn-success btnDetail"data-name="' + data[i].TenGiaoXu + '" data-id="' + data[i].MaGiaoXuRieng + '">Tệp</b>';
			noidung += " ";
			noidung += '<b class="btn btn-primary btn-edit" data-name="' + data[i].TenGiaoXu + '" data-id="' + data[i].MaGiaoXuRieng + '">Sửa giáo xứ</b>';
			noidung += '</div>';
			noidung += '</div>';
		}
		return noidung;
	}
	var path = $('#baseURL').val();
	$('body').on('click', '.page-link a', function (event) {
		url = $(this).attr('href');
		page = $(this).attr('data-ci-pagination-page');
		if (page == 1) {
			url += "/0";
		}
		$.ajax({
			url: url + '/1',
			type: 'post',
			dataType: 'json',
		})
			.always(function (dulieu) {
				if (dulieu != -1) {
					$('.wrap-giaoxu2').empty();
					var noidung = createrNoidungGX(dulieu.data);
					$('.wrap-giaoxu2').append(noidung);
					$('.wrap-giaoxu2').append(dulieu.pagi);
					setClickEdit();
					SetAnimation();
				}
			});
		return false;
	});
	function setClickEdit() {
		$(".btn-edit").click(function () {
			let giaoXuId = $(this).attr('data-id');
			let title = $(this).attr('data-name');
			getGiaoXuEdit(giaoXuId, title, true);
			$("#deny-giaoxu-info").hide();
			$("#move-giaoxu-info").hide();
			$('#edit-modal').modal('show');
		})
	}
	function getGiaoXusRequest() {
		let url = `GiaoXuCL/getGiaoXusRequest`;
		$.ajax({
			url: path + url,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				html += "<tr>"
				html += `<td>${data[i].UploadedDate}</td>`;
				html += `<td>${data[i].TenGiaoXu}</td>`;
				html += `<td><a href="#" class="consider" data-id='${data[i].MaGiaoXuDoi}' data-name='${data[i].TenGiaoXu}'>Xem xét</a></td>`
				html += "</tr>"
			}
			if (html == "") {
				$("#table-request tbody").html("<h4 class='text-center'>Hiện tại không có yêu cầu nào!</h4>");
				return false;
			} else {
				$("#table-request tbody").html(html);
			}
			$(".consider").click(function () {
				let giaoXuId = $(this).attr('data-id');
				let title = $(this).attr('data-name');
				getGiaoXuEdit(giaoXuId, title);
				$("#deny-giaoxu-info").show();
				$("#move-giaoxu-info").show();
				$('#edit-modal').modal('show');
			});
		});
	}
	function getGiaoXuEdit(giaoXuId, title, edit = false) {
		$(".edit").text("Giáo xứ " + title);
		getGiaoXu(giaoXuId, edit);
	}
	function getGiaoXu(maGiaoXuDoi, edit) {
		let url = "";
		if (edit) {
			url = `GiaoXuCL/getGxById/${maGiaoXuDoi}`;
		} else {
			url = `GiaoXuCL/getGiaoXuDoiByMaGiaoXuDoi/${maGiaoXuDoi}`;
		}

		$.ajax({
			url: path + url,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let giaoXu = data[0];
			let maGiaoPhan;
			let maGiaoHat;
			$("#thuocgiaophan").text("Thuộc giáo phận: " + giaoXu.TenGiaoPhan);
			$("#thuocgiaohat").text("Thuộc giáo hạt: " + giaoXu.TenGiaoHat);
			$("#txt-giaophan-name").val(giaoXu.TenGiaoPhan);
			$("#txt-giaohat-name").val(giaoXu.TenGiaoHat);
			$("#txt-giaoxu-name").val(giaoXu.TenGiaoXu);
			$("#txt-email").val(giaoXu.Email);
			$("#txt-sdt").val(giaoXu.DienThoai);
			$("#txt-website").val(giaoXu.Website);
			$("#txt-diachi").val(giaoXu.DiaChi);
			$("#txt-ghichu").val(giaoXu.GhiChu);
			if ((giaoXu.ID) != undefined) {
				$("#txt-giaoxu-id").val(giaoXu.MaGiaoXuRieng);
			}
			else {
				$("#txt-giaoxu-id").val(giaoXu.MaGiaoXuDoi)
			}
			$("#txt-giaoxu-hinh").val(giaoXu.Hinh);
			$("#status").val(giaoXu.status);
			if (edit) {
				maGiaoPhan = giaoXu.MaGiaoPhan;
				maGiaoHat = giaoXu.Ma_GiaoHat;
			} else {
				maGiaoPhan = giaoXu.MaGiaoPhanRieng;
				maGiaoHat = giaoXu.MaGiaoHatRieng;
			}
			getGiaoPhan(maGiaoPhan, maGiaoHat);
		});
	}
	function getGiaoHat(giaoPhanId, giaoHatCurrentId) {
		//getGHByIdGP
		let url = `GiaoHatCL/getGHByIdGP/${giaoPhanId}/web`;
		$.ajax({
			url: path + url,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				if (data[i].status == 0) {
					html += `<option value='${data[i].MaGiaoHat}' status='${data[i].status}'>${data[i].TenGiaoHat + " (Tạo mới) "}</option>`;
				} else {
					html += `<option value='${data[i].MaGiaoHat}' status='${data[i].status}'>${data[i].TenGiaoHat}</option>`;
				}
			}
			$("#cb-giaohat-name").html(html);
			$("#cb-giaohat-name").val(giaoHatCurrentId);
			if ($("#cb-giaohat-name").find(":selected").attr('status') == 0) {
				$(".giaohat-note").css('display', 'block');
			} else {
				$(".giaohat-note").css('display', 'none');
			}
		});
	}

	function getGiaoPhan(maGiaoPhanCurrent, maGiaoHatCurrent) {
		let giaoPhanUrl = `GiaoPhanCL/getGPjson/web`;
		$.ajax({
			url: path + giaoPhanUrl,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				if (data[i].status == 0) {
					html += `<option value='${data[i].MaGiaoPhan}' status='${data[i].status}'>${data[i].TenGiaoPhan + " (Tạo mới) "}</option>`;
				} else {
					html += `<option value='${data[i].MaGiaoPhan}' status='${data[i].status}'>${data[i].TenGiaoPhan}</option>`;
				}
			}
			$("#cb-giaophan-name").html(html);
			$("#cb-giaophan-name").val(maGiaoPhanCurrent);
			let selectd = $("#cb-giaophan-name").find(":selected");
			if (selectd.attr('status') == 0) {
				$(".giaophan-note").css('display', 'block');
			} else {
				$(".giaophan-note").css('display', 'none');
			}
			getGiaoHat(maGiaoPhanCurrent, maGiaoHatCurrent);
		})
	}
	function showData(nameSS, urlCL, title) {
		$('.TieuDeShow').text(title);
		$.ajax({
			url: path + urlCL,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		})
			.always(function (dulieu) {
				if (dulieu != -1) {
					var noidung = '';
					$('.wrap-giaoxu2').empty();
					noidung = createrNoidungGX(dulieu.data);
					$('.wrap-giaoxu2').append(noidung);
					$('.wrap-giaoxu2').append(dulieu.pagi);
					setClickEdit();
					SetAnimation();
				}
			});

	}
	$('.files').click(function () {
		showData("GX", "GiaoXuCL/index/0/1", "Giáo xứ trên hệ thống");
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

	$('.nut-remove').click(function () {
		closeEmail();
	});
	$('#txtContentEmail').text('');

	$('#btnSendEmail').click(function () {
		var email = $('#inputTo').val();
		if (!validateEmail(email)) {
			$('.tooltip').addClass('active');
			setTimeout(function () {
				$('.tooltip').removeClass('active');
			}, 2000);
			return;
		}

		$.ajax({
			url: path + 'GiaoXuCL/sendMail',
			type: 'post',
			dataType: 'json',
			data: {
				to: $('#inputTo').val(),
				subject: $('#inputSubject').val(),
				content: $('#txtContentEmail').val(),
				path: $(this).data('path'),
			},
		})
			.always(function (data) {
				console.log(data);
				if (data == 1) {
					closeEmail();
					$('.EmailSuccess').addClass('active');
					setTimeout(function () {
						$('.EmailSuccess').removeClass('active');
					}, 2000);
				}
				else {
					$('.EmailError').addClass('active');
					setTimeout(function () {
						$('.EmailError').removeClass('active');
					}, 2000);
				}
			});

	});
	$('.btnFind').click(function () {
		var contentFind = $('#contentFind').val();
		if (contentFind === "") {
			return;
		}
		$.ajax({
			url: path + 'GiaoXuCL/findContent',
			type: 'post',
			dataType: 'json',
			data: {
				value: contentFind,
			},
		})
			.always(function (data) {
				var length = 0;
				if (data != -1) {
					length = data.length;
					$('.wrap-giaoxu2').empty();
					var noidung = createrNoidungGX(data);
					$('.wrap-giaoxu2').append(noidung);
					setClickEdit();
					SetAnimation();
				}
				$('.TieuDeShow').text('Kết quả tìm kiếm: ' + contentFind);
				var noidung = "";
				noidung += '<span class="text-muted">';
				noidung += '<small> (' + length + ' kết quả)</small>';
				noidung += '</span>';
				$('.TieuDeShow').append(noidung);
			});

	});

	$('body').on("click", ".btnDownloadFile", function () {
		var id = $(this).data('id');
		window.location = path + 'BackupCL/downloadFile/' + id;
	});
	function outNenDen() {
		$('.wrap-content').removeClass('active');
		$('.wrap-backup').empty();
	}
	$(document).ajaxStart(function () {
		$('body').addClass('wait');

	}).ajaxComplete(function () {

		$('body').removeClass('wait');

	});
	$('.wrap-content .den').click(function () {
		outNenDen();
	});

	$('.btnCancel').click(function () {
		outNenDen();
	});
	//
	$('body').on('click', '.btnNewEmail', function () {

		$('.SoanEmail').addClass('active');
		var father = $(this).closest('.one-file');
		var path = father.find('.btnDownloadFile').data('path');
		var name = father.find('.btnDownloadFile').text();
		$('#btnSendEmail').data('path', path);
		$('#filekem').append('<i class="fa fa-paperclip"></i>' + name);

	});
	//chuyển file
	$('body').on('click', '.btnCopyFile', function () {
		//Call ajax Lấy máy nhập
		var father = $(this).closest('.one-file');
		var pathBackup = father.find('.btnDownloadFile').data('path');
		var name = father.find('.btnDownloadFile').text();
		var contentMail = "Hiện tại hệ thống qlgx.net đã chuyển tệp tin có tên " + name;
		contentMail += " vào máy của bạn. Vui lòng mở ứng dụng qlgx và vào";
		contentMail += " Hệ thống->Khôi phục dữ liệu->Máy chủ của hệ thống qlgx.net.";
		contentMail += " Chọn tệp tin " + name + " để khôi phục";
		var MaGiaoXuRiengGetMayNhap = pathBackup.split("/");
		$.ajax({
			url: path + 'BackupCL/getMayNhapByMaGiaoXuRieng/' + MaGiaoXuRiengGetMayNhap[1] + '/' + MaGiaoXuRiengGetMayNhap[2],
			type: 'post',
			dataType: 'json'
		}).always(function (data) {
			if (data != -1) {
				var noidung = data["noidung"];
				$('#cb-MayNhap').empty().append(noidung);
				$('#inputToCopyFile').val('');
				$('#inputSubjectCopyFile').val('Yêu cầu nhận tệp tin từ máy nhập khác');
				$('#txtContentEmailCopyFile').val(contentMail);
				$('#pathName').val(pathBackup);
				$('.CopyFile').addClass('active');
			}
			else {
				alert("Hiện tại giáo xứ chỉ có duy nhất một máy nhập");
			}
		});;


		/* var father=$(this).closest('.one-file');
		var path=father.find('.btnDowload').data('path');
		var name=father.find('.btnDowload').text();
		$('#btnSendEmail').data('path',path);
		$('#filekem').append('<i class="fa fa-paperclip"></i>'+name);
		alert("shut up"+path+"/"+name); */

	});

	//Button gửi
	$('body').on('click', '#btnCopyFile', function () {
		$pathBackup = $('#pathName').val().split('/');
		var email = $('#inputToCopyFile').val();
		if (!validateEmail(email)) {
			$('.tooltip').addClass('active');
			setTimeout(function () {
				$('.tooltip').removeClass('active');
			}, 2000);
			return;
		}
		$maynhap = $('#cb-MayNhap').val();
		if ($maynhap == null) {
			alert("Vui lòng chọn máy nhập cần chuyển tệp tin");
			return;
		}

		//Bắt đầu chuyển file và gửi mail
		$.ajax({
			url: path + 'BackupCL/copyFile',
			type: 'post',
			dataType: 'json',
			data: {
				"MaGiaoXuRieng": $pathBackup[1],
				"MaDinhDanhMayNhan": $('#cb-MayNhap').val(),
				"MaDinhDanhMayGui": $pathBackup[2],
				"FileName": $pathBackup[3],
				"TenMayNhan": $('#cb-MayNhap option:selected').text(),
				"To": $('#inputToCopyFile').val(),
				"Subject": $('#inputSubjectCopyFile').val(),
				"Body": $('#txtContentEmailCopyFile').val(),
			}
		}).always(function (data) {
			if (data != -1) {
				alert("Đã chuyển file thành công!");
				$('.CopyFile').removeClass('active');
			}
			else {
				alert("Hiện tại giáo xứ chỉ có duy nhất một máy nhập");
			}
		});;
	});

	$('.nut-removeCopyFile').click(function () {

		//close
		$('.CopyFile').removeClass('active');
	});

	$('body').on('click', '.btnDetail', function () {
		$('.wrap-content').addClass('active');
		$('#nameGx').text('Giáo xứ: ' + $(this).data("name"));
		var id = $(this).data('id');
		$.ajax({
			url: path + 'BackupCL/getAllFileByMaGiaoXuRieng/' + id,
			type: 'post',
			dataType: 'json',
		})
			.always(function (data) {
				if (data != -1) {
					var noidung = data["noidung"];
					$('.wrap-backup').append(noidung);
				}
			});

	});
	$(".down-management").click(() => {
		$("#child-management").toggle();
	})
	$("#management").click(() => {
		getGiaoXusRequest();
		$('#request-modal').modal('show');
	})
	setClickEdit();
	let resultRequest = getGiaoXusRequest();
	if (!resultRequest) {
		$('#request-modal').modal('show');
	}
	SetAnimation();
	$("#cb-giaohat-name").on('change', function () {
		let selectd = $("#cb-giaohat-name").find(":selected");
		if (selectd.attr('status') == 0) {
			$(".giaohat-note").css('display', 'block');
		} else {
			$(".giaohat-note").css('display', 'none');
		}
		$("#hidden-giaohat").val(selectd.attr('status'));
	})
	$("#cb-giaophan-name").on('change', () => {
		let selectd = $("#cb-giaophan-name").find(":selected");
		if (selectd.attr('status') == 0) {
			$(".giaophan-note").css('display', 'block');
		} else {
			$(".giaophan-note").css('display', 'none');
		}
		$("#hidden-giaophan").val(selectd.attr('status'));
		let giaoPhanId = selectd.val();
		getGiaoHat(giaoPhanId);
	});
	$("#deny-giaoxu-info").click(function () {

		$.ajax({
			url: path + 'GiaoXuCL/denyGiaoXu',
			type: 'post',
			dataType: 'json',
			data: {
				'txt-giaoxu-id': $("#txt-giaoxu-id").val(),
				'txt-email': $("#txt-email").val()
			}
		}).done(function (data) {
			if (data.success == 'success') {
				alert('Từ chối thành công');
			} else {
				alert('Từ chối thất bại');
			}
			$('#edit-modal').modal('hide');
			getGiaoXusRequest();
		})
	});
	$("#move-giaoxu-info").click(function () {
		let giaoXuId = $("#txt-giaoxu-id").val();
		let title = $("#txt-giaoxu-name").val();
		let email = $("#txt-email").val();
		$('#txt-giaoxu-doiid').val(giaoXuId);
		$('#txt-giaoxu-doiname').val(title);
		$('#txt-email-doi').val(email);
		$('#edit-modal').modal('hide');
		$('#move-giaoxu-modal').modal('show');
		getListGiaoPhan();
	});
	function getListGiaoPhan() {
		$.ajax({
			url: path + "GiaoPhanCL/getGPjson/web",
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				html += `<option value='${data[i].MaGiaoPhan}' status='${data[i].status}'>${data[i].TenGiaoPhan}</option>`;
			}
			$("#cb-giaophan-list").html(html);
			getListGiaoHat($("#cb-giaophan-list").val());
		})
	}
	$("#cb-giaophan-list").change(function () {
		getListGiaoHat($(this).val())
	});
	function getListGiaoHat(maGiaoPhan) {
		$.ajax({
			url: path + "GiaoHatCL/getGHByIdGP/" + maGiaoPhan + "\/web",
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				html += `<option value='${data[i].MaGiaoHat}' status='${data[i].status}'>${data[i].TenGiaoHat}</option>`;
			}
			$("#cb-giaohat-list").html(html);
			getListGiaoXu($("#cb-giaohat-list").val());
		});
	}
	$("#cb-giaohat-list").change(function () {
		getListGiaoXu($(this).val())
	});
	function getListGiaoXu(maGiaoHat) {
		$.ajax({
			url: path + "GiaoXuCL/getGXByIdGH/" + maGiaoHat,
			type: 'post',
			headers: {
				'password': 'admin',
			},
			dataType: 'json',
		}).always((data) => {
			let html = "";
			for (let i = 0; i < data.length; i++) {
				html += `<option value='${data[i].MaGiaoXuRieng}' status='${data[i].status}'>${data[i].TenGiaoXu}</option>`;
			}
			$("#cb-giaoxu-list").html(html);
			if (html == "") {
				alert("Hiện tại giáo hạt chưa có giáo xứ nào");
			}
		});
	}

	$(".btnclosemove").click(function () {
		/* let giaoXuId = $('#txt-giaoxu-doiid').val();
		let title = $('#txt-giaoxu-doiname').val();
		getGiaoXuEdit(giaoXuId,title); */
		//$('#edit-modal').modal('show');
	});
	$("#btnmove-giaoxu-info").click(function () {
		if ($("#cb-giaophan-list").val() <= 0) {
			alert("Vui lòng chọn giáo phận");
			return;
		}
		if ($("#cb-giaohat-list").val() <= 0) {
			alert("Vui lòng chọn giáo hạt");
			return;
		}
		if ($("#cb-giaoxu-list").val() <= 0) {
			alert("Vui lòng chọn giáo xứ");
			return;
		}
		//chuyển
		$.ajax({
			url: path + "GiaoXuCL/updateGiaoXuDoiMove",
			type: "post",
			data: {
				'MaGiaoXuDoi': $('#txt-giaoxu-doiid').val(),
				'TenGiaoPhan': $("#cb-giaophan-list option:selected").text(),
				'MaGiaoPhanRieng': $("#cb-giaophan-list").val(),
				'TenGiaoHat': $("#cb-giaohat-list option:selected").text(),
				'MaGiaoHatRieng': $("#cb-giaohat-list").val(),
				'MaGiaoXuRieng': $("#cb-giaoxu-list").val(),
				'TenGiaoXu': $("#cb-giaoxu-list option:selected").text(),
				'Email': $('#txt-email-doi').val()
			},
			headers: {
				'password': 'admin'
			},
			dataType: 'json'
		}).always((data) => {
			if (data.success == 'success') {
				alert('Chuyển thành công');
			} else {
				alert('Chuyển thất bại');
			}
			$('#move-giaoxu-modal').modal('hide');
			getGiaoXusRequest();
		});
	});
	$("#submit-giaoxu-info").click(function () {
		if ($("#cb-giaophan-name").val() <= 0) {
			alert("Vui lòng chọn giáo phận");
			return;
		}
		if ($("#cb-giaohat-name").val() <= 0) {
			alert("Vui lòng chọn giáo hạt");
			return;
		}
		var status = $("#status").val();
		let urlparam = "";
		if (status == 0) {
			urlparam = path + 'GiaoXuCL/insertGiaoXu';
		} else {
			urlparam = path + 'GiaoXuCL/updateGiaoXu';
		}
		$.ajax({
			url: urlparam,
			type: 'post',
			dataType: 'json',
			data: {
				'txt-giaoxu-id': $("#txt-giaoxu-id").val(),
				'txt-giaoxu-hinh': $("#txt-giaoxu-hinh").val(),
				'cb-giaophan-nameMa': $("#cb-giaophan-name").val(),
				'cb-giaophan-nameTen': $("#cb-giaophan-name option:selected").text(),
				'hidden-giaophan': $("#hidden-giaophan").val(),
				'cb-giaohat-nameMa': $("#cb-giaohat-name").val(),
				'cb-giaohat-nameTen': $("#cb-giaohat-name option:selected").text(),
				'hidden-giaohat': $("#hidden-giaohat").val(),
				'txt-giaoxu-name': $("#txt-giaoxu-name").val(),
				'txt-email': $("#txt-email").val(),
				'txt-sdt': $("#txt-sdt").val(),
				'txt-website': $("#txt-website").val(),
				'txt-diachi': $("#txt-diachi").val(),
				'txt-ghichu': $("#txt-ghichu").val()
			}
		}).done(function (data) {
			if (data.success == 'success') {
				alert('Cập nhật thông tin thành công');
			} else {
				alert('Cập nhật thông tin thất bại');
			}
			$('#edit-modal').modal('hide');
			getGiaoXusRequest();
		})
	})
});