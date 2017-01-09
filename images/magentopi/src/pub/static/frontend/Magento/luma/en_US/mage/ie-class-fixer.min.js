(function(){var userAgent=navigator.userAgent,html=document.documentElement,version=9,gap='';if(html.className){gap=' ';}
for(version;version<=10;version++){if(userAgent.indexOf('MSIE '+version)>-1){html.className+=gap+'ie'+version;}}
if(userAgent.match(/Trident.*rv[ :]*11\./)){html.className+=gap+'ie11';}})();