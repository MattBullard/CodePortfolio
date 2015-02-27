function setCookie(name, value, days, nothost)
{
   if (days)
   {
      var date = new Date();
      date.setTime(date.getTime() + (days * 86400 * 1000));
      var expires = "; expires=" + date.toGMTString();
   }
   else
      var expires = "";

   domain = '';
   if (!nothost) {
	host = document.location.hostname;
	if (host.match(/(www|dev).*loathing/)) {
		host = host.replace(/(www[0-9]*|dev)/, '');
		domain = '; domain='+host;
	}
  }

   document.cookie = name + "=" + escape(value) + expires + "; path=/" + domain;
};

function getCookie(name)
{
   var cookie;
   var cookies = document.cookie.split(/;\s*/);
   for (cookie in cookies)
   {
      var array = cookies[cookie].split(/=/);
      if (array[0] == name)
         return array[1];
   }
   return 0;
};

function deleteCookie(name)
{
   setCookie(name, "", -1);
};

/*
 * 
 *
 */

function writeCookie(name,value,days) {
    var date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires=" + date.toGMTString();
            }else{
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var i, c, ca, nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return '';
}


