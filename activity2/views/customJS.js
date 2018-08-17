$(function(){
	$(".hidethis").hide();
});


// pag check kung naa bay sud ang element
function checkEmptyField(field){
	if(field == ""){
		return true;
	}
	else{
		return false;
	}
}

// pagpakita sa error
function showDivError(id,message){
	$(id).show();
	$(id).append(message+"<br>");
}

// register
$(function(){
	$("#register").submit(function(event){
		event.preventDefault();
		let inputName = $("#inputName");
		let inputUsername = $("#inputUsername");
		let inputPassword = $("#inputPassword");
		let inputVerify = $("#inputVerify");
		if(checkEmptyField(inputName.val()) ||  checkEmptyField(inputUsername.val()) || checkEmptyField(inputPassword.val()) 
			|| checkEmptyField(inputVerify.val()) || inputPassword.val() != inputVerify.val()){
			if(checkEmptyField(inputName.val())){
				showDivError("#regError","Please input Name");
			}
			if(checkEmptyField(inputUsername.val())){
				showDivError("#regError","Please input Username");
			}
			if(checkEmptyField(inputPassword.val())){
				showDivError("#regError","Please input Password");
			}
			if(checkEmptyField(inputVerify.val())){
				showDivError("#regError","Please input PasswordVerify");
			}
			if(inputPassword.val() != inputVerify.val()){
				showDivError("#regError","Please make sure to input your password as same on 1st password input");
			}
		}else{
			$("#regError").hide();
			let dataFields = $("#register").serialize();
			$.ajax({
				url: "../controller/controller.php", //controller.php sa controller
				method: "POST",
				data: dataFields, //ang result ani kay username=qwe&password=353 blah blah blah
				success: function(a){
					let obj = JSON.parse(a);
					if(obj.error == "exist"){ //ang return sa controller kay exist, pasabot naay username na in ana
						showDivError("#regError","Username exist. Please choose another username");
					}
					else if(obj.error == "none"){ //wala
						alert("Successfully Register");
						window.location = "login.php";
					}else{
						alert(a);
					}

				},
				fail: function(){
					alert("cannot connect to server");
				}
			});
		}

	});
});

// pariha rana tanan balik2 ra
$(function(){
	$("#loginformSubmit").submit(function(event){
		event.preventDefault();
		let username = $("#inputUsername");
		let password = $("#inputPassword");
		let valid = $(".valid-feedback");
		let inValid = $(".invalid-feedback");
		if(checkEmptyField(username.val()) || checkEmptyField(password.val())){
			if(checkEmptyField(username.val())){
				showDivError("#errorDiv","Please don't forget to put your username");
			}
			if(checkEmptyField(password.val())){
				showDivError("#errorDiv","Please don't forget to put your password");
			}
		}else{
			valid.show();
			inValid.hide();
			let dataFields = $("#loginformSubmit").serialize();
			$.ajax({
				url: "../controller/controller.php",
				method: "POST",
				data: dataFields,
				success: function(a){
					let obj = JSON.parse(a);
					if(obj.error == "notExist"){
						showDivError("#errorDiv","Account doesn't exist. Please check your username or password");
					}
					else if(obj.error == "none"){
						$("#errorDiv").hide();
						window.location = "index.php";
					}
				},
				fail: function(){
					alert("cannot connect to server");
				}
			});
		}

	});
});

// para sa pag kuha sa ngan(kilid sa image)
$(function(){
	$("#nameLabel").ready(function(){
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"nameGet":""},
			success: function(a){
				$("#nameLabel").html(a);
			}
		});
	});
});


// para sa data sa table
$(function(){
	$("#bodyData").ready(function(){
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"getType":""},
			success: function(a){
				$("#bodyData").html(a);
			}
		});
	});
});

// para makita ang edit
$(function(){
	$("#editBody").ready(function(){
		let id = $("input[name=id]").val();
		let fname = $("#fname");
		let username = $("#username");
		let password = $("#password");
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"editUser":"","id":id},
			success: function(a){
				let obj = JSON.parse(a);
				fname.val(obj.fullname);
				username.val(obj.username);
				password.val(obj.password);
			}
		});
	})
});

// para ma edit
$(function(){
	$("#editUser").submit(function(event){
		event.preventDefault();
		let id = $("input[name=id]").val();
		let fname = $("#fname").val();
		let username = $("#username").val();
		let password = $("#password").val();
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"editLast":"","id":id,"fname":fname,"username":username,"password":password},
			success: function(a){
				let obj = JSON.parse(a);
				if(obj.error == "none"){
					alert("successfully edited");
					window.location="index.php"
				}
			}
		});
	});
});

// para ma delete

function deleteUser(id){
	let alert = confirm("Are you sure you want to deactivate this user?");
	if(alert == true){
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"deleteUser":"","id":id,"status":0},
			success: function(a){
				let obj = JSON.parse(a);
				if(obj.error == "none"){
					window.location="index.php"
				}
			}
		});	
	}
}

// para ma activate
function activateUser(id){
	let alert = confirm("Are you sure you want to activate this user?");
	if(alert == true){
		$.ajax({
			url: "../controller/controller.php",
			method: "POST",
			data: {"deleteUser":"","id":id,"status":1},
			success: function(a){
				let obj = JSON.parse(a);
				if(obj.error == "none"){
					window.location="index.php"
				}
			}
		});	
	}
}