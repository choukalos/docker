(function(root,factory){'use strict';if(typeof define==='function'&&define.amd){define(['underscore'],factory);}else{root.mageTemplate=factory(root._);}}(this,function(_){'use strict';function isSelector(selector){try{document.querySelector(selector);return true;}catch(e){return false;}}
function unescape(str){return str.replace(/&lt;%|%3C%/g,'<%').replace(/%&gt;|%%3E/g,'%>');}
function getTmplString(tmpl){if(isSelector(tmpl)){tmpl=document.querySelector(tmpl);if(tmpl){tmpl=tmpl.innerHTML.trim();}else{console.warn('No template was found by selector: '+tmpl);tmpl='';}}
return unescape(tmpl);}
return function(tmpl,data){var render;tmpl=getTmplString(tmpl);render=_.template(tmpl);return!_.isUndefined(data)?render(data):render;};}));