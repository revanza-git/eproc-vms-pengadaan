<script type="text/javascript">

$(function(){
	dataPost = {
		order: 'id',
		sort: 'desc'
	};
	var _xhr;
	$.ajax({
					url: '<?php echo site_url('pemaketan/formFilter')?>',
					async: false,
					dataType: 'json',
					success:function(xhr){
						_xhr = xhr;
					}
				})
				

	var folder = $('#tableGenerator').folder({
		url: '<?php echo site_url('pemaketan/getData/'); ?>',
		data: dataPost,
		dataRightClick: function(key, btn, value){
			_id 		= value[key][3].value;
			urlDivision = '<?php echo base_url('pemaketan/division');?>/'+_id;

			btn = [{
				icon : 'search',
				label: 'Lihat Data',
				class: 'buttonView',
			}];
			return btn;
		},
		callbackFunctionRightClick: function(){
			var view = $('.buttonView').click(function(){
				$(location).attr('href',urlDivision);
			});
		},

		renderContent: function(el, value, key){
			html = '';
			html += '<div class="caption"><p>'+value[0].value+'</p><p><b>'+value[1].value+'</b> Item(s)</p></div>';
			console.log(folder);
			return html;
		},
		additionFeature: function(el){
			<?php if ($this->session->userdata('admin')['id_division'] == 5) { ?>
				el.prepend(insertStepButton(site_url+"pemaketan/insertStep/<?php echo $id;?>"));
			<?php } ?>
		},
		finish: function(){
     		

		},

		filter: {
			wrapper: $('.contentWrap'),
			data : {
				data: _xhr
			}
		}

	});
	var add = $('.buttonAdd').modal({
		render : function(el, data){
			data.onSuccess = function(){
				folder.data('plugin_tableGenerator').fetchData();

						$(add).data('modal').close();
			}

			$(el).form(data);
			
			$('.modal [name="pengadaan"]').on('change', function(){
				// get parent value
				var pengadaan = $(this).val();

				// Change option on select based on parent
				if (pengadaan == "barang") {
					$('.modal [name="jenis_pengadaan"]').empty();
					$('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value=''>Pilih Salah Satu</option><option value=''>Pilih Salah Satu</option><option value=''>Pilih Salah Satu</option>");
				}else if(pengadaan == "jasa"){
					$('.modal [name="jenis_pengadaan"]').empty();
					$('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Dibawah Ini</option><option value='jasa_konstruksi'>Jasa Konstruksi</option><option value='jasa_konsultasi'>Jasa Konsultasi</option><option value='jasa_lainnya'>Jasa Lainnya</option>");
				}else{
					$('.modal [name="jenis_pengadaan"]').empty();
					$('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Jenis Pengadaan Diatas</option>");
				}
			});
		}
	});

	var resiko = $('.buttonStep').modal({
		header: 'Tambah Data FPPBJ',
		render : function(el, data){
			// console.log(data);

			$('#step5 .form5').append('<div class="alert" style="color: #f90606; font-size: 95%; text-align: center;"></div>')
			var fw = $(el).formWizard({
				data: data,
				url: '<?php echo site_url("pemaketan/insert");?>',
				onNext: function(){
					_wizard = $(fw).data('formWizard');
					
					step = _wizard.options.data.step;
					var _step = [];
					var i = 1;
					$.each(step, function(key, value){
						_step[i] = key;
						i++;
					})
					_formWrapper = $('.wizard-content #step'+_wizard.options.currentPosition);
					_form = $('form',_wizard.options.wrapper);
					formData = new FormData( _form[0]);
					formData .append('validation', _step[_wizard.options.currentPosition]);

					$.ajax({
						url			: '<?php echo site_url('pemaketan/insertFPPBJ')?>',
						data		: formData,
						method 		: 'POST',
						processData	: false,
						contentType	: false,
						async		: false,
						dataType	: 'json',	
						success: function(xhr){
							_formWrapper.data('form').element = _formWrapper;

							if(xhr.status=='error') {
								_formWrapper.data('form').options.errorMessage = 'Terjadi Kesalahan';
								_formWrapper.data('form').generateError(xhr.form);

								_return = false;
							}else{
								_formWrapper.data('form').removeError(_formWrapper);
								_return = true;
							}
						}
					});

					return _return;
				},
				onSubmit: function(){
					
				},
				onSuccessSubmit: function(){
					$(resiko).data('modal').close();
					folder.data('plugin_folder').fetchData();
					location.reload();
					// console.log(resiko);
				},
				onSuccess: function(){
					$(resiko).data('modal').close();
				}
			})
			
			// TIPE PR
			$('.modal [name="tipe_pr"]').on('change', function(){
				// get parent value
				var tipe_pr = $(this).val();
				// alert(pengadaan);

				// Change option on select based on parent
				if (tipe_pr == "direct_charge") {
					$('.modal [name="pengadaan"]').empty();
					$('.modal [name="pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='barang'>Pengadaan Barang</option>");
					$('.modal [name="metode_pengadaan"]').empty();
					$('.modal [name="metode_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='1'>Pelelangan</option><option value='2'>Pemilihan Langsung</option><option value='4'>Penunjukan Langsung</option><option value='5'>Pengadaan Langsung</option>");
				}
				else if(tipe_pr == "services"){
					$('.modal [name="pengadaan"]').empty();
					$('.modal [name="pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='jasa'>Pengadaan Jasa</option>");
					$('.modal [name="metode_pengadaan"]').empty();
					$('.modal [name="metode_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='1'>Pelelangan</option><option value='2'>Pemilihan Langsung</option><option value='4'>Penunjukan Langsung</option><option value='5'>Pengadaan Langsung</option>");
				}
				else if(tipe_pr == "user_purchase"){
					$('.modal [name="pengadaan"]').empty();
					$('.modal [name="pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='barang'>Pengadaan Barang</option><option value='jasa'>Pengadaan Jasa</option>");
					$('.modal [name="metode_pengadaan"]').empty();
					$('.modal [name="metode_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='5'>Pengadaan Langsung</option>");
				}
				else if(tipe_pr == "nda"){
					$('.modal [name="pengadaan"]').empty();
					$('.modal [name="pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='barang'>Pengadaan Barang</option><option value='jasa'>Pengadaan Jasa</option>");
					$('.modal [name="metode_pengadaan"]').empty();
					$('.modal [name="metode_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='3'>Swakelola</option>");
				}
			});

			// PENGADAAN TIPE
			$('.modal [name="pengadaan"]').on('change', function(){
				// get parent value
				var pengadaan = $(this).val();
				// alert(pengadaan);

				// Change option on select based on parent
				if (pengadaan == "barang") {
					$('.modal [name="jenis_pengadaan"]').empty();
					$('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Salah Satu</option><option value='stock'>Stock</option><option value='non_stock'>non Stock</option>");
				}else if(pengadaan == "jasa"){
					$('.modal [name="jenis_pengadaan"]').empty();
					$('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Dibawah Ini</option><option value='jasa_konstruksi'>Jasa Konstruksi</option><option value='jasa_konsultasi'>Jasa Konsultasi</option><option value='jasa_lainnya'>Jasa Lainnya</option>");

				}else{
					// $('.modal [name="jenis_pengadaan"]').empty();
					// $('.modal [name="jenis_pengadaan"]').append("<option value=''>Pilih Jenis Pengadaan Diatas</option>");
				}
			});
			

			// ANALISA RISIKO
				// POP UP ANALISA RISIKO
					var textbox = '';
					$('.nm-tg').click(function() {
						$('.nm-wrapper').addClass('active');
						
						line 	= "."+$(this).closest('tr').attr('class'); 
						parent	= $(this).parent().parent().find('[name="'+$(this).attr("name")+'"]'); 
						// console.log(parent);
						// var textbox = parent+' [name="'+$(this).attr("name")+'"]';
						
						if ('[name="'+$(this).attr("name")+'"]' == "manusia") {
								$('.new-matrix .manusia_desc').show();
								$('.new-matrix .aset_desc').hide();
								$('.new-matrix .lingkungan_desc').hide();
								$('.new-matrix .hukum_desc').hide();
							}else if ('[name="'+$(this).attr("name")+'"]' == "asset") {
								$('.new-matrix .manusia_desc').hide();
								$('.new-matrix .aset_desc').show();
								$('.new-matrix .lingkungan_desc').hide();
								$('.new-matrix .hukum_desc').hide();
							}else if ('[name="'+$(this).attr("name")+'"]' == "lingkungan") {
								$('.new-matrix .manusia_desc').hide();
								$('.new-matrix .aset_desc').hide();
								$('.new-matrix .lingkungan_desc').show();
								$('.new-matrix .hukum_desc').hide();
							}else if ('[name="'+$(this).attr("name")+'"]' == "hukum") {
								$('.new-matrix .manusia_desc').hide();
								$('.new-matrix .aset_desc').hide();
								$('.new-matrix .lingkungan_desc').hide();
								$('.new-matrix .hukum_desc').show();
						}
					});

					$('.nm-box').click(function() {
						var matrix = parseInt($(this).text());
						// APPEND TO INPUT
						nmBox(parent, matrix);
						// console.log(nmBox(parent, matrix))
						// GET CLASS OF THE INPUT
							var question 	= line;
							var _question 	= question+" input";
							// console.log("Line >>"+question);
							// console.log("Question >>"+_question);

							var manusia		= question+' [name="manusia[]"]';
							var asset		= question+' [name="asset[]"]';
							var lingkungan	= question+' [name="lingkungan[]"]';
							var hukum		= question+' [name="hukum[]"]';
							var catatan		= question+' .catatan';
							
							
							var _qtotal	= 0;
							// define category each question
							var _manusia	= parseInt($(manusia).val());
							var _asset		= parseInt($(asset).val());
							var _lingkungan	= parseInt($(lingkungan).val());
							var _hukum		= parseInt($(hukum).val());
							
							var cat_manusia		= setCategory(_manusia);
							var cat_asset		= setCategory(_asset);
							var cat_lingkungan	= setCategory(_lingkungan);
							var cat_hukum		= setCategory(_hukum);
												
							
								if (cat_manusia == "extreme" || cat_asset == "extreme" || cat_lingkungan == "extreme" || cat_hukum == "extreme") {
									category = '<span id="catatan" class="catatan red">E</span>';
								}else if (cat_manusia == "high" || cat_asset == "high" || cat_lingkungan == "high" || cat_hukum == "high") {
									category = '<span id="catatan" class="catatan red">H</span>';
								}else  if (cat_manusia == "medium" || cat_asset == "medium" || cat_lingkungan == "medium" || cat_hukum == "medium") {
									category = '<span id="catatan" class="catatan yellow">M</span>';
								}else if (cat_manusia == "low" || cat_asset == "low" || cat_lingkungan == "low" || cat_hukum == "low") {
									category = '<span id="catatan" class="catatan green">L</span>';
								}else{
									category = '<span id="catatan" class="catatan">?</span>';
								}
							
								$(catatan).empty();
								$(catatan).append(category);
								
								var lineTotal	= $(line+" td .catatan .catatan").text();
								var grandTotal	= getTotal();
								
								// console.log("TOTAL>>"+grandTotal);

								var cat__ = 0;
								if (grandTotal.indexOf("E") >= 0) {
										$("#total").empty();
										$("#total").append('<span class="catatan red">E</span>');
										// console.log(">>>> Extreme");
										cat__="1";

									}else if (grandTotal.indexOf("H") >= 0) {
										$("#total").empty();
										$("#total").append('<span class="catatan red">H</span>');
										// console.log(">>>> High");
										cat__="1";

									}else if (grandTotal.indexOf("M") >= 0) {
										$("#total").empty();
										$("#total").append('<span class="catatan yellow">M</span>');
										// console.log(">>>> Medium");
										cat__="2";

									}else if (grandTotal.indexOf("L") >= 0) {
										$("#total").empty();
										$("#total").append('<span class="catatan green">L</span>');
										// console.log(">>>> Low");
										cat__="3";
										
								}
							
							// GET THIS DPT RECOMMENDATION
							$.ajax({
								url: '<?php echo site_url('main/get_dpt_csms/')?>/'+cat__,
								// data: category,
								dataType: 'json',
								complete : function(){
								},
								success: function(dpt){
									// console.log(dpt);
									$('#step4 .checkboxWrapper').empty();
									$('#step4 label').css("float", "left")

									$('#step4 .checkboxWrapper').append('<div class="search-recomendation"><input id="searchDPT" type="text" onkeyup="filterDPT()" class="sc" placeholder="Cari DPT"/><span class="icon"><i class="fas fa-search"></i></span></div>');
									dpt.forEach(function(element) {
										$('#step4 .checkboxWrapper').append('<div class="inputGroup" id="inputGroup"> <input id="option'+element.id_vendor+'" name="type[]" type="checkbox" value="'+element.id_vendor+'"/> <label for="option'+element.id_vendor+'">'+element.vendor+' ('+element.score+')</label> </div>');
									});
								}
							});

							$("#searchDPT").on('keyup', function(){
								var matcher = new RegExp($(this).val(), 'gi');
								// console.log(matcher);
								// console.log($(this).val());
								$('.checkboxWrapper').show().not(function(){
									return matcher.test($(this).find('.inputGroup label').text())
								}).hide();
							});
						// });
					
					//Close the pop up 
					$('.nm-wrapper').removeClass('active');

					// Reset Parent Value
					parent = '';
				});
					
				$('.nm-box').click(function() {
					$('.nm-wrapper').removeClass('active');
				});

			// ANALISA SWAKELOLA
			$('#step5 select').on('change', function(){
				$(".matrix-box").removeClass("active");
				$(".ms-item").removeClass("active");

				// SWAKELOLA
				var waktu		= parseInt($('.modal [name="waktu"]').val());
				var biaya 		= parseInt($('.modal [name="biaya"]').val());
				var tenaga 		= parseInt($('.modal [name="tenaga"]').val());
				var bahan 		= parseInt($('.modal [name="bahan"]').val());
				var peralatan	= parseInt($('.modal [name="peralatan"]').val());

				// PARAMETER
				var swakelola	= waktu + biaya + tenaga + bahan + peralatan;
				
				var _class = '.m'+(swakelola);
				// jQuery(_class).addClass("active");
				$(_class).addClass('active');
				if (swakelola >= 12) {
					$('#step5 .alert').empty();
					$('#step5 .alert').append('Pengadaan harus dilaksanakan dengan metode pemilihan pengadaan barang/jasa yang lain.');
					$('#step5 .btn-submit').prop('disabled',true);
					$('#step5 .btn-submit').css('display','none');
				} else{
					$('#step5 .alert').empty();
					// $('#step5 .alert').remove();
					$('#step5 .btn-submit').removeAttr('disabled');
					$('#step5 .btn-submit').css('display','block');
				}
			});
			
			stepButton();
			_close();
		}
	});
});
function nmBox(ele,matrix){
	ele.val(matrix);
}

function stepButton(){
	var analisa_resiko 		= $('#stepHeader3');
	var dpt 				= $('#stepHeader4');
	var analisa_swakelola 	= $('#stepHeader5');
	var csms 				= $('#stepHeader6');
	
	// HIDE ALL MENU TAB
	$(analisa_resiko).hide();
	$(dpt).hide();
	$(csms).hide();
	$(analisa_swakelola).hide();
	//METODE PENGADAAN
	$('.modal [name="pengadaan"], .modal [name="metode_pengadaan"]').on('change', function(){
		var metode 				= $('.modal [name="metode_pengadaan"]').val();
		var pengadaan		 	= $('.modal [name="pengadaan"]').val();
		var form_swakelola 		= '<div class="form blockWrapper"><fieldset class="form-group   form0" for=""><label for="">Waktu*</label><select name="waktu" id="" class="form-control "><option value="0" selected="">Pilih Dibawah Ini</option><option value="1">Penyelesaian Pekerjaan ≤ 3 bulan</option><option value="2">Penyelesaian Pekerjaan &gt; 3 bulan s.d &lt; 6 bulan</option><option value="3">Penyelesaian Pekerjaan ≥ 6 bulan</option></select></fieldset><fieldset class="form-group   form1" for=""><label for="">Biaya*</label><select name="biaya" id="" class="form-control "><option value="0" selected="">Pilih Dibawah Ini</option><option value="1">Biaya Pelaksanaan Pekerjaan&nbsp;≤ 50 juta</option><option value="2">Biaya Pelaksanaan Pekerjaan&nbsp;&gt; 50 juta s.d &lt; 100 juta</option><option value="3">Biaya Pelaksanaan Pekerjaan&nbsp;≥ 100 juta</option></select></fieldset><fieldset class="form-group   form2" for=""><label for="">Tenaga Kerja*</label><select name="tenaga" id="" class="form-control "><option value="0" selected="">Pilih Dibawah Ini</option><option value="1">Kompetensi dan/atau ketersediaan jumlah Tenaga Kerja di Perusahaan memenuhi sebagai perencana dan pelaksana dan pengawas</option><option value="2">Kompetensi dan/atau ketersediaan jumlah Tenaga Kerja di Perusahaan memenuhi salah satu atau lebih sebagai perencana dan/atau pelaksana dan/atau pengawas</option><option value="3">Kompetensi dan/atau ketersediaan jumlah Tenaga Kerja di Perusahaan tidak memenuhi sebagai perencana dan pelaksana dan pengawas</option></select></fieldset><fieldset class="form-group   form3" for=""><label for="">Bahan*</label><select name="bahan" id="" class="form-control "><option value="0" selected="">Pilih Dibawah Ini</option><option value="1">Bahan mudah didapatkan langsung oleh Pekerja NR</option><option value="2">Bahan dapat diadakan melalui pihak ketiga</option><option value="3">Bahan lebih efisien apabila diadakan oleh pihak ketiga</option></select></fieldset><fieldset class="form-group   form4" for=""><label for="">Peralatan*</label><select name="peralatan" id="" class="form-control "><option value="0" selected="">Pilih Dibawah Ini</option><option value="1">Ketersediaan jumlah dan kemampuan peralatan kerja memenuhi kebutuhan pekerjaan</option><option value="2">Ketersediaan jumlah dan/atau kemampuan peralatan kerja tidak memenuhi kebutuhan pekerjaan</option><option value="3">Peralatan lebih efisien apabila diadakan oleh pihak ketiga</option></select></fieldset><fieldset class="form-group   form5" for=""><div class="matrix-swakelola-wrapper"><div class="matrix-swakelola"><div class="ms-item green m1">1</div><div class="ms-item green m2">2</div><div class="ms-item green m3">3</div><div class="ms-item green m4">4</div><div class="ms-item green m5">5</div><div class="ms-item green-light m6">6</div><div class="ms-item green-light m7">7</div><div class="ms-item green-light m8">8</div><div class="ms-item green-light m9">9</div><div class="ms-item green-light m10">10</div><div class="ms-item green-light sw m11">11</div><span class="ms-line"></span><div class="ms-item yellow pk m12">12</div><div class="ms-item yellow m13">13</div><div class="ms-item red m14">14</div><div class="ms-item red m15">15</div></div></div><div class="alert" style="color: #f90606; font-size: 95%; text-align: center;"></div></fieldset><div class="form-group btn-group"><button type="button" class="button is-primary btn-back">Sebelumnya</button><button type="button" class="button is-primary btn-submit">Lanjut</button></div></div>';
			

				/*******************************************************
				 ******* 			DEFINE FORM TYPE			 *******
				 *******************************************************/

				// JASA && SWAKELOLA
				if (metode == 3 && pengadaan == "jasa") {
						// SHOW MENU TAB
						$('#step5').empty();
						$('#step5').append(form_swakelola);
						$(analisa_resiko).show();
						$(dpt).show();
						$(csms).show();
						$(analisa_swakelola).show();
						$('#step4 .btn-group .btn-next').removeClass('btn-save').text('Lanjut');

					// JASA NON SWAKELOLA
					}else if(metode != 3 && pengadaan == "jasa"){
						$('#step5').empty();
						// SHOW MENU TAB
						$(analisa_resiko).show();
						$(dpt).show();
						$(csms).show();
						
						// HIDE SWAKELOLA
						$(analisa_swakelola).hide();

						$('.btn-to').attr('id', '3');
						$('#step5').hide();
					
					// BARANG && SWAKELOLA
					}else if(metode == 3 && pengadaan !== "jasa"){
						$('#step5').empty();
						$('#step5').append(form_swakelola);
						// HIDE ALL MENU TAB
						$(analisa_resiko).hide();
						$(dpt).hide();
						$(csms).hide();
						$(analisa_swakelola).show();
						$(csms).hide();

						$('.btn-to').attr('id', '5');
					
					// BARANG NON SWAKELOLA
					}else if(metode !== 3 && pengadaan !== "jasa"){
						$('#step5').empty();
						// HIDE ALL MENU TAB
						$(analisa_resiko).hide();
						$(dpt).hide();
						$(csms).hide();
						$(analisa_swakelola).hide();

						$('.btn-to').attr('id', '2');
						$('.btn-to').attr('id', ' end');
					
					// LAIN LAIN 
					}else{
						$('#step5').empty();
						// HIDE ALL MENU TAB
						$(analisa_resiko).hide();
						$(dpt).hide();
						$(csms).hide();
						$(analisa_swakelola).hide();
				}
					
	});	
}

function setCategory(val){
	if (val >= 1 && val <= 4) {
		return 'low';
		// return '<span id="catatan" class="catatan green">L</span>';
	}else if (val >= 5 && val <= 9) {
		return 'medium';
		// return '<span id="catatan" class="catatan yellow">M</span>';		
	}else if (val >= 10 && val <= 14) {
		return 'high';
		// return '<span id="catatan" class="catatan red">H</span>';
	}else if (val >= 15 && val <= 25) {
		return 'extreme';
		// return '<span id="catatan" class="catatan red">E</span>';
	}else{
		return false;
	}
}

function _close(){
	$('.btn-save').on('click', function(){
		$(resiko).data('modal').close();
		folder.data('plugin_folder').fetchData();
		location.reload();
	});
}

function getTotal(){
	var total__ = [];
	for (let q = 1; q <= 10; q++) {
		total__.push($('.q'+q+' td .catatan .catatan').text());
	}

	return total__;
}

function filterDPT(){
	// console.log($(".inputGroup label").val());
	$("#searchDPT").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$(".inputGroup").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}

function filterDPTFKPBJ(){
	// console.log($(".inputGroup label").val());
	$("#searchDPT").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$(".inputGroup").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}

function reject_notif(pesan){

	var notif = '<span class="tooltiptext reject">'+pesan+'</span>';
	console.log(notif)
	if($('.badge').hasClass('fppbj_reject') == true) {
		$('.fppbj_reject').addClass('tooltip').append(notif);
	}
	
}

</script>