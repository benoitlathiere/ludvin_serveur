function encrypt() {
						
						document.authentication.password.value = MD5(document.authentication.passwordEdit.value);
					
						document.authentication.passwordEdit.value = "";
					}
					
					function editText(t) {
						if (t.value == t.defaultValue) {
							t.value = '';
							t.style.color = "#000000";
							if (document.selection && document.selection.clear)
								document.selection.clear();
						}
					}
					
					function inviteText(t) {
						if (t.value == '') {
							t.value = t.defaultValue;
							t.style.color = "#C0C0C0";
						} 
					}
					
					function editPassword(t) {
						if (t.value == t.defaultValue) {
							t.value = '';
							t.style.color = "#000000";
							if (document.selection && document.selection.clear)
								document.selection.clear();
							t.type = "password";
						}
					}
					
					function invitePassword(t) {
						if (t.value == '') {
							t.value = t.defaultValue;
							t.style.color = "#C0C0C0";
							t.type = "text";
						} 
					}
					
					function connectIn(t) {
						t.style.background = "#B4A139";
					}
					
					function connectOut(t) {
						t.style.background = "#E7D46C";
					}