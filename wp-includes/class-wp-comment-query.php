                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /*! Iris Color Picker - v1.0.7 - 2014-11-28
* https://github.com/Automattic/Iris
* Copyright (c) 2014 Matt Wiebe; Licensed GPLv2 */
!function(a,b){function c(){var b,c,d="backgroundImage";j?k="filter":(b=a('<div id="iris-gradtest" />'),c="linear-gradient(top,#fff,#000)",a.each(l,function(a,e){return b.css(d,e+c),b.css(d).match("gradient")?(k=a,!1):void 0}),k===!1&&(b.css("background","-webkit-gradient(linear,0% 0%,0% 100%,from(#fff),to(#000))"),b.css(d).match("gradient")&&(k="webkit")),b.remove())}function d(b,c){return b="top"===b?"top":"left",c=a.isArray(c)?c:Array.prototype.slice.call(arguments,1),"webkit"===k?f(b,c):l[k]+"linear-gradient("+b+", "+c.join(", ")+")"}function e(b,c){var d,e,f,h,i,j,k,l,m;b="top"===b?"top":"left",c=a.isArray(c)?c:Array.prototype.slice.call(arguments,1),d="top"===b?0:1,e=a(this),f=c.length-1,h="filter",i=1===d?"left":"top",j=1===d?"right":"bottom",k=1===d?"height":"width",l='<div class="iris-ie-gradient-shim" style="position:absolute;'+k+":100%;"+i+":%start%;"+j+":%end%;"+h+':%filter%;" data-color:"%color%"></div>',m="","static"===e.css("position")&&e.css({position:"relative"}),c=g(c),a.each(c,function(a,b){var e,g,h;return a===f?!1:(e=c[a+1],b.stop!==e.stop&&(g=100-parseFloat(e.stop)+"%",b.octoHex=new Color(b.color).toIEOctoHex(),e.octoHex=new Color(e.color).toIEOctoHex(),h="progid:DXImageTransform.Microsoft.Gradient(GradientType="+d+", StartColorStr='"+b.octoHex+"', EndColorStr='"+e.octoHex+"')",m+=l.replace("%start%",b.stop).replace("%end%",g).replace("%filter%",h)),void 0)}),e.find(".iris-ie-gradient-shim").remove(),a(m).prependTo(e)}function f(b,c){var d=[];return b="top"===b?"0% 0%,0% 100%,":"0% 100%,100% 100%,",c=g(c),a.each(c,function(a,b){d.push("color-stop("+parseFloat(b.stop)/100+", "+b.color+")")}),"-webkit-gradient(linear,"+b+d.join(",")+")"}function g(b){var c=[],d=[],e=[],f=b.length-1;return a.each(b,function(a,b){var e=b,f=!1,g=b.match(/1?[0-9]{1,2}%$/);g&&(e=b.replace(/\s?1?[0-9]{1,2}%$/,""),f=g.shift()),c.push(e),d.push(f)}),d[0]===!1&&(d[0]="0%"),d[f]===!1&&(d[f]="100%"),d=h(d),a.each(d,function(a){e[a]={color:c[a],stop:d[a]}}),e}function h(b){var c,d,e,f,g=0,i=b.length-1,j=0,k=!1;if(b.length<=2||a.inArray(!1,b)<0)return b;for(;j<b.length-1;)k||b[j]!==!1?k&&b[j]!==!1&&(i=j,j=b.length):(g=j-1,k=!0),j++;for(d=i-g,f=parseInt(b[g].replace("%"),10),c=(parseFloat(b[i].replace("%"))-f)/d,j=g+1,e=1;i>j;)b[j]=f+e*c+"%",e++,j++;return h(b)}var i,j,k,l,m,n,o,p,q;return i='<div class="iris-picker"><div class="iris-picker-inner"><div class="iris-square"><a class="iris-square-value" href="#"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz"></div><div class="iris-square-inner iris-square-vert"></div></div><div class="iris-slider iris-strip"><div class="iris-slider-offset"></div></div></div></div>',m='.iris-picker{display:block;position:relative}.iris-picker,.iris-picker *{-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input+.iris-picker{margin-top:4px}.iris-error{background-color:#ffafaf}.iris-border{border-radius:3px;border:1px solid #aaa;width:200px;background-color:#fff}.iris-picker-inner{position:absolute;top:0;right:0;left:0;bottom:0}.iris-border .iris-picker-inner{top:10px;right:10px;left:10px;bottom:10px}.iris-picker .iris-square-inner{position:absolute;left:0;right:0;top:0;bottom:0}.iris-picker .iris-square,.iris-picker .iris-slider,.iris-picker .iris-square-inner,.iris-picker .iris-palette{border-radius:3px;box-shadow:inset 0 0 5px rgba(0,0,0,.4);height:100%;width:12.5%;float:left;margin-right:5%}.iris-picker .iris-square{width:76%;margin-right:10%;position:relative}.iris-picker .iris-square-inner{width:auto;margin:0}.iris-ie-9 .iris-square,.iris-ie-9 .iris-slider,.iris-ie-9 .iris-square-inner,.iris-ie-9 .iris-palette{box-shadow:none;border-radius:0}.iris-ie-9 .iris-square,.iris-ie-9 .iris-slider,.iris-ie-9 .iris-palette{outline:1px solid rgba(0,0,0,.1)}.iris-ie-lt9 .iris-square,.iris-ie-lt9 .iris-slider,.iris-ie-lt9 .iris-square-inner,.iris-ie-lt9 .iris-palette{outline:1px solid #aaa}.iris-ie-lt9 .iris-square .ui-slider-handle{outline:1px solid #aaa;background-color:#fff;-ms-filter:"alpha(Opacity=30)"}.iris-ie-lt9 .iris-square .iris-square-handle{background:0;border:3px solid #fff;-ms-filter:"alpha(Opacity=50)"}.iris-picker .iris-strip{margin-right:0;position:relative}.iris-picker .iris-strip .ui-slider-handle{position:absolute;background:0;margin:0;right:-3px;left:-3px;border:4px solid #aaa;border-width:4px 3px;width:auto;height:6px;border-radius:4px;box-shadow:0 1px 2px rgba(0,0,0,.2);opacity:.9;z-index:5;cursor:ns-resize}.iris-strip .ui-slider-handle:before{content:" ";position:absolute;left:-2px;right:-2px;top:-3px;bottom:-3px;border:2px solid #fff;border-radius:3px}.iris-picker .iris-slider-offset{position:absolute;top:11px;left:0;right:0;bottom:-3px;width:auto;height:auto;background:transparent;border:0;border-radius:0}.iris-picker .iris-square-handle{background:transparent;border:5px solid #aaa;border-radius:50%;border-color:rgba(128,128,128,.5);box-shadow:none;width:12px;height:12px;position:absolute;left:-10px;top:-10px;cursor:move;opacity:1;z-index:10}.iris-picker .ui-state-focus .iris-square-handle{opacity:.8}.iris-picker .iris-square-handle:hover{border-color:#999}.iris-picker .iris-square-value:focus .iris-square-handle{box-shadow:0 0 2px rgba(0,0,0,.75);opacity:.8}.iris-picker .iris-square-handle:hover::after{border-color:#fff}.iris-picker .iris-square-handle::after{position:absolute;bottom:-4px;right:-4px;left:-4px;top:-4px;border:3px solid #f9f9f9;border-color:rgba(255,255,255,.8);border-radius:50%;content:" "}.iris-picker .iris-square-value{width:8px;height:8px;position:absolute}.iris-ie-lt9 .iris-square-value,.iris-mozilla .iris-square-value{width:1px;height:1px}.iris-palette-container{position:absolute;bottom:0;left:0;margin:0;padding:0}.iris-border .iris-palette-container{left:10px;bottom:10px}.iris-picker .iris-palette{margin:0;cursor:pointer}.iris-square-handle,.ui-slider-handle{border:0;outline:0}',o=navigator.userAgent.toLowerCase(),p="Microsoft Internet Explorer"===navigator.appName,q=p?parseFloat(o.match(/msie ([0-9]{1,}[\.0-9]{0,})/)[1]):0,j=p&&10>q,k=!1,l=["-moz-","-webkit-","-o-","-ms-"],j&&7>=q?(a.fn.iris=a.noop,a.support.iris=!1,void 0):(a.support.iris=!0,a.fn.gradient=function(){var b=arguments;return this.each(function(){j?e.apply(this,b):a(this).css("backgroundImage",d.apply(this,b))})},a.fn.raninbowGradient=function(b,c){var d,e,f,g;for(b=b||"top",d=a.extend({},{s:100,l:50},c),e="hsl(%h%,"+d.s+"%,"+d.l+"%)",f=0,g=[];360>=f;)g.push(e.replace("%h%",f)),f+=30;return this.each(function(){a(this).gradient(b,g)})},n={options:{color:!1,mode:"hsl",controls:{horiz:"s",vert:"l",strip:"h"},hide:!0,border:!0,target:!1,width:200,palettes:!1},_color:"",_palettes:["#000","#fff","#d33","#d93","#ee2","#81d742","#1e73be","#8224e3"],_inited:!1,_defaultHSLControls:{horiz:"s",vert:"l",strip:"h"},_defaultHSVControls:{horiz:"h",vert:"v",strip:"s"},_scale:{h:360,s:100,l:100,v:100},_create:function(){var b=this,d=b.element,e=b.options.color||d.val();k===!1&&c(),d.is("input")?(b.picker=b.options.target?a(i).appendTo(b.options.target):a(i).insertAfter(d),b._addInputListeners(d)):(d.append(i),b.picker=d.find(".iris-picker")),p?9===q?b.picker.addClass("iris-ie-9"):8>=q&&b.picker.addClass("iris-ie-lt9"):o.indexOf("compatible")<0&&o.indexOf("khtml")<0&&o.match(/mozilla/)&&b.picker.addClass("iris-mozilla"),b.options.palettes&&b._addPalettes(),b._color=new Color(e).setHSpace(b.options.mode),b.options.color=b._color.toString(),b.controls={square:b.picker.find(".iris-square"),squareDrag:b.picker.find(".iris-square-value"),horiz:b.picker.find(".iris-square-horiz"),vert:b.picker.find(".iris-square-vert"),strip:b.picker.find(".iris-strip"),stripSlider:b.picker.find(".iris-strip .iris-slider-offset")},"hsv"===b.options.mode&&b._has("l",b.options.controls)?b.options.controls=b._defaultHSVControls:"hsl"===b.options.mode&&b._has("v",b.options.controls)&&(b.options.controls=b._defaultHSLControls),b.hue=b._color.h(),b.options.hide&&b.picker.hide(),b.options.border&&b.picker.addClass("iris-border"),b._initControls(),b.active="external",b._dimensions(),b._change()},_has:function(b,c){var d=!1;return a.each(c,function(a,c){return b===c?(d=!0,!1):void 0}),d},_addPalettes:function(){var b=a('<div class="iris-palette-container" />'),c=a('<a class="iris-palette" tabindex="0" />'),d=a.isArray(this.options.palettes)?this.options.palettes:this._palettes;this.picker.find(".iris-palette-container").length&&(b=this.picker.find(".iris-palette-container").detach().html("")),a.each(d,function(a,d){c.clone().data("color",d).css("backgroundColor",d).appendTo(b).height(10).width(10)}),this.picker.append(b)},_paint:function(){var a=this;a._paintDimension("top","strip"),a._paintDimension("top","vert"),a._paintDimension("left","horiz")},_paintDimension:function(a,b){var c,d=this,e=d._color,f=d.options.mode,g=d._getHSpaceColor(),h=d.controls[b],i=d.options.controls;if(b!==d.active&&("square"!==d.active||"strip"===b))switch(i[b]){case"h":if("hsv"===f){switch(g=e.clone(),b){case"horiz":g[i.vert](100);break;case"vert":g[i.horiz](100);break;case"strip":g.setHSpace("hsl")}c=g.toHsl()}else c="strip"===b?{s:g.s,l:g.l}:{s:100,l:g.l};h.raninbowGradient(a,c);break;case"s":"hsv"===f?"vert"===b?c=[e.clone().a(0).s(0).toCSS("rgba"),e.clone().a(1).s(0).toCSS("rgba")]:"strip"===b?c=[e.clone().s(100).toCSS("hsl"),e.clone().s(0).toCSS("hsl")]:"horiz"===b&&(c=["#fff","hsl("+g.h+",100%,50%)"]):c="vert"===b&&"h"===d.options.controls.horiz?["hsla(0, 0%, "+g.l+"%, 0)","hsla(0, 0%, "+g.l+"%, 1)"]:["hsl("+g.h+",0%,50%)","hsl("+g.h+",100%,50%)"],h.gradient(a,c);break;case"l":c="strip"===b?["hsl("+g.h+",100%,100%)","hsl("+g.h+", "+g.s+"%,50%)","hsl("+g.h+",100%,0%)"]:["#fff","rgba(255,255,255,0) 50%","rgba(0,0,0,0) 50%","rgba(0,0,0,1)"],h.gradient(a,c);break;case"v":c="strip"===b?[e.clone().v(100).toCSS(),e.clone().v(0).toCSS()]:["rgba(0,0,0,0)","#000"],h.gradient(a,c)}},_getHSpaceColor:function(){return"hsv"===this.options.mode?this._color.toHsv():this._color.toHsl()},_dimensions:function(b){var c,d,e,f,g=this,h=g.options,i=g.controls,j=i.square,k=g.picker.find(".iris-strip"),l="77.5%",m="12%",n=20,o=h.border?h.width-n:h.width,p=a.isArray(h.palettes)?h.palettes.length:g._palettes.length;return b&&(j.css("width",""),k.css("width",""),g.picker.css({width:"",height:""})),l=o*(parseFloat(l)/100),m=o*(parseFloat(m)/100),c=h.border?l+n:l,j.width(l).height(l),k.height(l).width(m),g.picker.css({width:h.width,height:c}),h.palettes?(d=2*l/100,f=l-(p-1)*d,e=f/p,g.picker.find(".iris-palette").each(function(b){var c=0===b?0:d;a(this).css({width:e,height:e,marginLeft:c})}),g.picker.css("paddingBottom",e+d),k.height(e+d+l),void 0):g.picker.css("paddingBottom","")},_addInputListeners:function(a){var b=this,c=100,d=function(c){var d=new Color(a.val()),e=a.val().replace(/^#/,"");a.removeClass("iris-error"),d.error?""!==e&&a.addClass("iris-error"):d.toString()!==b._color.toString()&&("keyup"===c.type&&e.match(/^[0-9a-fA-F]{3}$/)||b._setOption("color",d.toString()))};a.on("change",d).on("keyup",b._debounce(d,c)),b.options.hide&&a.one("focus",function(){b.show()})},_initControls:function(){var b=this,c=b.controls,d=c.square,e=b.options.controls,f=b._scale[e.strip];c.stripSlider.slider({orientation:"vertical",max:f,slide:function(a,c){b.active="strip","h"===e.strip&&(c.value=f-c.value),b._color[e.strip](c.value),b._change.apply(b,arguments)}}),c.squareDrag.draggable({containment:c.square.find(".iris-square-inner"),zIndex:1e3,cursor:"move",drag:function(a,c){b._squareDrag(a,c)},start:function(){d.addClass("iris-dragging"),a(this).addClass("ui-state-focus")},stop:function(){d.removeClass("iris-dragging"),a(this).removeClass("ui-state-focus")}}).on("mousedown mouseup",function(c){var d="ui-state-focus";c.preventDefault(),"mousedown"===c.type?(b.picker.find("."+d).removeClass(d).blur(),a(this).addClass(d).focus()):a(this).removeClass(d)}).on("keydown",function(a){var d=c.square,e=c.squareDrag,f=e.position(),g=b.options.width/100;switch(a.altKey&&(g*=10),a.keyCode){case 37:f.left-=g;break;case 38:f.top-=g;break;case 39:f.left+=g;break;case 40:f.top+=g;break;default:return!0}f.left=Math.max(0,Math.min(f.left,d.width())),f.top=Math.max(0,Math.min(f.top,d.height())),e.css(f),b._squareDrag(a,{position:f}),a.preventDefault()}),d.mousedown(function(c){var d,e;1===c.which&&a(c.target).is("div")&&(d=b.controls.square.offset(),e={top:c.pageY-d.top,left:c.pageX-d.left},c.preventDefault(),b._squareDrag(c,{position:e}),c.target=b.controls.squareDrag.get(0),b.controls.squareDrag.css(e).trigger(c))}),b.options.palettes&&b._paletteListeners()},_paletteListeners:function(){var b=this;b.picker.find(".iris-palette-container").on("click.palette",".iris-palette",function(){b._color.fromCSS(a(this).data("color")),b.active="external",b._change()}).on("keydown.palette",".iris-palette",function(b){return 13!==b.keyCode&&32!==b.keyCode?!0:(b.stopPropagation(),a(this).click(),void 0)})},_squareDrag:function(a,b){var c=this,d=c.options.controls,e=c._squareDimensions(),f=Math.round((e.h-b.position.top)/e.h*c._scale[d.vert]),g=c._scale[d.horiz]-Math.round((e.w-b.position.left)/e.w*c._scale[d.horiz]);c._color[d.horiz](g)[d.vert](f),c.active="square",c._change.apply(c,arguments)},_setOption:function(b,c){var d,e,f,g=this,h=g.options[b],i=!1;switch(g.options[b]=c,b){case"color":c=""+c,d=c.replace(/^#/,""),e=new Color(c).setHSpace(g.options.mode),e.error?g.options[b]=h:(g._color=e,g.options.color=g.options[b]=g._color.toString(),g.active="external",g._change());break;case"palettes":i=!0,c?g._addPalettes():g.picker.find(".iris-palette-container").remove(),h||g._paletteListeners();break;case"width":i=!0;break;case"border":i=!0,f=c?"addClass":"removeClass",g.picker[f]("iris-border");break;case"mode":case"controls":if(h===c)return;return f=g.element,h=g.options,h.hide=!g.picker.is(":visible"),g.destroy(),g.picker.remove(),a(g.element).iris(h)}i&&g._dimensions(!0)},_squareDimensions:function(a){var c,d,e=this.controls.square;return a!==b&&e.data("dimensions")?e.data("dimensions"):(d=this.controls.squareDrag,c={w:e.width(),h:e.height()},e.data("dimensions",c),c)},_isNonHueControl:function(a,b){return"square"===a&&"h"===this.options.controls.strip?!0:"external"===b||"h"===b&&"strip"===a?!1:!0},_change:function(){var b=this,c=b.controls,d=b._getHSpaceColor(),e=["square","strip"],f=b.options.controls,g=f[b.active]||"external",h=b.hue;"strip"===b.active?e=[]:"external"!==b.active&&e.pop(),a.each(e,function(a,e){var g,h,i;if(e!==b.active)switch(e){case"strip":g="h"===f.strip?b._scale[f.strip]-d[f.strip]:d[f.strip],c.stripSlider.slider("value",g);break;case"square":h=b._squareDimensions(),i={left:d[f.horiz]/b._scale[f.horiz]*h.w,top:h.h-d[f.vert]/b._scale[f.vert]*h.h},b.controls.squareDrag.css(i)}}),d.h!==h&&b._isNonHueControl(b.active,g)&&b._color.h(h),b.hue=b._color.h(),b.options.color=b._color.toString(),b._inited&&b._trigger("change",{type:b.active},{color:b._color}),b.element.is(":input")&&!b._color.error&&(b.element.removeClass("iris-error"),b.element.val()!==b._color.toString()&&b.element.val(b._color.toString())),b._paint(),b._inited=!0,b.active=!1},_debounce:function(a,b,c){var d,e;return function(){var f,g,h=this,i=arguments;return f=function(){d=null,c||(e=a.apply(h,i))},g=c&&!d,clearTimeout(d),d=setTimeout(f,b),g&&(e=a.apply(h,i)),e}},show:function(){this.picker.show()},hide:function(){this.picker.hide()},toggle:function(){this.picker.toggle()},color:function(a){return a===!0?this._color.clone():a===b?this._color.toString():(this.option("color",a),void 0)}},a.widget("a8c.iris",n),a('<style id="iris-css">'+m+"</style>").appendTo("head"),void 0)}(jQuery),function(a,b){var c=function(a,b){return this instanceof c?this._init(a,b):new c(a,b)};c.fn=c.prototype={_color:0,_alpha:1,error:!1,_hsl:{h:0,s:0,l:0},_hsv:{h:0,s:0,v:0},_hSpace:"hsl",_init:function(a){var c="noop";switch(typeof a){case"object":return a.a!==b&&this.a(a.a),c=a.r!==b?"fromRgb":a.l!==b?"fromHsl":a.v!==b?"fromHsv":c,this[c](a);case"string":return this.fromCSS(a);case"number":return this.fromInt(parseInt(a,10))}return this},_error:function(){return this.error=!0,this},clone:function(){for(var a=new c(this.toInt()),b=["_alpha","_hSpace","_hsl","_hsv","error"],d=b.length-1;d>=0;d--)a[b[d]]=this[b[d]];return a},setHSpace:function(a){return this._hSpace="hsv"===a?a:"hsl",this},noop:function(){return this},fromCSS:function(a){var b,c=/^(rgb|hs(l|v))a?\(/;if(this.error=!1,a=a.replace(/^\s+/,"").replace(/\s+$/,"").replace(/;$/,""),a.match(c)&&a.match(/\)$/)){if(b=a.replace(/(\s|%)/g,"").replace(c,"").replace(/,?\);?$/,"").split(","),b.length<3)return this._error();if(4===b.length&&(this.a(parseFloat(b.pop())),this.error))return this;for(var d=b.length-1;d>=0;d--)if(b[d]=parseInt(b[d],10),isNaN(b[d]))return this._error();return a.match(/^rgb/)?this.fromRgb({r:b[0],g:b[1],b:b[2]}):a.match(/^hsv/)?this.fromHsv({h:b[0],s:b[1],v:b[2]}):this.fromHsl({h:b[0],s:b[1],l:b[2]})}return this.fromHex(a)},fromRgb:function(a,c){return"object"!=typeof a||a.r===b||a.g===b||a.b===b?this._error():(this.error=!1,this.fromInt(parseInt((a.r<<16)+(a.g<<8)+a.b,10),c))},fromHex:function(a){return a=a.replace(/^#/,"").replace(/^0x/,""),3===a.length&&(a=a[0]+a[0]+a[1]+a[1]+a[2]+a[2]),this.error=!/^[0-9A-F]{6}$/i.test(a),this.fromInt(parseInt(a,16))},fromHsl:function(a){var c,d,e,f,g,h,i,j;return"object"!=typeof a||a.h===b||a.s===b||a.l===b?this._error():(this._hsl=a,this._hSpace="hsl",h=a.h/360,i=a.s/100,j=a.l/100,0===i?c=d=e=j:(f=.5>j?j*(1+i):j+i-j*i,g=2*j-f,c=this.hue2rgb(g,f,h+1/3),d=this.hue2rgb(g,f,h),e=this.hue2rgb(g,f,h-1/3)),this.fromRgb({r:255*c,g:255*d,b:255*e},!0))},fromHsv:function(a){var c,d,e,f,g,h,i,j,k,l,m;if("object"!=typeof a||a.h===b||a.s===b||a.v===b)return this._error();switch(this._hsv=a,this._hSpace="hsv",c=a.h/360,d=a.s/100,e=a.v/100,i=Math.floor(6*c),j=6*c-i,k=e*(1-d),l=e*(1-j*d),m=e*(1-(1-j)*d),i%6){case 0:f=e,g=m,h=k;break;case 1:f=l,g=e,h=k;break;case 2:f=k,g=e,h=m;break;case 3:f=k,g=l,h=e;break;case 4:f=m,g=k,h=e;break;case 5:f=e,g=k,h=l}return this.fromRgb({r:255*f,g:255*g,b:255*h},!0)},fromInt:function(a,c){return this._color=parseInt(a,10),isNaN(this._color)&&(this._color=0),this._color>16777215?this._color=16777215:this._color<0&&(this._color=0),c===b&&(this._hsv.h=this._hsv.s=this._hsl.h=this._hsl.s=0),this},hue2rgb:function(a,b,c){return 0>c&&(c+=1),c>1&&(c-=1),1/6>c?a+6*(b-a)*c:.5>c?b:2/3>c?a+6*(b-a)*(2/3-c):a},toString:function(){var a=parseInt(this._color,10).toString(16);if(this.error)return"";if(a.length<6)for(var b=6-a.length-1;b>=0;b--)a="0"+a;return"#"+a},toCSS:function(a,b){switch(a=a||"hex",b=parseFloat(b||this._alpha),a){case"rgb":case"rgba":var c=this.toRgb();return 1>b?"rgba( "+c.r+", "+c.g+", "+c.b+", "+b+" )":"rgb( "+c.r+", "+c.g+", "+c.b+" )";case"hsl":case"hsla":var d=this.toHsl();return 1>b?"hsla( "+d.h+", "+d.s+"%, "+d.l+"%, "+b+" )":"hsl( "+d.h+", "+d.s+"%, "+d.l+"% )";default:return this.toString()}},toRgb:function(){return{r:255&this._color>>16,g:255&this._color>>8,b:255&this._color}},toHsl:function(){var a,b,c=this.toRgb(),d=c.r/255,e=c.g/255,f=c.b/255,g=Math.max(d,e,f),h=Math.min(d,e,f),i=(g+h)/2;if(g===h)a=b=0;else{var j=g-h;switch(b=i>.5?j/(2-g-h):j/(g+h),g){case d:a=(e-f)/j+(f>e?6:0);break;case e:a=(f-d)/j+2;break;case f:a=(d-e)/j+4}a/=6}return a=Math.round(360*a),0===a&&this._hsl.h!==a&&(a=this._hsl.h),b=Math.round(100*b),0===b&&this._hsl.s&&(b=this._hsl.s),{h:a,s:b,l:Math.round(100*i)}},toHsv:function(){var a,b,c=this.toRgb(),d=c.r/255,e=c.g/255,f=c.b/255,g=Math.max(d,e,f),h=Math.min(d,e,f),i=g,j=g-h;if(b=0===g?0:j/g,g===h)a=b=0;else{switch(g){case d:a=(e-f)/j+(f>e?6:0);break;case e:a=(f-d)/j+2;break;case f:a=(d-e)/j+4}a/=6}return a=Math.round(360*a),0===a&&this._hsv.h!==a&&(a=this._hsv.h),b=Math.round(100*b),0===b&&this._hsv.s&&(b=this._hsv.s),{h:a,s:b,v:Math.round(100*i)}},toInt:function(){return this._color},toIEOctoHex:function(){var a=this.toString(),b=parseInt(255*this._alpha,10).toString(16);return 1===b.length&&(b="0"+b),"#"+b+a.replace(/^#/,"")},toLuminosity:function(){var a=this.toRgb();return.2126*Math.pow(a.r/255,2.2)+.7152*Math.pow(a.g/255,2.2)+.0722*Math.pow(a.b/255,2.2)},getDistanceLuminosityFrom:function(a){if(!(a instanceof c))throw"getDistanceLuminosityFrom requires a Color object";var b=this.toLuminosity(),d=a.toLuminosity();return b>d?(b+.05)/(d+.05):(d+.05)/(b+.05)},getMaxContrastColor:function(){var a=this.toLuminosity(),b=a>=.5?"000000":"ffffff";return new c(b)},getReadableContrastingColor:function(a,d){if(!a instanceof c)return this;var e=d===b?5:d,f=a.getDistanceLuminosityFrom(this),g=a.getMaxContrastColor(),h=g.getDistanceLuminosityFrom(a);if(e>=h)return g;if(f>=e)return this;for(var i=0===g.toInt()?-1:1;e>f&&(this.l(i,!0),f=this.getDistanceLuminosityFrom(a),0!==this._color&&16777215!==this._color););return this},a:function(a){if(a===b)return this._alpha;var c=parseFloat(a);return isNaN(c)?this._error():(this._alpha=c,this)},darken:function(a){return a=a||5,this.l(-a,!0)},lighten:function(a){return a=a||5,this.l(a,!0)},saturate:function(a){return a=a||15,this.s(a,!0)},desaturate:function(a){return a=a||15,this.s(-a,!0)},toGrayscale:function(){return this.setHSpace("hsl").s(0)},getComplement:function(){return this.h(180,!0)},getSplitComplement:function(a){a=a||1;var b=180+30*a;return this.h(b,!0)},getAnalog:function(a){a=a||1;var b=30*a;return this.h(b,!0)},getTetrad:function(a){a=a||1;var b=60*a;return this.h(b,!0)},getTriad:function(a){a=a||1;var b=120*a;return this.h(b,!0)},_partial:function(a){var c=d[a];return function(d,e){var f=this._spaceFunc("to",c.space);return d===b?f[a]:(e===!0&&(d=f[a]+d),c.mod&&(d%=c.mod),c.range&&(d=d<c.range[0]?c.range[0]:d>c.range[1]?c.range[1]:d),f[a]=d,this._spaceFunc("from",c.space,f))}},_spaceFunc:function(a,b,c){var d=b||this._hSpace,e=a+d.charAt(0).toUpperCase()+d.substr(1);return this[e](c)}};var d={h:{mod:360},s:{range:[0,100]},l:{space:"hsl",range:[0,100]},v:{space:"hsv",range:[0,100]},r:{space:"rgb",range:[0,255]},g:{space:"rgb",range:[0,255]},b:{space:"rgb",range:[0,255]}};for(var e in d)d.hasOwnProperty(e)&&(c.fn[e]=c.fn._partial(e));"object"==typeof exports?module.exports=c:a.Color=c}(this);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             /* global imageEditL10n, ajaxurl, confirm */

(function($) {
var imageEdit = window.imageEdit = {
	iasapi : {},
	hold : {},
	postid : '',
	_view : false,

	intval : function(f) {
		return f | 0;
	},

	setDisabled : function( el, s ) {
		/*
		 * `el` can be a single form element or a fieldset. Before #28864, the disabled state on
		 * some text fields  was handled targeting $('input', el). Now we need to handle the
		 * disabled state on buttons too so we can just target `el` regardless if it's a single
		 * element or a fieldset because when a fieldset is disabled, its descendants are disabled too.
		 */
		if ( s ) {
			el.removeClass( 'disabled' ).prop( 'disabled', false );
		} else {
			el.addClass( 'disabled' ).prop( 'disabled', true );
		}
	},

	init : function(postid) {
		var t = this, old = $('#image-editor-' + t.postid),
			x = t.intval( $('#imgedit-x-' + postid).val() ),
			y = t.intval( $('#imgedit-y-' + postid).val() );

		if ( t.postid !== postid && old.length ) {
			t.close(t.postid);
		}

		t.hold.w = t.hold.ow = x;
		t.hold.h = t.hold.oh = y;
		t.hold.xy_ratio = x / y;
		t.hold.sizer = parseFloat( $('#imgedit-sizer-' + postid).val() );
		t.postid = postid;
		$('#imgedit-response-' + postid).empty();

		$('input[type="text"]', '#imgedit-panel-' + postid).keypress(function(e) {
			var k = e.keyCode;

			if ( 36 < k && k < 41 ) {
				$(this).blur();
			}

			if ( 13 === k ) {
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		});
	},

	toggleEditor : function(postid, toggle) {
		var wait = $('#imgedit-wait-' + postid);

		if ( toggle ) {
			wait.fadeIn( 'fast' );
		} else {
			wait.fadeOut('fast');
		}
	},

	toggleHelp : function(el) {
		var $el = $( el );
		$el
			.attr( 'aria-expanded', 'false' === $el.attr( 'aria-expanded' ) ? 'true' : 'false' )
			.parents( '.imgedit-group-top' ).toggleClass( 'imgedit-help-toggled' ).find( '.imgedit-help' ).slideToggle( 'fast' );

		return false;
	},

	getTarget : function(postid) {
		return $('input[name="imgedit-target-' + postid + '"]:checked', '#imgedit-save-target-' + postid).val() || 'full';
	},

	scaleChanged : function(postid, x) {
		var w = $('#imgedit-scale-width-' + postid), h = $('#imgedit-scale-height-' + postid),
		warn = $('#imgedit-scale-warn-' + postid), w1 = '', h1 = '';

		if ( x ) {
			h1 = ( w.val() !== '' ) ? Math.round( w.val() / this.hold.xy_ratio ) : '';
			h.val( h1 );
		} else {
			w1 = ( h.val() !== '' ) ? Math.round( h.val() * this.hold.xy_ratio ) : '';
			w.val( w1 );
		}

		if ( ( h1 && h1 > this.hold.oh ) || ( w1 && w1 > this.hold.ow ) ) {
			warn.css('visibility', 'visible');
		} else {
			warn.css('visibility', 'hidden');
		}
	},

	getSelRatio : function(postid) {
		var x = this.hold.w, y = this.hold.h,
			X = this.intval( $('#imgedit-crop-width-' + postid).val() ),
			Y = this.intval( $('#imgedit-crop-height-' + postid).val() );

		if ( X && Y ) {
			return X + ':' + Y;
		}

		if ( x && y ) {
			return x + ':' + y;
		}

		return '1:1';
	},

	filterHistory : function(postid, setSize) {
		// apply undo state to history
		var history = $('#imgedit-history-' + postid).val(), pop, n, o, i, op = [];

		if ( history !== '' ) {
			history = JSON.parse(history);
			pop = this.intval( $('#imgedit-undone-' + postid).val() );
			if ( pop > 0 ) {
				while ( pop > 0 ) {
					history.pop();
					pop--;
				}
			}

			if ( setSize ) {
				if ( !history.length ) {
					this.hold.w = this.hold.ow;
					this.hold.h = this.hold.oh;
					return '';
				}

				// restore
				o = history[history.length - 1];
				o = o.c || o.r || o.f || false;

				if ( o ) {
					this.hold.w = o.fw;
					this.hold.h = o.fh;
				}
			}

			// filter the values
			for ( n in history ) {
				i = history[n];
				if ( i.hasOwnProperty('c') ) {
					op[n] = { 'c': { 'x': i.c.x, 'y': i.c.y, 'w': i.c.w, 'h': i.c.h } };
				} else if ( i.hasOwnProperty('r') ) {
					op[n] = { 'r': i.r.r };
				} else if ( i.hasOwnProperty('f') ) {
					op[n] = { 'f': i.f.f };
				}
			}
			return JSON.stringify(op);
		}
		return '';
	},

	refreshEditor : function(postid, nonce, callback) {
		var t = this, data, img;

		t.toggleEditor(postid, 1);
		data = {
			'action': 'imgedit-preview',
			'_ajax_nonce': nonce,
			'postid': postid,
			'history': t.filterHistory(postid, 1),
			'rand': t.intval(Math.random() * 1000000)
		};

		img = $( '<img id="image-preview-' + postid + '" alt="" />' )
			.on( 'load', { history: data.history }, function( event ) {
				var max1, max2,
					parent = $( '#imgedit-crop-' + postid ),
					t = imageEdit,
					historyObj;

				if ( '' !== event.data.history ) {
					historyObj = JSON.parse( event.data.history );
					// If last executed action in history is a crop action.
					if ( historyObj[historyObj.length - 1].hasOwnProperty( 'c' ) ) {
						/*
						 * A crop action has completed and the crop button gets disabled
						 * ensure the undo button is enabled.
						 */
						t.setDisabled( $( '#image-undo-' + postid) , true );
						// Move focus to the undo button to avoid a focus loss.
						$( '#image-undo-' + postid ).focus();
					}
				}

				parent.empty().append(img);

				// w, h are the new full size dims
				max1 = Math.max( t.hold.w, t.hold.h );
				max2 = Math.max( $(img).width(), $(img).height() );
				t.hold.sizer = max1 > max2 ? max2 / max1 : 1;

				t.initCrop(postid, img, parent);
				t.setCropSelection(postid, 0);

				if ( (typeof callback !== 'undefined') && callback !== null ) {
					callback();
				}

				if ( $('#imgedit-history-' + postid).val() && $('#imgedit-undone-' + postid).val() === '0' ) {
					$('input.imgedit-submit-btn', '#imgedit-panel-' + postid).removeAttr('disabled');
				} else {
					$('input.imgedit-submit-btn', '#imgedit-panel-' + postid).prop('disabled', true);
				}

				t.toggleEditor(postid, 0);
			})
			.on('error', function() {
				$('#imgedit-crop-' + postid).empty().append('<div class="error"><p>' + imageEditL10n.error + '</p></div>');
				t.toggleEditor(postid, 0);
			})
			.attr('src', ajaxurl + '?' + $.param(data));
	},

	action : function(postid, nonce, action) {
		var t = this, data, w, h, fw, fh;

		if ( t.notsaved(postid) ) {
			return false;
		}

		data = {
			'action': 'image-editor',
			'_ajax_nonce': nonce,
			'postid': postid
		};

		if ( 'scale' === action ) {
			w = $('#imgedit-scale-width-' + postid),
			h = $('#imgedit-scale-height-' + postid),
			fw = t.intval(w.val()),
			fh = t.intval(h.val());

			if ( fw < 1 ) {
				w.focus();
				return false;
			} else if ( fh < 1 ) {
				h.focus();
				return false;
			}

			if ( fw === t.hold.ow || fh === t.hold.oh ) {
				return false;
			}

			data['do'] = 'scale';
			data.fwidth = fw;
			data.fheight = fh;
		} else if ( 'restore' === action ) {
			data['do'] = 'restore';
		} else {
			return false;
		}

		t.toggleEditor(postid, 1);
		$.post(ajaxurl, data, function(r) {
			$('#image-editor-' + postid).empty().append(r);
			t.toggleEditor(postid, 0);
			// refresh the attachment model so that changes propagate
			if ( t._view ) {
				t._view.refresh();
			}
		});
	},

	save : function(postid, nonce) {
		var data,
			target = this.getTarget(postid),
			history = this.filterHistory(postid, 0),
			self = this;

		if ( '' === history ) {
			return false;
		}

		this.toggleEditor(postid, 1);
		data = {
			'action': 'image-editor',
			'_ajax_nonce': nonce,
			'postid': postid,
			'history': history,
			'target': target,
			'context': $('#image-edit-context').length ? $('#image-edit-context').val() : null,
			'do': 'save'
		};

		$.post(ajaxurl, data, function(r) {
			var ret = JSON.parse(r);

			if ( ret.error ) {
				$('#imgedit-response-' + postid).html('<div class="error"><p>' + ret.error + '</p></div>');
				imageEdit.close(postid);
				return;
			}

			if ( ret.fw && ret.fh ) {
				$('#media-dims-' + postid).html( ret.fw + ' &times; ' + ret.fh );
			}

			if ( ret.thumbnail ) {
				$('.thumbnail', '#thumbnail-head-' + postid).attr('src', ''+ret.thumbnail);
			}

			if ( ret.msg ) {
				$('#imgedit-response-' + postid).html('<div class="updated"><p>' + ret.msg + '</p></div>');
			}

			if ( self._view ) {
				self._view.save();
			} else {
				imageEdit.close(postid);
			}
		});
	},

	open : function( postid, nonce, view ) {
		this._view = view;

		var dfd, data, elem = $('#image-editor-' + postid), head = $('#media-head-' + postid),
			btn = $('#imgedit-open-btn-' + postid), spin = btn.siblings('.spinner');

		/*
		 * Instead of disabling the button, which causes a focus loss and makes screen
		 * readers announce "unavailable", return if the button was already clicked.
		 */
		if ( btn.hasClass( 'button-activated' ) ) {
			return;
		}

		spin.addClass( 'is-active' );

		data = {
			'action': 'image-editor',
			'_ajax_nonce': nonce,
			'postid': postid,
			'do': 'open'
		};

		dfd = $.ajax({
			url:  ajaxurl,
			type: 'post',
			data: data,
			beforeSend: function() {
				btn.addClass( 'button-activated' );
			}
		}).done(function( html ) {
			elem.html( html );
			head.fadeOut('fast', function(){
				elem.fadeIn('fast');
				btn.removeClass( 'button-activated' );
				spin.removeClass( 'is-active' );
			});
		});

		return dfd;
	},

	imgLoaded : function(postid) {
		var img = $('#image-preview-' + postid), parent = $('#imgedit-crop-' + postid);

		this.initCrop(postid, img, parent);
		this.setCropSelection(postid, 0);
		this.toggleEditor(postid, 0);
		// Editor is ready, move focus to the first focusable element.
		$( '.imgedit-wrap .imgedit-help-toggle' ).eq( 0 ).focus();
	},

	initCrop : function(postid, image, parent) {
		var t = this,
			selW = $('#imgedit-sel-width-' + postid),
			selH = $('#imgedit-sel-height-' + postid),
			$img;

		t.iasapi = $(image).imgAreaSelect({
			parent: parent,
			instance: true,
			handles: true,
			keys: true,
			minWidth: 3,
			minHeight: 3,

			onInit: function( img ) {
				// Ensure that the imgareaselect wrapper elements are position:absolute
				// (even if we're in a position:fixed modal)
				$img = $( img );
				$img.next().css( 'position', 'absolute' )
					.nextAll( '.imgareaselect-outer' ).css( 'position', 'absolute' );

				parent.children().mousedown(function(e){
					var ratio = false, sel, defRatio;

					if ( e.shiftKey ) {
						sel = t.iasapi.getSelection();
						defRatio = t.getSelRatio(postid);
						ratio = ( sel && sel.width && sel.height ) ? sel.width + ':' + sel.height : defRatio;
					}

					t.iasapi.setOptions({
						aspectRatio: ratio
					});
				});
			},

			onSelectStart: function() {
				imageEdit.setDisabled($('#imgedit-crop-sel-' + postid), 1);
			},

			onSelectEnd: function(img, c) {
				imageEdit.setCropSelection(postid, c);
			},

			onSelectChange: function(img, c) {
				var sizer = imageEdit.hold.sizer;
				selW.val( imageEdit.round(c.width / sizer) );
				selH.val( imageEdit.round(c.height / sizer) );
			}
		});
	},

	setCropSelection : function(postid, c) {
		var sel;

		c = c || 0;

		if ( !c || ( c.width < 3 && c.height < 3 ) ) {
			this.setDisabled($('.imgedit-crop', '#imgedit-panel-' + postid), 0);
			this.setDisabled($('#imgedit-crop-sel-' + postid), 0);
			$('#imgedit-sel-width-' + postid).val('');
			$('#imgedit-sel-height-' + postid).val('');
			$('#imgedit-selection-' + postid).val('');
			return false;
		}

		sel = { 'x': c.x1, 'y': c.y1, 'w': c.width, 'h': c.height };
		this.setDisabled($('.imgedit-crop', '#imgedit-panel-' + postid), 1);
		$('#imgedit-selection-' + postid).val( JSON.stringify(sel) );
	},

	close : function(postid, warn) {
		warn = warn || false;

		if ( warn && this.notsaved(postid) ) {
			return false;
		}

		this.iasapi = {};
		this.hold = {};

		// If we've loaded the editor in the context of a Media Modal, then switch to the previous view,
		// whatever that might have been.
		if ( this._view ){
			this._view.back();
		}

		// In case we are not accessing the image editor in the context of a View, close the editor the old-skool way
		else {
			$('#image-editor-' + postid).fadeOut('fast', function() {
				$( '#media-head-' + postid ).fadeIn( 'fast', function() {
					// Move focus back to the Edit Image button. Runs also when saving.
					$( '#imgedit-open-btn-' + postid ).focus();
				});
				$(this).empty();
			});
		}


	},

	notsaved : function(postid) {
		var h = $('#imgedit-history-' + postid).val(),
			history = ( h !== '' ) ? JSON.parse(h) : [],
			pop = this.intval( $('#imgedit-undone-' + postid).val() );

		if ( pop < history.length ) {
			if ( confirm( $('#imgedit-leaving-' + postid).html() ) ) {
				return false;
			}
			return true;
		}
		return false;
	},

	addStep : function(op, postid, nonce) {
		var t = this, elem = $('#imgedit-history-' + postid),
			history = ( elem.val() !== '' ) ? JSON.parse( elem.val() ) : [],
			undone = $( '#imgedit-undone-' + postid ),
			pop = t.intval( undone.val() );

		while ( pop > 0 ) {
			history.pop();
			pop--;
		}
		undone.val(0); // reset

		history.push(op);
		elem.val( JSON.stringify(history) );

		t.refreshEditor(postid, nonce, function() {
			t.setDisabled($('#image-undo-' + postid), true);
			t.setDisabled($('#image-redo-' + postid), false);
		});
	},

	rotate : function(angle, postid, nonce, t) {
		if ( $(t).hasClass('disabled') ) {
			return false;
		}

		this.addStep({ 'r': { 'r': angle, 'fw': this.hold.h, 'fh': this.hold.w }}, postid, nonce);
	},

	flip : function (axis, postid, nonce, t) {
		if ( $(t).hasClass('disabled') ) {
			return false;
		}

		this.addStep({ 'f': { 'f': axis, 'fw': this.hold.w, 'fh': this.hold.h }}, postid, nonce);
	},

	crop : function (postid, nonce, t) {
		var sel = $('#imgedit-selection-' + postid).val(),
			w = this.intval( $('#imgedit-sel-width-' + postid).val() ),
			h = this.intval( $('#imgedit-sel-height-' + postid).val() );

		if ( $(t).hasClass('disabled') || sel === '' ) {
			return false;
		}

		sel = JSON.parse(sel);
		if ( sel.w > 0 && sel.h > 0 && w > 0 && h > 0 ) {
		