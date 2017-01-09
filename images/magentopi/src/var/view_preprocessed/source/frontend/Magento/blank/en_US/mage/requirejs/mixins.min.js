define('mixins',['module'],function(module){'use strict';var rjsMixins;function hasPlugin(name){return!!~name.indexOf('!');}
function addPlugin(name){return'mixins!'+name;}
function removeBaseUrl(url,config){var baseUrl=config.baseUrl||'',index=url.indexOf(baseUrl);if(~index){url=url.substring(baseUrl.length-index);}
return url;}
function getPath(name,config){var url=require.toUrl(name);return removeBaseUrl(url,config);}
function isRelative(name){return!!~name.indexOf('./');}
function applyMixins(target){var mixins=Array.prototype.slice.call(arguments,1);mixins.forEach(function(mixin){target=mixin(target);});return target;}
rjsMixins={load:function(name,req,onLoad,config){var path=getPath(name,config),mixins=this.getMixins(path),deps=[name].concat(mixins);req(deps,function(){onLoad(applyMixins.apply(null,arguments));});},getMixins:function(path){var config=module.config()||{},mixins=config[path]||{};return Object.keys(mixins).filter(function(mixin){return mixins[mixin]!==false;});},hasMixins:function(path){return this.getMixins(path).length;},processNames:function(names,context){var config=context.config;function processName(name){var path=getPath(name,config);if(!hasPlugin(name)&&(isRelative(name)||rjsMixins.hasMixins(path))){return addPlugin(name);}
return name;}
return typeof names!=='string'?names.map(processName):processName(names);}};return rjsMixins;});require(['mixins'],function(mixins){'use strict';var originalRequire=window.require,originalDefine=window.define,contexts=originalRequire.s.contexts,defContextName='_',hasOwn=Object.prototype.hasOwnProperty,getLastInQueue;getLastInQueue='(function () {'+'var queue  = globalDefQueue,'+'item   = queue[queue.length - 1];'+''+'return item;'+'})();';function getOwn(obj,prop){return hasOwn.call(obj,prop)&&obj[prop];}
window.require=function(deps,callback,errback,optional){var contextName=defContextName,context,config;if(!Array.isArray(deps)&&typeof deps!=='string'){config=deps;if(Array.isArray(callback)){deps=callback;callback=errback;errback=optional;}else{deps=[];}}
if(config&&config.context){contextName=config.context;}
context=getOwn(contexts,contextName);if(!context){context=contexts[contextName]=require.s.newContext(contextName);}
if(config){context.configure(config);}
deps=mixins.processNames(deps,context);return context.require(deps,callback,errback);};window.define=function(name,deps,callback){var context=getOwn(contexts,defContextName),result=originalDefine.apply(this,arguments),queueItem=require.exec(getLastInQueue),lastDeps=queueItem&&queueItem[1];if(Array.isArray(lastDeps)){queueItem[1]=mixins.processNames(lastDeps,context);}
return result;};Object.keys(originalRequire).forEach(function(key){require[key]=originalRequire[key];});Object.keys(originalDefine).forEach(function(key){define[key]=originalDefine[key];});window.requirejs=window.require;});