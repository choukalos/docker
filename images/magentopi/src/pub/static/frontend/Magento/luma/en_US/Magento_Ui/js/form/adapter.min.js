define(['jquery','underscore'],function($,_){'use strict';var buttons={'reset':'#reset','save':'#save','saveAndContinue':'#save_and_continue'},selectorPrefix='',eventPrefix;function initListener(callback,action){var selector=selectorPrefix?selectorPrefix+' '+buttons[action]:buttons[action],elem=$(selector)[0];if(!elem){return;}
if(elem.onclick){elem.onclick=null;}
$(elem).on('click'+eventPrefix,callback);}
function destroyListener(action){var selector=selectorPrefix?selectorPrefix+' '+buttons[action]:buttons[action],elem=$(selector)[0];if(!elem){return;}
if(elem.onclick){elem.onclick=null;}
$(elem).off('click'+eventPrefix);}
return{on:function(handlers,selectorPref,eventPref){selectorPrefix=selectorPrefix||selectorPref;eventPrefix=eventPref;_.each(handlers,initListener);selectorPrefix='';},off:function(handlers,eventPref){eventPrefix=eventPref;_.each(handlers,destroyListener);}};});