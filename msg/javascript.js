



function openSmiley(smurl) {
w=window.open(smurl, "smileys", "fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,directories=no,location=no,width=300,height=300");
  if(!w.opener)
  {
  w.opener=self;
  }
}

function mboard_checkFields() {
d=document.form;
if (d.name.value=='') {alert('Please enter your name!'); return false;}
if (
    d.email.value!='' && (
      d.email.value.indexOf(".") == -1 ||
      d.email.value.indexOf("@") == -1
    )
) {alert('Please enter a valid e-mail address!'); return false;}
if (d.subject.value=='') {alert('Please write a subject!'); return false;}
if (d.message.value=='') {alert('Please write a message!'); return false;}
return true;
}

