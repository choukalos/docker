define(['jquery','underscore','mage/utils/objects','mage/utils/strings'],function(jQuery,_,utils,stringUtils){'use strict';var tmplSettings=_.templateSettings,interpolate=/\$\{([\s\S]+?)\}/g,opener='${',template,hasStringTmpls;hasStringTmpls=(function(){var testString='var foo = "bar"; return `${ foo }` === foo';try{return Function(testString)();}catch(e){return false;}})();if(hasStringTmpls){template=function(tmpl,$){return eval('`'+tmpl+'`');};}else{template=function(tmpl,data){var cached=tmplSettings.interpolate;tmplSettings.interpolate=interpolate;tmpl=_.template(tmpl,{variable:'$'})(data);tmplSettings.interpolate=cached;return tmpl;};}
function isTemplate(value){return typeof value==='string'&&~value.indexOf(opener);}
function render(tmpl,data,castString){var last=tmpl;while(~tmpl.indexOf(opener)){tmpl=template(tmpl,data);if(tmpl===last){break;}
last=tmpl;}
return castString?stringUtils.castString(tmpl):tmpl;}
return{template:function(tmpl,data,castString,dontClone){if(typeof tmpl==='string'){return render(tmpl,data,castString);}
if(!dontClone){tmpl=utils.copy(tmpl);}
tmpl.$data=data||{};_.each(tmpl,function iterate(value,key,list){if(key==='$data'){return;}
if(isTemplate(key)){delete list[key];key=render(key,tmpl);list[key]=value;}
if(isTemplate(value)){list[key]=render(value,tmpl,castString);}else if(jQuery.isPlainObject(value)||Array.isArray(value)){_.each(value,iterate);}});delete tmpl.$data;return tmpl;}};});