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
